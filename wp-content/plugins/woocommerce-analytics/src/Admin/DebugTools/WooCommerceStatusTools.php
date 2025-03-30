<?php

namespace Automattic\WooCommerce\Analytics\Admin\DebugTools;

use Automattic\Jetpack\Connection\Manager as JetpackManager;
use Automattic\WooCommerce\Analytics\API\SyncStatus;
use Automattic\WooCommerce\Analytics\HelperTraits\LoggerTrait;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;
use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;
use Automattic\WooCommerce\Analytics\Utilities\Tracking;

/**
 * Class WooCommerceStatusTools
 *
 * Provides tools for debugging WooCommerce status.
 */
class WooCommerceStatusTools implements RegistrableInterface {

	use LoggerTrait;
	use Utilities;

	/**
	 * @var JetpackManager
	 */
	protected JetpackManager $manager;

	/**
	 * @var SyncStatus
	 */
	protected SyncStatus $sync_status;

	/**
	 * Used to detect if we can select the tool based on status or the action.
	 *
	 * @var bool $after_refresh
	 */
	private $after_refresh = false;

	private const FULL_SYNC_TRANSIENT_KEY = 'woocommerce_analytics_full_sync_stop';

	/**
	 * JetpackSyncStatus constructor.
	 *
	 * @param LoggerInterface $logger The logger.
	 * @param SyncStatus      $sync_status The sync status.
	 */
	public function __construct( LoggerInterface $logger, SyncStatus $sync_status ) {
		$this->set_logger( $logger );
		$this->sync_status = $sync_status;
	}

	/**
	 * Register method required by the RegistrableInterface.
	 * Hooks registration for the class.
	 */
	public function register(): void {
		add_filter( 'woocommerce_debug_tools', array( $this, 'add_data_sync_tool' ) );
	}

	/**
	 * Add tool to trigger full sync.
	 * Available from WooCommerce > Status > Tools.
	 *
	 * @param array $tools The current tools.
	 * @return array The modified tools.
	 */
	public function add_data_sync_tool( $tools ) {

		// Limit access to the tool to users who can manage WooCommerce.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return $tools;
		}

		$sync_status = null;
		try {
			$sync_status = $this->sync_status->sync_status();
		} catch ( \Exception $e ) {
			$this->get_logger()->log_error( 'Failed to get sync status.', __METHOD__ );
			return $tools;
		}

		// Only show the tool if the plugin is connected.
		if ( ! $sync_status['is_connected'] ) {
			return $tools;
		}

		// Verify nonce before processing the action.
		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ?? '' ) );
		if ( wp_verify_nonce( $nonce, 'woocommerce_analytics_start_full_sync' ) ) {
			/*
			* Check if action is set and use the appropriate tool.
			*
			* The reason for this code is to detect the scenario when the sync has already stopped but the user hasn't refreshed the page.
			* In this scenario clicking the button was triggering the wrong action.
			* This was caused by the fact that the logic of enqueuing either
			* the start or stop tool was based on the sync status. Which has changed
			* since the page was originally loaded.
			*
			* By ensuring that the correct tool is loaded the correct action can be triggered.
			* It is up to the action to decide that it is too late for it too execute.
			*/
			$action = sanitize_text_field( wp_unslash( $_GET['action'] ?? '' ) );

			if ( $this->after_refresh ) {
				$action = '';
			}

			if ( 'woocommerce_analytics_start_full_sync' === $action ) {
				$tools['woocommerce_analytics_start_full_sync'] = $this->get_sync_start_tool( $sync_status );
				return $tools;
			}

			if ( 'woocommerce_analytics_stop_full_sync' === $action ) {
				$tools['woocommerce_analytics_stop_full_sync'] = $this->get_sync_stop_tool( $sync_status );
				return $tools;
			}
		}

		// For the regular scenario we select the tool based on the sync status.
		$is_sync_running       = $this->sync_status->is_full_sync_running();
		$right_after_sync_stop = get_transient( self::FULL_SYNC_TRANSIENT_KEY );

		if ( $is_sync_running && ! $right_after_sync_stop ) {
			$tools['woocommerce_analytics_stop_full_sync'] = $this->get_sync_stop_tool( $sync_status );
		} else {
			$tools['woocommerce_analytics_start_full_sync'] = $this->get_sync_start_tool( $sync_status );
		}

		return $tools;
	}

	/**
	 * Create full sync start tool.
	 *
	 * @param array $sync_status The current sync status.
	 *
	 * @return array Full sync tool specification.
	 */
	private function get_sync_start_tool( $sync_status ) {
		$finished        = date( 'Y-m-d H:i:s', $sync_status['full_status']['finished'] ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		$overview        = __( 'Synchronize the WooCommerce Analytics data.', 'woocommerce-analytics' );
		$after_sync_stop = get_transient( self::FULL_SYNC_TRANSIENT_KEY );

		if ( $after_sync_stop ) {
			$status = __( 'The synchronization process is being stopped. Please check again later if you want to start again.', 'woocommerce-analytics' );
		} else {
			$status = sprintf(
				/* translators: %s is the date and time of the last sync. */
				__( 'Last synchronization procedure has been finished at: %s', 'woocommerce-analytics' ),
				'<strong>' . $finished . '</strong>'
			);
		}

		$description = $overview . '<br />' . $status;

		return array(
			'name'             => __( 'WooCommerce Analytics data synchronization', 'woocommerce-analytics' ),
			'button'           => __( 'Synchronize', 'woocommerce-analytics' ),
			'requires_refresh' => true,
			'desc'             => $description,
			'callback'         => array( $this, 'trigger_full_sync' ),
			'disabled'         => $after_sync_stop,
		);
	}

	/**
	 * Create full sync stop tool.
	 *
	 * @param array $sync_status The current sync status.
	 *
	 * @return array Full sync stop tool specification.
	 */
	private function get_sync_stop_tool( $sync_status ) {
		$started  = date( 'Y-m-d H:i:s', $sync_status['full_status']['started'] ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		$overview = __( 'Stop the WooCommerce Analytics data synchronization.', 'woocommerce-analytics' );
		$status   = sprintf(
			/* translators: %s is date and time of the last sync start. */
			__( 'The synchronization process was started at: %s', 'woocommerce-analytics' ),
			'<strong>' . $started . '</strong>'
		);

		$description = $overview . '<br />' . $status;

		return array(
			'name'             => __( 'WooCommerce Analytics data synchronization', 'woocommerce-analytics' ),
			'button'           => __( 'Stop Synchronization', 'woocommerce-analytics' ),
			'requires_refresh' => true,
			'desc'             => $description,
			'callback'         => array( $this, 'trigger_full_sync_stop' ),
		);
	}

	/**
	 * Triggers a full synchronization of WooCommerce Analytics data.
	 *
	 * @return string The result message of the sync action.
	 */
	public function trigger_full_sync() {
		/*
		 * We set it here so the next time we will know that the action has been executed.
		 * We can select the correct tool post action.
		 */
		$this->after_refresh = true;

		// Limit access to the tool to users who can manage WooCommerce.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return __( 'Current user can\'t trigger this action.', 'woocommerce-analytics' );
		}

		// Start only if the full-sync module is available.
		if ( ! $this->sync_status->is_full_sync_available() ) {
			return __( 'A full sync is not available.', 'woocommerce-analytics' );
		}

		// Check if a full sync is already running.
		if ( $this->sync_status->is_full_sync_running() ) {
			return __( 'A full sync is already running.', 'woocommerce-analytics' );
		}

		// Run the full sync.
		$this->run_full_sync();
		Tracking::track_manual_sync_action( 'started' );
		return __( 'Full Sync has been triggered.', 'woocommerce-analytics' );
	}

	/**
	 * Triggers a stop of the full synchronization of WooCommerce Analytics data.
	 *
	 * @return string The result message of the sync action.
	 */
	public function trigger_full_sync_stop() {
		/*
		 * We set it here so the next time we will know that the action has been executed.
		 * We can select the correct tool post action.
		 */
		$this->after_refresh = true;

		set_transient( self::FULL_SYNC_TRANSIENT_KEY, true, 60 );

		// Limit access to the tool to users who can manage WooCommerce.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return __( 'Current user can\'t trigger this action.', 'woocommerce-analytics' );
		}

		// Check if a full sync is already running.
		if ( ! $this->sync_status->is_full_sync_running() ) {
			return __( 'A full sync is not currently running.', 'woocommerce-analytics' );
		}

		// Stop the full sync.
		$this->stop_full_sync();
		Tracking::track_manual_sync_action( 'stopped' );
		return __( 'Full Sync has been stopped.', 'woocommerce-analytics' );
	}
}
