<?php
/**
 * CreateNewPostFeed.
 * php version 5.6
 *
 * @category CreateNewPostFeed
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCommunity\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use FluentCommunity\Modules\Course\Services\CourseHelper;
use FluentCommunity\App\Services\FeedsHelper;

/**
 * CreateNewPostFeed
 *
 * @category CreateNewPostFeed
 * @package  SureTriggers
 */
class CreateNewPostFeed extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'FluentCommunity';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'fc_create_new_post_feed';

	use SingletonLoader;

	/**
	 * Register an action.
	 *
	 * @param array $actions Actions array.
	 *
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create New Post in Feed', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];

		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id         User ID.
	 * @param int   $automation_id   Automation ID.
	 * @param array $fields          Fields.
	 * @param array $selected_options Selected options.
	 *
	 * @return array|void
	 *
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		// Sanitize inputs.
		$space_id = isset( $selected_options['space_id'] ) ? (int) sanitize_text_field( $selected_options['space_id'] ) : 0;
		$user_id  = isset( $selected_options['user_id'] ) ? (int) $selected_options['user_id'] : 0;

		$title     = isset( $selected_options['title'] ) ? sanitize_text_field( $selected_options['title'] ) : '';
		$message   = isset( $selected_options['message'] ) ? sanitize_textarea_field( $selected_options['message'] ) : '';
		$media_url = isset( $selected_options['media_url'] ) ? $selected_options['media_url'] : '';

		// Validate user ID.
		if ( ! $this->is_valid_user( $user_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid User ID.',
			];
		}

		// Validate space ID.
		if ( ! $this->is_valid_space( $space_id ) ) {
			return [
				'status'  => 'error',
				'message' => 'Invalid Space ID.',
			];
		}

		$feed_data = [
			'message'  => $message,
			'title'    => $title,
			'space_id' => (int) $space_id,
			'user_id'  => $user_id,
		];

		if ( ! empty( $media_url ) ) {
			$supported_images = [ '.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg' ];
			if ( in_array( strtolower( substr( $media_url, -4 ) ), $supported_images, true ) ) {
				$feed_data['message'] = $message . "\n\n ![]($media_url)";
			} else {
				$feed_data['media'] = [
					'url'  => $media_url,
					'type' => 'oembed',
				];
			}
		}

		$feed = null;
		if ( class_exists( '\FluentCommunity\App\Services\FeedsHelper' ) ) {
			$feed = FeedsHelper::createFeed( $feed_data );
		}

		if ( is_wp_error( $feed ) ) {
			return [
				'status'  => 'error',
				'message' => $feed->get_error_message(),
			];
		}

		return [
			'status'   => 'success',
			'response' => 'Post created in feed successfully',
			'space_id' => $space_id,
			'user_id'  => $user_id,
			'feed_id'  => $feed->id,
			'title'    => $title,
			'message'  => $message,
			'feed_url' => $feed->getPermalink(),
		];
	}

	/**
	 * Check if user ID is valid.
	 *
	 * @param mixed $user_id User ID.
	 *
	 * @return bool
	 */
	private function is_valid_user( $user_id ) {
		// Check if user_id is a valid integer and exists in the system.
		return ( is_int( $user_id ) && (bool) get_user_by( 'id', $user_id ) );
	}

	/**
	 * Check if space ID is valid.
	 *
	 * @param int $space_id Space ID.
	 *
	 * @return bool
	 */
	private function is_valid_space( $space_id ) {
		global $wpdb;
		$space = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}fcom_spaces WHERE ID = %d", $space_id ) );
		return (bool) $space;
	}
}

CreateNewPostFeed::get_instance();
