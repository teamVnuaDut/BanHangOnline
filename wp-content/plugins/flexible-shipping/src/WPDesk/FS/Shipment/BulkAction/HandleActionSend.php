<?php
/**
 * Class HandleActionSend
 */

namespace WPDesk\FS\Shipment\BulkAction;

use Exception;
use FSVendor\WPDesk\Session\SessionFactory;
use WPDesk_Flexible_Shipping_Shipment;
use WPDesk_Flexible_Shipping_Shipment_Interface;

/**
 * .
 */
class HandleActionSend implements HandleActionStrategyInterface {

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
		$messages = [];
		foreach ( $post_ids as $post_id ) {
			$shipments            = fs_get_order_shipments( $post_id );
			$messages[ $post_id ] = [];

			foreach ( $shipments as $shipment ) {
				/* @var $shipment WPDesk_Flexible_Shipping_Shipment|WPDesk_Flexible_Shipping_Shipment_Interface */
				try {
					$shipment->set_sent_via_bulk();
					$shipment->api_create();
					$messages[ $post_id ][ $shipment->get_id() ] = [
						'status'  => 'created',
						'message' => __( 'Shipment created.', 'flexible-shipping' ),
					];
				} catch ( Exception $e ) {
					$messages[ $post_id ][ $shipment->get_id() ] = [
						'status'  => 'error',
						'message' => $e->getMessage(),
					];
				}
			}
			$messages[ $post_id ][] = apply_filters(
				'flexible_shipping_bulk_send',
				[
					'status'  => 'none',
					'message' => __( 'No action performed.', 'flexible-shipping' ),
				],
				$post_id
			);
		}

		$this->session_factory->get_woocommerce_session_adapter()->set( 'flexible_shipping_bulk_send', $messages );

		return add_query_arg( 'bulk_flexible_shipping_send', count( $post_ids ), $redirect_to );
	}
}
