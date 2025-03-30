<?php
/**
 * AddTagToPost.
 * php version 5.6
 *
 * @category AddTagToPost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Wordpress\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use WP_User;
use Exception;

/**
 * AddTagToPost
 *
 * @category AddTagToPost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddTagToPost extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'WordPress';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'add_tag_to_post';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Get All Users By Role', 'suretriggers' ),
			'action'   => 'add_tag_to_post',
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
	 * @return array|string
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$tag_id  = $selected_options['tag'];
		$post_id = $selected_options['post_id'];

		$tag = wp_set_object_terms( $post_id, (int) $tag_id, 'post_tag', true );

		if ( ! $tag ) {
			throw new Exception( 'Failed to add tag.' );
		}

		$last_response = get_post( $post_id );

		$post_type = get_post_type( $post_id );

		if ( ! $post_type ) {
			throw new Exception( 'Invalid post ID or post type not found.' );
		}

		$response_taxonomy = get_object_taxonomies( $post_type );
		$taxonomy_terms    = [];
		foreach ( $response_taxonomy as $taxonomy_name ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy_name );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$taxonomy_terms[] = $term;
				}
			}           
		}

		if ( ! $last_response ) {
			return 'Tag added successfully.';
		} else {
			return [
				$last_response,
				'taxonomy_terms' => $taxonomy_terms,
			];
		}

	}
}

AddTagToPost::get_instance();
