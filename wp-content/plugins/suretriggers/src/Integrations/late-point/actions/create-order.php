<?php
/**
 * CreateBundleOrder.
 * php version 5.6
 *
 * @category CreateBundleOrder
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\LatePoint\Actions;

use Exception;
use OsAgentModel;
use OsBookingModel;
use OsCustomerModel;
use OsOrderModel;
use OsOrdersHelper;
use OsOrderItemModel;
use OsBundleModel;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;

/**
 * CreateBundleOrder
 *
 * @category CreateBundleOrder
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateBundleOrder extends AutomateAction {
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
	public $action = 'lp_create_order';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Bundle Order', 'suretriggers' ),
			'action'   => 'lp_create_order',
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
		if ( ! class_exists( 'OsBundleModel' ) || ! class_exists( 'OsCustomerModel' ) || 
				! class_exists( 'OsOrderModel' ) || ! class_exists( 'OsOrderItemModel' ) ) {
			throw new Exception( 'LatePoint plugin not installed.' );
		}
		
			$required_params = [
				'bundle_id',
				'customer_type',
			];
		
			foreach ( $required_params as $param ) {
				if ( ! isset( $selected_options[ $param ] ) ) {
					throw new Exception( "Missing required parameter: {$param}" );
				}
			}
		
			$bundle = new OsBundleModel( $selected_options['bundle_id'] );
			
			if ( ! $bundle->id ) {
				throw new Exception( 'Invalid bundle ID provided.' );
			}
		
			$customer_type = isset( $selected_options['customer_type'] ) ? 
							$selected_options['customer_type'] : 'new';
		
			$customer_id = null;
			if ( 'existing' === $customer_type ) {
				$customer_id = isset( $selected_options['customer_id'] ) ? $selected_options['customer_id'] : null;
				
				if ( ! $customer_id ) {
					throw new Exception( 'Customer ID not provided.' );
				}
			}
		
		
			if ( 'new' === $customer_type ) {
				$customer_params = [
					'first_name'  => isset( $selected_options['customer_first_name'] ) ? $selected_options['customer_first_name'] : '',
					'last_name'   => isset( $selected_options['customer_last_name'] ) ? $selected_options['customer_last_name'] : '',
					'email'       => isset( $selected_options['customer_email'] ) ? $selected_options['customer_email'] : '',
					'phone'       => isset( $selected_options['customer_phone'] ) ? $selected_options['customer_phone'] : '',
					'notes'       => isset( $selected_options['customer_notes'] ) ? $selected_options['customer_notes'] : '',
					'admin_notes' => isset( $selected_options['admin_notes'] ) ? $selected_options['admin_notes'] : '',
				];
		
				$customer_custom_fields = [];
				if ( ! empty( $selected_options['customer_fields'] ) ) {
					foreach ( $selected_options['customer_fields'] as $field ) {
						if ( is_array( $field ) && ! empty( $field ) ) {
							foreach ( $field as $key => $value ) {
								if ( false === strpos( $key, 'field_column' ) && '' !== $value ) {
									$customer_custom_fields[ $key ] = $value;
								}
							}
						}
					}
				}
		
				$customer_params['custom_fields'] = $customer_custom_fields;
		
				$customer          = new OsCustomerModel();
				$existing_customer = $customer->where( [ 'email' => $customer_params['email'] ] )
											->set_limit( 1 )
											->get_results_as_models();
		
				if ( isset( $existing_customer->id ) && ! empty( $existing_customer->id ) ) {
					$customer = new OsCustomerModel( $existing_customer->id );
				} else {
					$customer = new OsCustomerModel();
				}
		
				$customer->set_data( $customer_params );
				
				if ( ! $customer->save() ) {
					$errors    = $customer->get_error_messages();
					$error_msg = isset( $errors[0] ) ? $errors[0] : 'Customer could not be created.';
					throw new Exception( $error_msg );
				}
			} else {
				$customer = new OsCustomerModel( $customer_id );
				if ( ! $customer->id ) {
					throw new Exception( 'Customer not found.' );
				}
			}
		
			$order                     = new OsOrderModel();
			$order->status             = isset( $selected_options['status'] ) ? $selected_options['status'] : 'open';
			$order->fulfillment_status = isset( $selected_options['fulfillment_status'] ) ? $selected_options['fulfillment_status'] : $order->get_default_fulfillment_status();
			$order->customer_id        = $customer->id;
			$order->payment_status     = isset( $selected_options['payment_status'] ) ? $selected_options['payment_status'] : 'not_paid';
		
			if ( ! $order->save() ) {
				$errors    = $order->get_error_messages();
				$error_msg = isset( $errors[0] ) ? $errors[0] : 'Order could not be created.';
				throw new Exception( $error_msg );
			}
		
			$order_item_model           = new OsOrderItemModel();
			$order_item_model->variant  = defined( 'LATEPOINT_ITEM_VARIANT_BUNDLE' ) ? LATEPOINT_ITEM_VARIANT_BUNDLE : 'bundle';
			$order_item_model->order_id = $order->id;
		
			if ( ! $order_item_model->save() ) {
				$errors    = $order_item_model->get_error_messages();
				$error_msg = isset( $errors[0] ) ? $errors[0] : 'Order Item could not be created.';
				throw new Exception( $error_msg );
			}
		
			$bundle_data = [
				'bundle_id' => $bundle->id,
			];
		
			$order_item_model->item_data = wp_json_encode( $bundle_data );
			$order_item_model->recalculate_prices();
		
			$order->total    = $order_item_model->total;
			$order->subtotal = $order_item_model->subtotal;
			$order->save();
			$order_item_model->save();
		
			do_action( 'latepoint_order_created', $order );
		
			return [
				'bundle' => $bundle->get_data_vars(),
				'order'  => $order->get_data_vars(),
			];
	}
}

CreateBundleOrder::get_instance();
