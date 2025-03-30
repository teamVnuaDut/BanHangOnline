<?php
/**
 * AddUserToCourse.
 * php version 5.6
 *
 * @category AddUserToCourse
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
use FluentCommunity\Modules\Course\Services\CourseHelper;

/**
 * AddUserToCourse
 *
 * @category AddUserToCourse
 * @package  SureTriggers
 * @since    1.0.0
 */
class AddUserToCourse extends AutomateAction {

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
	public $action = 'fc_add_user_to_course';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add User to Course', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];

		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id         User ID.
	 * @param int   $automation_id   Automation ID.
	 * @param array $fields          Fields.
	 * @param array $selected_options Selected options.
	 *
	 * @return array|void
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$course_id = isset( $selected_options['course_id'] ) ? (int) sanitize_text_field( $selected_options['course_id'] ) : 0;
		$user_id   = isset( $selected_options['user_id'] ) ? (int) sanitize_text_field( $selected_options['user_id'] ) : 0;

		if ( ! class_exists( 'FluentCommunity\Modules\Course\Services\CourseHelper' ) ) {
			return [
				'status'  => 'error',
				'message' => 'CourseHelper class not found.',
			];
		}

		if ( ! $this->is_valid_user( $user_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid User ID.',
			];
		}

		if ( ! $this->is_valid_course( $course_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid Course ID.',
			];
		}

		try {
			CourseHelper::enrollCourse( $course_id, $user_id );
		} catch ( Exception $e ) {
			return [
				'status'  => 'error',
				'message' => 'An error occurred: ' . $e->getMessage(),
			];
		}

		return [
			'status'    => 'success',
			'message'   => 'User added to course successfully',
			'course_id' => $course_id,
			'user_id'   => $user_id,
		];
	}

	/**
	 * Check if the user ID is valid.
	 *
	 * @param int $user_id User ID.
	 * @return bool
	 */
	private function is_valid_user( $user_id ) {
		$user = get_user_by( 'id', $user_id );
		return (bool) $user;
	}

	/**
	 * Check if the course ID is valid.
	 *
	 * @param int $course_id Course ID.
	 * @return bool
	 */
	private function is_valid_course( $course_id ) {
		global $wpdb;
		$course = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}fcom_spaces WHERE ID = %d AND type = 'course'", $course_id ) );
		return (bool) $course;
	}
}

AddUserToCourse::get_instance();
