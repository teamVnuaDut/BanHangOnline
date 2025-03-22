<?php
/**
 * Class Tracker
 */

namespace WPDesk\FS\ProFeatures\Tracker;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk_Flexible_Shipping_Tracker;

/**
 * Class Tracker
 */
class Tracker implements Hookable {

	const TRACKER_DATA_FILTER_PRIORITY = WPDesk_Flexible_Shipping_Tracker::TRACKER_DATA_FILTER_PRIORITY + 1;

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
	 * Hooks.
	 */
	public function hooks() {
		add_filter( 'wpdesk_tracker_data', [ $this, 'add_tracking_data' ], self::TRACKER_DATA_FILTER_PRIORITY );
	}

	/**
	 * Add pro features data to tracker.
	 *
	 * @param mixed $data .
	 *
	 * @return array
	 */
	public function add_tracking_data( $data ): array {
		$data['flexible_shipping']['pro_features_visible'] = $this->tracking_data->get_tracking_data();

		return $data;
	}
}
