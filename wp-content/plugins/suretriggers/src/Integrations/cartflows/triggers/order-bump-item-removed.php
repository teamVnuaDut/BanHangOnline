<?php
/**
 * OrderBumpItemRemoved.
 * php version 5.6
 *
 * @category OrderBumpItemRemoved
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\CartFlows\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Integrations\WooCommerce\WooCommerce;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'OrderBumpItemRemoved' ) ) :

	/**
	 * OrderBumpItemRemoved
	 *
	 * @category OrderBumpItemRemoved
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class OrderBumpItemRemoved {

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
		public $trigger = 'wcf_order_bump_item_removed';

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
				'label'         => __( 'Order Bump Item Removed', 'suretriggers' ),
				'action'        => $this->trigger,
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param int $product_id order object.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $product_id ) {
			$product_data['product_id'] = $product_id;
			$product_data['product']    = WooCommerce::get_product_context( $product_id );
			$terms                      = get_the_terms( $product_id, 'product_cat' );
			if ( ! empty( $terms ) && is_array( $terms ) && isset( $terms[0] ) ) {
				$cat_name = [];
				foreach ( $terms as $cat ) {
					$cat_name[] = $cat->name;
				}
				$product_data['product']['category'] = implode( ', ', $cat_name );
			}
			$terms_tags = get_the_terms( $product_id, 'product_tag' );
			if ( ! empty( $terms_tags ) && is_array( $terms_tags ) && isset( $terms_tags[0] ) ) {
				$tag_name = [];
				foreach ( $terms_tags as $tag ) {
					$tag_name[] = $tag->name;
				}
				$product_data['product']['tag'] = implode( ', ', $tag_name );
			}
			unset( $product_data['product']['id'] ); //phpcs:ignore
			$context = $product_data;
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
	OrderBumpItemRemoved::get_instance();

endif;
