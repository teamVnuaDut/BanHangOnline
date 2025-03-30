<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Internal\Jetpack;

use Automattic\Jetpack\Sync\Modules;
use Automattic\Jetpack\Sync\Modules\Full_Sync_Immediately;

defined( 'ABSPATH' ) || exit;

/**
 * Class SyncModules
 *
 * Used to retrieve the Full Sync Immediately module when needed
 * (and not before JetpackConfiguration initializes the sync modules).
 */
class SyncModules {

	/**
	 * Get the full sync immediately module.
	 *
	 * @return Full_Sync_Immediately
	 */
	public function get_full_sync_immediately(): Full_Sync_Immediately {
		return Modules::get_module( 'full-sync' );
	}
}
