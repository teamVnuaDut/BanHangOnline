<?php

namespace WPDesk\FS\TableRate\ShippingMethodsIntegration;

use FSVendor\Psr\Log\LoggerInterface;
use FSVendor\Psr\Log\NullLogger;
use FSVendor\WPDesk\FS\TableRate\Logger\NoticeLogger;
use FSVendor\WPDesk\FS\TableRate\Logger\ShippingMethodLogger;
use FSVendor\WPDesk\FS\TableRate\Settings\MethodSettings;
use FSVendor\WPDesk\FS\TableRate\Settings\MethodSettingsFactory;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FS\TableRate\Rule\Condition\ConditionsFactory;
use WPDesk\FS\TableRate\Rule\Cost\RuleAdditionalCostFactory;
use WPDesk\FS\TableRate\Rule\Cost\RuleCostFieldsFactory;
use WPDesk\FS\TableRate\Rule\CostsCalculator;
use WPDesk\FS\TableRate\Rule\ShippingContents\DestinationAddressFactory;
use WPDesk\FS\TableRate\Rule\ShippingContents\ShippingContentsImplementation;
use WPDesk\FS\TableRate\Rule\SpecialAction\SpecialActionFactory;

/**
 * Class ShippingRate
 */
class ShippingRate implements Hookable {

	const FS_CANCEL = 'fs_cancel';

	/**
	 * Logger provided by Flexible Shipping plugin.
	 *
	 * @var LoggerInterface
	 */
	protected static $fs_logger;

	private array $package;

	/**
	 * Set logger. This logger is set by Flexible Shipping plugin.
	 *
	 * @param LoggerInterface $fs_logger .
	 */
	public static function set_fs_logger( LoggerInterface $fs_logger ) {
		static::$fs_logger = $fs_logger;
	}

	public function hooks() {
		add_filter( 'woocommerce_shipping_method_add_rate_args', [ $this, 'add_table_rate_shipping_rate' ], 10, 3 );
		add_action( 'woocommerce_before_get_rates_for_package', [ $this, 'save_package' ], 10 );
		add_filter( 'woocommerce_package_rates', [ $this, 'remove_canceled_rates' ] );
	}

	/**
	 * @param array $rates
	 *
	 * @return array
	 */
	public function remove_canceled_rates( $rates ) {
		$meta_key = self::FS_CANCEL;
		return array_filter(
			$rates,
			function ( $rate ) use ( $meta_key ) {
				return ! ( $rate->get_meta_data()[ $meta_key ] ?? false );
			}
		);
	}

	/**
	 * @param array $package
	 */
	public function save_package( $package ): void {
		$this->package = $package;
	}

	/**
	 * @param array               $args
	 * @param \WC_Shipping_Method $shipping_method
	 *
	 * @return array
	 */
	public function add_table_rate_shipping_rate( $args, $shipping_method ) {
		if ( ! isset( $this->package ) ) {
			return $args;
		}
		if ( ! is_array( $args ) || $shipping_method->get_option( SettingsFields::FS_CALCULATION_ENABLED, 'no' ) === 'no' ) {
			return $args;
		}
		$base_shipping_cost = is_array( $args['cost'] ) ? array_sum( $args['cost'] ) : (float) $args['cost'];
		$cost_calculator    = $this->prepare_cost_calculator( $shipping_method, $this->package );
		$cost_calculator->process_rules( $base_shipping_cost );
		$additional_cost = $cost_calculator->get_calculated_cost() - $base_shipping_cost;
		if ( ! is_array( $args['meta_data'] ) ) {
			$args['meta_data'] = [];
		}
		if ( $cost_calculator->is_cancel() ) {
			$args['meta_data'][ self::FS_CANCEL ] = true;
		} else {
			if ( ! is_array( $args['cost'] ) ) {
				$args['cost'] = [ $args['cost'] ];
			}
			$args['meta_data'][ OrderMetaData::META_KEY ] = OrderMetaData::prepare_meta_value(
				$base_shipping_cost,
				$additional_cost
			);
			$args['cost'][]                               = $additional_cost;
		}

		return $args;
	}

	private function prepare_cost_calculator( $shipping_method, $package ): CostsCalculator {
		$available_conditions       = ( new ConditionsFactory() )->get_conditions();
		$cost_fields                = ( new RuleCostFieldsFactory() )->get_fields();
		$available_additional_costs = ( new RuleAdditionalCostFactory() )->get_additional_costs();
		$available_special_actions  = ( new SpecialActionFactory() )->get_special_actions();
		$shop_currency              = get_option( 'woocommerce_currency' );
		$rules                      = $shipping_method->get_option( SettingsFields::SETTING_METHOD_RULES, '[]' );
		$method_settings            = MethodSettingsFactory::create_from_array( [ 'method_rules' => json_decode( $rules, true ) ] );
		$shipping_contents          = new ShippingContentsImplementation(
			$package['contents'],
			WC()->cart->display_prices_including_tax(),
			wc_get_price_decimals(),
			DestinationAddressFactory::create_from_package_destination( $package['destination'] ?? [] ),
			get_woocommerce_currency()
		);

		return new CostsCalculator(
			$method_settings,
			$shipping_contents,
			$available_conditions,
			$cost_fields,
			$available_additional_costs,
			$available_special_actions,
			wc_get_price_decimals(),
			$shop_currency,
			$this->prepare_shipping_method_calculation_logger( $method_settings, $shipping_method->instance_id )
		);
	}

	private function prepare_shipping_method_calculation_logger( MethodSettings $shipping_method_settings, int $instance_id ): ShippingMethodLogger {
		$method_debug_mode     = $shipping_method_settings->get_debug_mode();
		$shipping_method_title = $shipping_method_settings->get_title();
		$shipping_method_url   = admin_url(
			'admin.php?page=wc-settings&tab=shipping&instance_id=' . sanitize_key( $instance_id ) . '&action=edit&method_id=' . sanitize_key( $shipping_method_settings->get_id() )
		);
		if ( null !== static::$fs_logger ) {
			$fs_logger = static::$fs_logger;
		} else {
			$fs_logger = new NullLogger();
		}

		return new ShippingMethodLogger(
			$fs_logger,
			new NoticeLogger(
				$shipping_method_title,
				$shipping_method_url,
				'yes' === $method_debug_mode && current_user_can( 'manage_woocommerce' )
			)
		);
	}
}
