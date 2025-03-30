<?php
/**
 * UserJoinsSpace.
 * php version 5.6
 *
 * @category UserJoinsSpace
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'UserJoinsSpace' ) ) :

	/**
	 * UserJoinsSpace
	 *
	 * @category UserJoinsSpace
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class UserJoinsSpace {

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
		public $trigger = 'fc_user_joins_space';

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
				'label'         => __( 'User Joins Space', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'fluent_community/space/joined',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 3,
			];
			return $triggers;
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $space  The space object.
		 * @param int    $user_id The user ID.
		 * @param string $by     The action that triggered the join.
		 * @return void
		 */
		public function trigger_listener( $space, $user_id, $by ) {
			if ( empty( $space ) || empty( $user_id ) || empty( $by ) ) {
				return;
			}

			$context = [
				'space'  => $space,
				'userId' => $user_id,
				'by'     => $by,
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
	UserJoinsSpace::get_instance();

endif;
