<?php
/**
 * VoxelPostQuoted.
 * php version 5.6
 *
 * @category VoxelPostQuoted
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

if ( ! class_exists( 'VoxelPostQuoted' ) ) :

	/**
	 * VoxelPostQuoted
	 *
	 * @category VoxelPostQuoted
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class VoxelPostQuoted {


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
		public $trigger = 'voxel_post_quoted';

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
				'label'         => __( 'Post Reposted', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'voxel/app-events/users/timeline/post-quoted',
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
			if ( ! property_exists( $event, 'status' ) || ! property_exists( $event, 'author' ) || ! property_exists( $event, 'recipient' ) || ! property_exists( $event, 'quote_of' ) ) {
				return;
			}
			$author = get_userdata( $event->author->get_id() );
			if ( $author ) {
				$author_data                    = (array) $author->data;
				$context['author_display_name'] = $author_data['display_name'];
				$context['author_name']         = $author_data['user_nicename'];
				$context['author_email']        = $author_data['user_email'];
			}
			$recipient_user = get_userdata( $event->recipient->get_id() );
			if ( $recipient_user ) {
				$recipient_user_data                       = (array) $recipient_user->data;
				$context['recipient']['user_display_name'] = $recipient_user_data['display_name'];
				$context['recipient']['user_name']         = $recipient_user_data['user_nicename'];
				$context['recipient']['user_email']        = $recipient_user_data['user_email'];
			}
			if ( class_exists( 'Voxel\Timeline\Status' ) ) {
				$status_details = \Voxel\Timeline\Status::get( $event->status->get_id() );
				foreach ( (array) $status_details as $key => $value ) {
					$clean_key                       = preg_replace( '/^\0.*?\0/', '', $key );
					$context['status'][ $clean_key ] = $value;
				}
				$repost_details = \Voxel\Timeline\Status::get( $event->quote_of->get_id() );
				foreach ( (array) $repost_details as $key => $value ) {
					$clean_key                         = preg_replace( '/^\0.*?\0/', '', $key );
					$context['quote_of'][ $clean_key ] = $value;
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
	VoxelPostQuoted::get_instance();

endif;
