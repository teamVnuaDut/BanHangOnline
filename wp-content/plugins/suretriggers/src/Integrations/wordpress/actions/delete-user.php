<?php
/**
 * DeleteUser.
 * php version 5.6
 *
 * @category DeleteUser
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
 * DeleteUser
 *
 * @category DeleteUser
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class DeleteUser extends AutomateAction {

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
	public $action = 'delete_user';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Delete User', 'suretriggers' ),
			'action'   => 'delete_user',
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
	 * @return array|bool
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$delete_user = $selected_options['user_delete'];
		$reassign    = $selected_options['user_reassign'];

		if ( ! $delete_user ) {
			return false;
		}

		// Check if the user is an administrator.
		$user = get_userdata( $delete_user );
		
		if ( ! $user ) {
			throw new Exception( 'User not found.' );
		}
		if ( in_array( 'administrator', (array) $user->roles ) ) {
			throw new Exception( 'You cannot delete an administrator.' );
		}
		require_once ABSPATH . '/wp-admin/includes/user.php';

		if ( ! $reassign ) {
			if ( wp_delete_user( $delete_user ) ) {
				return [
					'success'  => true,
					'message'  => 'User deleted successfully.',
					'user_id'  => $delete_user,
					'reassign' => $reassign,
				];
			} else {
				throw new Exception( 'There was an error in deleting the user.' );
			}       
		} else {
			if ( wp_delete_user( $delete_user, $reassign ) ) {
				return [
					'success'  => true,
					'message'  => 'User deleted successfully.',
					'user_id'  => $delete_user,
					'reassign' => $reassign,
				];

			} else {
				throw new Exception( 'There was an error in deleting the user.' );
			}
		}
	}
}

DeleteUser::get_instance();
