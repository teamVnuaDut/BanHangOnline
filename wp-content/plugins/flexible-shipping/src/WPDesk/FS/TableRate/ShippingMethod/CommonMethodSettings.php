<?php
/**
 * Class CommonMethodSettings
 *
 * @package WPDesk\FS\TableRate\ShippingMethod
 */

namespace WPDesk\FS\TableRate\ShippingMethod;

use FSVendor\WPDesk\FS\TableRate\CalculationMethodOptions;
use FSVendor\WPDesk\FS\TableRate\Settings\CartCalculationOptions;
use WPDesk\FS\TableRate\DefaultRulesSettings;
use WPDesk\FS\TableRate\RulesSettingsField;
use WPDesk_Flexible_Shipping;

/**
 * Common shipping method settings.
 */
class CommonMethodSettings implements MethodSettings {

	const METHOD_TITLE       = 'method_title';
	const METHOD_DESCRIPTION = 'method_description';
	const METHOD_RULES       = 'method_rules';
	const CART_CALCULATION   = 'cart_calculation';

	const SETTING_METHOD_CALCULATION_METHOD = 'method_calculation_method';
	const METHOD_DEFAULT                    = 'method_default';

	const METHOD_FREE_SHIPPING_REQUIRES_UPSELLING = 'method_free_shipping_requires_upselling';

	/**
	 * @param array $method_settings           .
	 * @param bool  $with_integration_settings Append integration settings.
	 *
	 * @return array
	 */
	public function get_settings_fields( array $method_settings, $with_integration_settings ) {
		if ( ! isset( $method_settings['method_free_shipping_label'] ) ) {
			$method_settings['method_free_shipping_label'] = __( 'Free', 'flexible-shipping' );
		}

		if ( empty( $method_settings['method_integration'] ) ) {
			$method_settings['method_integration'] = '';
		}

		$method_free_shipping = '';
		if ( isset( $method_settings['method_free_shipping'] ) && '' !== $method_settings['method_free_shipping'] ) {
			$method_free_shipping = floatval( $method_settings['method_free_shipping'] );
		}

		$settings = [];

		$settings['method_enabled'] = [
			'title'   => __( 'Enable/Disable', 'flexible-shipping' ),
			'type'    => 'checkbox',
			'default' => $this->get_value_from_settings( $method_settings, 'method_enabled', 'yes' ),
			'label'   => __( 'Enable this shipment method', 'flexible-shipping' ),
		];

		$settings[ self::METHOD_TITLE ] = [
			'title'             => __( 'Method Title', 'flexible-shipping' ),
			'type'              => 'text',
			'description'       => __( 'This controls the title which the user sees during checkout.', 'flexible-shipping' ),
			'desc_tip'          => true,
			'default'           => $this->get_value_from_settings( $method_settings, self::METHOD_TITLE, 'Flexible Shipping' ),
			'custom_attributes' => [ 'required' => true ],
		];

		$settings[ self::METHOD_DESCRIPTION ] = [
			'title'       => __( 'Method Description', 'flexible-shipping' ),
			'type'        => 'text',
			'description' => __( 'This controls method description which the user sees during checkout.', 'flexible-shipping' ),
			'desc_tip'    => true,
			'default'     => $this->get_value_from_settings( $method_settings, self::METHOD_DESCRIPTION, '' ),
		];

		$settings['method_free_shipping'] = [
			'title'       => __( 'Free shipping threshold', 'flexible-shipping' ),
			'type'        => 'price',
			'default'     => $method_free_shipping,
			'description' => sprintf(
				// Translators: bolds.
				__( 'Enter a minimum threshold value which once reached will result in granting your customers the free shipping. It will be applied, as long as this shipping method will be available and its configured shipping cost calculation conditions are met. Example: If the %1$sFree shipping threshold%2$s is set to $200, and your price-based shipping cost calculation rules end at $199.99, you need to configure one more rule - %1$sWhen: Price - is from $200 - cost is $0%2$s to cover the range above the %1$sFree shipping threshold%2$s value.', 'flexible-shipping' ),
				'<strong>',
				'</strong>'
			),
			'desc_tip'    => true,
		];

		$settings['method_free_shipping_label'] = [
			'title'       => __( 'Free Shipping Label', 'flexible-shipping' ),
			'type'        => 'text',
			'default'     => $this->get_value_from_settings( $method_settings, 'method_free_shipping_label', '' ),
			'description' => __( 'Enter the text for the additional shipping method\'s label which will be displayed once the free shipping is triggered or calculated.', 'flexible-shipping' ),
			'desc_tip'    => true,
		];

		$description = sprintf( //phpcs:ignore.
						__( 'Learn %1$show to customize the displayed notice &rarr;%2$s', 'flexible-shipping' ), //phpcs:ignore.
							sprintf( //phpcs:ignore.
								'<a href="%s" target="_blank">', //phpcs:ignore.
								esc_url( get_user_locale() === 'pl_PL' ? 'https://octol.io/fs-free-notice-pl' : 'https://octol.io/fs-free-notice' ) //phpcs:ignore.
						), //phpcs:ignore.
						'</a>' //phpcs:ignore.
					) . '<br /><br />' . __( 'Please mind that if you use any additional plugins to split the shipment into packages, the \'Left to free shipping notice\' will not be displayed.', 'flexible-shipping' ); //phpcs:ignore.

		$settings[ WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE ] = [
			'title'       => __( '\'Left for free shipping\' notice (LFFS)', 'flexible-shipping' ),
			'type'        => 'checkbox',
			'default'     => $this->get_value_from_settings( $method_settings, WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE, 'no' ),
			'label'       => __( 'Display the notice with the amount left for free shipping', 'flexible-shipping' ),
			'description' => $description,
		];

		$settings[ self::SETTING_METHOD_CALCULATION_METHOD ] = [
			'title'       => __( 'Rules Calculation', 'flexible-shipping' ),
			'type'        => 'select',
			'description' => __( 'Select how rules will be calculated. If you choose "sum" the rules order is important.', 'flexible-shipping' ),
			'default'     => $this->get_value_from_settings( $method_settings, self::SETTING_METHOD_CALCULATION_METHOD, '' ),
			'desc_tip'    => true,
			'options'     => ( new CalculationMethodOptions() )->get_options(),
		];

		$settings[ self::CART_CALCULATION ] = [
			'title'       => __( 'Cart Calculation', 'flexible-shipping' ),
			'type'        => 'select',
			'default'     => $this->get_value_from_settings( $method_settings, self::CART_CALCULATION, isset( $method_settings[ self::METHOD_DESCRIPTION ] ) ? CartCalculationOptions::CART : CartCalculationOptions::PACKAGE ),
			'options'     => ( new CartCalculationOptions() )->get_options(),
			'description' => __( 'Choose Package value to exclude virtual products from rules calculation.', 'flexible-shipping' ),
			'desc_tip'    => true,
		];

		$settings['method_visibility'] = [
			'title'   => __( 'Visibility', 'flexible-shipping' ),
			'type'    => 'checkbox',
			'default' => $this->get_value_from_settings( $method_settings, 'method_visibility', 'no' ),
			'label'   => __( 'Show only for logged in users', 'flexible-shipping' ),
		];

		$settings[ self::METHOD_DEFAULT ] = [
			'title'   => __( 'Default', 'flexible-shipping' ),
			'type'    => 'checkbox',
			'default' => $this->get_value_from_settings( $method_settings, self::METHOD_DEFAULT, 'no' ),
			'label'   => __( 'Check the box to set this option as the default selected choice on the cart page.', 'flexible-shipping' ),
		];

		$settings['method_debug_mode'] = [
			'title'       => __( 'FS Debug Mode', 'flexible-shipping' ),
			'type'        => 'checkbox',
			'default'     => $this->get_value_from_settings( $method_settings, 'method_debug_mode', 'no' ),
			'label'       => __( 'Enable FS Debug Mode', 'flexible-shipping' ),
			'description' => sprintf(
			// Translators: documentation link.
				__( 'Enable FS debug mode to verify the shipping methods\' configuration, check which one was used and how the shipping cost was calculated as well as identify any possible mistakes. %1$sLearn more how the Debug Mode works →%2$s', 'flexible-shipping' ),
				'<a href="' . ( 'pl_PL' !== get_user_locale() ? 'https://octol.io/fs-debug-mode' : 'https://octol.io/fs-debug-mode-pl' ) . '" target="_blank">',
				'</a>'
			),
		];

		if ( $with_integration_settings ) {
			$settings = $this->append_integration_settings_if_present( $settings, $method_settings );
		}

		$settings[ self::METHOD_RULES ] = [
			'title'            => __( 'Shipping Cost Calculation Rules', 'flexible-shipping' ),
			'type'             => RulesSettingsField::FIELD_TYPE,
			'default'          => $this->get_value_from_settings( $method_settings, self::METHOD_RULES, ( new DefaultRulesSettings() )->get_normalized_settings() ),
			self::METHOD_TITLE => $this->get_value_from_settings( $method_settings, self::METHOD_TITLE, __( 'Flexible Shipping', 'flexible-shipping' ) ),
		];

		$settings[] = [
			'type'    => 'title',
			'id'      => 'flexible_shipping_method_settings_end',
			'default' => '',
		];

		return apply_filters( 'flexible-shipping/settings/common-method-settings', $settings );
	}

	/**
	 * @param array $settings        .
	 * @param array $method_settings .
	 *
	 * @return array
	 */
	private function append_integration_settings_if_present( array $settings, $method_settings ) {
		$integrations_options = apply_filters( 'flexible_shipping_integration_options', [ '' => __( 'None', 'flexible-shipping' ) ] );

		if ( 1 < count( $integrations_options ) ) {
			$settings['title_shipping_integration'] = [
				'title'   => __( 'Shipping Integration', 'flexible-shipping' ),
				'type'    => 'title',
				'default' => '',
			];
			$settings['method_integration']         = [
				'title'    => __( 'Integration', 'flexible-shipping' ),
				'type'     => 'select',
				'desc_tip' => false,
				'options'  => $integrations_options,
				'default'  => $this->get_value_from_settings( $method_settings, 'method_integration' ),
			];
		}

		$filtered_settings = apply_filters( 'flexible_shipping_method_settings', $settings, $method_settings );

		$settings = [];

		foreach ( $filtered_settings as $settings_key => $settings_value ) {
			if ( 'method_enabled' === $settings_key ) {
				$settings['title_general_settings'] = [
					'title'   => __( 'General Settings', 'flexible-shipping' ),
					'type'    => 'title',
					'default' => '',
				];
			}

			if ( 'method_free_shipping_requires' === $settings_key || ( 'method_free_shipping' === $settings_key && ! isset( $settings['method_free_shipping_requires'] ) ) ) {
				$free_shipping_docs_url          = get_user_locale() === 'pl_PL' ? 'https://octol.io/fs-free-shipping-coming-from-the-rules-pl' : 'https://octol.io/fs-free-shipping-coming-from-the-rules';
				$settings['title_free_shipping'] = [
					'title'       => __( 'Free Shipping', 'flexible-shipping' ),
					'type'        => 'title',
					'description' => sprintf(
					// Translators: strong and link.
						__( 'Specify when the free shipping should be available to your customers. You can use the %1$sFree shipping threshold%2$s option below or/and you can also set up the free shipping resulting directly from the Flexible Shipping cost calculation rules. %3$sLearn how to configure the free shipping coming from the cost calculation rules &rarr;%4$s', 'flexible-shipping' ),
						'<strong>',
						'</strong>',
						'<a href="' . $free_shipping_docs_url . '" target="_blank">',
						'</a>'
					),
					'default'     => '',
				];
				if ( ! defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' ) ) {
					$compare_versions_link                                     = get_user_locale() === 'pl_PL' ? 'https://octol.io/free-shipping-requires-fs-free-pro-comparison-pl' : 'https://octol.io/free-shipping-requires-fs-free-pro-comparison';
					$settings[ self::METHOD_FREE_SHIPPING_REQUIRES_UPSELLING ] = [
						'title'       => __( 'Free Shipping Requires', 'flexible-shipping' ),
						'type'        => 'select',
						'options'     => [
							'order_amount'            => __( 'Minimum order value', 'flexible-shipping' ),
							'item_quantity'           => __( 'Minimum item quantity (PRO)', 'flexible-shipping' ),
							'coupon'                  => __( 'Free shipping coupon (PRO)', 'flexible-shipping' ),
							'order_amount_or_coupon'  => __( 'Free shipping coupon or minimum order amount (PRO) ', 'flexible-shipping' ),
							'order_amount_and_coupon' => __( 'Free shipping coupon and minimum order amount (PRO)', 'flexible-shipping' ),
						],
						'description' => sprintf(
							// Translators: link.
							__( 'Compare the %1$sdifferences between Flexible Shipping FREE and PRO &rarr;%2$s', 'flexible-shipping' ),
							'<a href="' . $compare_versions_link . '" target="_blank">',
							'</a>'
						),
						'desc_tip'    => 'Define the condition which must be met for the free shipping to be granted.',
					];
				}
			}

			if ( 'method_max_cost' === $settings_key || ( self::SETTING_METHOD_CALCULATION_METHOD === $settings_key && ! isset( $settings['method_max_cost'] ) ) ) {
				$settings['title_cost_calculation'] = [
					'title'   => __( 'Cost Calculation', 'flexible-shipping' ),
					'type'    => 'title',
					'default' => '',
				];
			}

			if ( 'method_visibility' === $settings_key ) {
				$settings['title_advanced_options'] = [
					'title'   => __( 'Advanced Options', 'flexible-shipping' ),
					'type'    => 'title',
					'default' => '',
				];
			}

			$settings[ $settings_key ] = $settings_value;
		}

		return $settings;
	}

	/**
	 * @param array        $settings   .
	 * @param string       $field_name .
	 * @param string|array $default    .
	 *
	 * @return string
	 */
	private function get_value_from_settings( array $settings, $field_name, $default = '' ) {
		return isset( $settings[ $field_name ] ) ? $settings[ $field_name ] : $default;
	}
}
