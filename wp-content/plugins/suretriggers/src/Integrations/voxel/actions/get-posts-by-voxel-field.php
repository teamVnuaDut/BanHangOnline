<?php
/**
 * GetPostsByVoxelField.
 * php version 5.6
 *
 * @category GetPostsByVoxelField
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use Exception;
use SureTriggers\Integrations\Voxel\Voxel;

/**
 * GetPostsByVoxelField
 *
 * @category GetPostsByVoxelField
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetPostsByVoxelField extends AutomateAction {

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
	public $action = 'voxel_get_posts_by_voxel_field';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Get Posts by Voxel Field', 'suretriggers' ),
			'action'   => 'voxel_get_posts_by_voxel_field',
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
		$post_type = $selected_options['vx_post_type'];
		$field_key = $selected_options['vx_field_key'];
		$field_val = $selected_options['vx_field_value'];
		$number    = isset( $selected_options['vx_posts_no'] ) ? (int) $selected_options['vx_posts_no'] : -1;

		if ( ! class_exists( 'Voxel\Post_Type' ) ) {
			return false;
		}
		// Get fields for post.
		$post_type_obj = \Voxel\Post_Type::get( $post_type );
		$post_fields   = $post_type_obj->get_fields();
		$post_field    = [];
		foreach ( $post_fields as $key => $field ) {
			if ( $key === $field_key ) {
				$post_field = $field->get_props();
				break;
			}
		}
		if ( ! empty( $post_field ) && isset( $post_field['type'] ) && 'post-relation' === $post_field['type'] ) {
			global $wpdb;

			$field    = $post_type_obj->get_field( $field_key );
			$rows     = $wpdb->get_col(
				$wpdb->prepare(
					"SELECT parent_id
				FROM {$wpdb->prefix}voxel_relations
				LEFT JOIN {$wpdb->posts} AS p ON parent_id = p.ID
				WHERE child_id = %d
					AND relation_key = %s
				ORDER BY 'order ASC",
					$field_val,
					$field_key 
				) 
			);
			$post_ids = array_map( 'absint', (array) $rows );
			$posts    = get_posts(
				[
					'post_type'        => $post_type,
					'numberposts'      => $number,
					'post__in'         => $post_ids,
					'orderby'          => 'post__in',
					'no_found_rows'    => true,
					'suppress_filters' => false,
				]
			);
		} elseif ( isset( $post_field['type'] ) && 'taxonomy' === $post_field['type'] ) {
			$posts = get_posts(
				[
					'post_type'   => $post_type,
					'numberposts' => $number,
					'tax_query'   => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						[
							'taxonomy' => $post_field['taxonomy'],
							'field'    => 'slug',
							'terms'    => $field_val,
						],
					],
				]
			);
		} else {
			$posts = get_posts(
				[
					'post_type'   => $post_type,
					'numberposts' => $number,
					'meta_query'  => [
						[
							'key'     => $field_key,
							'value'   => $field_val,
							'compare' => 'LIKE',
						],
					],
				]
			);
		}

		if ( ! $posts ) {
			throw new Exception( 'No posts found' );
		}
		$posts_array = [
			'all_posts' => [],
		];
		foreach ( $posts as $key => $post ) {
			$post_array                       = [
				'post_id'        => $post->ID,
				'post_title'     => $post->post_title,
				'post_status'    => $post->post_status,
				'post_content'   => stripslashes( esc_attr( $post->post_content ) ),
				'post_author_id' => $post->post_author,
				'post_permalink' => get_permalink( $post->ID ),
			];
			$posts_array['all_posts'][ $key ] = $post_array;
		}

		return $posts_array;
	}

}

GetPostsByVoxelField::get_instance();
