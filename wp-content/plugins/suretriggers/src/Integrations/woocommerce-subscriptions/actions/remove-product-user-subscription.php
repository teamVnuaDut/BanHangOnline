<?php
/**
 * RemoveProductUserSubscription.
 * php version 5.6
 *
 * @category RemoveProductUserSubscription
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\WoocommerceSubscriptions\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use WC_Subscription;

/**
 * RemoveProductUserSubscription
 *
 * @category RemoveProductUserSubscription
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveProductUserSubscription extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'WoocommerceSubscriptions';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'wc_remove_product_user_subscription';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Remove Product User Subscription', 'suretriggers' ),
			'action'   => 'wc_remove_product_user_subscription',
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
	 * @return object|array|void
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$user_id                 = $selected_options['user_id'];
		$subscription_product_id = $selected_options['product_id'];
		$subscription_id         = $selected_options['subscription_id'];
		
		if ( ! class_exists( 'WC_Subscription' ) || ! function_exists( 'wcs_get_users_subscriptions' ) || ! function_exists( 'wcs_get_subscriptions' ) || ! function_exists( 'wcs_get_subscription' ) ) {
			return;
		}
		$user = get_userdata( $user_id );
		if ( $user ) {
			$product = wc_get_product( absint( $subscription_product_id ) );

			if ( $product instanceof \WC_Product && ! $product->is_type( [ 'subscription_variation', 'variable-subscription', 'subscription' ] ) ) {
				throw new Exception( 'The provided product is not a valid subscription product.' );
			}

			if ( ! empty( $subscription_id ) ) {
				$subscription = wcs_get_subscription( absint( $subscription_id ) );
				if ( ! $subscription instanceof WC_Subscription ) {
					throw new Exception( 'The provided subscription ID is not a valid subscription ID.' );
				}

				if ( ! $subscription->has_product( $subscription_product_id ) ) {
					throw new Exception( 'The subscription does not contain the provided product.' );
				}

				$subscription_items = $subscription->get_items();

				if ( empty( $subscription_items ) ) {
					throw new Exception( 'The subscription does not contain the provided product.' );
				}

				$modified = false;
				foreach ( $subscription_items as $item_id => $item ) {
					$product = $item->get_product();
					if ( $product && (int) $product->get_id() === (int) $subscription_product_id ) {
						$subscription->update_status( 'on-hold' );
						// Remove the product from the subscription.
						$c = $subscription->remove_item( $item_id );
						if ( $c ) {
							$modified = true;
						}
						$subscription->calculate_totals();
						$subscription->save();
						$subscription->update_status( 'active' );
						return [
							'status'          => 'success',
							'message'         => 'Product removed successfully from the subscription.',
							'subscription_id' => $subscription_id,
							'subscription'    => $subscription,
						];
					}
				}
				/** 
				 * 
				 * Ignore line
				 * 
				 * @phpstan-ignore-next-line
				 */
				if ( ! $modified ) {
					throw new Exception( 'Unable to remove the product from the subscription.' );
				}
			}
		} else {
			throw new Exception( 'User does not exists.' );
		}
	}
}

RemoveProductUserSubscription::get_instance();
