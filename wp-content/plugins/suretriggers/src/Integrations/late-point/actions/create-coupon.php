<?php
/**
 * CreateCoupon.
 * php version 5.6
 *
 * @category CreateCoupon
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\LatePoint\Actions;

use OsCouponModel;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use Exception;

/**
 * CreateCoupon
 *
 * @category CreateCoupon
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateCoupon extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'LatePoint';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'lp_create_coupon';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Coupon', 'suretriggers' ),
			'action'   => 'lp_create_coupon',
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
	 * @return array
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {

		if ( ! class_exists( 'OsCouponModel' ) ) {
			throw new Exception( 'LatePoint Pro Features plugin is not installed.' );
		}

		$coupon_params = [
			'code'           => isset( $selected_options['code'] ) ? $selected_options['code'] : '',
			'name'           => isset( $selected_options['name'] ) ? $selected_options['name'] : '',
			'discount_value' => isset( $selected_options['discount_value'] ) ? $selected_options['discount_value'] : '',
			'discount_type'  => isset( $selected_options['discount_type'] ) ? $selected_options['discount_type'] : '',
			'rules'          => wp_json_encode(
				[
					'limit_per_customer' => isset( $selected_options['limit_per_customer'] ) ? $selected_options['limit_per_customer'] : '',
					'limit_total'        => isset( $selected_options['limit_total'] ) ? $selected_options['limit_total'] : '',
					'orders_more'        => isset( $selected_options['orders_more'] ) ? $selected_options['orders_more'] : '',
					'orders_less'        => isset( $selected_options['orders_less'] ) ? $selected_options['orders_less'] : '',
					'agent_ids'          => isset( $selected_options['agent_ids'] ) ? $selected_options['agent_ids'] : '',
					'customer_ids'       => isset( $selected_options['customer_ids'] ) ? $selected_options['customer_ids'] : '',
					'service_ids'        => isset( $selected_options['service_ids'] ) ? $selected_options['service_ids'] : '',
				]
			),
			'status'         => isset( $selected_options['status'] ) ? $selected_options['status'] : '',
		];

		$coupon = new OsCouponModel();
		$coupon->set_data( $coupon_params );

		if ( $coupon->save() ) {
			return [
				'status'    => 'success',
				'message'   => __( 'Coupon created successfully', 'suretriggers' ),
				'coupon_id' => $coupon->id,
			];
		} else {
			$errors    = $coupon->get_error_messages();
			$error_msg = isset( $errors[0] ) ? $errors[0] : __( 'Coupon could not be created.', 'suretriggers' );
		
			return [
				'status'  => 'failure',
				'message' => $error_msg,
			];
		}
	}
}

CreateCoupon::get_instance();
