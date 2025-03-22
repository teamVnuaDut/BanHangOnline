<?php
/**
 * Class ModifyStatuses
 */

namespace WPDesk\FS\Shipment;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * .
 */
class ModifyStatuses implements Hookable {

	/**
	 * @return void
	 */
	public function hooks() {
		add_filter( 'flexible_shipping_status', [ $this, 'flexible_shipping_status' ] );
	}

	/**
	 * @param mixed $statuses .
	 *
	 * @return array
	 */
	public function flexible_shipping_status( $statuses ): array {
		$statuses = is_array( $statuses ) ? $statuses : [];

		$statuses['new']       = __( 'New', 'flexible-shipping' );
		$statuses['created']   = __( 'Created', 'flexible-shipping' );
		$statuses['confirmed'] = __( 'Confirmed', 'flexible-shipping' );
		$statuses['manifest']  = __( 'Manifest', 'flexible-shipping' );
		$statuses['failed']    = __( 'Failed', 'flexible-shipping' );

		return $statuses;
	}
}
