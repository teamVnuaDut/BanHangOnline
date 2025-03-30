<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\HelperTraits;

use Automattic\Jetpack\Connection\Manager;
use Automattic\WooCommerce\Internal\Features\FeaturesController;
use Automattic\WooCommerce\Internal\Traits\OrderAttributionMeta;
use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\Jetpack\Sync\Actions as Jetpack_Sync_Actions;
use Automattic\Jetpack\Sync\Modules;

use WC_DateTime;
use DateTimeZone;

defined( 'ABSPATH' ) || exit;

/**
 * Trait Utilities.
 */
trait Utilities {

	use OrderAttributionMeta;

	/**
	 * Get plugin name.
	 *
	 * @return string
	 */
	protected function get_plugin_name(): string {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		return get_plugin_data( WC_ANALYTICS_FILE, true, false )['Name'];
	}

	/**
	 * Get plugin base name.
	 *
	 * @return string
	 */
	protected function get_plugin_base_name(): string {
		return plugin_basename( WC_ANALYTICS_FILE );
	}

	/**
	 * Get plugin slug.
	 *
	 * @return string
	 */
	public function get_plugin_slug(): string {
		return dirname( $this->get_plugin_base_name() );
	}

	/**
	 * Gets the plugin's path.
	 *
	 * E.g. /path/to/wp-content/plugins/plugin-directory/
	 *
	 * @return string
	 */
	protected function get_plugin_path(): string {
		return plugin_dir_path( WC_ANALYTICS_FILE );
	}

	/**
	 * Gets the plugin's URL.
	 *
	 * @return string
	 */
	protected function get_plugin_url(): string {
		return plugin_dir_url( WC_ANALYTICS_FILE );
	}

	/**
	 * Gets the CDN URL for the assets.
	 *
	 * @param string $version The assets version in the CDN.
	 *
	 * @return string
	 */
	protected function get_cdn_url( string $version ): string {
		return 'https://widgets.wp.com/woocommerce-analytics/' . $version . '/';
	}

	/**
	 * Get local build path
	 *
	 * @return string The local build path.
	 */
	protected function get_local_build_path(): string {
		return $this->get_plugin_path() . 'build/';
	}

	/**
	 * Get build meta information from the CDN.
	 *
	 * @param string $build_dir The CDN build directory URL.
	 * @return array Returns an array with two keys:
	 *               - 'dependencies': (string[]) An array of dependency strings.
	 *               - 'version': (string|null) The version string or null if not available.
	 */
	protected function get_assets_data( string $build_dir ): array {
		$dependencies = array();
		$version      = null;
		$response     = wp_safe_remote_get( $build_dir . 'build-meta.json?t=' . time(), array( 'timeout' => 5 ) );

		if ( ! is_wp_error( $response ) ) {
			$body       = wp_remote_retrieve_body( $response );
			$build_meta = json_decode( $body, true );

			if ( JSON_ERROR_NONE === json_last_error() && isset( $build_meta['dependencies'] ) ) {
				$dependencies = array_map( 'sanitize_text_field', $build_meta['dependencies'] );
				$version      = isset( $build_meta['version'] ) ? sanitize_text_field( $build_meta['version'] ) : null;
			}
		}

		return array(
			'dependencies' => $dependencies,
			'version'      => $version,
		);
	}

	/**
	 * Check if the asset is being loaded locally.
	 *
	 * @param string $asset_file_name The asset file name to check.
	 *
	 * @return bool True if the asset is local, false otherwise.
	 */
	protected function is_asset_local( string $asset_file_name ): bool {
		return file_exists( $this->get_local_build_path() . $asset_file_name );
	}

	/**
	 * Can site sync orders to WPCOM infrastructure.
	 *
	 * @return boolean
	 */
	protected function can_site_sync_orders(): bool {
		return $this->is_order_attribution_enabled();
	}

	/**
	 * Check if the order attribution feature is enabled.
	 *
	 * @return bool
	 */
	protected function is_order_attribution_enabled(): bool {

		if ( ! class_exists( FeaturesController::class ) ) {
			return false;
		}

		try {
			/** @var FeaturesController $oa_controller */
			$feature_controller = wc_get_container()->get( FeaturesController::class );
			$is_enabled         = $feature_controller->feature_is_enabled( 'order_attribution' );

			/*
			 * When the feature settings form is submitted, feature_is_enabled won't return false right away
			 * We need to check what value was actually posted. Only checked options are posted.
			 * So if it's a POST request and $_POST[ 'woocommerce_custom_orders_table_enabled' ] does not exist
			 * we optimistically assume this is now false.
			 */
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['section'] ) && 'features' === $_GET['section'] ) {
				// phpcs:disable WordPress.Security.NonceVerification.Missing
				if ( isset( $_POST['woocommerce_feature_order_attribution_enabled'] ) ) {
					$posted_order_attribution = wc_clean( sanitize_text_field( wp_unslash( $_POST['woocommerce_feature_order_attribution_enabled'] ) ) );
					$is_enabled               = wc_string_to_bool( $posted_order_attribution );
				} elseif ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
					$is_enabled = false;
				}
			}

			return $is_enabled;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Checks whether HPOS is enabled.
	 *
	 * @return bool
	 */
	public function custom_orders_table_usage_is_enabled() {
		if ( ! class_exists( OrderUtil::class ) ) {
			return false;
		}

		if ( ! method_exists( OrderUtil::class, 'custom_orders_table_usage_is_enabled' ) ) {
			return false;
		}

		try {
			$is_enabled = OrderUtil::custom_orders_table_usage_is_enabled();

			/*
			 * When the feature settings form is submitted, custom_orders_table_usage_is_enabled won't return false right away
			 * We need to check what value was actually posted. Only checked options are posted.
			 * So if it's a POST request and $_POST[ 'woocommerce_custom_orders_table_enabled' ] does not exist
			 * we optimistically assume this is now false.
			 */
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['section'] ) && 'features' === $_GET['section'] ) {
				// phpcs:disable WordPress.Security.NonceVerification.Missing
				if ( isset( $_POST['woocommerce_custom_orders_table_enabled'] ) ) {
					$posted_custom_orders_table_enabled = wc_clean( sanitize_text_field( wp_unslash( $_POST['woocommerce_custom_orders_table_enabled'] ) ) );
					$is_enabled                         = wc_string_to_bool( $posted_custom_orders_table_enabled );
				} elseif ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
					$is_enabled = false;
				}
			}

			return $is_enabled;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Get the fields to be added to the Jetpack sync whitelist.
	 *
	 * @return array
	 */
	protected function get_order_attribution_fields(): array {
		$fields = array();
		$this->set_fields_and_prefix();
		foreach ( $this->field_names as $field ) {
			$fields[] = "_{$this->get_prefixed_field_name($field)}";
		}

		/**
		 * Add device_type to the whitelist.
		 *
		 * @see https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/src/Internal/Traits/OrderAttributionMeta.php#L260
		 */
		$fields[] = "_{$this->get_prefixed_field_name('device_type')}";

		return $fields;
	}

	/**
	 * Check if Jetpack is connected.
	 *
	 * @return bool
	 */
	protected function is_jetpack_connected(): bool {
		$jetpack_connection_manager = new Manager();
		return $jetpack_connection_manager->is_connected();
	}

	/**
	 * Run full sync for HPOS orders.
	 * This will sync all the orders together with the order attribution data.
	 *
	 * @return void
	 */
	protected function run_full_sync(): void {
		Jetpack_Sync_Actions::do_full_sync(
			array(
				'options'               => true,
				'constants'             => true,
				'functions'             => true,
				'woocommerce_analytics' => true,
			)
		);
	}


	/**
	 * Stop full sync of hpos orders.
	 * To stop full sync of hpos orders we send a full sync request with only the options flag set.
	 *
	 * @return void
	 */
	protected function stop_full_sync(): void {
		Jetpack_Sync_Actions::do_full_sync(
			array(
				'options' => true,
			)
		);
	}

	/**
	 * Gets the order_stats table name.
	 *
	 * @return string
	 */
	protected function get_order_stats_table_name(): string {
		global $wpdb;
		return $wpdb->prefix . 'wc_order_stats';
	}

	/**
	 * Maps order status provided by the user to the one used in the database.
	 *
	 * @param string $status Order status.
	 * @return string
	 */
	protected static function normalize_order_status( $status ) {
		$status                 = str_replace( 'wc-', '', $status );
		$wc_order_status_keys   = array_keys( wc_get_order_statuses() );
		$wc_order_status_keys[] = 'wc-checkout-draft'; // Related to Woo bug as `wc-checkout-draft` is missing from `wc_get_order_statuses`.

		return in_array( 'wc-' . $status, $wc_order_status_keys, true ) ? 'wc-' . $status : $status;
	}

	/**
	 * Convert the WC_DateTime objects to stdClass objects to ensure they are properly encoded.
	 *
	 * @param WC_DateTime|mixed $wc_datetime The datetime object.
	 * @param bool              $utc         Whether to convert to UTC.
	 * @return object|null
	 */
	protected static function datetime_to_object( $wc_datetime, $utc = false ) {
		if ( is_string( $wc_datetime ) ) {
			$wc_datetime = new WC_DateTime( $wc_datetime );
		}

		if ( is_a( $wc_datetime, 'WC_DateTime' ) ) {
			if ( $utc ) {
				$wc_datetime->setTimezone( new DateTimeZone( 'UTC' ) );
			} else {
				$wc_datetime->setTimezone( new DateTimeZone( wc_timezone_string() ) );
			}
			return (object) (array) $wc_datetime;
		}
	}
}
