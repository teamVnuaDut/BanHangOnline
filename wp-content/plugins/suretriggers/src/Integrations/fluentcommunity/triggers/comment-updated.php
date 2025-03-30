<?php
/**
 * CommentUpdated.
 * php version 5.6
 *
 * @category CommentUpdated
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'CommentUpdated' ) ) :

	/**
	 * CommentUpdated
	 *
	 * @category CommentUpdated
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class CommentUpdated {

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
		public $trigger = 'fc_comment_updated';

		use SingletonLoader;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'fluent_community/comment_updated', [ $this, 'trigger_listener' ], 10, 2 );
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $comment The newly created comment object.
		 * @param object $feed The newly created feed object.
		 * @return void
		 */
		public function trigger_listener( $comment, $feed ) {

			if ( empty( $comment ) ) {
				return;
			}

			// Prepare context with the course object.
			$context = [
				'comment' => $comment,
			];

			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);
		}
	}

	CommentUpdated::get_instance();

endif;
