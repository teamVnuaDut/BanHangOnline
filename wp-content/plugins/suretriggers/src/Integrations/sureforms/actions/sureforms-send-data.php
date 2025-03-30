<?php
/**
 * SureFormsSendData.
 * php version 5.6
 *
 * @category SureFormsSendData
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\SureForms\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;

/**
 * SureFormsSendData
 *
 * @category SureFormsSendData
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SureFormsSendData extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'SureForms';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'sureforms_send_data';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Send Data', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];
		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id user_id.
	 * @param int   $automation_id automation_id.
	 * @param array $fields fields.
	 * @param array $selected_options selectedOptions.
	 * @psalm-suppress UndefinedMethod
	 * 
	 * @throws \Exception Exception.
	 *
	 * @return array|mixed
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$endpoint_url    = isset( $selected_options['endpoint_url'] ) ? esc_url( $selected_options['endpoint_url'] ) : '';
		$sf_data         = isset( $selected_options['sf_data_body'] ) ? $selected_options['sf_data_body'] : '';
		$file_attachment = isset( $selected_options['sf_attachment'] ) ? $selected_options['sf_attachment'] : '';

		// Handling SSRF Attack.
		$blocked_hosts = [
			'127.0.0.1', // Local access.
			'localhost',
			'192.168.0.0/16', // Organization access.
			'10.0.0.0/8',
			'172.16.0.0/12',
			'169.254.0.0/16',
			'0.0.0.0',
			'0.0.0.0/0',
			'169.254.169.254', // EC2 meta-data access.
		];

		$host = wp_parse_url( $endpoint_url, PHP_URL_HOST );

		if ( in_array( $host, $blocked_hosts ) ) {
			throw new \Exception( 'Access blocked.' );
		}

		$form_data = [
			'body'       => $sf_data,
			'attachment' => $file_attachment,
		];
		$json_body = wp_json_encode( $form_data );
		if ( false === $json_body ) {
			throw new \Exception( 'Failed to encode form data to JSON.' );
		}

		$args = [
			'method'    => 'POST',
			'headers'   => [
				'Content-Type' => 'application/json',
				'User-Agent'   => 'SureTriggers',
			],
			'sslverify' => true,
			'timeout'   => 30, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			'body'      => $json_body,
		];

		if ( null === $endpoint_url ) {
			return [];
		}
		// Send the HTTP request based on the method.
		$response = wp_remote_request( $endpoint_url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			if ( ! empty( $selected_options['test_action'] ) ) {
				return [
					'success' => false,
					'message' => 'Error: ' . $error_message,
				];
			}
			throw new \Exception( 'Request failed: ' . $error_message );
		}
		
		// Check for successful HTTP status codes (200, 201, 204).
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( ! in_array( $status_code, [ 200, 201, 204 ], true ) ) {
			$error = 'Failed to communicate with the API: ' . $endpoint_url;
			if ( ! empty( $selected_options['test_action'] ) ) {
				return [
					'success' => false,
					'message' => $error,
				];
			}
			throw new \Exception( 'API request failed: ' . wp_remote_retrieve_body( $response ) );
		}

		$result = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$result = [ 'response' => wp_remote_retrieve_body( $response ) ];
		}
		return $result;
	}
}

SureFormsSendData::get_instance();
