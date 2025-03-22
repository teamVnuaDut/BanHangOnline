<?php
/**
 * Class AjaxTracker
 */

namespace WPDesk\FS\ProFeatures\Tracker;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can handle Ajax requests for pro features.
 */
class AjaxTracker implements Hookable {

	const AJAX_ACTION = 'flexible_shipping_pro_features_tracking';

	/**
	 * @var TrackingData
	 */
	private $tracking_data;

	/**
	 * @param TrackingData $tracking_data .
	 */
	public function __construct( TrackingData $tracking_data ) {
		$this->tracking_data = $tracking_data;
	}

	/**
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION, [ $this, 'handle_ajax' ] );
	}

	/**
	 * @return void
	 */
	public function handle_ajax(): void {
		check_ajax_referer( self::AJAX_ACTION );
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error();
		}

		$status = wc_string_to_bool( sanitize_text_field( wp_unslash( $_REQUEST['status'] ?? 'false' ) ) );

		if ( $status ) {
			$this->tracking_data->increase_show_count();
		} else {
			$this->tracking_data->increase_hide_count();
		}

		wp_send_json_success();
	}
}
