<?php
/**
 * UpdateProfile.
 * php version 5.6
 *
 * @category UpdateProfile
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
 * UpdateProfile
 *
 * @category UpdateProfile
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdateProfile extends AutomateAction {

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
	public $action = 'voxel_update_existing_profile';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Update Profile', 'suretriggers' ),
			'action'   => 'voxel_update_existing_profile',
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
		if ( ! class_exists( 'Voxel\User' ) ) {
			return false;
		}
		$profile_id = $selected_options['profile_id'];

		$profile = \Voxel\User::get_by_profile_id( $profile_id );

		if ( ! $profile ) {
			throw new Exception( 'Profile not found' );
		}

		// Create the profile, if not exist.
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

		// Update Collection fields.
		Voxel::voxel_update_post( $post_fields, $profile_id, 'profile' );

		return [
			'success'        => true,
			'message'        => esc_attr__( 'Profile created successfully', 'suretriggers' ),
			'profile_id'     => $profile_id,
			'profile_url'    => get_author_posts_url( $user_id ),
			'profile_author' => $user_id,
		];
	}

}

UpdateProfile::get_instance();
