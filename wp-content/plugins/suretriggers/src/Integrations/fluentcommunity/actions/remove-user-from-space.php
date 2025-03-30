<?php
/**
 * RemoveUserFromSpace
 *
 * @category RemoveUserFromSpace
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use FluentCommunity\App\Services\Helper;

/**
 * Class RemoveUserFromSpace
 *
 * @category RemoveUserFromSpace
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @since    1.0.0
 */
class RemoveUserFromSpace extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'FluentCommunity';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'fc_remove_user_from_space';

	use SingletonLoader;

	/**
	 * Register the action.
	 *
	 * @param array $actions Actions array.
	 *
	 * @return array Modified actions array.
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Remove User from Space', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];
		return $actions;
	}

	/**
	 * Action listener for removing user from space.
	 *
	 * @param int   $user_id        User ID.
	 * @param int   $automation_id  Automation ID.
	 * @param array $fields         Fields data.
	 * @param array $selected_options Selected options.
	 *
	 * @return array|void Status and message.
	 *
	 * @throws Exception If removal fails.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$space_id = isset( $selected_options['space_id'] ) ? (int) sanitize_text_field( $selected_options['space_id'] ) : 0;
		$user_id  = isset( $selected_options['user_id'] ) ? (int) sanitize_text_field( $selected_options['user_id'] ) : 0;
		$by       = 'by_automation';

		if ( empty( $space_id ) || ! $this->is_valid_space_id( $space_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid space ID.',
			];
		}

		if ( empty( $user_id ) || ! $this->is_valid_user_id( $user_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid user ID.',
			];
		}

		if ( ! class_exists( 'FluentCommunity\App\Services\Helper' ) ) {
			return [
				'status'  => 'error',
				'message' => 'Helper class not found.',
			];
		}

		try {
			Helper::removeFromSpace( $space_id, $user_id, $by );
			return [
				'status'   => 'success',
				'message'  => 'User removed from space successfully',
				'space_id' => $space_id,
				'user_id'  => $user_id,
			];
		} catch ( Exception $e ) {
			return [
				'status'  => 'error',
				'message' => 'Error removing user from space: ' . $e->getMessage(),
			];
		}
	}

	/**
	 * Validate space ID.
	 *
	 * @param int $space_id Space ID.
	 *
	 * @return bool Whether space ID is valid.
	 */
	private function is_valid_space_id( $space_id ) {
		global $wpdb;
		// Directly prepare the query and pass to get_var.
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}fcom_spaces WHERE ID = %d", $space_id ) ) > 0;
	}

	/**
	 * Validate user ID.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return bool Whether user ID is valid.
	 */
	private function is_valid_user_id( $user_id ) {
		global $wpdb;
		// Directly prepare the query and pass to get_var.
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}users WHERE ID = %d", $user_id ) ) > 0;
	}

}

RemoveUserFromSpace::get_instance();
