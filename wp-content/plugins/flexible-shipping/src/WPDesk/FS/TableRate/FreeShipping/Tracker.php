<?php

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can append free shipping data to tracker.
 */
class Tracker implements Hookable {

	const PROGRESS_BAR_COUNT = 'method_free_shipping_progress_bar_count';
	const NOTICE_TEXT_COUNT = 'method_free_shipping_notice_text_count';

	public function hooks() {
		add_filter( 'flexible-shipping/tracker/method-settings', [ $this, 'append_free_shipping_data_to_tracker_data' ], 10, 2 );
	}

	public function append_free_shipping_data_to_tracker_data( $data, $shipping_method_settings ) {
		if ( ! is_array( $data ) || ! is_array( $shipping_method_settings ) ) {
			return $data;
		}

		if ( ! isset( $data[ self::PROGRESS_BAR_COUNT ] ) ) {
			$data[ self::PROGRESS_BAR_COUNT ] = 0;
		}
		if ( isset( $shipping_method_settings[ ProgressBarSettings::FIELD_NAME ] ) && $shipping_method_settings[ ProgressBarSettings::FIELD_NAME ] === 'yes' ) {
			$data[ self::PROGRESS_BAR_COUNT ]++;
		}

		if ( ! isset( $data[ self::NOTICE_TEXT_COUNT ] ) ) {
			$data[ self::NOTICE_TEXT_COUNT ] = 0;
		}
		if ( isset( $shipping_method_settings[ NoticeTextSettings::FIELD_NAME ] ) && $shipping_method_settings[ NoticeTextSettings::FIELD_NAME ] !== '' ) {
			$data[ self::NOTICE_TEXT_COUNT ]++;
		}

		return $data;
	}

}
