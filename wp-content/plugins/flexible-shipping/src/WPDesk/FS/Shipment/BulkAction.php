<?php
/**
 * Class BulkAction
 */

namespace WPDesk\FS\Shipment;

use Exception;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\Session\SessionFactory;
use WPDesk\FS\Shipment\BulkAction\HandleAction;
use WPDesk\FS\Shipment\BulkAction\HandleActionStrategy;
use WPDesk\FS\Shipment\BulkAction\HandleActionStrategyInterface;

/**
 * Bulk actions on order screen.
 */
class BulkAction implements Hookable {

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
		add_filter( 'bulk_actions-edit-shop_order', [ $this, 'add_bulk_action_options' ] );
		add_filter( 'handle_bulk_actions-edit-shop_order', [ $this, 'handle_bulk_action' ], 10, 3 );

		// New Screen.
		add_filter( 'bulk_actions-woocommerce_page_wc-orders', [ $this, 'add_bulk_action_options' ] );
		add_action( 'admin_init', [ $this, 'handle_bulk_action_new_screen' ] );
	}

	public function handle_bulk_action_new_screen() {
		if ( ! isset( $_GET['page'], $_GET['action'], $_GET['id'] ) || empty( $_GET['id'] ) || 'wc-orders' !== wp_unslash( $_GET['page'] ) ) { //phpcs:ignore
			return;
		}

		$action = wp_unslash( $_GET['action'] ); //phpcs:ignore
		$orders = wp_parse_id_list( wp_unslash( $_GET['id'] ) );

		try {
			$this->get_handle_action_strategy( $action );
		} catch ( Exception $e ) {
			return;
		}

		check_admin_referer( 'bulk-orders' );
		wp_safe_redirect( $this->handle_action( wp_get_referer(), $action, $orders ) );
		die();
	}

	/**
	 * @param array $bulk_actions .
	 *
	 * @return mixed
	 */
	public function add_bulk_action_options( $bulk_actions ) {
		$integrations = apply_filters( 'flexible_shipping_integration_options', [] );

		if ( count( $integrations ) ) {
			$bulk_actions['flexible_shipping_send']   = __( 'Send shipment', 'flexible-shipping' );
			$bulk_actions['flexible_shipping_labels'] = __( 'Get labels', 'flexible-shipping' );

			if ( apply_filters( 'flexible_shipping_has_manifests', false ) ) {
				$bulk_actions['flexible_shipping_manifest'] = __( 'Create shipping manifest', 'flexible-shipping' );
			}
		}

		return $bulk_actions;
	}

	/**
	 * @param string $redirect_to .
	 * @param string $action      .
	 * @param array  $post_ids    .
	 *
	 * @return string
	 */
	public function handle_bulk_action( $redirect_to, $action, $post_ids ): string {
		$redirect_to = is_string( $redirect_to ) ? $redirect_to : add_query_arg( 'post_type', 'shop_order', admin_url( 'edit.php' ) );

		return $this->handle_action( $redirect_to, $action, $post_ids );
	}

	/**
	 * @param string $redirect_to .
	 * @param string $action      .
	 * @param array  $ids         .
	 *
	 * @return string
	 */
	private function handle_action( string $redirect_to, string $action, array $ids ): string {
		$redirect_to = remove_query_arg( 'bulk_flexible_shipping_send', $redirect_to );
		$redirect_to = remove_query_arg( 'bulk_flexible_shipping_labels', $redirect_to );
		$redirect_to = remove_query_arg( 'bulk_flexible_shipping_manifests', $redirect_to );

		try {
			$handle_action = $this->get_handle_action();

			$handle_action->set_strategy( $this->get_handle_action_strategy( $action ) );

			return $handle_action->handle( $redirect_to, wp_parse_id_list( $ids ) );
		} catch ( Exception $e ) { // phpcs:ignore
			// Do nothing.
		}

		return $redirect_to;
	}

	/**
	 * @return HandleAction
	 */
	protected function get_handle_action(): HandleAction {
		return new HandleAction();
	}

	/**
	 * @param string $action
	 *
	 * @return HandleActionStrategyInterface
	 * @throws Exception
	 */
	protected function get_handle_action_strategy( string $action ): HandleActionStrategyInterface {
		return ( new HandleActionStrategy( $this->session_factory ) )->get( $action );
	}

}
