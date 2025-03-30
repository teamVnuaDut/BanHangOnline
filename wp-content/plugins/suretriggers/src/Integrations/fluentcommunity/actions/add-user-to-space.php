<?php
/**
 * AddUserToSpace.
 * php version 5.6
 *
 * @category AddUserToSpace
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
 * AddUserToSpace
 *
 * @category AddUserToSpace
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUserToSpace extends AutomateAction {

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
	public $action = 'fc_add_user_to_space';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add User to Space', 'suretriggers' ),
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
	 * @param array $fields fields.
	 * @param array $selected_options selected options.
	 *
	 * @return array|void
	 *
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		// Sanitize inputs.
		$space_id = isset( $selected_options['space_id'] ) ? (int) sanitize_text_field( $selected_options['space_id'] ) : 0;
		$user_id  = isset( $selected_options['user_id'] ) ? (int) sanitize_text_field( $selected_options['user_id'] ) : 0;
		$role     = isset( $selected_options['role'] ) ? sanitize_text_field( $selected_options['role'] ) : '';
		$by       = 'by_automation';

		// Check if class exists.
		if ( ! class_exists( 'FluentCommunity\App\Services\Helper' ) ) {
			return [
				'status'  => 'error',
				'message' => 'Helper class not found.',
			];
		}

		// Validate user ID.
		if ( ! $this->is_valid_user( $user_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid User ID.',
			];
		}

		// Validate space ID.
		if ( ! $this->is_valid_space( $space_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid Space ID.',
			];
		}

		// Add user to space.
		try {
			Helper::addToSpace( $space_id, $user_id, $role, $by );
		} catch ( Exception $e ) {
			return [
				'status'  => 'error',
				'message' => 'An error occurred: ' . $e->getMessage(),
			];
		}

		return [
			'status'   => 'success',
			'message'  => 'User added to space successfully',
			'space_id' => $space_id,
			'user_id'  => $user_id,
			'role'     => $role,
		];
	}

	/**
	 * Helper function to check if user ID is valid.
	 *
	 * @param int $user_id User ID.
	 * @return bool True if valid, false otherwise.
	 */
	private function is_valid_user( $user_id ) {
		$user = get_userdata( $user_id );
		return ( $user ) ? true : false;
	}

	/**
	 * Helper function to check if space ID is valid.
	 *
	 * @param int $space_id Space ID.
	 * @return bool True if valid, false otherwise.
	 */
	private function is_valid_space( $space_id ) {
		global $wpdb;
		$space = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}fcom_spaces WHERE ID = %d", $space_id ) );
		return ( $space ) ? true : false;
	}
}

AddUserToSpace::get_instance();
