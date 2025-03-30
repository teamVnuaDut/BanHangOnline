<?php
/**
 * ExtendUserSubscription.
 * php version 5.6
 *
 * @category ExtendUserSubscription
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
use SureTriggers\Integrations\WordPress\WordPress;

/**
 * ExtendUserSubscription
 *
 * @category ExtendUserSubscription
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ExtendUserSubscription extends AutomateAction {

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
	public $action = 'wc_extend_subscription_date';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Extend User Subscription', 'suretriggers' ),
			'action'   => 'wc_extend_subscription_date',
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
		$user_id         = $selected_options['user_id'];
		$subscription_id = $selected_options['subscription_id'];
		
		if ( ! function_exists( 'wcs_get_users_subscriptions' ) || ! function_exists( 'wcs_get_subscriptions' ) || ! function_exists( 'wcs_get_subscription' ) || ! function_exists( 'wcs_add_time' ) || ! function_exists( 'wcs_get_edit_post_link' ) ) {
			return;
		}
		$user = get_userdata( $user_id );
		if ( $user ) {
			$subscriptions = wcs_get_subscriptions(
				[
					'subscriptions_per_page' => -1,
					'orderby'                => 'start_date',
					'order'                  => 'DESC',
					'customer_id'            => $user_id,
					'subscription_status'    => [ 'active' ],
					'meta_query_relation'    => 'AND',
				]
			);
			if ( empty( $subscriptions ) ) {
				throw new Exception( 'No active subscriptions were found.' );
			}
			if ( ! array_key_exists( $subscription_id, $subscriptions ) ) {
				throw new Exception( 'No active subscriptions was found with provided Subscription ID.' );
			}

			$count        = 0;
			$subscription = wcs_get_subscription( $subscription_id );

			$expiry = $subscription->get_date( 'end' );
			if ( empty( $expiry ) || intval( $expiry ) === 0 ) {
				throw new Exception( 'The subscription does not expire, no need to extend the date.' );
			}
			
			$new_extended_date = strtotime( '+' . $selected_options['extend_no'] . ' ' . $selected_options['extend_length'], $subscription->get_time( 'end' ) );
			
			if ( is_int( $new_extended_date ) ) {
				$dates_to_update['end'] = gmdate( 'Y-m-d H:i:s', $new_extended_date );
				// Prepare the date to update.
				$order_number = sprintf( _x( '#%s', 'hash before order number', 'suretriggers' ), $subscription->get_order_number() );
				$order_link   = sprintf( '<a href="%s">%s</a>', esc_url( wcs_get_edit_post_link( $subscription->get_id() ) ), $order_number );
			
				try {
					$subscription->update_dates( $dates_to_update );
					wp_cache_delete( $subscription_id, 'posts' );
					$subscription->add_order_note( sprintf( __( 'Subscription successfully extended by SureTriggers. Order %s', 'suretriggers' ), $order_link ) );
					$count++;
				} catch ( Exception $e ) {
					$subscription->add_order_note( sprintf( __( 'Failed to extend subscription after customer renewed early. Order %s', 'suretriggers' ), $order_link ) );
				}
				if ( 0 === $count ) {
					throw new Exception( 'The subscription has no end date.' );
				}

				$subscription_status            = $subscription->get_status();
				$subscription_start_date        = $subscription->get_date_created();
				$subscription_next_payment_date = $subscription->get_date( 'next_payment' );

				$context                 = WordPress::get_user_context( $user_id );
				$context['subscription'] = [
					'subscription_id'   => $subscription_id,
					'status'            => $subscription_status,
					'start_date'        => $subscription_start_date,
					'next_payment_date' => $subscription_next_payment_date,
					'end_date'          => $subscription->get_date( 'end' ),
				];
				return $context;
			}
		} else {
			throw new Exception( 'User does not exists.' );
		}
	}
}

ExtendUserSubscription::get_instance();
