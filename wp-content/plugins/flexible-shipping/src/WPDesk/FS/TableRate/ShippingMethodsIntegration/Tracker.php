<?php

namespace WPDesk\FS\TableRate\ShippingMethodsIntegration;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Class Tracker
 * Can add data to tracker.
 */
class Tracker implements Hookable {

	public function hooks(): void {
		add_action(
			'wpdesk_tracker_data',
			[
				$this,
				'add_data_to_tracker',
			],
			\WPDesk_Flexible_Shipping_Tracker::TRACKER_DATA_FILTER_PRIORITY + 1
		);
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function add_data_to_tracker( $data ) {
		$shipping_methods = $this->get_shipping_methods();
		$tracker_data     = [];
		foreach ( $shipping_methods as $shipping_method ) {
			$tracker_data = $this->add_shipping_method_to_tracker_data( $tracker_data, $shipping_method );
		}
		$data['flexible_shipping']['rules_table_other_methods'] = $tracker_data;

		return $data;
	}

	/**
	 * @return \WC_Shipping_Method[]
	 */
	private function get_shipping_methods(): array {
		$shipping_methods = [];
		$shipping_zones   = [ new \WC_Shipping_Zone( 0 ) ];
		foreach ( \WC_Shipping_Zones::get_zones() as $zone ) {
			$shipping_zones[] = new \WC_Shipping_Zone( $zone['zone_id'] );
		}
		foreach ( $shipping_zones as $zone ) {
			$zone_shipping_methods = $zone->get_shipping_methods( true, 'admin' );
			foreach ( $zone_shipping_methods as $zone_shipping_method ) {
				if ( $zone_shipping_method instanceof \WC_Shipping_Method ) {
					$shipping_methods[] = $zone_shipping_method;
				}
			}
		}

		return $shipping_methods;
	}

	private function add_shipping_method_to_tracker_data( array $tracker_data, \WC_Shipping_Method $shipping_method ): array {
		if ( $shipping_method->get_option( SettingsFields::FS_CALCULATION_ENABLED, 'no' ) === 'no' ) {
			return $tracker_data;
		}
		if ( empty( $tracker_data[ $shipping_method->id ] ) ) {
			$tracker_data[ $shipping_method->id ] = 1;
		} else {
			++$tracker_data[ $shipping_method->id ];
		}

		return $tracker_data;
	}
}
