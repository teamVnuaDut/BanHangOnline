<?php
/**
 * FeedCreated.
 * php version 7.0+
 *
 * @category FeedCreated
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'FeedCreated' ) ) :

	/**
	 * FeedCreated Class
	 *
	 * Handles the trigger when a new feed is created in FluentCommunity.
	 *
	 * @since 1.0.0
	 */
	class FeedCreated {

		use SingletonLoader;

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
		public $trigger = 'fcs_feed_created';

		/**
		 * Constructor
		 *
		 * Initializes the FeedCreated class.
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
		 * @return array Modified triggers with the new trigger added.
		 */
		public function register( $triggers ) {
			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'New Feed Created', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'fluent_community/feed/created',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * Listens for the `fluent_community/feed/created` action and triggers automation.
		 *
		 * @param object $feed Feed object containing details of the created feed.
		 * @return void
		 */
		public function trigger_listener( $feed ) {
			if ( empty( $feed ) ) {
				return;
			}

			// Prepare the context data for automation handling.
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

	/**
	 * Initialize the singleton instance of FeedCreated.
	 */
	FeedCreated::get_instance();

endif;
