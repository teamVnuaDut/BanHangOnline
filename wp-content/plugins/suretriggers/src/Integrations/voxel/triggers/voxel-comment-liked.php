<?php
/**
 * VoxelCommentLiked.
 * php version 5.6
 *
 * @category VoxelCommentLiked
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Integrations\Voxel\Voxel;

if ( ! class_exists( 'VoxelCommentLiked' ) ) :

	/**
	 * VoxelCommentLiked
	 *
	 * @category VoxelCommentLiked
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class VoxelCommentLiked {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'Voxel';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'voxel_comment_liked';

		use SingletonLoader;


		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			add_filter( 'sure_trigger_register_trigger', [ $this, 'register' ] );
		}

		/**
		 * Register action.
		 *
		 * @param array $triggers trigger data.
		 * @return array
		 */
		public function register( $triggers ) {
			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'Comment Liked', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'voxel/app-events/users/timeline/comment-liked',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param object $event Event.
		 * @return void
		 */
		public function trigger_listener( $event ) {
			if ( ! property_exists( $event, 'comment' ) || ! property_exists( $event, 'user' ) || ! property_exists( $event, 'recipient' ) ) {
				return;
			}
			$user = get_userdata( $event->user->get_id() );
			if ( $user ) {
				$user_data                    = (array) $user->data;
				$context['user_display_name'] = $user_data['display_name'];
				$context['user_name']         = $user_data['user_nicename'];
				$context['user_email']        = $user_data['user_email'];
			}
			$recipient_user = get_userdata( $event->recipient->get_id() );
			if ( $recipient_user ) {
				$recipient_user_data                       = (array) $recipient_user->data;
				$context['recipient']['user_display_name'] = $recipient_user_data['display_name'];
				$context['recipient']['user_name']         = $recipient_user_data['user_nicename'];
				$context['recipient']['user_email']        = $recipient_user_data['user_email'];
			}
			if ( class_exists( 'Voxel\Timeline\Reply' ) ) {
				$reply_details = \Voxel\Timeline\Reply::get( $event->comment->get_id() );
				foreach ( (array) $reply_details as $key => $value ) {
					$clean_key             = preg_replace( '/^\0.*?\0/', '', $key );
					$context[ $clean_key ] = $value;
				}
			}
			if ( ! empty( $context ) ) {
				AutomationController::sure_trigger_handle_trigger(
					[
						'trigger' => $this->trigger,
						'context' => $context,
					]
				);
			}
		}
	}

	/**
	 * Ignore false positive
	 *
	 * @psalm-suppress UndefinedMethod
	 */
	VoxelCommentLiked::get_instance();

endif;
