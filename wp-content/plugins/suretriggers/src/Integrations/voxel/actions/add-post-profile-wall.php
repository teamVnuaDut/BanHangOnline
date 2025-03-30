<?php
/**
 * AddPostProfileWall.
 * php version 5.6
 *
 * @category AddPostProfileWall
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Integrations\Voxel\Voxel;
use Exception;

/**
 * AddPostProfileWall
 *
 * @category AddPostProfileWall
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddPostProfileWall extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'Voxel';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'voxel_add_post_profile_wall';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add Post to Profile Wall', 'suretriggers' ),
			'action'   => 'voxel_add_post_profile_wall',
			'function' => [ $this, 'action_listener' ],
		];

		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id user_id.
	 * @param int   $automation_id automation_id.
	 * @param array $fields fields.
	 * @param array $selected_options selectedOptions.
	 * 
	 * @throws Exception Exception.
	 * 
	 * @return bool|array
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$user_email = $selected_options['wp_user_email'];
		$content    = $selected_options['content'];
		$file_ids   = isset( $selected_options['image_ids'] ) && '' !== $selected_options['image_ids'] ? explode( ',', $selected_options['image_ids'] ) : [];

		if ( ! class_exists( 'Voxel\User' ) || ! class_exists( 'Voxel\Timeline\Status' ) || ! class_exists( 'Voxel\Post' ) || ! class_exists( 'Voxel\Events\Timeline\Statuses\Post_Wall_Status_Created_Event' ) || ! defined( 'Voxel\MODERATION_APPROVED' ) || ! defined( 'Voxel\MODERATION_PENDING' ) ) {
			return false;
		}

		if ( is_email( $user_email ) ) {
			$user    = get_user_by( 'email', $user_email );
			$user_id = $user ? $user->ID : 1;
		}
		$profile    = \Voxel\User::get( $user_id );
		$profile_id = $profile->get_profile_id();

		if ( ! $profile ) {
			throw new Exception( 'Profile not found' );
		}

		$details = [];
		if ( ! empty( $file_ids ) ) {
			$details['files'] = Voxel::sanitize_files( $file_ids );
		}

		$status = \Voxel\Timeline\Status::create(
			[
				'feed'       => 'user_timeline',
				'user_id'    => $user_id,
				'post_id'    => $profile_id,
				'content'    => $content,
				'details'    => ! empty( $details ) ? $details : null,
				'moderation' => $profile->timeline_posts_require_approval() ? \Voxel\MODERATION_PENDING : \Voxel\MODERATION_APPROVED,
			],
			[ 'link_preview' => 'instant' ]
		);

		$post = \Voxel\Post::force_get( $profile_id );

		// Create and send the wall post created event.
		( new \Voxel\Events\Timeline\Statuses\Post_Wall_Status_Created_Event( $post->post_type ) )->dispatch( $status->get_id() );

		return [
			'success'     => true,
			'message'     => esc_attr__( "Post added to user's profile wall successfully", 'suretriggers' ),
			'profile_id'  => $profile_id,
			'profile_url' => get_author_posts_url( $user_id ),
			'status_id'   => $status->get_id(),
		];
	}

}

AddPostProfileWall::get_instance();
