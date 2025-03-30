<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\API;

use Automattic\Jetpack\Connection\Manager as JetpackManager;
use Automattic\Jetpack\Sync\Modules\Full_Sync_Immediately;
use Automattic\WooCommerce\Analytics\HelperTraits\LoggerTrait;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;
use Automattic\WooCommerce\Analytics\Internal\FullSyncCheck\AdminFullSyncEmailNotification;
use Automattic\WooCommerce\Analytics\Internal\Jetpack\SyncModules;
use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;
use Automattic\WooCommerce\Analytics\Utilities\Tracking;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Class SyncStatus.
 * Returns information about the Jetpack sync status.
 * Note that Automattic\Jetpack\Sync\REST_Endpoints has a similar /jetpack/v4/sync-status endpoint,
 * but it doesn't return the progress percentage.
 *
 * @package Automattic\WooCommerce\Analytics\API
 */
class SyncStatus extends WC_REST_Controller implements RegistrableInterface {
	use LoggerTrait;
	use Utilities;

	/** @var string */
	private const NAMESPACE = 'wc/v3';

	/** @var string */
	private const REST_BASE_SUFFIX = '/sync-status';

	/** @var string */
	private const INITIAL_FULL_SYNC_OPTION = 'woocommerce_analytics_initial_full_sync_finished';

	/** @var string */
	private const EMAIL_NOTIFICATION_SENT_OPTION = 'woocommerce_analytics_email_notification_sent';

	/**
	 * The base of the REST API route.
	 *
	 * @var string
	 */
	protected $rest_base;

	/**
	 * @var JetpackManager
	 */
	protected JetpackManager $manager;

	/**
	 * @var SyncModules
	 */
	protected SyncModules $sync_modules;

	/**
	 * JetpackSyncStatus constructor.
	 *
	 * @param SyncModules     $sync_modules The sync modules.
	 * @param LoggerInterface $logger The logger.
	 * @param JetpackManager  $manager The Jetpack manager.
	 */
	public function __construct( SyncModules $sync_modules, LoggerInterface $logger, JetpackManager $manager ) {
		$this->rest_base = $this->get_plugin_slug() . self::REST_BASE_SUFFIX;
		$this->set_logger( $logger );
		$this->manager      = $manager;
		$this->sync_modules = $sync_modules;
	}

	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

		add_action( 'jetpack_sync_processed_actions', array( $this, 'on_sync_processed_actions' ) );
	}

	/**
	 * Callback for the jetpack_sync_processed_actions action, used to send notification email
	 * and track full sync completion.
	 *
	 * @param array $actions The actions that were processed.
	 *
	 * @return void
	 */
	public function on_sync_processed_actions( array $actions ): void {
		// If full sync has already finished previously, we expect the email notification to have been sent already.
		$full_sync_ever_finished = get_option( self::INITIAL_FULL_SYNC_OPTION, 0 );
		if ( $full_sync_ever_finished ) {
			return;
		}

		// If the Woocommerce Analytics sync is not finished, then full sync isn't either so we won't need to do anything.
		$full_status = $this->sync_modules->get_full_sync_immediately()->get_status();
		if ( empty( $full_status['progress']['woocommerce_analytics']['finished'] ) ) {
			return;
		}

		/*
		 * Send the email notification to the merchant on WooCommerce Analytics sync completion.
		 * Set the option in order to only send the email once, even if the module is resynced.
		 */
		$email_notification_sent = get_option( self::EMAIL_NOTIFICATION_SENT_OPTION, 'no' );
		if ( 'no' === $email_notification_sent ) {
			try {
				AdminFullSyncEmailNotification::send_email_notification();
				update_option( self::EMAIL_NOTIFICATION_SENT_OPTION, 'yes' );
			} catch ( \Exception $e ) {
				$this->logger->log_error( 'Failed to send sync completion email.', __METHOD__ );
			}
		}

		// Track full sync completion.
		foreach ( $actions as $action ) {
			if ( 'jetpack_full_sync_end' === $action[0] ) {
				/*
				 * The last update_status call in Full_Sync_Immediately::send() doesn't happen until after jetpack_full_sync_end is called.
				 * So we use the timestamp of jetpack_full_sync_end action to set the `finished` timestamp in full_status (in testing, they're the same).
				 * Note: see Year 2038 problem.
				 */
				$full_status['finished'] = intval( $action[3] );
				Tracking::track_full_sync_completed( $full_status );
				update_option( self::INITIAL_FULL_SYNC_OPTION, $full_status['finished'] );
			}
		}
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes(): void {

		// Register the sync status route.
		register_rest_route(
			self::NAMESPACE,
			$this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_sync_status' ),
					'permission_callback' => array( $this, 'check_permission' ),
					'args'                => array(
						'verbose' => array(
							'description'       => 'Whether to include detailed sync status information.',
							'type'              => 'boolean',
							'default'           => false,
							'validate_callback' => function ( $param ) {
								// Allow "true" and "false" strings as valid boolean values.
								return in_array( $param, array( true, false, 'true', 'false' ), true );
							},
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		// Register the reset initial full sync route.
		register_rest_route(
			self::NAMESPACE,
			$this->rest_base . '/reset-initial-full-sync',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'reset_sync_status' ),
					'permission_callback' => array( $this, 'check_permission' ),
					'schema'              => array( $this, 'get_reset_sync_schema' ),
				),
			)
		);
	}

	/**
	 * Check if a given request has permission to read the sync status.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return bool
	 */
	public function check_permission( WP_REST_Request $request ): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get the current sync status.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error Response object or WP_Error.
	 */
	public function get_sync_status( WP_REST_Request $request ) {
		if ( ! class_exists( 'Automattic\Jetpack\Sync\Modules' ) ) {
			return new WP_Error( 'jetpack_sync_not_available', 'Sync is not available.', array( 'status' => 404 ) );
		}

		try {
			$verbose_status = $this->sync_status();
			$verbose        = $request->get_param( 'verbose' );

			// If this is a non-verbose request, we only want to return the basic sync status.
			$status = array(
				'is_connected'               => $verbose_status['is_connected'],
				'is_started'                 => isset( $verbose_status['full_status']['progress']['woocommerce_analytics'] ),
				'progress_percentage'        => $verbose_status['analytics_reports_progress_percentage'],
				'initial_full_sync_finished' => $verbose_status['initial_full_sync_finished'],
			);

			if ( wc_string_to_bool( $verbose ) ) {
				$status['verbose_status'] = $verbose_status;
			}

			return new WP_REST_Response( $status, 200 );
		} catch ( \Exception $e ) {
			$this->logger->log_exception( $e, __METHOD__ );
			return new WP_Error( 'sync_status_error', 'Error retrieving sync status', array( 'status' => 500 ) );
		}
	}

	/**
	 * Get the sync status. The progress percentage is the full sync progress percentage if full sync is in progress.
	 * Woocommerce Analytics Reports progress percentage is shown if available.
	 * Progress = 0 and Woocommerce Analytics Reports progress = 0 means waiting for initial full sync to start.
	 * Progress < 100 and Woocommerce Analytics Reports progress < 100 means initial full sync is in progress.
	 * Progress = 100 and Woocommerce Analytics Reports progress = 100 means initial full sync has been done and Woocommerce Analytics Reports is synced.
	 * Progress < 100 and Woocommerce Analytics Reports progress = 100 means initial full sync is in progress but Woocommerce Analytics Reports has synced already.
	 * Progress = 100 and Woocommerce Analytics Reports progress < 0 means initial full sync has been done and Woocommerce Analytics Reports resync is in progress.
	 *
	 * @return array The sync status.
	 */
	public function sync_status() {
		$is_connected       = $this->manager->is_connected();
		$sync_module        = $this->sync_modules->get_full_sync_immediately();
		$full_status        = $sync_module->get_status();
		$full_sync_finished = get_option( self::INITIAL_FULL_SYNC_OPTION, 0 );
		$is_finished        = $is_connected && $full_sync_finished > 0;

		/*
		 * Is finished = connected and full sync has finished, ever.
		 * Progress percentage is 100 once full sync has been done, because Woocommerce Analytics Reports will have been synced.
		 * Woocommerce Analytics Reports progress percentage matches the overall progress percentage by default.
		 */
		$status = array(
			'is_connected'                          => $is_connected,
			'is_started'                            => $sync_module->is_started(),
			'is_finished'                           => $is_finished,
			'progress_percentage'                   => $is_finished ? 100 : 0,
			'analytics_reports_progress_percentage' => $is_finished ? 100 : 0,
			'initial_full_sync_finished'            => intval( $full_sync_finished ),
			'full_status'                           => $full_status,
		);

		// Check that Woocommerce Analytics Reports data are queued to sync (i.e., the status isn't just for the initial mini sync).
		$orders_in_queue = ! empty( $full_status['progress']['woocommerce_analytics'] );
		if ( $orders_in_queue ) {

			// We show the actual full sync progress percentage if full sync is in progress.
			if ( $is_finished ) {
				$status['progress_percentage'] = 100;
			} else {
				try {
					$status['progress_percentage'] = $sync_module->get_sync_progress_percentage() ?? 0;
				} catch ( \Exception $e ) {
					$this->logger->log_exception( $e, __METHOD__ );
					$status['progress_percentage'] = 0;
				}
			}

			// Use the real Woocommerce Analytics progress percentage in case full sync is in progress but Woocommerce Analytic is further ahead.
			$woocommerce_analytics_progress = $full_status['progress']['woocommerce_analytics'];
			// Avoid division by zero and show 100% if there are no orders to sync.
			if ( $woocommerce_analytics_progress['total'] > 0 ) {
				$status['analytics_reports_progress_percentage'] = round( $woocommerce_analytics_progress['sent'] / $woocommerce_analytics_progress['total'] * 100 );
			} else {
				$status['analytics_reports_progress_percentage'] = 100;
			}
		}

		return $status;
	}

	/**
	 * Check if a full sync is currently running.
	 *
	 * @return bool True if a full sync is running, false otherwise.
	 */
	public function is_full_sync_running(): bool {
		$full_sync_module = $this->sync_modules->get_full_sync_immediately();
		return $full_sync_module->is_started() && ! $full_sync_module->is_finished();
	}

	/**
	 * Check if a full sync is available.
	 *
	 * @return bool True if a full sync is available, false otherwise.
	 */
	public function is_full_sync_available(): bool {
		return $this->sync_modules->get_full_sync_immediately() !== false;
	}

	/**
	 * Get the schema for the sync status endpoint.
	 *
	 * @return array
	 */
	public function get_item_schema(): array {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'jetpack_sync_status',
			'type'       => 'object',
			'properties' => array(
				'is_connected'               => array(
					'description' => 'Whether the store is currently connected.',
					'type'        => 'boolean',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'is_started'                 => array(
					'description' => 'Whether the full sync has started.',
					'type'        => 'boolean',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'progress_percentage'        => array(
					'description' => 'The progress percentage of the sync (max of full sync and analytics reports sync).',
					'type'        => 'integer',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'initial_full_sync_finished' => array(
					'description' => 'Timestamp when the initial full sync was finished.',
					'type'        => 'integer',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'verbose_status'             => array(
					'description' => 'Verbose: detailed sync status information.',
					'type'        => 'object',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Reset the initial full sync status and email notification sent status by removing the options.
	 * Mostly used for development and debugging.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error Response object or WP_Error.
	 */
	public function reset_sync_status( WP_REST_Request $request ) {
		if ( $this->manager->is_connected() ) {
			return new WP_Error( 'site_connected', 'Cannot reset initial full sync status while connected.', array( 'status' => 400 ) );
		}

		delete_option( self::EMAIL_NOTIFICATION_SENT_OPTION );

		if ( delete_option( self::INITIAL_FULL_SYNC_OPTION ) ) {
			return new WP_REST_Response( array( 'message' => 'Initial full sync status reset successfully.' ), 200 );
		}

		return new WP_REST_Response( array( 'message' => 'Initial full sync status already unset.' ), 200 );
	}

	/**
	 * Get the schema for the reset-initial-full-sync endpoint.
	 *
	 * @return array
	 */
	public function get_reset_sync_schema(): array {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'reset_sync_status',
			'type'       => 'object',
			'properties' => array(
				'message' => array(
					'description' => 'A message indicating the result of the reset operation.',
					'type'        => 'string',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
