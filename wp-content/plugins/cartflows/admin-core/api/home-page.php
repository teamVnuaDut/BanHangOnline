<?php
/**
 * CartFlows Common Settings Data Query.
 *
 * @package CartFlows
 */

namespace CartflowsAdmin\AdminCore\Api;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CartflowsAdmin\AdminCore\Api\ApiBase;
use CartflowsAdmin\AdminCore\Inc\AdminHelper;

/**
 * Class Admin_Query.
 */
class HomePage extends ApiBase {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = '/admin/homepage/';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base_setup = '/admin/setup-checklist/';

	/**
	 * Instance
	 *
	 * @access private
	 * @var object Class object.
	 * @since 1.0.0
	 */
	private static $instance;

	/**
	 * Initiator
	 *
	 * @since 1.0.0
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Init Hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_routes() {

		$namespace = $this->get_api_namespace();

		register_rest_route(
			$namespace,
			$this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_home_page_settings' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => array(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);

		register_rest_route(
			$namespace,
			$this->rest_base_setup,
			array(
				array(
					'methods'             => 'POST', // WP_REST_Server::READABLE.
					'callback'            => array( $this, 'get_setup_details' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => array(), // get_collection_params may use.
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get common settings.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 */
	public function get_home_page_settings( $request ) {

		$boxes_settings = AdminHelper::get_admin_settings_option( $request['wcf_box_id'] );

		$home_page_settings = array(
			'is_hidden' => $boxes_settings,
		);

		return $home_page_settings;
	}

	/**
	 * Get items
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function get_setup_details( $request ) {

		// Get only the checkout steps.
		$checkout_steps = get_posts(
			array(
				'post_type'   => CARTFLOWS_STEP_POST_TYPE,
				'post_status' => array( 'publish' ),
				'orderby'     => 'ID',
				'order'       => 'DESC',
				'meta_query'  => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => 'wcf-step-type',
						'value'   => 'checkout',
						'compare' => '===',
					),
				),
				'fields'      => 'ids',
				'numberposts' => 10,
			) 
		);
		
		// Select the first checkout step from the array.
		$first_checkout_id = ! empty( $checkout_steps ) && is_array( $checkout_steps ) ? reset( $checkout_steps ) : '';
		// Get the flow ID from the checkout step from the first selected checkout ID from the array.
		$first_step_flow_id = ! empty( $first_checkout_id ) ? (int) wcf()->utils->get_flow_id_from_step_id( $first_checkout_id ) : '';

		// Prepare the data for the free version and use the filter to add the PRO related data.
		$setup_data = apply_filters( 
			'cartflows_onboarding_setup_details',
			array(
				'flow_exists'      => intval( wp_count_posts( CARTFLOWS_FLOW_POST_TYPE )->publish ),
				'checkout_step_id' => $first_checkout_id,
				'checkout_flow_id' => $first_step_flow_id,
			),
			$checkout_steps
		);

		$response = new \WP_REST_Response( $setup_data );
		$response->set_status( 200 );

		return $response;
	}

	/**
	 * Check whether a given request has permission to read notes.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {

		if ( ! current_user_can( 'cartflows_manage_flows_steps' ) ) {
			return new \WP_Error( 'cartflows_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'cartflows' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}
}
