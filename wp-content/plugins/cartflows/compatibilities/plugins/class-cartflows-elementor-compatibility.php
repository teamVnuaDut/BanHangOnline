<?php
/**
 * Elementor page builder compatibility
 *
 * @package CartFlows
 */

namespace Elementor\Modules\PageTemplates;

use Elementor\Core\Base\Document;
use Elementor\Plugin;
use Cartflows_Helper;
use WP_REST_Request;
use WP_REST_Response;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class for elementor page builder compatibility
 */
class Cartflows_Elementor_Compatibility {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Constructor
	 */
	public function __construct() {
		add_filter( 'cartflows_page_template', array( $this, 'get_page_template' ) );

		if ( wcf()->is_woo_active ) {

			// On Editor - Register WooCommerce frontend hooks before the Editor init.
			// Priority = 5, in order to allow plugins remove/add their wc hooks on init.
			if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
			}

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'maybe_init_cart' ) );
		}
		add_action( 'wp_enqueue_scripts', array( $this, 'generate_gcp_elem_css_style' ), 10000 );
		add_filter( 'rest_post_dispatch', array( $this, 'elementor_add_cartflows_colors' ), 1000, 3 );
		add_filter( 'rest_request_after_callbacks', array( $this, 'display_cartflows_global_colors_front_end' ), 1000, 3 );
	
		
	}

	/**
	 * Enqueue styles for elementor preview mode
	 *
	 * @since 2.1.8
	 * @return void
	 */
	public function generate_gcp_elem_css_style() {

		$gcp_vars = '';

		$flow_id = wcf()->utils->get_flow_id();
		if ( empty( $flow_id ) ) {
			return;
		}

		$gcp_vars_array = array_keys( Cartflows_Helper::get_gcp_vars() );

		foreach ( $gcp_vars_array as $slug ) {
			// Gather value of global color VAR.
			$color_value = wcf()->options->get_flow_meta_value( $flow_id, $slug, '' );

			if ( empty( $color_value ) ) {
				continue;
			}

			// Convert it into the CSS var.
			$slug      = str_replace( '-', '', $slug );
			$gcp_vars .= '--e-global-color-' . $slug . ': ' . $color_value . '; ';
		}
		$output = ':root { ' . $gcp_vars . ' }';
		wp_add_inline_style( 'wcf-frontend-global', $output );
	}

	/**
	 * Get page template filter callback for elementor preview mode
	 *
	 * @param string $template page template.
	 * @return string
	 */
	public function get_page_template( $template ) {

		if ( is_singular() ) {
			$document = Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );

			if ( $document ) {
				$template = $document->get_meta( '_wp_page_template' );
			}
		}

		return $template;
	}

	/**
	 * Rgister wc hookes for elementor preview mode
	 */
	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	/**
	 * Init cart in elementor preview mode
	 */
	public function maybe_init_cart() {

		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	/**
	 * Display theme global colors to Elementor Global colors.
	 *
	 * @since 2.1.8
	 * @param WP_REST_Response                      $response REST request response.
	 * @param array{0: object, 1: string}|callable  $handler Route handler used for the request.
	 * @param WP_REST_Request<array<string, mixed>> $request Request used to generate the response.
	 * @return WP_REST_Response
	 */
	public function elementor_add_cartflows_colors( $response, $handler, $request ) {

		$route = $request->get_route();

		if ( '/elementor/v1/globals' !== $route ) {
			return $response;
		}

		// Ensure $data is an array.
		$data = $response->get_data();

		if ( ! is_array( $data ) ) {
			return $response; // Ensure `$data` is an array before accessing keys.
		}

		$referer_url = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : ''; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$parsed_url = parse_url( $referer_url ); //phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
		$post_id    = 0;

		// Check if the query part exists in the URL.
		if ( isset( $parsed_url['query'] ) ) {
			parse_str( $parsed_url['query'], $query_params );
			$post_id = isset( $query_params['post'] ) ? (int) $query_params['post'] : 0;
		}

		// Get flow ID from CartFlows.
		$flow_id = wcf()->utils->get_flow_id_from_step_id( $post_id );

		// Generate global palette.
		$global_palette = Cartflows_Helper::generate_css_var_array( $flow_id, 'elementor' );

		// Ensure 'colors' exists in the array before merging.
		if ( ! isset( $data['colors'] ) || ! is_array( $data['colors'] ) ) {
			$data['colors'] = array();
		}

		$data['colors'] = array_merge( $data['colors'], $global_palette );

		$response->set_data( $data );

		return $response;
	}

	/**
	 * Display theme global colors to Elementor Global colors.
	 *
	 * @since 2.1.8
	 * @param WP_REST_Response                      $response REST request response.
	 * @param array{0: object, 1: string}|callable  $handler Route handler used for the request.
	 * @param WP_REST_Request<array<string, mixed>> $request Request used to generate the response.
	 * @return WP_REST_Response
	 */
	public function display_cartflows_global_colors_front_end( $response, $handler, $request ) {
		$route = $request->get_route();
		
		if ( 0 !== strpos( $route, '/elementor/v1/globals' ) ) {
			return $response;
		}

		$post_id = get_the_ID();
	
		// Get flow ID from CartFlows.
		$flow_id = wcf()->utils->get_flow_id_from_step_id( $post_id );
	
		// Generate global palette.
		$slug_map       = array();
		$global_palette = Cartflows_Helper::generate_css_var_array( $flow_id, 'elementor' );
		$css_vars       = Cartflows_Helper::get_gcp_vars();
		foreach ( $css_vars as $key => $slug ) {
			// Remove hyphens as hyphens do not work with Elementor global styles.
			$no_hyphens              = str_replace( '-', '', $key );
			$slug_map[ $no_hyphens ] = $no_hyphens;
		}

		$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );
		if ( ! in_array( $rest_id, array_keys( $slug_map ), true ) ) {
			return $response;
		}
		
		$response = rest_ensure_response(
			array(
				'id'    => esc_attr( $rest_id ),
				'title' => $slug_map[ $rest_id ],
				'value' => $global_palette[ $slug_map[ $rest_id ] ],
			)
		);
		
		return $response;
	}
}

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Cartflows_Elementor_Compatibility::get_instance();
