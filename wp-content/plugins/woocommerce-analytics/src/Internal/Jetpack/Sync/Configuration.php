<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Internal\Jetpack\Sync;

use Automattic\Jetpack\Config;
use Automattic\Jetpack\Sync\Data_Settings;
use Automattic\Jetpack\Sync\Modules as JetpackSyncModules;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Class Configuration for Jetpack Sync
 */
class Configuration implements RegistrableInterface {
	use Utilities;

	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'plugins_loaded', array( $this, 'initialize_jetpack' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_jetpack_connection_script' ) );

		if ( $this->can_site_sync_orders() ) {
			add_filter( 'jetpack_full_sync_config', array( $this, 'expand_full_sync_config' ) );
			add_filter( 'jetpack_sync_checksum_allowed_tables', array( $this, 'add_order_stats_to_checksum' ) );
		}
	}

	/**
	 * Initialize the Jetpack functionalities: sync, connection, etc...
	 *
	 * @return void
	 */
	public function initialize_jetpack(): void {
		$config = new Config();

		$config->ensure(
			'sync',
			$this->get_jetpack_sync_config()
		);

		$config->ensure(
			'connection',
			$this->get_jetpack_connection_config()
		);
	}

	/**
	 * Jetpack Sync module configuration.
	 *
	 * @return array Jetpack Sync config array.
	 */
	private function get_jetpack_sync_config(): array {
		return array_merge_recursive(
			Data_Settings::MUST_SYNC_DATA_SETTINGS,
			array(
				'jetpack_sync_modules'             => array(
					'Automattic\\WooCommerce\\Analytics\\Internal\\Jetpack\\Sync\\Modules\\Analytics', // WooCommerce Analytics module.
				),
				'jetpack_sync_options_whitelist'   => array(
					'woocommerce_custom_orders_table_enabled', // Required for HPOS checksums.
					'woocommerce_excluded_report_order_statuses', // Required for generating analytics reports.
					'woocommerce_date_type', // Date used to determine the date range for analytics reports.
				),
				'jetpack_sync_constants_whitelist' => array(
					'WC_ANALYTICS_VERSION',
				),
			)
		);
	}

	/**
	 * Jetpack Connection module configuration.
	 *
	 * @return array Jetpack Connection config array.
	 */
	private function get_jetpack_connection_config(): array {
		return array(
			'slug' => $this->get_plugin_slug(),
			'name' => $this->get_plugin_name(),
		);
	}

	/**
	 * Enqueue the Jetpack Connection script (registered in actions.php which is auto-loaded).
	 *
	 * @return void
	 */
	public function enqueue_jetpack_connection_script(): void {
		// Don't use Assets::enqueue_script because it includes the CSS, which we don't need.
		wp_enqueue_script( 'jetpack-connection' );
	}

	/**
	 * Expand full sync config with module required by WooCommerce Analytics if not already present.
	 *
	 * @param array $config The current full sync configuration.
	 * @return array The modified full sync configuration.
	 */
	public function expand_full_sync_config( array $config ): array {
		if ( ! isset( $config['woocommerce_analytics'] ) ) {
			$config = array( 'woocommerce_analytics' => 1 ) + $config;
		}

		return $config;
	}

	/**
	 * Adds the order stats table to the checksum allowed tables.
	 *
	 * @param array $tables The current checksum allowed tables.
	 * @return array The modified checksum allowed tables.
	 */
	public function add_order_stats_to_checksum( array $tables ): array {
		global $wpdb;
		$order_stats_checksum_table = array(
			'wc_order_stats' => array(
				'table'                     => "{$wpdb->prefix}wc_order_stats",
				'range_field'               => 'order_id',
				'key_fields'                => array( 'order_id' ),
				'checksum_fields'           => array( 'date_paid', 'date_completed', 'total_sales' ),
				'checksum_text_fields'      => array( 'status' ),
				'is_table_enabled_callback' => function () {
					return false !== JetpackSyncModules::get_module( 'woocommerce_analytics' );
				},
			),
		);
		return array_merge( $tables, $order_stats_checksum_table );
	}
}
