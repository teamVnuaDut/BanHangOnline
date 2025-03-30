<?php
/**
 * SpaceCreated.
 * php version 5.6
 *
 * @category SpaceCreated
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'SpaceCreated' ) ) :

	/**
	 * SpaceCreated
	 *
	 * @category SpaceCreated
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class SpaceCreated {

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
		public $trigger = 'fc_space_created';

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
				'label'         => __( 'New Space Created', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'fluent_community/space/created',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 2,
			];
			return $triggers;
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $space The created space object.
		 * @param array  $data  The data used to create the space.
		 * @return void
		 */
		public function trigger_listener( $space, $data ) {
			if ( empty( $space ) ) {
				return;
			}

			$context = [
				'space' => $space,
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
	SpaceCreated::get_instance();

endif;
