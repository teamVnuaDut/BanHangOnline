<?php

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can add settings field.
 */
class ProgressBarSettings implements Hookable {

	const FIELD_NAME = 'method_free_shipping_progress_bar';

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
			if ( $field_name === NoticeTextSettings::FIELD_NAME ) {
				$new_settings[ self::FIELD_NAME ] = $this->get_settings_field();
			}
		}

		return $new_settings;
	}

	private function get_settings_field() {
		return [
			'title'       => __( 'LFFS progress bar', 'flexible-shipping' ),
			'type'        => 'checkbox',
			'label'       => __( 'Display the \'Left for free shipping\' progress bar', 'flexible-shipping' ),
			'desc_tip'    => sprintf(
				__( 'Tick this checkbox to display an additional progress bar to your customers showing the amount left to qualify for free shipping.', 'flexible-shipping' ),
				'<b>',
				'</b>'
			),
		];
	}

}
