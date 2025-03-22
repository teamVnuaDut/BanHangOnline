<?php
/**
 * Class DispatchLabelFile
 */

namespace WPDesk\FS\Shipment;

use FSVendor\WPDesk\FS\Shipment\Label\LabelsFileDispatcher;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class DispatchLabelFile implements Hookable {

	/**
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_init', [ $this, 'dispatch_labels_file_if_expected' ], 1 );
	}

	/**
	 * Dispatch labels file if requested.
	 */
	public function dispatch_labels_file_if_expected() {
		if ( ! isset( $_GET['flexible_shipping_labels'], $_GET['tmp_file'], $_GET['nonce'] ) ) {
			return;
		}

		$file     = trailingslashit( sys_get_temp_dir() ) . basename( sanitize_text_field( wp_unslash( $_GET['flexible_shipping_labels'] ) ) );
		$tmp_file = trailingslashit( sys_get_temp_dir() ) . basename( sanitize_text_field( wp_unslash( $_GET['tmp_file'] ) ) );

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'flexible_shipping_labels' . basename( $tmp_file ) ) ) {
			wp_die( esc_html__( 'Nonce verification failed!', 'flexible-shipping' ) );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page!', 'flexible-shipping' ) );
		}

		if ( ! file_exists( $tmp_file ) ) {
			wp_die( esc_html__( 'This file was already downloaded! Please retry bulk action!', 'flexible-shipping' ) );
		}

		$labels_file_dispatcher = new LabelsFileDispatcher();
		$labels_file_dispatcher->dispatch_and_delete_labels_file( $file, $tmp_file );
		die();
	}
}
