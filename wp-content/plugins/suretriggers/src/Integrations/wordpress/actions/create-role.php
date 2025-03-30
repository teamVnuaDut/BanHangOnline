<?php
/**
 * CreateRole.
 * php version 5.6
 *
 * @category CreateRole
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Wordpress\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use WP_User;
use Exception;

/**
 * CreateRole
 *
 * @category CreateRole
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateRole extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'WordPress';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'create_role';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Role', 'suretriggers' ),
			'action'   => 'create_role',
			'function' => [ $this, 'action_listener' ],
		];

		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id user_id.
	 * @param int   $automation_id automation_id.
	 * @param array $fields fields.
	 * @param array $selected_options selectedOptions.
	 * @return array|string
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$role_name                 = $selected_options['role_name'];
		$role_dis_name             = $selected_options['role_display_name'];
		$role_capabilities_options = $selected_options['role_capabilities'];
		$role_capabilities         = [];
		global $wp_roles; // Get the global roles object.
		$all_capabilities = [];

		foreach ( $wp_roles->roles as $role ) {
			$all_capabilities = array_merge( $all_capabilities, $role['capabilities'] );
		}
		// Handle if role_capabilities_options is a string.
		if ( is_string( $role_capabilities_options ) ) {
			$capabilities_array = explode( ',', $role_capabilities_options );
			foreach ( $capabilities_array as $capability ) {
				$capability = trim( $capability );
					
				if ( array_key_exists( $capability, $all_capabilities ) ) {
					$role_capabilities[ $capability ] = true;
				} else {
						throw new Exception( 'Capability ' . $capability . ' does not exist.' );
				}
			}
		} elseif ( is_array( $role_capabilities_options ) ) {
			foreach ( $role_capabilities_options as $role_capabilities_option ) {
				$capability = $role_capabilities_option['value'];

				// Check if the capability exists.
				if ( array_key_exists( $capability, $all_capabilities ) ) {
					$role_capabilities[ $capability ] = true;
				} else {
					throw new Exception( 'Capability ' . $capability . ' does not exist.' );
				}
			}
		} else {
			throw new Exception( 'Map Role Capabilities in comma-separated values.' );
		}

			$new_role = add_role( $role_name, $role_dis_name, $role_capabilities ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.custom_role_add_role

		if ( ! $new_role ) {
			throw new Exception( 'Role with same name exists.' );
		} else {
			return [
				$new_role,
			];
		}
	}
}

CreateRole::get_instance();
