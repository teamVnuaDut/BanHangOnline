<?php
/**
 * PreCheckoutOfferItemAdded.
 * php version 5.6
 *
 * @category PreCheckoutOfferItemAdded
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\CartFlows\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Integrations\WordPress\WordPress;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'PreCheckoutOfferItemAdded' ) ) :

	/**
	 * PreCheckoutOfferItemAdded
	 *
	 * @category PreCheckoutOfferItemAdded
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class PreCheckoutOfferItemAdded {

		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'CartFlows';

		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'wcf_pre_checkout_offer_item_added';

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
				'label'         => __( 'Pre Checkout Offer Item Added', 'suretriggers' ),
				'action'        => $this->trigger,
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 2,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param int    $checkout_id Checkout ID.
		 * @param string $cart_hash Cart Hash.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $checkout_id, $cart_hash ) {
			$pre_checkout_offer_product        = get_post_meta( $checkout_id, 'wcf-pre-checkout-offer-product', true );
			$pre_checkout_product              = get_post_meta( $checkout_id, 'wcf-checkout-products', true );
			$context['checkout_offer_product'] = $pre_checkout_offer_product;
			if ( is_array( $pre_checkout_product ) && isset( $pre_checkout_product['product'] ) ) {
				$checkout_products            = $pre_checkout_product['product'];
				$context['checkout_products'] = $checkout_products;
			}
			$context['funnel_id']      = get_post_meta( $checkout_id, '	wcf-flow-id', true );
			$context['funnel_step_id'] = $checkout_id;
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
	PreCheckoutOfferItemAdded::get_instance();

endif;
