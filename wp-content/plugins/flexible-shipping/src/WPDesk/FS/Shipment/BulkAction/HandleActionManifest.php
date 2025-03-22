<?php
/**
 * Class HandleActionManifest
 */

namespace WPDesk\FS\Shipment\BulkAction;

use Exception;
use FSVendor\WPDesk\Session\SessionFactory;
use WPDesk_Flexible_Shipping_Shipment;
use WPDesk_Flexible_Shipping_Shipment_Interface;

/**
 * .
 */
class HandleActionManifest implements HandleActionStrategyInterface {

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
	 * @param string $redirect_to .
	 * @param array  $post_ids    .
	 *
	 * @return string
	 */
	public function handle( string $redirect_to, array $post_ids ): string {
		$manifests = [];
		foreach ( $post_ids as $post_id ) {
			$shipments = fs_get_order_shipments( $post_id );
			foreach ( $shipments as $shipment ) {
				/* @var $shipment WPDesk_Flexible_Shipping_Shipment|WPDesk_Flexible_Shipping_Shipment_Interface */
				if ( $shipment->get_status() !== 'fs-confirmed' || $shipment->get_meta( '_manifest', '' ) !== '' ) {
					continue;
				}
				try {
					$integration   = $shipment->get_integration();
					$manifest_name = $integration;
					if ( method_exists( $shipment, 'get_manifest_name' ) ) {
						$manifest_name = $shipment->get_manifest_name();
					}
					$manifest = null;
					if ( empty( $manifests[ $manifest_name ] ) ) {
						if ( fs_manifest_integration_exists( $integration ) ) {
							$manifest = fs_create_manifest( $integration );
						}
					} else {
						$manifest = $manifests[ $manifest_name ];
					}
					if ( null !== $manifest ) {
						$manifest->add_shipments( $shipment );
						$manifest->save();
						$shipment->update_status( 'fs-manifest' );
						$shipment->save();
						$manifests[ $manifest_name ] = $manifest;
					}
				} catch ( Exception $e ) { // phpcs:ignore
					// Do nothing.
				}
			}
		}
		$messages     = [];
		$integrations = apply_filters( 'flexible_shipping_integration_options', [] );

		foreach ( $manifests as $manifest ) {
			try {
				$manifest->generate();
				$manifest->save();
				$download_manifest_url = admin_url( 'edit.php?post_type=shipping_manifest&flexible_shipping_download_manifest=' . $manifest->get_id() . '&nonce=' . wp_create_nonce( 'flexible_shipping_download_manifest' ) );
				$messages[]            = [
					'type'    => 'updated',
					'message' => sprintf(
					// Translators: manifests count and integration.
						__( 'Created manifest: %s (%s). If download not start automatically click %shere%s.', 'flexible-shipping' ), // phpcs:ignore
						$manifest->get_number(),
						$integrations[ $manifest->get_integration() ],
						'<a class="shipping_manifest_download" target="_blank" href="' . $download_manifest_url . '">',
						'</a>'
					),
				];
			} catch ( Exception $e ) {
				$messages[] = [
					'type'    => 'error',
					'message' => sprintf(
						__( 'Manifest creation error: %s (%s).', 'flexible-shipping' ), // phpcs:ignore
						$e->getMessage(),
						$integrations[ $manifest->get_integration() ]
					),
				];
				fs_delete_manifest( $manifest );
			}
		}
		if ( count( $messages ) === 0 ) {
			$messages[] = [
				'type'    => 'updated',
				'message' => __( 'No manifests created.', 'flexible-shipping' ),
			];
		}
		$this->session_factory->get_woocommerce_session_adapter()->set( 'flexible_shipping_bulk_manifests', $messages );

		return add_query_arg( 'bulk_flexible_shipping_manifests', count( $post_ids ), $redirect_to );
	}
}
