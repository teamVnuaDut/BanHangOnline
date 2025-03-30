<?php
/**
 * OrdersPromotionCanceled.
 * php version 5.6
 *
 * @category OrdersPromotionCanceled
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;
use SureTriggers\Integrations\WordPress\WordPress;

if ( ! class_exists( 'OrdersPromotionCanceled' ) ) :

	/**
	 * OrdersPromotionCanceled
	 *
	 * @category OrdersPromotionCanceled
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class OrdersPromotionCanceled {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'Voxel';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'voxel_orders_promotion_canceled';

		use SingletonLoader;


		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			add_filter( 'sure_trigger_register_trigger', [ $this, 'register' ] );
		}

		/**
		 * Register action.
		 *
		 * @param array $triggers trigger data.
		 * @return array
		 */
		public function register( $triggers ) {

			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'Order Promotion Canceled', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'voxel/app-events/promotions/promotion:canceled',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param object $event Event.
		 * @return void
		 */
		public function trigger_listener( $event ) {
			if ( ! property_exists( $event, 'order' ) || ! property_exists( $event, 'customer' ) ) {
				return;
			}
			$order                      = $event->order;
			$context['id']              = $order->get_id();
			$context['payment_method']  = $order->get_payment_method_key();
			$context['tax_amount']      = $order->get_tax_amount();
			$context['discount_amount'] = $order->get_discount_amount();
			$context['shipping_amount'] = $order->get_shipping_amount();
			$context['status']          = $order->get_status();
			$context['created_at']      = $order->get_created_at();
			$context['subtotal']        = $order->get_subtotal();
			$context['total']           = $order->get_total();

			// Get order items.
			$order_items                 = $order->get_items();
			$context['order_item_count'] = $order->get_item_count();
			foreach ( $order_items as $item ) {
				$context['order_items'][] = [
					'id'                    => $item->get_id(),
					'type'                  => $item->get_type(),
					'currency'              => $item->get_currency(),
					'quantity'              => $item->get_quantity(),
					'subtotal'              => $item->get_subtotal(),
					'product_id'            => $item->get_post()->get_id(),
					'product_label'         => $item->get_product_label(),
					'product_thumbnail_url' => $item->get_product_thumbnail_url(),
					'product_link'          => $item->get_product_link(),
					'description'           => $item->get_product_description(),
				];
			}
			$context['details'] = $order->get_details();
			// Get Customer.
			$context['customer'] = WordPress::get_user_context( $event->customer->get_id() );
	
			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);
		}
	}

	/**
	 * Ignore false positive
	 *
	 * @psalm-suppress UndefinedMethod
	 */
	OrdersPromotionCanceled::get_instance();

endif;
