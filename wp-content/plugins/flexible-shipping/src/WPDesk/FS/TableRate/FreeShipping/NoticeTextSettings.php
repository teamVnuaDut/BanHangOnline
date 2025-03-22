<?php

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can add settings field.
 */
class NoticeTextSettings implements Hookable {

	const FIELD_NAME = 'method_free_shipping_notice_text';

	public function hooks() {
		add_filter( 'flexible-shipping/settings/common-method-settings', [ $this, 'add_field_to_settings' ] );
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function add_field_to_settings( $settings ) {
		if ( ! is_array( $settings ) ) {
			return $settings;
		}

		$new_settings = [];
		foreach ( $settings as $field_name => $field ) {
			$new_settings[ $field_name ] = $field;
			if ( $field_name === \WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE ) {
				$new_settings[ self::FIELD_NAME ] = $this->get_settings_field();
			}
		}

		return $new_settings;
	}

	private function get_settings_field() {
		return [
			'title'       => __( 'LFFS notice text', 'flexible-shipping' ),
			'type'        => 'textarea',
			// Translators: amount with currency.
			'placeholder' => __( 'You only need %1$s more to get free shipping!', 'flexible-shipping' ),
			'label'       => __( 'Display the notice with the amount left for free shipping', 'flexible-shipping' ),
			'desc_tip'    => sprintf(
			// Translators: bold.
				__( 'Enter your own custom text to be used for \'Left for free shipping\' notice in your shop. Please mind that inserting the %1$s%%1$s%2$s placeholder in the notice content is required to display the numeric value of the amount left for free shipping.', 'flexible-shipping' ),
				'<b>',
				'</b>'
			),
			// Translators: bold.
			'description' => sprintf( __( 'The %1$s%%1$s%2$s placeholder displays the numeric value of the amount left for free shipping.', 'flexible-shipping' ), '<b>', '</b>' ),
		];
	}

}
