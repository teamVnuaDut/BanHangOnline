<?php
/**
 * FindPosts.
 * php version 5.6
 *
 * @category FindPosts
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\WordPress\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use WP_Query;
use Exception;

/**
 * FindPosts
 *
 * @category FindPosts
 * @package  SureTriggers
 * @since    1.0.0
 */
class FindPosts extends AutomateAction {

	use SingletonLoader;

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
	public $action = 'find_post_by_criteria';

	/**
	 * Register action.
	 *
	 * @param array $actions Action data.
	 *
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Find Posts', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];

		return $actions;
	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id          User ID.
	 * @param int   $automation_id    Automation ID.
	 * @param array $fields           Fields data.
	 * @param array $selected_options Selected options.
	 *
	 * @return array
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$post_type   = isset( $selected_options['post_type'] ) ? sanitize_text_field( $selected_options['post_type'] ) : 'post';
		$search_term = isset( $selected_options['search_term'] ) ? sanitize_text_field( $selected_options['search_term'] ) : '';
		$status      = isset( $selected_options['status'] ) ? sanitize_text_field( $selected_options['status'] ) : '';
		$date_range  = isset( $selected_options['date_range'] ) ? explode( ' - ', sanitize_text_field( $selected_options['date_range'] ) ) : [];

		$args = [
			'post_type'      => $post_type,
			'post_status'    => $status,
			'posts_per_page' => 10,
		];

		if ( ! empty( $search_term ) ) {
			$args['s'] = $search_term;
		}

		if ( ! empty( $status ) ) {
			$args['post_status'] = $status;
		} else {
			$args['post_status'] = [ 'publish', 'draft', 'pending' ];
		}

		if ( count( $date_range ) === 2 ) {
			$args['date_query'] = [
				[
					'after'     => $date_range[0],
					'before'    => $date_range[1] . ' 23:59:59',
					'inclusive' => true,
				],
			];
		}

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return [
				'status'  => 'error',
				'message' => 'No posts found.',
			];
		}

		$posts_data = [];

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			$post_details = [
				'ID'               => $post_id,
				'Title'            => html_entity_decode( get_the_title(), ENT_QUOTES, 'UTF-8' ),
				'Content'          => html_entity_decode( get_the_content(), ENT_QUOTES, 'UTF-8' ),
				'Excerpt'          => html_entity_decode( get_the_excerpt(), ENT_QUOTES, 'UTF-8' ),
				'Author'           => get_the_author(),
				'Publication Date' => get_the_date(),
				'Status'           => get_post_status( (int) $post_id ), 
				'Permalink'        => get_permalink( (int) $post_id ),
			];

			if ( function_exists( 'get_fields' ) ) {
				$acf_fields = get_fields( $post_id );
				if ( $acf_fields ) {
					$post_details['ACF Fields'] = $acf_fields;
				}
			}

			$posts_data[] = $post_details;
		}

		wp_reset_postdata();

		return [
			'status' => 'success',
			'posts'  => $posts_data,
		];
	}
}

FindPosts::get_instance();
