<?php
/**
 * ClaimPost.
 * php version 5.6
 *
 * @category ClaimPost
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
 * ClaimPost
 *
 * @category ClaimPost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ClaimPost extends AutomateAction {

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
	public $action = 'voxel_claim_post';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Claim Post', 'suretriggers' ),
			'action'   => 'voxel_claim_post',
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

		if ( ! class_exists( 'Voxel\Post' ) || ! class_exists( 'Voxel\User' ) || ! function_exists( 'Voxel\get' ) ) {
			return false;
		}
		
		// Check if claims are enabled.
		if ( ! \Voxel\get( 'product_settings.claims.enabled' ) ) {
			throw new Exception( 'Claims are not enabled.' );
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

		// Set the post author to claimer.
		wp_update_post(
			[
				'ID'          => $post_id,
				'post_author' => $user_id,
			]
		);

		// Get the post.
		$post = \Voxel\Post::force_get( $post_id );

		// Set the post verified.
		$post->set_verified( true );

		delete_user_meta( $user_id, 'voxel:post_stats' );

		return [
			'success'             => true,
			'message'             => esc_attr__( 'Post claimed successfully.', 'suretriggers' ),
			'post_id'             => $post_id,
			'post_following_user' => WordPress::get_user_context( $user_id ),
			'claimed'             => WordPress::get_post_context( $post_id ),
		];
	}

}

ClaimPost::get_instance();
