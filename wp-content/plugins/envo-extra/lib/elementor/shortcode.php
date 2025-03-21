<?php

/**
 * Shortcode for elementor
 *
 * Based on plugin https://wordpress.org/plugins/anywhere-elementor/
 *
 * @since 1.0.0
 */

namespace Elementor;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class envo_extra_Shortcode {

	const SHORTCODE = 'elementor-template';

	public function __construct() {
		$this->add_actions();
	}

	public function admin_columns_headers( $defaults ) {
		$defaults[ 'shortcode' ] = esc_html__( 'Shortcode', 'envo-extra' );

		return $defaults;
	}

	public function admin_columns_content( $column_name, $post_id ) {
		if ( 'shortcode' === $column_name ) {
			// %s = shortcode, %d = post_id
			$shortcode = sprintf( '[%s id="%d"]', self::SHORTCODE, $post_id );
			printf( '<input class="widefat" type="text" readonly onfocus="this.select()" value="%s" />', esc_attr( $shortcode ) );
		}
	}

	public function shortcode( $attributes = [ ] ) {
		if ( !class_exists( 'Elementor\Plugin' ) ) {
			return '';
		}
		if ( empty( $attributes[ 'id' ] ) ) {
			return '';
		}
		if ( get_post_status ( $attributes[ 'id' ] ) != 'publish'  ) {
			return '';
		}

		$response = Plugin::instance()->frontend->get_builder_content_for_display( $attributes[ 'id' ] );
		return $response;
	}

	private function add_actions() {
		if ( is_admin() ) {
			add_action( 'manage_elementor_library_posts_columns', [ $this, 'admin_columns_headers' ] );
			add_action( 'manage_elementor_library_posts_custom_column', [ $this, 'admin_columns_content' ], 10, 2 );
		}

		add_shortcode( self::SHORTCODE, [ $this, 'shortcode' ] );
	}

}

new envo_extra_Shortcode();
