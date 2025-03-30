<?php
/**
 * UserCompletesCourse.
 * php version 5.6
 *
 * @category UserCompletesCourse
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Integrations\WordPress\WordPress;

if ( ! class_exists( 'UserCompletesCourse' ) ) :

	/**
	 * UserCompletesCourse
	 *
	 * @category UserCompletesCourse
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class UserCompletesCourse {

		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'FluentCommunity';

		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'fc_user_completes_course';

		use SingletonLoader;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'sure_trigger_register_trigger', [ $this, 'register' ] );
		}

		/**
		 * Register the trigger.
		 *
		 * @param array $triggers Existing triggers.
		 * @return array
		 */
		public function register( $triggers ) {
			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'User Completes Course', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'fluent_community/course/completed',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 2,
			];
			return $triggers;
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $course  The course object.
		 * @param int    $user_id The user ID.
		 * @return void
		 */
		public function trigger_listener( $course, $user_id ) {
			if ( empty( $course ) || empty( $user_id ) ) {
				return;
			}

			$context = [
				'course' => $course,
				'userID' => $user_id,
				'user'   => WordPress::get_user_context( $user_id ),
			];

			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);
		}
	}

	// Initialize the class.
	UserCompletesCourse::get_instance();

endif;
