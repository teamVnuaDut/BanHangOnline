<?php
/**
 * ListUserEnrolledCourses.
 * php version 5.6
 *
 * @category ListUserEnrolledCourses
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\LearnDash\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Integrations\LearnDash\LearnDash;
use SureTriggers\Traits\SingletonLoader;

/**
 * ListUserEnrolledCourses
 *
 * @category ListUserEnrolledCourses
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ListUserEnrolledCourses extends AutomateAction {

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
	public $action = 'ld_list_user_enrolled_courses';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'List User Enrolled Courses', 'suretriggers' ),
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
	 * @param array $selected_options selectedOptions.
	 * @throws \Exception Exception.
	 *
	 * @return bool|array
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {

		$user_id = $selected_options['wp_user_email'];

		if ( ! function_exists( 'learndash_user_get_enrolled_courses' ) ) {
			throw new \Exception( 'LearnDash learndash_user_get_enrolled_courses() function not found.' );
		}

		if ( is_email( $user_id ) ) {
			$user = get_user_by( 'email', $user_id );

			if ( $user ) {
				$user_id     = $user->ID;
				$course_data = [];
				$courses     = learndash_user_get_enrolled_courses( $user_id );
				if ( ! empty( $courses ) ) {
					foreach ( $courses as $key => $course_id ) {
						$course_data[ $key ] = LearnDash::get_course_pluggable_data( $course_id );
					}
					$course_data['status'] = 'Courses Found';
					return $course_data;
				} else {
					$message = [
						'status'   => esc_attr__( 'Success', 'suretriggers' ),
						'response' => esc_attr__( 'No Enrolled Courses found for this User.', 'suretriggers' ),
					];
					return $message;
				}
			} else {
				$error = [
					'status'   => esc_attr__( 'Error', 'suretriggers' ),
					'response' => esc_attr__( 'User not found with specified email address.', 'suretriggers' ),
				];
				return $error;
			}
		} else {
			$error = [
				'status'   => esc_attr__( 'Error', 'suretriggers' ),
				'response' => esc_attr__( 'Please enter valid email address.', 'suretriggers' ),
			];
			return $error;
		}
	}

}

ListUserEnrolledCourses::get_instance();
