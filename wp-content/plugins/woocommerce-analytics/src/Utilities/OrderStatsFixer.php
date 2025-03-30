<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Utilities;

use Automattic\WooCommerce\Internal\Admin\Schedulers\OrdersScheduler;
use Automattic\WooCommerce\Admin\Schedulers\SchedulerTraits;
use Automattic\WooCommerce\Admin\API\Reports\Cache as ReportsCache;
use Automattic\WooCommerce\Utilities\OrderUtil;

use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Analytics\HelperTraits\LoggerTrait;
use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;

defined( 'ABSPATH' ) || exit;

/**
 * The OrderStatsFixer class.
 *
 * Fixes bugs in WooCommerce Analytics core so that the wc_order_stats table is
 * in parity with the wc_orders/wc_posts table. This class will only make change to
 * the wc_order_stats table. It does not make any changes to the wc_orders/wp_posts table.
 */
class OrderStatsFixer implements RegistrableInterface {

	/**
	 * Slug to identify the scheduler.
	 *
	 * @var string
	 */
	public static $name = 'order_stats_fixer';

	use Utilities;
	use LoggerTrait;

	/**
	 * Scheduler traits.
	 */
	use SchedulerTraits {
		init as scheduler_init;
	}

	/**
	 * OrderStatsFixer constructor.
	 *
	 * @param LoggerInterface $logger The logger object.
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->set_logger( $logger );
	}

	/**
	 * Get all available scheduling actions.
	 * Used to determine action hook names and clear events.
	 *
	 * @return array
	 */
	public static function get_scheduler_actions() {
		return array(
			'cleanup_order_stats' => 'woocommerce_analytics_schedule_order_stats_cleanup',
		);
	}

	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		/**
		 * Fixes a bug in WooCommerce core where the order status in Analytics is not updated when an order is trashed or untrashed.
		 *
		 * @see https://github.com/woocommerce/woocommerce/issues/44371
		 */
		add_action( 'woocommerce_trash_order', array( OrdersScheduler::class, 'possibly_schedule_import' ) );
		add_action( 'woocommerce_untrash_order', array( OrdersScheduler::class, 'possibly_schedule_import' ) );

		/**
		 * The wc_order_stats table stores the order status as wc-trash instead of trash which is the correct core WordPress status.
		 */
		add_filter( 'woocommerce_analytics_update_order_stats_data', array( $this, 'update_order_stats_data' ) );
		add_action( 'woocommerce_analytics_update_order_stats', array( $this, 'fix_incorrect_order_status' ), 5, 1 );
		add_action( 'woocommerce_analytics_incorrect_order_status_detected', array( $this, 'fix_incorrect_order_status' ), 5, 1 );

		/**
		 * Delete orphaned refund orders from the wc_order_stats table.
		 */
		add_action( 'woocommerce_analytics_delete_order_stats', array( $this, 'delete_orphaned_refund_orders' ), 5 );

		/**
		 * Cleanup order stats table when missing orders are detected.
		 */
		add_action( 'woocommerce_analytics_missing_orders_detected', array( $this, 'possibly_schedule_cleanup' ) );
		self::scheduler_init();
	}

	/**
	 * Uses the correct status for the order stats table.
	 *
	 * The wc_order_stats table stores the order status as wc-trash instead of trash which is the correct core WordPress status.
	 *
	 * @param array $data The order stats data.
	 * @return array
	 */
	public function update_order_stats_data( $data ) {
		if ( isset( $data['status'] ) ) {
			$data['status'] = self::normalize_order_status( $data['status'] );
		}
		return $data;
	}

	/**
	 * Fixes the incorrect order status in the wc_order_stats table.
	 *
	 * The status of a refund order is set to the status of the parent order before inserting into the
	 * wc_order_stats table. This difference in status causes the wc_order_stats table to be out of sync with the
	 * wc_orders/wp_posts table data. This function fixes the issue by updating the status in the wc_order_stats table.
	 *
	 * @param int $order_id The order ID.
	 * @return int|bool Returns -1 if order won't be processed, or a boolean indicating processing success.
	 */
	public function fix_incorrect_order_status( $order_id ) {
		global $wpdb;
		$order_stats_table = $this->get_order_stats_table_name();

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			$this->get_logger()->log_message( sprintf( 'Order #%d not found, cannot fix incorrect order status', $order_id ), __METHOD__ );
			return -1;
		}

		// This issue occurs only for refund orders.
		if ( 'shop_order_refund' !== $order->get_type() ) {
			return -1;
		}

		// Get current status in order_stats table.
		$current_status_query = $wpdb->prepare(
			"SELECT status FROM {$order_stats_table} WHERE order_id = %d", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$order_id
		);
		$current_status       = $wpdb->get_var( $current_status_query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$order_status         = self::normalize_order_status( $order->get_status() );

		// Log the discrepancy before making any changes.
		if ( $current_status !== $order_status ) {
			$this->get_logger()->log_message(
				sprintf(
					'Order status discrepancy detected for order #%d. WC_Orders status: "%s", Order_Stats status: "%s"',
					$order_id,
					$order_status,
					$current_status
				),
				__METHOD__
			);
		}

		// Only update if there's actually a difference.
		if ( $current_status !== $order_status ) {
			$data         = array( 'status' => $order_status );
			$where        = array( 'order_id' => $order_id );
			$format       = array( '%s' );
			$where_format = array( '%d' );
			$result       = $wpdb->update( $order_stats_table, $data, $where, $format, $where_format );

			if ( is_numeric( $result ) && $result > 0 ) {
				$this->get_logger()->log_message(
					sprintf(
						'Fixed order status discrepancy for refund order #%d. Changed from "%s" to "%s"',
						$order_id,
						$current_status,
						$order_status
					),
					__METHOD__
				);
			}

			return is_numeric( $result );
		}

		return true; // No change needed.
	}

	/**
	 * Delete orphaned refund orders from the wc_order_stats table.
	 *
	 * When an order is deleted, the corresponding refund order is not deleted from the wc_order_stats table.
	 * This function fixes that issue by deleting the orphaned refund orders.
	 *
	 * @param int $order_id The order ID.
	 * @return void
	 */
	public function delete_orphaned_refund_orders( $order_id ) {
		global $wpdb;
		$order_stats_table = $this->get_order_stats_table_name();

		$wpdb->delete( $order_stats_table, array( 'parent_id' => $order_id ), array( '%d' ) );

		ReportsCache::invalidate();
	}

	/**
	 * Schedule cleanup of the order stats table when missing orders are detected.
	 *
	 * @param array $missing_order_ids The missing orders.
	 * @return void
	 */
	public function possibly_schedule_cleanup( $missing_order_ids ) {
		$order_ids = array_map( 'absint', $missing_order_ids );
		$order_ids = array_filter( $missing_order_ids );

		if ( empty( $order_ids ) ) {
			return;
		}

		self::schedule_action( 'cleanup_order_stats', array( $order_ids ) );
	}

	/**
	 * Cleanup the order stats table.
	 *
	 * @param array $missing_order_ids The order IDs to cleanup.
	 * @return void
	 */
	public static function cleanup_order_stats( $missing_order_ids ) {
		global $wpdb;

		$order_stats_table = $wpdb->prefix . 'wc_order_stats';
		$missing_order_ids = is_array( $missing_order_ids ) ? array_map( 'absint', $missing_order_ids ) : array( absint( $missing_order_ids ) );
		$hpos_enabled      = OrderUtil::custom_orders_table_usage_is_enabled();
		$orders_table      = OrderUtil::get_table_for_orders();
		$orders_column_id  = $hpos_enabled ? 'id' : 'ID';

		// Check if the missing order IDs are actually missing.
		$placeholders                = implode( ',', array_fill( 0, count( $missing_order_ids ), '%d' ) );
		$query                       = "SELECT {$orders_column_id} FROM {$orders_table} WHERE {$orders_column_id} IN ( $placeholders )";
		$found_order_ids             = $wpdb->get_col( $wpdb->prepare( $query, $missing_order_ids ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$confirmed_missing_order_ids = array_diff( $missing_order_ids, $found_order_ids );

		// If no missing orders are found, return.
		if ( empty( $confirmed_missing_order_ids ) ) {
			return;
		}

		// Delete the confirmed missing orders from the order stats table.
		$delete_query_placeholders = implode( ',', array_fill( 0, count( $confirmed_missing_order_ids ), '%d' ) );
		$delete_query              = "DELETE FROM $order_stats_table WHERE order_id IN ($placeholders) OR parent_id IN ($placeholders)";
		$delete_sql                = $wpdb->prepare( $delete_query, array_merge( $confirmed_missing_order_ids, $confirmed_missing_order_ids ) ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$wpdb->query( $delete_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		ReportsCache::invalidate();
	}
}
