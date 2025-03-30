<?php
/**
 * FollowPost.
 * php version 5.6
 *
 * @category FollowPost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Integrations\WordPress\WordPress;
use SureTriggers\Integrations\Voxel\Voxel;
use Exception;

/**
 * FollowPost
 *
 * @category FollowPost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class FollowPost extends AutomateAction {

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
	public $action = 'voxel_follow_post';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Follow Post', 'suretriggers' ),
			'action'   => 'voxel_follow_post',
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
		$post_id    = (int) $selected_options['post_id'];
		
		if ( ! class_exists( 'Voxel\Post' ) || ! class_exists( 'Voxel\User' ) ) {
			return false;
		}

		if ( is_email( $user_email ) ) {
			$user = get_user_by( 'email', $user_email );
			if ( $user ) {
				$user_id = $user->ID;
			}
		}
		
		// Get the post.
		$post = \Voxel\Post::get( $post_id );
		if ( ! $post ) {
			throw new Exception( 'Post not found.' );
		}

		$current_user = \Voxel\User::get( $user_id );
		if ( ! $current_user ) {
			throw new Exception( 'User not found.' );
		}
		$current_status = $current_user->get_follow_status( 'post', $post->get_id() );
		if ( 1 === $current_status ) {
			$current_user->set_follow_status( 'post', $post->get_id(), null );
		} else {
			$current_user->set_follow_status( 'post', $post->get_id(), 1 );
		}

		return [
			'success'             => true,
			'message'             => esc_attr__( 'Post followed successfully.', 'suretriggers' ),
			'post_id'             => $post_id,
			'post_following_user' => WordPress::get_user_context( $user_id ),
			'followed'            => WordPress::get_post_context( $post_id ),
		];
	}

}

FollowPost::get_instance();
