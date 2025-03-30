<?php
/**
 * ProfileGrid core integrations file
 *
 * @since 1.0.0
 * @package SureTrigger
 */

namespace SureTriggers\Integrations\ProfileGrid;

use SureTriggers\Controllers\IntegrationsController;
use SureTriggers\Integrations\Integrations;
use SureTriggers\Traits\SingletonLoader;

/**
 * Class SureTrigger
 *
 * @package SureTriggers\Integrations\ProfileGrid
 */
class ProfileGrid extends Integrations {

	use SingletonLoader;

	/**
	 * ID
	 *
	 * @var string
	 */
	protected $id = 'ProfileGrid';

	/**
	 * SureTrigger constructor.
	 */
	public function __construct() {
		$this->name        = __( 'ProfileGrid', 'suretriggers' );
		$this->description = __( 'Create WordPress user profiles, groups, communities, paid memberships, directories, WooCommerce profiles, bbPress profiles, content restriction, sign-up pages, blog submissions, notifications, social activity and private messaging, beautiful threaded interface and a lot more!', 'suretriggers' );
		$this->icon_url    = SURE_TRIGGERS_URL . 'assets/icons/profilegrid.svg';

		parent::__construct();
	}

	/**
	 * Profile Grid Group Details.
	 *
	 * @param int|string $group_id Group ID.
	 * 
	 * @return array
	 */
	public static function pg_group_details( $group_id ) {
		global $wpdb;
		$group = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}promag_groups WHERE id = %d", $group_id ), ARRAY_A );

		return [
			'group_name'        => $group['group_name'],
			'group_description' => $group['group_desc'],
		];
	}

	/**
	 * Is Plugin dependent plugin is installed or not.
	 *
	 * @return bool
	 */
	public function is_plugin_installed() {
		return class_exists( 'Profile_Magic' );
	}
}

IntegrationsController::register( ProfileGrid::class );
