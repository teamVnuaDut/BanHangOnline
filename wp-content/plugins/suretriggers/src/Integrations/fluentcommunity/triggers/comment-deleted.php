<?php
/**
 * CommentDeleted.
 * php version 5.6
 *
 * @category CommentDeleted
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'CommentDeleted' ) ) :

	/**
	 * CommentDeleted
	 *
	 * @category CommentDeleted
	 * @package  SureTriggers
	 * @since    1.0.0
	 */
	class CommentDeleted {

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
		public $trigger = 'fc_comment_deleted';

		use SingletonLoader;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'fluent_community/comment_deleted', [ $this, 'trigger_listener' ], 10, 2 );
		}

		/**
		 * Trigger listener.
		 *
		 * @param object $comment_id The newly created comment id.
		 * @param object $feed The newly created feed object.
		 * @return void
		 */
		public function trigger_listener( $comment_id, $feed ) {

			if ( empty( $comment_id ) ) {
				return;
			}

			// Prepare context with the course object.
			$context = [
				'comment_id' => $comment_id,
			];

			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);
		}
	}

	CommentDeleted::get_instance();

endif;
