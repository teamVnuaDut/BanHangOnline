<?php
/**
 * UpsellOfferSkipped.
 * php version 5.6
 *
 * @category UpsellOfferSkipped
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

if ( ! class_exists( 'UpsellOfferSkipped' ) ) :

	/**
	 * UpsellOfferSkipped
	 *
	 * @category UpsellOfferSkipped
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class UpsellOfferSkipped {

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
		public $trigger = 'cartflows_upsell_offer_skipped';

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
				'label'         => __( 'Upsell Offer Skipped', 'suretriggers' ),
				'action'        => $this->trigger,
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 3,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param int    $step_id Step ID.
		 * @param object $order order object.
		 * @param object $offer_product offer_product.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $step_id, $order, $offer_product ) {
			$user_id = ap_get_current_user_id();
			// Ensure $order is an instance of WC_Order.
			if ( ! $order instanceof \WC_Order ) {
				return;
			}
			if ( is_int( $user_id ) ) {
				$context = WordPress::get_user_context( $user_id );
			}
			$context['order']          = $order->get_data();
			$context['upsell']         = $offer_product;
			$context['funnel_step_id'] = $step_id;
			$context['funnel_id']      = get_post_meta( $step_id, 'wcf-flow-id', true );
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
	UpsellOfferSkipped::get_instance();

endif;
