<?php
/**
 * Class AdminNotices
 */

namespace WPDesk\FS\Shipment;

use FSVendor\WPDesk\Notice\Notice;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\Session\SessionFactory;

/**
 * Display admin notices.
 */
class AdminNotices implements Hookable {

	/**
	 * @var SessionFactory
	 */
	private $session_factory;

	/**
	 * @param SessionFactory $session_factory .
	 */
	public function __construct( SessionFactory $session_factory ) {
		$this->session_factory = $session_factory;
	}

	/**
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );
	}

	/**
	 * .
	 */
	public function admin_notices() {
		if ( ! empty( $_REQUEST['bulk_flexible_shipping_send'] ) ) {
			$bulk_flexible_shipping_send_count = (int) sanitize_text_field( wp_unslash( $_REQUEST['bulk_flexible_shipping_send'] ) );

			new Notice(
				sprintf( __( 'Bulk send shipment - processed orders: %d', 'flexible-shipping' ), $bulk_flexible_shipping_send_count ) // phpcs:ignore
			);
		}

		if ( ! empty( $_REQUEST['bulk_flexible_shipping_labels'] ) ) {
			$bulk_flexible_shipping_labels_count = (int) sanitize_text_field( wp_unslash( $_REQUEST['bulk_flexible_shipping_labels'] ) );

			if ( ! empty( $_REQUEST['bulk_flexible_shipping_no_labels_created'] ) ) {
				new Notice(
					sprintf( __( 'Bulk labels - processed orders: %d. No labels for processed orders.', 'flexible-shipping' ), $bulk_flexible_shipping_labels_count ) // phpcs:ignore
				);
			} else {
				$labels = $this->session_factory->get_woocommerce_session_adapter()->get( 'flexible_shipping_bulk_labels' );
				if ( is_array( $labels ) ) {
					if ( isset( $labels['error'] ) ) {
						new Notice( $labels['error'], Notice::NOTICE_TYPE_ERROR, true, 20 );
					} else {
						$nonce = wp_create_nonce( 'flexible_shipping_labels' . basename( $labels['tmp_file'] ) );
						new Notice(
							sprintf(
								__( 'Bulk labels - processed orders: %d. If download not start automatically click %shere%s.', 'flexible-shipping' ), // phpcs:ignore
								$bulk_flexible_shipping_labels_count,
								'<a id="flexible_shipping_labels_url" target="_blank" href=' . esc_url( admin_url( 'admin.php?flexible_shipping_labels=' . basename( $labels['client_file'] ) . '&tmp_file=' . basename( $labels['tmp_file'] ) . '&nonce=' . $nonce ) ) . '>',
								'</a>'
							)
						);
					}
				}
			}
		}

		if ( ! empty( $_REQUEST['bulk_flexible_shipping_manifests'] ) ) {
			$bulk_flexible_shipping_manifest_count = (int) sanitize_text_field( wp_unslash( $_REQUEST['bulk_flexible_shipping_manifests'] ) );
			new Notice(
				sprintf( __( 'Bulk shipping manifest - processed orders: %d', 'flexible-shipping' ), $bulk_flexible_shipping_manifest_count ) // phpcs:ignore
			);

			if ( $this->session_factory->get_woocommerce_session_adapter()->get( 'flexible_shipping_bulk_manifests' ) ) {
				$messages = $this->session_factory->get_woocommerce_session_adapter()->get( 'flexible_shipping_bulk_manifests' );

				foreach ( $messages as $message ) {
					new Notice(
						$message['message'],
						$message['type']
					);
				}

				$this->session_factory->get_woocommerce_session_adapter()->set( 'flexible_shipping_bulk_manifests', null );
			}
		}
	}
}
