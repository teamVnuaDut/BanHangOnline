<?php
/**
 * EDDCreateDiscount.
 * php version 5.6
 *
 * @category EDDCreateDiscount
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\EDD\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Integrations\WordPress\WordPress;
use Exception;

/**
 * EDDCreateDiscount
 *
 * @category EDDCreateDiscount
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class EDDCreateDiscount extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'EDD';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'edd_create_discount';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Discount', 'suretriggers' ),
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
	 * @param array $selected_options selected_options.
	 * @return array|bool
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {

		// Add conditional check as this action only works with EDD Discounts Pro extension.
		if ( ! class_exists( 'edd_dp' ) ) {
			throw new Exception( 'EDD Discounts Pro plugin is not active.' );
		}

		$title = ! empty( $selected_options['discount_title'] ) ? $selected_options['discount_title'] : false;

		$type = ! empty( $selected_options['discount_type'] ) ? sanitize_key( wp_strip_all_tags( stripslashes( trim( $selected_options['discount_type'] ) ) ) ) : false;
		
		$quantity = ! empty( $selected_options['discount_quantity'] ) ? absint( trim( $selected_options['discount_quantity'] ) ) : false;
		
		$value = ! empty( $selected_options['discount_value'] ) ? wp_strip_all_tags( stripslashes( trim( $selected_options['discount_value'] ) ) ) : false;
		
		if ( ! empty( $selected_options['products'] ) ) {
			$products_ids = array_column( $selected_options['products'], 'value' );
			$products     = $products_ids;
		} else {
			$products = [];
		}
		
		if ( ! empty( $selected_options['categories'] ) ) {
			$categories_ids = array_column( $selected_options['categories'], 'value' );
			$categories     = $categories_ids;
		} else {
			$categories = [];
		}
		
		if ( ! empty( $selected_options['tags'] ) ) {
			$tags_ids = array_column( $selected_options['tags'], 'value' );
			$tags     = $tags_ids;
		} else {
			$tags = [];
		}

		$user_emails = explode( ',', $selected_options['user_email'] );
		if ( ! empty( $user_emails ) ) {
			$users = [];
			foreach ( $user_emails as $email ) {
				$user = get_user_by( 'email', $email );
				if ( $user ) {
					$users[] = $user->ID;
				}
			}
		} else {
			$users = [];
		}

		$start           = ! empty( $selected_options['start_date'] ) ? sanitize_text_field( trim( $selected_options['start_date'] ) ) : '';
		$start_hour_time = explode( ':', $selected_options['start_hour'] );
		$start_hour      = ! empty( $start_hour_time[0] ) ? absint( trim( $start_hour_time[0] ) ) : '00';
		$start_minute    = ! empty( $start_hour_time[1] ) ? absint( trim( $start_hour_time[1] ) ) : '00';
		$full_start_date = '';
		if ( ! empty( $start ) ) {
			$full_start_date = gmdate( 'Y-m-d H:i:s', (int) strtotime( sprintf( '%s %d:%d', $start, $start_hour, $start_minute ) ) );
		}

		$end           = ! empty( $selected_options['expiration_date'] ) ? sanitize_text_field( trim( $selected_options['expiration_date'] ) ) : '';
		$end_hour_time = explode( ':', $selected_options['expiration_hour'] );
		$end_hour      = ! empty( $end_hour_time[0] ) ? absint( trim( $end_hour_time[0] ) ) : '23';
		$end_minute    = ! empty( $end_hour_time[1] ) ? absint( trim( $end_hour_time[1] ) ) : '59';
		$full_end_date = '';
		if ( ! empty( $end ) ) {
			$full_end_date = gmdate( 'Y-m-d H:i:s', (int) strtotime( sprintf( '%s %d:%d', $end, $end_hour, $end_minute ) ) );
		}

		$cust = ! empty( $selected_options['cust'] ) ? true : false;

		if ( ! empty( $selected_options['groups'] ) ) {
			$group_ids = array_column( $selected_options['groups'], 'value' );
			$groups    = $group_ids;
		} else {
			$groups = [];
		}

		if ( isset( $selected_options['include_or_exclude'] ) ) {
			$include_or_exclude = sanitize_text_field( $selected_options['include_or_exclude'] );
		} else {
			$include_or_exclude = 'include';
		}

		$meta = [
			'type'               => $type,
			'quantity'           => $quantity,
			'value'              => $value,
			'products'           => $products,
			'include_or_exclude' => $include_or_exclude,
			'categories'         => $categories,
			'tags'               => $tags,
			'users'              => $users,
			'groups'             => $groups,
			'start'              => $full_start_date,
			'end'                => $full_end_date,
			'cust'               => $cust,
		];

		$result_arr = [
			'post_title'  => $title,
			'post_type'   => 'customer_discount',
			'post_status' => 'publish',
		];

		$post_id = wp_insert_post( $result_arr );

		foreach ( array_filter( $meta ) as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
		update_post_meta( $post_id, 'frontend', $meta );

		$donation           = WordPress::get_post_context( $post_id );
		$discount_meta_data = get_post_meta( $post_id );

		return array_merge( $donation, (array) $discount_meta_data );
	}
}

EDDCreateDiscount::get_instance();
