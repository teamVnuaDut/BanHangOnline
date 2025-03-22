<?php

namespace WPDesk\FS\TableRate\ShippingMethodsIntegration;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FS\TableRate\DefaultRulesSettings;
use WPDesk\FS\TableRate\RulesSettingsField;

/**
 * Class SettingsFields
 */
class SettingsFields implements Hookable {

	const SETTING_METHOD_RULES   = 'fs_method_rules';
	const FS_CALCULATION_ENABLED = 'fs_calculation_enabled';

	public function hooks() {
		add_filter( 'woocommerce_generate_' . RulesSettingsField::FIELD_TYPE . '_html', [ $this, 'generate_shipping_rules_html' ], 10, 4 );
		add_filter( 'woocommerce_shipping_methods', [ $this, 'add_fields' ], PHP_INT_MAX );
		add_filter( 'woocommerce_shipping_method_supports', [ $this, 'remove_modal_settings_from_shipping_methods' ], 10, 3 );
	}

	/**
	 * @param bool $supports
	 * @param string $feature
	 * @param \WC_Shipping_Method $shipping_method
	 *
	 * @return bool
	 */
	public function remove_modal_settings_from_shipping_methods( $supports, $feature, $shipping_method ) {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'wc-settings'
			&& isset( $_GET['tab'] ) && $_GET['tab'] === 'shipping'
			&& isset( $_GET['instance_id'] )
		) {
			if ( $feature === 'instance-settings-modal' ) {
				return false;
			}
		}
		if ( in_array( $shipping_method->id ?? '', $this->get_allowed_shipping_methods_instance_settings(), true ) ) {
			if ( $feature === 'instance-settings-modal' ) {
				return false;
			}
		}
		return $supports;
	}

	/**
	 * @param array $methods
	 *
	 * @return array
	 */
	public function add_fields( $methods ) {
		foreach ( $methods as $shipping_method => $class ) {
			if ( ! in_array( $shipping_method, $this->get_not_allowed_shipping_methods_instance_settings(), true ) ) {
				add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method, [ $this, 'add_table_rate_fields' ], PHP_INT_MAX );
			}
			if ( in_array( $shipping_method, $this->get_allowed_shipping_methods_global_settings(), true ) ) {
				add_filter( 'woocommerce_settings_api_form_fields_' . $shipping_method, [ $this, 'add_table_rate_fields' ], PHP_INT_MAX );
			}
		}

		return $methods;
	}

	private function get_not_allowed_shipping_methods_instance_settings() {
		return apply_filters(
			'flexible-shipping/integration/not-allowed-shipping-methods-instance-settings',
			[
				'flexible_shipping_single',
				'free_shipping',
				'box_now_delivery',
				'mondialrelay_official_shipping',
			]
		);
	}

	private function get_allowed_shipping_methods_instance_settings() {
		return apply_filters(
			'flexible-shipping/integration/allowed-shipping-methods-instance-settings',
			[
				'flat_rate',
			]
		);
	}

	private function get_allowed_shipping_methods_global_settings() {
		return apply_filters(
			'flexible-shipping/integration/allowed-shipping-methods-global-settings',
			[]
		);
	}

	/**
	 * @param string $field_html
	 * @param string $key
	 * @param array $data
	 * @param \WC_Shipping_Method $shipping_method
	 *
	 * @return string
	 */
	public function generate_shipping_rules_html( $field_html, $key, $data, $shipping_method ): string {
		$title          = $data['title'];
		$field_key      = $shipping_method->plugin_id . $shipping_method->id . '_' . self::SETTING_METHOD_RULES;
		$rules_settings = new RulesSettingsField(
			$field_key,
			$field_key,
			$title,
			[],
			json_decode( $shipping_method->get_option( self::SETTING_METHOD_RULES, json_encode( ( new DefaultRulesSettings() )->get_normalized_settings() ) ), true ),
			$shipping_method->instance_settings
		);

		return $rules_settings->render();
	}

	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public function add_table_rate_fields( $fields ) {
		if ( ! is_array( $fields ) || empty( $fields ) ) {

			return $fields;
		}
		$fields['fs_method_rules_title']        = [
			'type'    => 'title',
			'title'   => __( 'Additional costs by Flexible Shipping Table Rate', 'flexible-shipping' ),
			'default' => '',
		];
		$fields[ self::FS_CALCULATION_ENABLED ] = [
			'title'   => __( 'Additional Costs', 'flexible-shipping' ),
			'type'    => 'checkbox',
			'label'   => __( 'Enable Flexible Shipping Rules Table', 'flexible-shipping' ),
			'default' => 'no',
			'class'   => 'fs-costs-calculation-enabled',
		];
		$fields[ self::SETTING_METHOD_RULES ]   = [
			'title'             => __( 'Shipping Cost Calculation Rules', 'flexible-shipping' ),
			'type'              => RulesSettingsField::FIELD_TYPE,
			'sanitize_callback' => [ self::class, 'sanitize_shipping_rules' ],
			'default'           => json_encode( ( new DefaultRulesSettings() )->get_normalized_settings() ),
		];

		return $fields;
	}

	/**
	 * @param array $value
	 *
	 * @return string
	 */
	public static function sanitize_shipping_rules( $value ) {
		if ( is_array( $value ) ) {
			return json_encode( $value );
		} else {
			return $value;
		}
	}
}
