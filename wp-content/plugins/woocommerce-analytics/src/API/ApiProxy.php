<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\API;

use Automattic\Jetpack\Connection\Client as JetpackClient;
use Automattic\Jetpack\Connection\Manager as JetpackManager;
use Automattic\WooCommerce\Analytics\HelperTraits\LoggerTrait;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;
use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Class ApiProxy
 *
 * @package Automattic\WooCommerce\Analytics\API
 */
class ApiProxy extends WC_REST_Controller implements RegistrableInterface {
	use LoggerTrait;
	use Utilities;

	/** @var string */
	private const NAMESPACE = 'wc/v3';

	/** @var string */
	private const REST_BASE_SUFFIX = '/proxy';

	/** @var int */
	private const CACHE_TTL = 5 * MINUTE_IN_SECONDS;

	/**
	 * The base of the REST API route.
	 *
	 * @var string
	 */
	protected $rest_base;

	/**
	 * Timeout for the external API request.
	 *
	 * @var int
	 */
	protected int $api_timeout = 20;

	/**
	 * @var JetpackManager
	 */
	protected JetpackManager $manager;

	/**
	 * ApiProxy constructor.
	 *
	 * @param JetpackManager  $manager The Jetpack manager.
	 * @param LoggerInterface $logger The logger.
	 */
	public function __construct( JetpackManager $manager, LoggerInterface $logger ) {
		$this->rest_base = $this->get_plugin_slug() . self::REST_BASE_SUFFIX;
		$this->manager   = $manager;
		$this->set_logger( $logger );
	}

	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			$this->rest_base . '/(?P<endpoint>.*)',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'handle_proxy_request' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Check if a given request has permission to read the external API.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return bool
	 */
	public function check_permission( WP_REST_Request $request ): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Handle a proxy request to a third-party API.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error Response object or WP_Error.
	 */
	public function handle_proxy_request( WP_REST_Request $request ) {
		// Check if there's a valid Jetpack connection.
		if ( ! $this->manager->is_connected() ) {
			return new WP_Error( 'no_connection', 'Please connect to external API.', array( 'status' => 403 ) );
		}

		$endpoint_url = $this->build_endpoint_url( $request );
		try {
			$response = JetpackClient::wpcom_json_api_request_as_blog(
				$endpoint_url,
				'2',
				array(
					'method'  => 'GET',
					'timeout' => $this->api_timeout,
				),
				null,
				'wpcom'
			);

			if ( is_wp_error( $response ) ) {
				$this->logger->log_error( 'API request failed: ' . $response->get_error_message(), __METHOD__ );
				return new WP_Error( 'api_error', 'Error communicating with external API', array( 'status' => 500 ) );
			}

			return $this->get_response_maybe_cached( $response, $request );
		} catch ( \Exception $e ) {
			$this->logger->log_exception( $e, __METHOD__ );
			return new WP_Error( 'api_error', 'Error processing external API request', array( 'status' => 500 ) );
		}
	}

	/**
	 * Build the full URL for the external API endpoint.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return string The full URL for the external API endpoint.
	 */
	protected function build_endpoint_url( WP_REST_Request $request ): string {
		$endpoint = $request->get_param( 'endpoint' );

		// Prepend the Analytics API base to the endpoint including the site ID.
		$site_id      = (string) \Jetpack_Options::get_option( 'id' );
		$endpoint_url = sprintf( '/sites/%s/analytics/%s', $site_id, $endpoint );

		// Add query params to the endpoint.
		$params = $request->get_query_params();
		if ( is_array( $params ) && ! empty( $params ) ) {
			$endpoint_url .= '?' . http_build_query( $params );
		}

		return $endpoint_url;
	}

	/**
	 * Get the schema for the proxy endpoint.
	 *
	 * @return array
	 */
	public function get_item_schema(): array {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'proxy',
			'type'       => 'object',
			'properties' => array(
				'endpoint' => array(
					'description' => 'The remote API endpoint to proxy.',
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Get the REST API path for the proxy endpoint.
	 *
	 * @return string The REST API path.
	 */
	public function get_proxy_path(): string {
		return self::NAMESPACE . '/' . $this->rest_base;
	}

	/**
	 * Get the response from the external API, possibly cached.
	 *
	 * @param array           $jetpack_client_http_response Jetpack Client HTTP response.
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response The response object.
	 */
	protected function get_response_maybe_cached( array $jetpack_client_http_response, WP_REST_Request $request ): WP_REST_Response {
		$body    = wp_remote_retrieve_body( $jetpack_client_http_response );
		$status  = wp_remote_retrieve_response_code( $jetpack_client_http_response );
		$headers = wp_remote_retrieve_headers( $jetpack_client_http_response );

		$response = new WP_REST_Response( json_decode( $body, false ), $status );

		$headers_to_include = array( 'x-wp-total', 'x-wp-totalpages' );
		foreach ( $headers_to_include as $header_name ) {
			if ( isset( $headers[ $header_name ] ) ) {
				$response->header( $header_name, $headers[ $header_name ] );
			}
		}

		$cache_time = $this->determine_cache_time( $body, $status, $request );
		if ( $cache_time > 0 ) {
			add_filter( 'rest_send_nocache_headers', '__return_false' );
			$response->header( 'Cache-Control', 'private, max-age=' . $cache_time );
		}

		return $response;
	}

	/**
	 * Determine the appropriate cache time based on the response and request data.
	 *
	 * @param string          $body The response body.
	 * @param int             $status The response status code.
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return int The cache time in seconds.
	 */
	private function determine_cache_time( string $body, int $status, WP_REST_Request $request ): int {
		// TODO: Implement logic to determine cache time based on endpoint and response.
		return 200 === $status ? self::CACHE_TTL : 0;
	}
}
