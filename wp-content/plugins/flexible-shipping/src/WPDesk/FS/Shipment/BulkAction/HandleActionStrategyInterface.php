<?php
/**
 * Interface HandleActionStrategyInterface
 */

namespace WPDesk\FS\Shipment\BulkAction;

/**
 * .
 */
interface HandleActionStrategyInterface {
	/**
	 * @param string $redirect_to .
	 * @param array  $post_ids    .
	 *
	 * @return string
	 */
	public function handle( string $redirect_to, array $post_ids ): string;
}
