<?php
/**
 * UpdatePostExcerpt.
 * php version 5.6
 *
 * @category UpdatePostExcerpt
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Wordpress\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use Exception;

/**
 * UpdatePostExcerpt
 *
 * @category UpdatePostExcerpt
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdatePostExcerpt extends AutomateAction {

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
	public $action = 'update_post_excerpt';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Post: Create a Post', 'suretriggers' ),
			'action'   => 'update_post_excerpt',
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
	 * @return bool|object
	 * @throws Exception Error.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$post_id      = $selected_options['post_id'];
		$post_excerpt = $selected_options['post_excerpt'];
		if ( is_null( get_post( $post_id ) ) ) {
			throw new Exception( 'Invalid post ID.' );
		}

		$post_data    = [
			'ID'           => $post_id,
			'post_excerpt' => $post_excerpt,
		];
		$post_updated = wp_update_post( $post_data, true );

		if ( is_wp_error( $post_updated ) ) {
			$message = $post_updated->get_error_message();
			throw new Exception( $message );
		}
		$last_response = get_post( $post_id );

		$taxonomy_terms    = [];
		$response_taxonomy = get_object_taxonomies( (string) get_post_type( $post_id ) );
		foreach ( $response_taxonomy as $taxonomy_name ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy_name );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$taxonomy_terms[] = $term;
				}
			}           
		}
		$featured_image_url = get_the_post_thumbnail_url( $post_id, 'full' );

		return (object) [
			$last_response,
			'taxonomy_term'      => $taxonomy_terms,
			'featured_image_url' => $featured_image_url,
		];

	}
}

UpdatePostExcerpt::get_instance();
