<?php
/**
 * Class FilterOrders
 */

namespace WPDesk\FS\Shipment;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class FilterOrders implements Hookable {

	/**
	 * @return void
	 */
	public function hooks() {
		add_filter( 'posts_where', [ $this, 'posts_where' ], 999 );

		add_action( 'restrict_manage_posts', [ $this, 'restrict_manage_posts' ], 9999 );
	}

	/**
	 * @param string $where .
	 *
	 * @return string
	 */
	public function posts_where( $where = '' ) {
		global $pagenow, $wp_query, $wpdb;

		$should_modify_query = 'edit.php' === $pagenow && is_admin() && isset( $wp_query->query_vars['post_type'] ) && 'shop_order' === $wp_query->query_vars['post_type'];

		if ( ! $should_modify_query ) {
			return $where;
		}

		$integration = sanitize_key( $_GET['flexible_shipping_integration_filter'] ?? '' );
		$status      = sanitize_key( $_GET['flexible_shipping_status_filter'] ?? '' );

		if ( empty( $integration ) && empty( $status ) ) {
			return $where;
		}

		$add_where_meta_integration     = '';
		$add_where_meta_status          = '';
		$add_where_shipment_integration = '';
		$add_where_shipment_status      = '';
		$add_where                      = '';

		if ( '' !== $integration ) {
			$add_where_meta_integration     = " EXISTS ( SELECT 1 FROM {$wpdb->postmeta} fs_postmeta WHERE {$wpdb->posts}.ID = fs_postmeta.post_id AND fs_postmeta.meta_key = '_flexible_shipping_integration' AND  fs_postmeta.meta_value = '$integration' ) ";
			$add_where_shipment_integration = " EXISTS ( SELECT 1 FROM {$wpdb->posts} fs_posts, {$wpdb->postmeta} fs_postmeta WHERE {$wpdb->posts}.ID = fs_posts.post_parent AND fs_posts.ID = fs_postmeta.post_id AND fs_postmeta.meta_key = '_integration' AND  fs_postmeta.meta_value = '$integration' ) ";
		}

		if ( '' !== $status ) {
			$add_where_meta_status     = " EXISTS ( SELECT 1 FROM {$wpdb->postmeta} fs_postmeta WHERE {$wpdb->posts}.ID = fs_postmeta.post_id AND fs_postmeta.meta_key = '_flexible_shipping_status' AND  fs_postmeta.meta_value = '$status' ) ";
			$add_where_shipment_status = " EXISTS ( SELECT 1 FROM {$wpdb->posts} fs_posts WHERE {$wpdb->posts}.ID = fs_posts.post_parent AND fs_posts.post_status = 'fs-{$status}' ) ";
		}

		$add_where_meta = '';

		if ( '' !== $add_where_meta_integration ) {
			$add_where_meta .= $add_where_meta_integration;
		}

		if ( '' !== $add_where_meta_status ) {
			if ( '' !== $add_where_meta ) {
				$add_where_meta .= ' AND ';
			}
			$add_where_meta .= $add_where_meta_status;
		}

		$add_where_shipment = '';
		if ( '' !== $add_where_shipment_integration ) {
			$add_where_shipment .= $add_where_shipment_integration;
		}

		if ( '' !== $add_where_shipment_status ) {
			if ( '' !== $add_where_shipment ) {
				$add_where_shipment .= ' AND ';
			}
			$add_where_shipment .= $add_where_shipment_status;
		}

		$add_where_meta     = ' ( ' . $add_where_meta . ' ) ';
		$add_where_shipment = ' ( ' . $add_where_shipment . ' ) ';
		$add_where          = ' AND ( ' . $add_where_meta . ' OR ' . $add_where_shipment . ' ) ';
		$where              .= $add_where;

		return $where;
	}

	/**
	 * .
	 */
	public function restrict_manage_posts() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( apply_filters( 'flexible_shipping_disable_order_filters', false ) ) {
			return;
		}

		$integrations = apply_filters( 'flexible_shipping_integration_options', [] );

		if ( ! count( $integrations ) ) {
			return;
		}

		if ( ! in_array( $screen->id, [ 'woocommerce_page_wc-orders', 'edit-shop_order' ], true ) ) {
			return;
		}

		$integrations = apply_filters( 'flexible_shipping_integration_options', [] );
		$statuses     = apply_filters( 'flexible_shipping_status', [] );
		$integration  = '';

		if ( isset( $_GET['flexible_shipping_integration_filter'] ) ) {
			$integration = sanitize_key( $_GET['flexible_shipping_integration_filter'] );
		}

		$status = '';
		if ( isset( $_GET['flexible_shipping_status_filter'] ) ) {
			$status = sanitize_key( $_GET['flexible_shipping_status_filter'] );
		}

		include __DIR__ . '/views/html-orders-filter-form.php';
	}
}
