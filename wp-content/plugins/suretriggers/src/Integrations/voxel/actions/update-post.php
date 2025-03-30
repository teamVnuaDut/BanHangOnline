<?php
/**
 * UpdatePost.
 * php version 5.6
 *
 * @category UpdatePost
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
 * UpdatePost
 *
 * @category UpdatePost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdatePost extends AutomateAction {

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
	public $action = 'voxel_update_existing_post';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Update Post', 'suretriggers' ),
			'action'   => 'voxel_update_existing_post',
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
		if ( ! class_exists( 'Voxel\Post' ) ) {
			return false;
		}

		// Get the post type.
		$post_type = $selected_options['voxel_post_type'];

		$post_id = $selected_options['post_id'];

		$selected_post_posttype = get_post_type( $post_id );

		if ( $post_type != $selected_post_posttype ) {
			throw new Exception( 'Post ID type does not match the selected post type.' );
		}

		$post_fields = [];
		foreach ( $selected_options['field_row_repeater'] as $key => $field ) {
			$field_name = $field['value']['name'];
			if ( 'repeater' == $field['value']['type'] ) {
				if ( 'work-hours' == $field['value']['name'] ) {
					$arr_value = $selected_options['field_row'][ $key ][ $field_name ];
					foreach ( $arr_value as $key => $val ) {
						$post_fields[ $field_name ][ $key ]['days']   = $val['work_days'];
						$post_fields[ $field_name ][ $key ]['status'] = $val['work_status'];
						if ( '' != $val['work_hours'] ) {
							$hours = explode( '-', $val['work_hours'] );
							$post_fields[ $field_name ][ $key ]['hours'][] = [
								'from' => $hours[0],
								'to'   => $hours[1],
							];
						}
					}
				} else {
					$arr_value = $selected_options['field_row'][ $key ][ $field_name ];
					foreach ( $arr_value as $key => $val ) {
						$post_fields[ $field_name ][ $key ] = $val;
					}
				}
			} else {
				$value                      = trim( $selected_options['field_row'][ $key ][ $field_name ] );
				$post_fields[ $field_name ] = $value;
			}
		}

		$post_fields['post_status'] = isset( $selected_options['post_status'] ) && '' !== $selected_options['post_status'] ? $selected_options['post_status'] : '';

		// Update Post fields.
		Voxel::voxel_update_post( $post_fields, $post_id, $post_type );

		return [
			'success'  => true,
			'message'  => esc_attr__( 'Post updated successfully', 'suretriggers' ),
			'post_id'  => $post_id,
			'post_url' => get_permalink( $post_id ),
		];
	}

}

UpdatePost::get_instance();
