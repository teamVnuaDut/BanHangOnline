<?php
/**
 * CourseDeleted.
 * php version 5.6
 *
 * @category CourseDeleted
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'CourseDeleted' ) ) :

	/**
	 * CourseDeleted
	 *
	 * @category CourseDeleted
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class CourseDeleted {

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
		public $trigger = 'fc_course_deleted';

		use SingletonLoader;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'fluent_community/course/deleted', [ $this, 'trigger_listener' ], 10, 1 );
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $course_id The newly created course object.
		 * @return void
		 */
		public function trigger_listener( $course_id ) {
			
			if ( empty( $course_id ) ) {
				return;
			}

			// Prepare context with the course object.
			$context = [
				'course' => $course_id,
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
	CourseDeleted::get_instance();

endif;
