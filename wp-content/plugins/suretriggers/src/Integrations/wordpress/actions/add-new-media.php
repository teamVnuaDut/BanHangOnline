<?php
/**
 * AddNewMedia.
 * php version 5.6
 *
 * @category AddNewMedia
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
 * AddNewMedia
 *
 * @category AddNewMedia
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddNewMedia extends AutomateAction {

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
	public $action = 'add_new_media';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add New Image To Media Library', 'suretriggers' ),
			'action'   => 'add_new_media',
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
	 * @return bool|array
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$description = $selected_options['wp_image_description'];
		$title       = $selected_options['wp_image_title'];
		$caption     = $selected_options['wp_image_caption'];
		$alt_text    = $selected_options['wp_image_alt'];
		$image       = $selected_options['wp_image_url'];

		$image_url = filter_var( $image, FILTER_SANITIZE_URL );

		if ( empty( $image_url ) ) {
			throw new Exception( 'Image URL is empty or invalid.' );
		}

		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		
		$image_id = media_sideload_image( $image_url, 0, null, 'id' );

		if ( is_wp_error( $image_id ) ) {
			throw new Exception( $image_id->get_error_message() );
		}

		$image_uploaded = wp_get_attachment_url( (int) $image_id );

		if ( $image_uploaded ) {
			$filetype = wp_check_filetype( basename( $image_uploaded ) );
		} else {
			throw new Exception( 'Failed to get the uploaded image URL.' );
		}

		$image_details = [
			'post_title'     => sanitize_text_field( $title ),
			'post_excerpt'   => sanitize_text_field( $caption ),
			'post_content'   => sanitize_text_field( $description ),
			'ID'             => $image_id,
			'file'           => $image_uploaded,
			'post_mime_type' => $filetype['type'],
		];

		$image_updated = wp_insert_attachment( $image_details );

		if ( ! $image_updated || ! is_numeric( $image_updated ) ) {
			throw new Exception( 'Failed to update the image attachment.' );
		}

		update_post_meta( (int) $image_id, '_wp_attachment_image_alt', $alt_text );

		return [
			'image_id'       => $image_id,
			'image_url'      => $image_uploaded,
			'image_details'  => $image_details,
			'image_alt_text' => $alt_text,
		];

	}

}

AddNewMedia::get_instance();
