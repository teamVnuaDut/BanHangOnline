<?php
/**
 * Class ModifyOrderTable
 */

namespace WPDesk\FS\Shipment;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\Session\SessionFactory;
use WC_Order;
use WPDesk_Flexible_Shipping_Shipment;
use WPDesk_Flexible_Shipping_Shipment_Interface;

/**
 * .
 */
class ModifyOrderTable implements Hookable {

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
		// Old Screen.
		add_filter( 'manage_edit-shop_order_columns', [ $this, 'manage_edit_shop_order_columns' ], 11 );
		add_action( 'manage_shop_order_posts_custom_column', [ $this, 'manage_shop_order_posts_custom_column' ], 11, 2 );

		// New Screen.
		add_filter( 'manage_woocommerce_page_wc-orders_columns', [ $this, 'manage_edit_shop_order_columns' ], 11 );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', [ $this, 'manage_shop_order_posts_custom_column' ], 11, 2 );
	}

	/**
	 * @param array $columns .
	 *
	 * @return array
	 */
	public function manage_edit_shop_order_columns( $columns ) {
		if ( isset( $columns['flexible_shipping'] ) ) {
			return $columns;
		}

		$integrations = apply_filters( 'flexible_shipping_integration_options', [] );

		if ( ! count( $integrations ) ) {
			return $columns;
		}

		$ret = [];

		$col_added = false;

		foreach ( $columns as $key => $column ) {
			if ( ! $col_added && in_array( $key, [ 'order_actions', 'wc_actions' ], true ) ) {
				$ret['flexible_shipping'] = __( 'Shipping', 'flexible-shipping' );
				$col_added                = true;
			}
			$ret[ $key ] = $column;
		}

		if ( ! $col_added ) {
			$ret['flexible_shipping'] = __( 'Shipping', 'flexible-shipping' );
		}

		return $ret;
	}

	/**
	 * @param string                $column .
	 * @param int|\WP_Post|WC_Order $post   .
	 *
	 * @return void
	 */
	public function manage_shop_order_posts_custom_column( string $column, $post_id ) {
		if ( 'flexible_shipping' !== $column ) {
			return;
		}

		$order = wc_get_order( $post_id );

		$classes   = $this->get_classess();
		$statuses  = $this->get_statuses();
		$shippings = $this->get_shippings( $order );

		foreach ( $shippings as $shipping ) {
			if ( 'error' === $shipping['status'] ) {
				$statuses['error'] = $shipping['error'];
			} else {
				$statuses['error'] = __( 'Error', 'flexible-shipping' );
			}

			include __DIR__ . '/views/html-column-shipping-shipping.php';
		}

		$messages = $this->session_factory->get_woocommerce_session_adapter()->get( 'flexible_shipping_bulk_send', [] );

		if ( isset( $messages[ $order->get_id() ] ) ) {
			unset( $messages[ $order->get_id() ] );
		}

		$this->session_factory->get_woocommerce_session_adapter()->set( 'flexible_shipping_bulk_send', $messages );
	}

	/**
	 * @param WC_Order $order .
	 *
	 * @return array
	 */
	private function get_shippings( WC_Order $order ): array {
		$shippings = [];
		/** @var WPDesk_Flexible_Shipping_Shipment[]|WPDesk_Flexible_Shipping_Shipment_Interface[] $shipments */
		$shipments = fs_get_order_shipments( $order->get_id() );

		foreach ( $shipments as $shipment ) {
			$shipping                    = [];
			$shipping['order_id']        = $order->get_id();
			$shipping['integration']     = $shipment->get_integration();
			$shipping['url']             = $shipment->get_order_metabox_url();
			$shipping['error']           = $shipment->get_error_message();
			$shipping['status']          = $shipment->get_status_for_shipping_column();
			$shipping['tracking_number'] = $shipment->get_tracking_number();
			$shipping['label_url']       = $shipment->get_label_url();
			$shipping['tracking_url']    = $shipment->get_tracking_url();
			$shipping['shipment']        = $shipment;
			$shippings[]                 = $shipping;
		}

		$shippings = apply_filters( 'flexible_shipping_shipping_data', $shippings, $order );

		return is_array( $shippings ) ? $shippings : [];
	}

	/**
	 * @return string[]
	 */
	private function get_classess(): array {
		return [
			'error'     => 'failed',
			'new'       => 'on-hold',
			'created'   => 'processing created',
			'confirmed' => 'processing confirmed',
			'manifest'  => 'processing manifest',
		];
	}

	/**
	 * @return string[]
	 */
	private function get_statuses(): array {
		return [
			'error'     => __( 'Error', 'flexible-shipping' ),
			'new'       => __( 'New shipment', 'flexible-shipping' ),
			'created'   => __( 'Created', 'flexible-shipping' ),
			'confirmed' => __( 'Confirmed', 'flexible-shipping' ),
			'manifest'  => __( 'Manifest created', 'flexible-shipping' ),
		];
	}
}
