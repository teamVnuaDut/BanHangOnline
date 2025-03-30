<?php
/**
 * RestController.
 * php version 5.6
 *
 * @category RestController
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Controllers;

use Exception;
use SureTriggers\Integrations\WordPress\WordPress;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Models\SaasApiToken;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

/**
 * RestController
 *
 * @category RestController
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RestController {

	/**
	 * Access token for authentication.
	 *
	 * @var string $acccess_token
	 */
	private $secret_key;

	use SingletonLoader;

	/**
	 * Initialise data.
	 */
	public function __construct() {
		$this->secret_key = SaasApiToken::get();
		add_filter( 'determine_current_user', [ $this, 'basic_auth_handler' ], 20 );
		add_filter( 'debug_information', [ $this, 'sure_triggers_connection_info' ] );
	}

	/**
	 * Permission callback for rest api after determination of current user.
	 *
	 * @param WP_REST_Request $request Request.
	 */
	public function autheticate_user( $request ) {
		$secret_key       = $request->get_header( 'st_authorization' );
		list($secret_key) = sscanf( $secret_key, 'Bearer %s' );

		if ( $this->secret_key !== $secret_key ) {
			return false;
		}

		return true;
	}

	/**
	 * Create WP Connection.
	 * 
	 * @param WP_REST_Request $request Request data.
	 * @return WP_REST_Response
	 */
	public function create_wp_connection( $request ) {

		$user_agent = $request->get_header( 'user-agent' );
		if ( 'SureTriggers' !== $user_agent ) {
			return new WP_REST_Response(
				[
					'success' => false,
					'data'    => 'Unauthorized',
				],
				403
			);
		}
		$params = $request->get_json_params();

		$username = isset( $params['wp-username'] ) ? sanitize_text_field( $params['wp-username'] ) : '';
		$password = isset( $params['wp-password'] ) ? $params['wp-password'] : '';

		if ( empty( $username ) || empty( $password ) ) {
			return new WP_REST_Response(
				[
					'success' => false,
					'data'    => 'Username and password are required.',
				],
				400
			);
		}

		$user = wp_authenticate_application_password( null, $username, $password );

		if ( is_wp_error( $user ) ) {
			return new WP_REST_Response(
				[
					'success' => false,
					'data'    => 'Invalid username or password.',
				],
				403
			);
		}

		$connection_status = $request->get_param( 'connection-status' );
		$access_key        = $request->get_param( 'sure-triggers-access-key' );
		$connected_email   = $request->get_param( 'connected_email' );

		if ( false === $connection_status ) {
			$access_key = 'connection-denied';
		}
		
		$connected_email_id = isset( $connected_email ) ? sanitize_email( wp_unslash( $connected_email ) ) : '';

		if ( isset( $access_key ) ) {
			SaasApiToken::save( $access_key );
		}
		OptionController::set_option( 'connected_email_key', $connected_email_id );

		return new WP_REST_Response(
			[
				'success' => true,
				'data'    => 'Connected successfully.',
			],
			200
		);
	}

	/**
	 * Verify user token.
	 * 
	 * @return  array|WP_Error $response Response.
	 */
	public static function verify_user_token() {
		$args     = [
			'body'      => [
				'token'      => SaasApiToken::get(),
				'saas-token' => SaasApiToken::get(),
				'base_url'   => str_replace( '/wp-json/', '', get_rest_url() ),
			],
			'sslverify' => false,
		];
		$response = wp_remote_post( SURE_TRIGGERS_API_SERVER_URL . '/token/verify', $args );

		return $response;
	}

	/**
	 * Verify connection.
	 * 
	 * @return  array|WP_Error $response Response.
	 */
	public static function suretriggers_verify_wp_connection() {
		$args     = [
			'body'      => [
				'saas-token'     => SaasApiToken::get(),
				'base_url'       => str_replace( '/wp-json/', '', get_rest_url() ),
				'plugin_version' => SURE_TRIGGERS_VER,
			],
			'sslverify' => false,
		];
		$response = wp_remote_post( SURE_TRIGGERS_API_SERVER_URL . '/connection/wordpress/ping', $args );
		return $response;
	}

	/**
	 * Authenticate User for API calls.
	 *
	 * @param array|object $user USer.
	 *
	 * @return int|null
	 */
	public function basic_auth_handler( $user ) {
		// Don't authenticate twice.
		if ( ! empty( $user ) ) {
			return $user;
		}

		// Check that we're trying to authenticate.
		if ( ! isset( $_SERVER['PHP_AUTH_USER'] ) || ! isset( $_SERVER['PHP_AUTH_PW'] ) ) { //phpcs:ignore
			return $user;
		}

		$username = sanitize_text_field( wp_unslash( $_SERVER['PHP_AUTH_USER'] ) ); //phpcs:ignore
		$password = sanitize_text_field( wp_unslash( $_SERVER['PHP_AUTH_PW'] ) ); //phpcs:ignore

		/**
		 * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls.
		 * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite.
		 * recursion and a stack overflow unless the current function is removed from the determine_current_user.
		 * filter during authentication.
		 */
		remove_filter( 'determine_current_user', [ $this, 'basic_auth_handler' ], 20 );

		$user = wp_authenticate( $username, $password );

		add_filter( 'determine_current_user', [ $this, 'basic_auth_handler' ], 20 );

		if ( is_wp_error( $user ) ) {
			return null;
		}

		return $user->ID;
	}

	/**
	 * Authenticate user for new connection create api.
	 *
	 * @return bool
	 */
	public function is_current_user() {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Execute action events.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return WP_REST_Response
	 */
	public function run_action( $request ) {
		$request->get_param( 'wp_user_id' );

		$user_id          = $request->get_param( 'wp_user_id' );
		$automation_id    = $request->get_param( 'automation_id' );
		$integration      = $request->get_param( 'integration' );
		$action_type      = $request->get_param( 'type_event' );
		$selected_options = $request->get_param( 'selected_options' );
		$context          = $request->get_param( 'context' );
		$fields           = $request->get_param( 'fields' );

		if ( empty( $user_id ) ) {
			$user_id = isset( $context['pluggable_data']['wp_user_id'] ) ? sanitize_text_field( $context['pluggable_data']['wp_user_id'] ) : '';
		}

		if ( empty( $integration ) || empty( $action_type ) ) {
			return self::error_message( 'Integration or action type is missing' );
		}

		if ( isset( $selected_options['wp_user_email'] ) && ! ( 'EDD' === $integration && 'find_user_purchased_download' === $action_type ) ) {
			$is_valid = WordPress::validate_email( $selected_options['wp_user_email'] );

			if ( ! $is_valid->valid ) {
				if ( $is_valid->multiple ) {
					return self::error_message( 'One or more email address is not valid.' );
				} else {
					return self::error_message( 'Email address is not valid.' );
				}
			}

			if ( str_contains( $selected_options['wp_user_email'], ',' ) ) {
				$email_list = explode( ',', $selected_options['wp_user_email'] );

				foreach ( $email_list as $single_email ) {
					if ( ! email_exists( trim( $single_email ) ) ) {
						return self::error_message( 'User with email ' . $single_email . ' does not exists.' );
					}
				}
			} else {
				if ( ! email_exists( $selected_options['wp_user_email'] ) ) {
					return self::error_message( 'User with email ' . $selected_options['wp_user_email'] . ' does not exists.' );
				}
			}
		}
		$registered_actions = EventController::get_instance()->actions;
		$action_event       = $registered_actions[ $integration ][ $action_type ];

		$fun_params = [
			$user_id,
			$automation_id,
			$fields,
			$selected_options,
			$context,
		];

		try {
			$result = call_user_func_array(
				$action_event['function'],
				$fun_params
			);
			return self::success_message( $result );
		} catch ( Exception $e ) {
			return self::error_message( $e->getMessage(), 400 );
		}
	}

	/**
	 * Error message format.
	 *
	 * @param string $message Error message.
	 * @param string $status Error message.
	 *
	 * @return object
	 */
	public static function error_message( $message, $status = 401 ) {
		return new WP_REST_Response(
			[
				'success' => false,
				'data'    => [
					'errors' => $message,
				],
			],
			$status
		);
	}

	/**
	 * Success message format.
	 *
	 * @param array $data response data to be sent.
	 *
	 * @return object
	 */
	public static function success_message( $data = [] ) {
		$result = [];

		if ( ! empty( $data ) ) {
			$result['result'] = $data;
		}

		return new WP_REST_Response(
			[
				'success' => true,
				'data'    => $result,
			],
			200
		);

	}

	/**
	 * Add/Remove/Update the triggers..
	 * When new/update/remove automation on Sass then execute this endpoint to update the automation.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return WP_REST_Response
	 */
	public function manage_triggers( $request ) {
		$events = $request->get_param( 'events' ) ? json_decode( stripslashes( $request->get_param( 'events' ) ), true ) : [];

		// Selected field data from the trigger.
		$data = $request->get_param( 'data' ) ? json_decode( stripslashes( $request->get_param( 'data' ) ), true ) : [];

		// Get the trigger data from the option and append data in trigger data option.
		$trigger_data = OptionController::get_option( 'trigger_data' );
		if ( empty( $trigger_data ) ) {
			$trigger_data = [];
		}

		if ( is_array( $data ) && is_array( $events ) ) {
			$index = array_search( $data['trigger'], array_column( $events, 'trigger' ) );
			if ( is_array( $trigger_data ) && false !== $index && $data['integration'] === $events[ $index ]['integration'] ) {
				$trigger_data[ $data['integration'] ][ $data['trigger'] ]['selected_options'] = $data['selected_data'];
			}
		}

		OptionController::set_option( 'triggers', $events );
		// Set the new option for the trigger data.
		OptionController::set_option( 'trigger_data', $trigger_data );
		$events = array_column( $events, 'trigger' );
		return self::success_message(
			[
				'events' => $events,
				'data'   => $trigger_data,
			] 
		);
	}

	/**
	 * Send response to Saas that trigger is executed.
	 *
	 * @param array $trigger_data Trigger data.
	 *
	 * @return bool
	 */
	public function trigger_listener( $trigger_data ) {
		// Pass unique WordPress webhook id.
		$wordpress_webhook_uuid                 = str_replace( '-', '', wp_generate_uuid4() );
		$site_url                               = esc_url_raw( str_replace( '/wp-json/', '', get_site_url() ) );
		$site_url                               = preg_replace( '/^https?:\/\//', '', $site_url );
		$encoded_site_url                       = urlencode( (string) $site_url );
		$trigger_data['wordpress_webhook_uuid'] = $wordpress_webhook_uuid . '_' . $encoded_site_url;
		$args                                   = [
			'headers'   => [
				'Authorization'  => 'Bearer ' . $this->secret_key,
				'Referer'        => str_replace( '/wp-json/', '', get_site_url() ),
				'RefererRestUrl' => str_replace( '/wp-json/', '', get_rest_url() ),
			],
			'body'      => json_decode( wp_json_encode( $trigger_data ), 1 ),
			'sslverify' => false,
		];
		
		/**
		 *
		 * Ignore line
		 *
		 * @phpstan-ignore-next-line
		 */
		$response = wp_remote_post( SURE_TRIGGERS_WEBHOOK_SERVER_URL . '/wordpress/webhook', $args );
		// Store every webhook requests.
		$error_info = wp_remote_retrieve_body( $response );
		if ( 405 === wp_remote_retrieve_response_code( $response ) ) {
			$error_info = wp_remote_retrieve_response_message( $response );
		}
		if ( 0 === wp_remote_retrieve_response_code( $response ) ) {
			$error_info = __( 'Service not available', 'suretriggers' );
		}
		unset( $args['headers']['Authorization'] );
		WebhookRequestsController::suretriggers_log_request( (string) wp_json_encode( $args ), (int) wp_remote_retrieve_response_code( $response ), $error_info );

		if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
			return true;
		}

		return false;
	}

	/**
	 * Update the connection from SAAS.
	 *
	 * @param WP_REST_Request $request Request data.
	 *
	 * @return void
	 */
	public function connection_update( $request ) {
		$secret = $request->get_param( 'secret_key' );
		if ( $secret && is_string( $secret ) ) {
			SaasApiToken::save( $secret );
		}
	}

	/**
	 * Disconnect connection
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return WP_REST_Response
	 */
	public function connection_disconnect( $request ) {
		SaasApiToken::save( null );
		return self::success_message();
	}

	/**
	 * Test Trigger
	 * When test trigger is initiated on Sass then execute this endpoint to create a transient for identifying trigger event.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return WP_REST_Response
	 */
	public function test_triggers( $request ) {
		$test_triggers = (array) OptionController::get_option( 'test_triggers', [] );
		$event         = [
			'trigger'     => $request->get_param( 'trigger' ),
			'integration' => $request->get_param( 'integration' ),
		];

		// if request is to delete the transient, delete it and return.
		if ( $request->get_param( 'clear_transient_data' ) === 'yes' ) {
			$test_triggers = array_filter(
				$test_triggers,
				function ( $v ) use ( $event ) {
					return $v !== $event;
				}
			);
			OptionController::set_option( 'test_triggers', $test_triggers );

			return;
		}

		$test_triggers[]   = $event;
		$test_triggers     = array_unique( $test_triggers, SORT_REGULAR );
		$tmp_test_triggers = [];

		foreach ( $test_triggers as $test_trigger ) {
			if ( ! empty( $test_trigger['trigger'] ) ) {
				$tmp_test_triggers[] = $test_trigger;
			}
		}

		OptionController::set_option( 'test_triggers', $tmp_test_triggers );
	}

	/**
	 * SureTriggers Connection Info
	 *
	 * @param array $debug_info Info data.
	 * @return array
	 */
	public function sure_triggers_connection_info( $debug_info ) {
		// Verify if SureTriggers is connected successfully.
		$response   = self::verify_user_token();
		$connection = ( wp_remote_retrieve_response_code( $response ) === 200 );
		if ( $connection ) {
			$connection_status = 'Connection Successfully Set';
		} else {
			$connection_status = 'Error in Connection';
		}
		$debug_info['suretriggers'] = [
			'label'  => __( 'SureTriggers', 'suretriggers' ),
			'fields' => [
				'suretriggers_status'  => [
					'label'   => __( 'SureTriggers Status', 'suretriggers' ),
					'value'   => $connection_status,
					'private' => false,
				],
				'rest_url'             => [
					'label'   => __( 'Rest URL', 'suretriggers' ),
					'value'   => esc_url( get_rest_url() ),
					'private' => false,
				],
				'suretriggers_version' => [
					'label'   => __( 'SureTriggers Version', 'suretriggers' ),
					'value'   => SURE_TRIGGERS_VER,
					'private' => false,
				],
			],
		];
		return $debug_info;
	}

}

RestController::get_instance();
