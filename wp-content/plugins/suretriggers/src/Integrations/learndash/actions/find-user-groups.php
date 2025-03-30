<?php
/**
 * FindUserGroups.
 * php version 5.6
 *
 * @category FindUserGroups
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\LearnDash\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Integrations\LearnDash\LearnDash;
use SureTriggers\Traits\SingletonLoader;

/**
 * FindUserGroups
 *
 * @category FindUserGroups
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class FindUserGroups extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'LearnDash';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'learndash_find_user_groups';

	use SingletonLoader;

	/**
	 * Register an action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Find User Groups', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];
		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id user ID.
	 * @param int   $automation_id automation ID.
	 * @param array $fields template fields.
	 * @param array $selected_options saved template data.
	 *
	 * @return bool|array
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		if ( ! $user_id ) {
			$this->set_error(
				[
					'msg' => __( 'User Not Found', 'suretriggers' ),
				]
			);
			return false;
		}

		if ( ! function_exists( 'learndash_get_users_group_ids' ) ) {
			$this->set_error(
				[
					'msg' => __( 'LearnDash function not available', 'suretriggers' ),
				]
			);
			return false;
		}
		

		$user_groups = learndash_get_users_group_ids( $user_id );
		
		if ( empty( $user_groups ) ) {
			return [
				'message' => __( 'User is not part of any group', 'suretriggers' ),
			];
		}

		$group_data = [];

	
		foreach ( $user_groups as $group_id ) {
			$group_data[] = [
				'group_id'   => $group_id,
				'group_name' => get_the_title( $group_id ),
			];
		}

		$user_data = LearnDash::get_user_pluggable_data( $user_id );

		return [
			'user'   => $user_data,
			'groups' => $group_data,
		];
	}
}

FindUserGroups::get_instance();
