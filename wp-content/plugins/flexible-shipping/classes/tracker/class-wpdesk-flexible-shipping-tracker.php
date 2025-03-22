<?php
/**
 * Tracker.
 *
 * @package Flexible Shipping.
 */

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FS\TableRate\ShippingMethodSingle;
use WPDesk\FS\TableRate\SingleRuleSettings;

/**
 * Handle Tracker actions and filters.
 */
class WPDesk_Flexible_Shipping_Tracker implements Hookable {

	const PLUGIN_ACTION_LINKS_FILTER_NAME = 'plugin_action_links_flexible-shipping/flexible-shipping.php';
	const FLEXIBLE_SHIPPING_PLUGIN_FILE   = 'flexible-shipping/flexible-shipping.php';
	const FLEXIBLE_SHIPPING_PLUGIN_SLUG   = 'flexible-shipping';
	const FLEXIBLE_SHIPPING_PLUGIN_TITLE  = 'Flexible Shipping';
	const TRACKER_DATA_FILTER_PRIORITY    = 11;

	/**
	 * Is plugin flexible shipping in data.
	 *
	 * @param array $data Data.
	 * @return bool
	 */
	public static function is_plugin_flexible_shipping_in_data( $data ) {
		return is_array( $data ) && isset( $data['plugin'] ) && self::FLEXIBLE_SHIPPING_PLUGIN_FILE === $data['plugin'];
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_filter( 'wpdesk_tracker_data', [ $this, 'wpdesk_tracker_data_flexible_shipping' ], self::TRACKER_DATA_FILTER_PRIORITY );
	}

	/**
	 * Append data.
	 *
	 * @param array $data Data.
	 *
	 * @return array
	 */
	public function wpdesk_tracker_data_flexible_shipping( $data ) {
		$all_shipping_methods = flexible_shipping_get_all_shipping_methods();

		$flexible_shipping = $all_shipping_methods['flexible_shipping'];

		$flexible_shipping_rates                                      = $flexible_shipping->get_all_rates();
		$data['flexible_shipping']                                    = [];
		$data['flexible_shipping']['total_shipping_methods']          = 0;
		$data['flexible_shipping']['group_shipping_methods']          = 0;
		$data['flexible_shipping']['single_shipping_methods']         = 0;
		$data['flexible_shipping']['avg_rules']                       = 0;
		$data['flexible_shipping']['max_rules']                       = 0;
		$data['flexible_shipping']['integrations']                    = [];
		$data['flexible_shipping']['free_shipping_requires']          = [];
		$data['flexible_shipping']['calculation_methods']             = [];
		$data['flexible_shipping']['based_on']                        = [];
		$data['flexible_shipping']['shipping_class_option']           = [];
		$data['flexible_shipping']['method_description_count']        = 0;
		$data['flexible_shipping']['free_shipping_label_count']       = 0;
		$data['flexible_shipping']['free_shipping_cart_notice_count'] = 0;
		$data['flexible_shipping']['max_cost_count']                  = 0;
		$data['flexible_shipping']['visibility_count']                = 0;
		$data['flexible_shipping']['default_count']                   = 0;

		$data['flexible_shipping']['additional_cost_count'] = 0;

		$data['flexible_shipping']['min_count'] = 0;
		$data['flexible_shipping']['max_count'] = 0;

		$data['flexible_shipping']['cost_per_order_count'] = 0;
		$data['flexible_shipping']['stop_count']           = 0;
		$data['flexible_shipping']['cancel_count']         = 0;

		foreach ( $flexible_shipping_rates as $rate_id => $flexible_shipping_rate ) {

			if ( ! isset( $flexible_shipping_rate['method_rules'] ) ) {
				$flexible_shipping_rate['method_rules'] = [];
			}

			$data['flexible_shipping']['total_shipping_methods'] ++;

			if ( ShippingMethodSingle::SHIPPING_METHOD_ID === $flexible_shipping_rate['method_id'] ) {
				$data['flexible_shipping']['single_shipping_methods']++;
			}

			if ( WPDesk_Flexible_Shipping::METHOD_ID === $flexible_shipping_rate['method_id'] ) {
				$data['flexible_shipping']['group_shipping_methods']++;
			}

			$data['flexible_shipping']['avg_rules'] += count( $flexible_shipping_rate['method_rules'] );

			if ( count( $flexible_shipping_rate['method_rules'] ) > $data['flexible_shipping']['max_rules'] ) {
				$data['flexible_shipping']['max_rules'] = count( $flexible_shipping_rate['method_rules'] );
			}

			if ( empty( $flexible_shipping_rate['method_integration'] ) ) {
				$flexible_shipping_rate['method_integration'] = 'none';
			}
			if ( empty( $data['flexible_shipping']['integrations'][ $flexible_shipping_rate['method_integration'] ] ) ) {
				$data['flexible_shipping']['integrations'][ $flexible_shipping_rate['method_integration'] ] = 0;
			}
			$data['flexible_shipping']['integrations'][ $flexible_shipping_rate['method_integration'] ] ++;

			if ( ! empty( $flexible_shipping_rate['method_free_shipping_requires'] ) ) {
				if ( empty( $data['flexible_shipping']['free_shipping_requires'][ $flexible_shipping_rate['method_free_shipping_requires'] ] ) ) {
					$data['flexible_shipping']['free_shipping_requires'][ $flexible_shipping_rate['method_free_shipping_requires'] ] = 0;
				}
				$data['flexible_shipping']['free_shipping_requires'][ $flexible_shipping_rate['method_free_shipping_requires'] ] ++;
			}

			if ( empty( $data['flexible_shipping']['calculation_methods'][ $flexible_shipping_rate['method_calculation_method'] ] ) ) {
				$data['flexible_shipping']['calculation_methods'][ $flexible_shipping_rate['method_calculation_method'] ] = 0;
			}
			$data['flexible_shipping']['calculation_methods'][ $flexible_shipping_rate['method_calculation_method'] ] ++;

			if ( ! empty( $flexible_shipping_rate['method_description'] ) ) {
				$data['flexible_shipping']['method_description_count'] ++;
			}

			if ( ! empty( $flexible_shipping_rate['method_free_shipping_label'] ) ) {
				$data['flexible_shipping']['free_shipping_label_count'] ++;
			}

			if ( ! empty( $flexible_shipping_rate[ WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE ] )
				&& 'yes' === $flexible_shipping_rate[ WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE ]
			) {
				$data['flexible_shipping']['free_shipping_cart_notice_count'] ++;
			}

			if ( ! empty( $flexible_shipping_rate['method_max_cost'] ) ) {
				$data['flexible_shipping']['max_cost_count'] ++;
			}

			if ( ! empty( $flexible_shipping_rate['method_visibility'] ) && 'no' !== $flexible_shipping_rate['method_visibility'] ) {
				$data['flexible_shipping']['visibility_count'] ++;
			}

			if ( ! empty( $flexible_shipping_rate['method_default'] ) && 'no' !== $flexible_shipping_rate['method_default'] ) {
				$data['flexible_shipping']['default_count'] ++;
			}
			$data['flexible_shipping'] = apply_filters( 'flexible-shipping/tracker/method-settings', $data['flexible_shipping'], $flexible_shipping_rate );

			foreach ( $flexible_shipping_rate['method_rules'] as $method_rule ) {
				$rule_settings = new SingleRuleSettings( $method_rule );
				$data['flexible_shipping'] = apply_filters( 'flexible-shipping/tracker/method-rule-data', $data['flexible_shipping'], $rule_settings->get_normalized_settings() );
			}
		}

		if ( 0 !== (int) $data['flexible_shipping']['total_shipping_methods'] ) {
			$data['flexible_shipping']['avg_rules'] = (float) $data['flexible_shipping']['avg_rules'] / (float) $data['flexible_shipping']['total_shipping_methods'];
		}

		return $data;
	}

}

if ( ! function_exists( 'wpdesk_tracker_enabled' ) ) {
	/**
	 * Disable tracker on localhost.
	 *
	 * @return bool
	 */
	function wpdesk_tracker_enabled() {
		$tracker_enabled = true;
		if ( ! empty( $_SERVER['SERVER_ADDR'] ) && '127.0.0.1' === $_SERVER['SERVER_ADDR'] ) {
			$tracker_enabled = false;
		}
		return apply_filters( 'wpdesk_tracker_enabled', $tracker_enabled );
	}
}


