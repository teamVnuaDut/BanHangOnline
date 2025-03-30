<?php
/**
 * NewSpaceFeedCreated.
 * php version 5.6
 *
 * @category NewSpaceFeedCreated
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'NewSpaceFeedCreated' ) ) :

	/**
	 * NewSpaceFeedCreated
	 *
	 * @category NewSpaceFeedCreated
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class NewSpaceFeedCreated {

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
		public $trigger = 'fcs_new_space_feed_created';

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
				'label'         => __( 'New Space Feed Created', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'fluent_community/space_feed/created',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $feed The created feed object.
		 * @return void
		 */
		public function trigger_listener( $feed ) {
			if ( empty( $feed ) ) {
				return;
			}

			$context = [
				'feed' => $feed,
			
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
	NewSpaceFeedCreated::get_instance();

endif;
