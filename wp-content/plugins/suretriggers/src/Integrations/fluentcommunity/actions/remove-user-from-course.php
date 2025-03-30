<?php
/**
 * RemoveUserFromCourse.
 * php version 5.6
 *
 * @category RemoveUserFromCourse
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
 * RemoveUserFromCourse
 *
 * @category RemoveUserFromCourse
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveUserFromCourse extends AutomateAction {

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
	public $action = 'fc_remove_user_from_course';

	use SingletonLoader;

	/**
	 * Register an action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Remove User from Course', 'suretriggers' ),
			'action'   => $this->action,
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
	 * @param array $selected_options selected_options.
	 *
	 * @return array|void
	 *
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$course_id = isset( $selected_options['course_id'] ) ? (int) sanitize_text_field( $selected_options['course_id'] ) : 0;
		$user_id   = isset( $selected_options['user_id'] ) ? (int) sanitize_text_field( $selected_options['user_id'] ) : 0;

		if ( empty( $course_id ) || ! $this->is_valid_course_id( $course_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid course ID.',
			];
		}

		if ( empty( $user_id ) || ! $this->is_valid_user_id( $user_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid user ID.',
			];
		}

		if ( ! class_exists( 'FluentCommunity\Modules\Course\Services\CourseHelper' ) ) {
			return [
				'status'  => 'error',
				'message' => 'CourseHelper class not found.',
			];
		}

		try {
			CourseHelper::leaveCourse( $course_id, $user_id );

			return [
				'status'    => 'success',
				'message'   => 'User removed from course successfully',
				'course_id' => $course_id,
				'user_id'   => $user_id,
			];
		} catch ( Exception $e ) {
			return [
				'status'  => 'error',
				'message' => 'Error removing user from course: ' . $e->getMessage(),
			];
		}
	}

	/**
	 * Validate course ID.
	 *
	 * @param int $course_id Course ID.
	 *
	 * @return bool Whether course ID is valid.
	 */
	private function is_valid_course_id( $course_id ) {
		global $wpdb;
		// Directly prepare the query and pass to get_var.
		return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}fcom_spaces WHERE id = %d AND type = 'course'", $course_id ) ) > 0;
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

RemoveUserFromCourse::get_instance();
