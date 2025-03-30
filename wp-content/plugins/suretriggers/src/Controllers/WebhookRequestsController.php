<?php
/**
 * WebhookRequestsController.
 * php version 5.6
 *
 * @category WebhookRequestsController
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Controllers;

use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Models\SaasApiToken;
use SureTriggers\Controllers\RestController;

/**
 * WebhookRequestsController- Store Webhook requests and retry for failed.
 *
 * @category WebhookRequestsController
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class WebhookRequestsController {

	use SingletonLoader;

	/**
	 * Webhook Requests Table name.
	 *
	 * @var string
	 */
	protected static $name = 'suretriggers_webhook_requests';

	/**
	 * Initialise data.
	 */
	public function __construct() {
		add_action( 'suretriggers_retry_failed_requests', [ $this, 'suretriggers_retry_failed_trigger_requests' ] );
		add_action( 'suretriggers_webhook_requests_cleanup_logs', [ $this, 'suretriggers_cleanup_requests_logs' ] );
		add_action( 'suretriggers_verify_api_connection', [ $this, 'suretriggers_verify_api_wp_connection' ] );
		add_filter( 'cron_schedules', [ $this, 'suretriggers_custom_cron_schedule' ] );
	}

	/**
	 * Get the table name.
	 *
	 * @return string
	 */
	public static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . self::$name;
	}

	/**
	 * Adds a custom cron schedule for every 30 minutes.
	 *
	 * @param array $schedules An array of non-default cron schedules.
	 * @return array Filtered array of non-default cron schedules.
	 */
	public static function suretriggers_custom_cron_schedule( $schedules ) {
		$schedules['suretriggers_retry_cron_schedule'] = [
			'interval' => 30 * MINUTE_IN_SECONDS,
			'display'  => __( 'Every 30 minutes', 'suretriggers' ),
		];
		return $schedules;
	}

	/**
	 * Custom table for storing of webhook requests logs.
	 *
	 * @return void
	 */
	public static function suretriggers_webhook_request_log_table() {
		global $wpdb;
		$table_name      = self::get_table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			request_method varchar(255) NULL,
			request_url varchar(255) NOT NULL,
			request_data longtext NOT NULL,
			response_code int(3) NOT NULL,
			status varchar(20) NOT NULL,
			error_info varchar(255) NOT NULL,
			retry_attempts int(3) DEFAULT 0,
			processed_at datetime NULL,
			created_at datetime,
			updated_at datetime ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Setup cron to retry failed webhook requests and cleanup logs for Triggers.
	 *
	 * @return void
	 */
	public static function suretriggers_setup_custom_cron() {
		// Retry failed requests.
		if ( ! wp_next_scheduled( 'suretriggers_retry_failed_requests' ) ) {
			wp_schedule_event( time(), 'suretriggers_retry_cron_schedule', 'suretriggers_retry_failed_requests' );
		}

		// Clean up log requests that are older than 15 days.
		if ( ! wp_next_scheduled( 'suretriggers_webhook_requests_cleanup_logs' ) ) {
			wp_schedule_event( time(), 'daily', 'suretriggers_webhook_requests_cleanup_logs' );
		}
		
		// Verify the API connection every 12 hours to keep the connection alive.
		if ( ! wp_next_scheduled( 'suretriggers_verify_api_connection' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'suretriggers_verify_api_connection' );
		}
	}

	/**
	 * Log Request handler.
	 *
	 * @param string $data Request data.
	 * @param int    $response_code Response Code.
	 * @param string $error_info Error Info.
	 * 
	 * @return void
	 */
	public static function suretriggers_log_request( $data, $response_code, $error_info ) {
		global $wpdb;
		// Store the data in request logs.
		$wpdb->insert(
			self::get_table_name(),
			[
				'request_method' => 'POST',
				'request_url'    => SURE_TRIGGERS_WEBHOOK_SERVER_URL . '/wordpress/webhook',
				'request_data'   => $data,
				'response_code'  => $response_code,
				'status'         => ( 200 === $response_code ) ? 'success' : 'failed',
				'error_info'     => $error_info,
				'retry_attempts' => 0,
				'processed_at'   => null,
				'created_at'     => current_time( 'mysql' ),
				'updated_at'     => current_time( 'mysql' ),
			]
		);
	}

	/**
	 * Update Failed Webhook Request handler via cron.
	 * 
	 * @return void
	 */
	public static function suretriggers_retry_failed_trigger_requests() {
		global $wpdb;
		$table_name = self::get_table_name();

		// Select all failed requests that haven't exceeded retry attempts.
		$failed_requests = $wpdb->get_results( 
			$wpdb->prepare( 
				"SELECT * FROM {$table_name} WHERE status = %s AND retry_attempts < %d", //phpcs:ignore
				'failed', 
				5
			), 
			ARRAY_A 
		);

		foreach ( $failed_requests as $request ) {
			$data = json_decode( $request['request_data'], true );
			if ( is_array( $data ) ) {
				$data['headers']['Authorization'] = 'Bearer ' . SaasApiToken::get();
				$response                         = wp_remote_post( $request['request_url'], $data );
				$response_code                    = wp_remote_retrieve_response_code( $response );
				$error_info                       = wp_remote_retrieve_body( $response );
				if ( 405 === $response_code ) {
					$error_info = wp_remote_retrieve_response_message( $response );
				}
				if ( 0 === $response_code ) {
					$error_info = __( 'Service not available', 'suretriggers' );
				}
				// Update the request if failed with the new response.
				$wpdb->update(
					$table_name,
					[
						'request_method' => $request['request_method'],
						'request_url'    => $request['request_url'],
						'request_data'   => $request['request_data'],
						'response_code'  => $response_code,
						'status'         => ( 200 === $response_code ) ? 'success' : 'failed',
						'error_info'     => $error_info,
						'retry_attempts' => $request['retry_attempts'] + 1,
						'processed_at'   => current_time( 'mysql' ),
						'updated_at'     => current_time( 'mysql' ),
					],
					[ 'id' => $request['id'] ]
				);
			}
		}
	}

	/**
	 * Update Failed Webhook Request handler via Retry button.
	 * 
	 * @param int $id ID.
	 * 
	 * @return bool
	 */
	public static function suretriggers_retry_trigger_request( $id ) {
		global $wpdb;
		$table_name      = self::get_table_name();
		$failed_requests = $wpdb->get_row( 
			$wpdb->prepare( 
				"SELECT * FROM {$table_name} WHERE id = %d", //phpcs:ignore
				$id
			), 
			ARRAY_A 
		);

		$data = json_decode( $failed_requests['request_data'], true );
		if ( is_array( $data ) ) {
			$data['headers']['Authorization'] = 'Bearer ' . SaasApiToken::get();
			$response                         = wp_remote_post( $failed_requests['request_url'], $data );
			$response_code                    = wp_remote_retrieve_response_code( $response );
			$error_info                       = wp_remote_retrieve_body( $response );
			if ( 405 === wp_remote_retrieve_response_code( $response ) ) {
				$error_info = wp_remote_retrieve_response_message( $response );
			}
			if ( 0 === wp_remote_retrieve_response_code( $response ) ) {
				$error_info = __( 'Service not available', 'suretriggers' );
			}
			$wpdb->update(
				$table_name,
				[
					'request_method' => $failed_requests['request_method'],
					'request_url'    => $failed_requests['request_url'],
					'request_data'   => $failed_requests['request_data'],
					'response_code'  => $response_code,
					'status'         => ( 200 === $response_code ) ? 'success' : 'failed',
					'error_info'     => $error_info,
					'retry_attempts' => $failed_requests['retry_attempts'] + 1,
					'processed_at'   => current_time( 'mysql' ),
					'updated_at'     => current_time( 'mysql' ),
				],
				[ 'id' => $id ]
			);
			return true;
		}
		return false;
	}

	/**
	 * Delete failed webhook requests log that are 60 days older.
	 * Delete success webhook requests log that are 30 days older.
	 * 
	 * @return void
	 */
	public static function suretriggers_cleanup_requests_logs() {
		global $wpdb;
		$table_name = self::get_table_name();
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$table_name} WHERE status = %s AND created_at < NOW() - INTERVAL %d DAY", 'failed', 60 ) ); //phpcs:ignore
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$table_name} WHERE status = %s AND created_at < NOW() - INTERVAL %d DAY", 'success', 30 ) ); //phpcs:ignore
	}

	/**
	 * Verify WordPress connection with SureTriggers API to check the connection status twice daily.
	 * 
	 * @return void
	 */
	public static function suretriggers_verify_api_wp_connection() {
		$response = RestController::suretriggers_verify_wp_connection();
		// Check if the response is valid.
		if ( is_wp_error( $response ) ) {
			update_option( 'suretriggers_verify_connection', 'suretriggers_connection_wp_error' );
		} else {
			$status_code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $status_code ) {
				update_option( 'suretriggers_verify_connection', 'suretriggers_connection_error' );
			} else {
				update_option( 'suretriggers_verify_connection', 'suretriggers_connection_successful' );
			}
		}
	}
 
	/**
	 * Unschedule the event on plugin deletion.
	 * 
	 * @return void
	 */
	public static function suretriggers_remove_table_retry_cron() {
		// Clear custom scheduled cron created.
		wp_clear_scheduled_hook( 'suretriggers_retry_cron_schedule' );

		// Remove retry cron schedule on plugin deletion.
		$retry_failed_requests = wp_next_scheduled( 'suretriggers_retry_failed_requests' );
		if ( $retry_failed_requests ) {
			wp_unschedule_event( $retry_failed_requests, 'suretriggers_retry_failed_requests' );
		}

		// Remove clean up cron schedule.
		$webhook_requests_cleanup = wp_next_scheduled( 'suretriggers_webhook_requests_cleanup_logs' );
		if ( $webhook_requests_cleanup ) {
			wp_unschedule_event( $webhook_requests_cleanup, 'suretriggers_webhook_requests_cleanup_logs' );
		}

		// Remove connection verification cron schedule.
		$webhook_requests_cleanup = wp_next_scheduled( 'suretriggers_verify_api_connection' );
		if ( $webhook_requests_cleanup ) {
			wp_unschedule_event( $webhook_requests_cleanup, 'suretriggers_verify_api_connection' );
		}

		// Delete table on plugin delete.
		global $wpdb;
		$table_name = self::get_table_name();
		// Drop the custom table.
		$table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );
		if ( $table_exists ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$sql = "DROP TABLE IF EXISTS $table_name";
			dbDelta( $sql );
		}
	}

}

WebhookRequestsController::get_instance();
