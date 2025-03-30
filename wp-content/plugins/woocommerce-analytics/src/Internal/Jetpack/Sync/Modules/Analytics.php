<?php

namespace Automattic\WooCommerce\Analytics\Internal\Jetpack\Sync\Modules;

use Automattic\Jetpack\Sync\Modules\Module as JetpackSyncModule;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Internal\Traits\OrderAttributionMeta;
use Automattic\Jetpack\Sync\Modules\WooCommerce_HPOS_Orders;
use WC_Abstract_Order;
use WC_Order;

/**
 * WooCommerce Analytics Module class.
 */
class Analytics extends JetpackSyncModule {

	use Utilities;
	use OrderAttributionMeta;

	/**
	 * Get the module name.
	 *
	 * @return string
	 */
	public function name() {
		return 'woocommerce_analytics';
	}

	/**
	 * Get the ID field for the module.
	 *
	 * @return string
	 */
	public function id_field() {
		return 'order_id';
	}

	/**
	 * Get the table in the database.
	 *
	 * @return string
	 */
	public function table() {
		global $wpdb;
		return $wpdb->prefix . 'wc_order_stats';
	}

	/**
	 * Init listeners.
	 *
	 * @param callable $handler Action handler callable.
	 *
	 * @return void
	 */
	public function init_listeners( $handler ) {
		// Actions to update order stats.
		add_action( 'woocommerce_analytics_update_order_stats', array( $this, 'sync_analytics_reports_data' ) );
		add_action( 'woocommerce_analytics_delete_order_stats', array( $this, 'sync_deleted_analytics_data' ) );

		// Sync actions.
		add_action( 'woocommerce_analytics_sync_reports_data', $handler );
		add_action( 'woocommerce_analytics_delete_reports_data', $handler );

		// Expand data.
		add_filter( 'jetpack_sync_before_enqueue_woocommerce_analytics_sync_reports_data', array( $this, 'expand_data' ) );
		add_filter( 'jetpack_sync_before_enqueue_woocommerce_analytics_delete_reports_data', array( $this, 'expand_data' ) );
	}

	/**
	 * Expand order stats data and attribution data.
	 *
	 * @param array $args List of arguments.
	 *
	 * @return array
	 */
	public function expand_data( $args ) {
		if ( ! is_array( $args ) || ! isset( $args[0] ) ) {
			return false;
		}

		$data = $args[0];

		return $data;
	}

	/**
	 * Init full sync listeners.
	 *
	 * @param callable $handler Action handler callable.
	 *
	 * @return void
	 */
	public function init_full_sync_listeners( $handler ) {
		add_action( 'jetpack_full_sync_woocommerce_analytics', $handler );
	}

	/**
	 * Get full sync actions.
	 *
	 * @return string[] The full sync actions.
	 */
	public function get_full_sync_actions() {
		return array( 'jetpack_full_sync_woocommerce_analytics' );
	}

	/**
	 * Retrieves multiple orders data by their ID.
	 *
	 * @param string $object_type Type of object to retrieve. Should be `order`.
	 * @param array  $ids         List of order IDs.
	 *
	 * @return array
	 */
	public function get_objects_by_id( $object_type, $ids ) {
		if ( 'order' !== $object_type || empty( $ids ) || ! is_array( $ids ) ) {
			return array();
		}

		$orders = wc_get_orders(
			array(
				'post__in'    => $ids,
				'post_status' => WooCommerce_HPOS_Orders::get_all_possible_order_status_keys(),
				'limit'       => -1,
				'orderby'     => 'id',
				'order'       => 'DESC',
			)
		);

		// Get the order stats data for the orders.
		$order_stats_items = $this->get_order_stats_items( $ids );
		$order_stats_data  = array();
		if ( ! empty( $order_stats_items ) ) {
			$order_stats_data = array_column( $order_stats_items, null, 'order_id' );
		}

		$orders_data     = array();
		$found_order_ids = array();
		foreach ( $orders as $order ) {
			$order_id                 = $order->get_id();
			$found_order_ids[]        = $order_id;
			$orders_data[ $order_id ] = $this->build_woocommerce_analytics_reports_data( $order );
			if ( isset( $order_stats_data[ $order_id ] ) ) {
				$this->do_order_status_discrepancy_check( $order, $order_stats_data[ $order_id ] );
			}
		}

		// Check for missing order_ids in wc_order_stats table for orders that were not found.
		$missing_order_ids = array_diff( $ids, $found_order_ids );

		/**
		 * Trigger missing orders detected action.
		 *
		 * @param array $missing_order_ids The missing order IDs.
		 */
		do_action( 'woocommerce_analytics_missing_orders_detected', $missing_order_ids );

		foreach ( $missing_order_ids as $missing_order_id ) {
			$orders_data[ $missing_order_id ] = $this->build_woocommerce_analytics_reports_data( $missing_order_id );
		}

		return $orders_data;
	}

	/**
	 * Retrieve the analytics order data by its ID.
	 *
	 * @param string $object_type Type of the sync object.
	 * @param int    $id          ID of the sync object.
	 * @return mixed Object, or false if the object is invalid.
	 */
	public function get_object_by_id( $object_type, $id ) {
		if ( 'order' !== $object_type ) {
			return false;
		}

		$order = wc_get_order( $id );

		if ( ! $order instanceof WC_Abstract_Order ) {
			$order = $id; // If the order does not exists. We'll check if the order_id exists in wc_order_stats table.
		}

		return $this->build_woocommerce_analytics_reports_data( $order );
	}

	/**
	 * Enqueue full sync actions.
	 *
	 * @param array   $config               Full sync configuration.
	 * @param int     $max_items_to_enqueue Maximum number of items to enqueue.
	 * @param boolean $state                True if full sync has finished enqueueing this module.
	 * @return array Number of actions enqueued, and next module state.
	 */
	public function enqueue_full_sync_actions( $config, $max_items_to_enqueue, $state ) {
		return $this->enqueue_all_ids_as_action(
			'jetpack_full_sync_woocommerce_analytics',
			$this->table(),
			$this->id_field(),
			$this->get_where_sql( $config ),
			$max_items_to_enqueue,
			$state
		);
	}

	/**
	 * Estimate full sync actions.
	 *
	 * @param array $config Full sync configuration.
	 * @return int Number of items yet to be enqueued.
	 */
	public function estimate_full_sync_actions( $config ) {
		global $wpdb;

		$query = "SELECT COUNT(*) FROM {$this->table()}";

		$where_sql = $this->get_where_sql( $config );
		if ( $where_sql ) {
			$query .= ' WHERE ' . $where_sql;
		}

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$count = (int) $wpdb->get_var( $query );

		return (int) ceil( $count / self::ARRAY_CHUNK_SIZE );
	}

	/**
	 * Get where SQL clause for the module.
	 *
	 * @param array $config Full sync configuration.
	 * @return string
	 */
	public function get_where_sql( $config ) {
		global $wpdb;

		$where = '1=1';

		if ( ! empty( $config['start_date'] ) ) {
			$where .= $wpdb->prepare( ' AND date_created >= %s', $config['start_date'] );
		}
		if ( ! empty( $config['end_date'] ) ) {
			$where .= $wpdb->prepare( ' AND date_created <= %s', $config['end_date'] );
		}

		/**
		 * Filter the WHERE SQL for analytics full sync
		 *
		 * @param string $where The WHERE SQL clause
		 * @param array  $config The sync configuration
		 */
		return apply_filters( 'woocommerce_analytics_full_sync_where_sql', $where, $config );
	}

	/**
	 * Initialize module in the sender.
	 */
	public function init_before_send() {
		// Full sync.
		add_filter(
			'jetpack_sync_before_send_jetpack_full_sync_woocommerce_analytics',
			array( $this, 'build_full_sync_action_array' )
		);
	}

	/**
	 * Build the full sync action object.
	 *
	 * @param array $args An array with filtered objects and previous end.
	 *
	 * @return array An array with orders and previous end.
	 */
	public function build_full_sync_action_array( $args ) {
		list( $filtered_orders, $previous_end ) = $args;
		return array(
			'orders'       => $filtered_orders['objects'],
			'previous_end' => $previous_end,
		);
	}

	/**
	 * Given the Module Configuration and Status return the next chunk of items to send.
	 * This function also expands the posts and metadata and filters them based on the maximum size constraints.
	 *
	 * @param array $config This module Full Sync configuration.
	 * @param array $status This module Full Sync status.
	 * @param int   $chunk_size Chunk size.
	 *
	 * @return array
	 */
	public function get_next_chunk( $config, $status, $chunk_size ) {

		$order_ids = parent::get_next_chunk( $config, $status, $chunk_size );

		if ( empty( $order_ids ) ) {
			return array();
		}

		$orders = $this->get_objects_by_id( 'order', $order_ids );

		// If no orders were fetched, make sure to return the expected structure so that status is updated correctly.
		if ( empty( $orders ) ) {
			return array(
				'object_ids' => $order_ids,
				'objects'    => array(),
			);
		}

		// Filter the orders based on the maximum size constraints.
		list( $filtered_order_ids, $filtered_orders, ) = $this->filter_analytics_objects_by_size( $orders );

		return array(
			'object_ids' => $filtered_order_ids,
			'objects'    => $filtered_orders,
		);
	}

	/**
	 * Filters objects and metadata based on maximum size constraints.
	 * It always allows the first object with its metadata, even if they exceed the limit.
	 *
	 * @param array $objects The array of objects to filter.
	 *
	 * @return array An array containing the filtered object IDsand  filtered objects
	 */
	public function filter_analytics_objects_by_size( $objects ) {
		$filtered_objects    = array();
		$filtered_object_ids = array();
		$current_size        = 0;

		foreach ( $objects as $key => $value ) {
			$object_size = strlen( maybe_serialize( $value ) );

			// Always allow the first object.
			if ( empty( $filtered_object_ids ) || ( $current_size + $object_size ) <= self::MAX_SIZE_FULL_SYNC ) {
				$filtered_object_ids[]    = $key;
				$filtered_objects[ $key ] = $value;
				$current_size            += $object_size;
			} else {
				break;
			}
		}

		return array(
			$filtered_object_ids,
			$filtered_objects,
		);
	}


	/**
	 * Handle Sync analytics reports data.
	 *
	 * @param int $order_id The order ID.
	 * @return void
	 */
	public function sync_analytics_reports_data( $order_id ) {

		$data = $this->get_object_by_id( 'order', $order_id );

		if ( ! $data ) {
			return;
		}

		/**
		 * Trigger the action to sync the reports data.
		 *
		 * @param array $data Analytics reports sync data.
		 */
		do_action( 'woocommerce_analytics_sync_reports_data', $data );
	}

	/**
	 * Handle syncing of analytics deletion data.
	 *
	 * @param int $order_id The order ID.
	 * @return void
	 */
	public function sync_deleted_analytics_data( $order_id ) {
		if ( empty( $order_id ) ) {
			return;
		}

		$data = array(
			'id' => $order_id,
		);

		/**
		 * Filter the deletion data before syncing.
		 *
		 * @param array    $data The deletion data.
		 * @param WC_Order $order The order object.
		 */
		$data = apply_filters( 'woocommerce_analytics_deletion_data', $data );

		/**
		 * Trigger the action to sync the deletion.
		 *
		 * @param array $data The deletion sync data.
		 */
		do_action( 'woocommerce_analytics_delete_reports_data', $data );
	}

	/**
	 * Build the WooCommerce analytics reports data.
	 *
	 * @param mixed $order The order ID or the WC_Order object.
	 * @return array The reports data.
	 */
	protected function build_woocommerce_analytics_reports_data( $order ) {
		$order_stats_data       = $this->get_order_stats_data( $order );
		$order_attribution_data = $this->get_order_attribution_data( $order );

		$reports_data = array();

		if ( $order_stats_data ) {
			$reports_data['order_stats'] = $order_stats_data;
		}

		if ( $order_attribution_data ) {
			$reports_data['order_attribution_data'] = $order_attribution_data;
		}

		/**
		 * Filter the reports data before syncing.
		 *
		 * @param array $data The reports data.
		 * @param WC_Order $order The order object.
		 */
		return apply_filters( 'woocommerce_analytics_reports_data', $reports_data, $order );
	}

	/**
	 * Get order attribution data.
	 *
	 * @param mixed $order The order ID or the WC_Order object.
	 * @return array|bool The order attribution data or false if the order is invalid.
	 */
	protected function get_order_attribution_data( $order ) {
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		if ( ! $order ) {
			return false;
		}

		$this->set_fields_and_prefix();
		$order_id     = $order->get_id();
		$type         = $order->get_type();
		$allowed_keys = array(
			'utm_campaign',
			'utm_source',
			'utm_medium',
			'utm_content',
			'utm_term',
			'utm_source_platform',
			'origin',
			'device_type',
			'source_type',
		);

		if ( 'shop_order_refund' === $type && ! empty( $order->get_parent_id() ) ) {
			$order_object_to_use = wc_get_order( $order->get_parent_id() );
		} else {
			$order_object_to_use = $order;
		}

		$attribution_data = array(
			'order_id' => $order_id,
		);

		foreach ( $allowed_keys as $key ) {
			$meta_key                 = $this->get_meta_prefixed_field_name( $key );
			$attribution_data[ $key ] = $order_object_to_use->get_meta( $meta_key, true );
		}

		return $attribution_data;
	}

	/**
	 * Handler order stats update.
	 *
	 * @param mixed $order The order ID or the WC_Order object.
	 * @return array|bool The order attribution data or false if the order stats item does not exist.
	 */
	protected function get_order_stats_data( $order ) {
		if ( is_numeric( $order ) ) {
			$order_id = $order;
			$order    = wc_get_order( $order );
		} elseif ( $order instanceof WC_Abstract_Order ) {
			$order_id = $order->get_id();
		} else {
			return false;
		}

		// If the order does not exit, check if the stats item is present in the wc_order_stats table.
		if ( ! $order ) {
			$order_stats_data_from_db = $this->get_order_stats_data_from_db( $order_id );
			return $order_stats_data_from_db;
		}

		$order_stats_data = array(
			'order_id'           => $order->get_id(),
			'parent_id'          => $order->get_parent_id(),
			'date_created'       => self::datetime_to_object( $order->get_date_created() ),
			'date_paid'          => self::datetime_to_object( $order->get_date_paid() ),
			'date_completed'     => self::datetime_to_object( $order->get_date_completed() ),
			'num_items_sold'     => self::get_num_items_sold( $order ),
			'total_sales'        => $order->get_total(),
			'tax_total'          => $order->get_total_tax(),
			'total_fees'         => $order->get_total_fees(),
			'total_fees_tax'     => self::get_total_fees_tax( $order ),
			'shipping_total'     => $order->get_shipping_total(),
			'shipping_tax'       => $order->get_shipping_tax(),
			'discount_total'     => $order->get_discount_total(),
			'discount_tax'       => $order->get_discount_tax(),
			'net_total'          => self::get_net_total( $order ),
			'returning_customer' => $order->is_returning_customer(),
			'status'             => self::normalize_order_status( $order->get_status() ),
			'customer_id'        => $order->get_report_customer_id(),
		);

		if ( 'shop_order_refund' === $order->get_type() ) {
			$parent_order = wc_get_order( $order->get_parent_id() );
			if ( $parent_order ) {
				$order_stats_data['parent_id'] = $parent_order->get_id();
			}
			/**
			 * Set date_completed and date_paid the same as date_created to avoid problems
			 * when they are being used to sort the data, as refunds don't have them filled
			*/
			$date_created_gmt                   = self::datetime_to_object( $order->get_date_created() );
			$order_stats_data['date_completed'] = $date_created_gmt;
			$order_stats_data['date_paid']      = $date_created_gmt;
		}

		return $order_stats_data;
	}

	/**
	 * Calculation methods.
	 */

	/**
	 * Get number of items sold among all orders.
	 *
	 * @param WC_Order $order WC_Order object.
	 * @return int
	 */
	protected static function get_num_items_sold( $order ) {
		$num_items = 0;

		$line_items = $order->get_items( 'line_item' );
		foreach ( $line_items as $line_item ) {
			$num_items += $line_item->get_quantity();
		}

		return $num_items;
	}

	/**
	 * Get the net amount from an order without shipping, tax, or refunds.
	 *
	 * @param WC_Order $order WC_Order object.
	 * @return float
	 */
	protected static function get_net_total( $order ) {
		$net_total = floatval( $order->get_total() ) - floatval( $order->get_total_tax() ) - floatval( $order->get_shipping_total() );
		return (float) $net_total;
	}

	/**
	 * Get the total fees tax from an order.
	 *
	 * @param WC_Order $order WC_Order object.
	 * @return float
	 */
	protected static function get_total_fees_tax( $order ) {
		$total_fees_tax = array_sum(
			array_map(
				function ( $item ) {
					return $item->get_total_tax();
				},
				array_values( $order->get_items( 'fee' ) )
			)
		);

		return $total_fees_tax;
	}

	/**
	 * Get the order stats row for a given order ID.
	 *
	 * @param int $order_id The order ID.
	 * @return array|null|void Database query result in format specified by $output or null on failure.
	 */
	private function get_order_stats_item( $order_id ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT * FROM {$this->table()} WHERE order_id = %d", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$order_id
		);

		return $wpdb->get_row( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Get the order stats rows for a given order IDs.
	 *
	 * @param array $order_ids The order IDs.
	 * @return array|null Database query result in format specified by $output or null on failure.
	 */
	private function get_order_stats_items( $order_ids ) {
		global $wpdb;

		$placeholders = implode( ',', array_fill( 0, count( $order_ids ), '%d' ) );
		$query        = $wpdb->prepare(
			"SELECT * FROM {$this->table()} WHERE order_id IN ( $placeholders )", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$order_ids
		);

		return $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Get the order stats data from the database.
	 *
	 * @param int $order_id The order ID.
	 * @return array|bool The order stats data or false if the order stats item does not exist.
	 */
	private function get_order_stats_data_from_db( $order_id ) {
		$order_stats_data = $this->get_order_stats_item( $order_id );

		if ( ! $order_stats_data ) {
			return false;
		}

		// Convert date strings to datetime objects.
		$order_stats_data['date_created']   = self::datetime_to_object( $order_stats_data['date_created'] );
		$order_stats_data['date_completed'] = self::datetime_to_object( $order_stats_data['date_completed'] );
		$order_stats_data['date_paid']      = self::datetime_to_object( $order_stats_data['date_paid'] );

		return $order_stats_data;
	}

	/**
	 * Perform an order status discrepancy check between the order object and the item in the wc_order_stats table.
	 *
	 * @param WC_Order $order WC_Order object.
	 * @param array    $order_stats_item The order stats item.
	 *
	 * @return void
	 */
	private function do_order_status_discrepancy_check( $order, $order_stats_item = array() ) {
		if ( ! $order instanceof WC_Abstract_Order ) {
			return;
		}

		$order_id = $order->get_id();

		// If the order_stats_item is empty, then fetch it from the wc_order_stats table.
		if ( empty( $order_stats_item ) ) {
			$order_stats_item = $this->get_order_stats_data_from_db( $order_id );
		}

		// Check for discrepancy in the order status. Happens in old orders that were not updated and hence the OrderStatsFixer did not run.
		$normalized_order_status = self::normalize_order_status( $order->get_status() );
		if ( $order_stats_item && $normalized_order_status !== $order_stats_item['status'] ) {
			/**
			 * Trigger the action to fix the order stats. The OrderStatusFixer should be hooked to this action.
			 *
			 * @param int $order_id The order ID.
			 */
			do_action( 'woocommerce_analytics_incorrect_order_status_detected', $order_id );
		}
	}
}
