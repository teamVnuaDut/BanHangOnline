<?php
/**
 * UpdateCloneableFieldValue.
 * php version 5.6
 *
 * @category UpdateCloneableFieldValue
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;

/**
 * UpdateCloneableFieldValue
 *
 * @category UpdateCloneableFieldValue
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdateCloneableFieldValue extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'MetaBox';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'update_cloneable_field_value';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Update Cloneable Field Value', 'suretriggers' ),
			'action'   => $this->action,
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
	 * @psalm-suppress UndefinedMethod
	 * @throws Exception Exception.
	 * 
	 * @return array|bool|void
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$post_user_id   = $selected_options['post_user_id'];
		$field_name     = $selected_options['mb_field_name'];
		$response_array = [];
		$rows           = [];
		$current_row    = [];

		if ( ! function_exists( 'rwmb_set_meta' ) || ! function_exists( 'rwmb_get_value' ) ) {
			return;
		}

		if ( ! empty( $selected_options['mb_meta_update_value'] ) ) {
			foreach ( $selected_options['mb_meta_update_value'] as $meta ) {
				$meta_key     = $meta['meta_key'];
				$meta_value   = $meta['meta_value'];
				$is_meta_json = json_decode( $meta_value, true );
				if ( null !== $is_meta_json ) {
					$meta_value = $is_meta_json;
				}
				if ( ! empty( $current_row ) && array_key_exists( $meta_key, $current_row ) ) {
					$rows[]      = $current_row;
					$current_row = [];
				}
				$current_row[ $meta_key ] = $meta_value;
			}
			if ( ! empty( $current_row ) ) {
				$rows[] = $current_row;
			}

			rwmb_set_meta( $post_user_id, $field_name, $rows );
			update_post_meta( $post_user_id, $field_name, $rows );
			$response_array[ $field_name ] = rwmb_get_value( $field_name, [], $post_user_id );
			$response_array['id']          = $post_user_id;
			$response_array['field_name']  = $field_name;
			return $response_array;
		} else {
			throw new Exception( 'Field values are empty.' );
		}
	}
}

UpdateCloneableFieldValue::get_instance();
