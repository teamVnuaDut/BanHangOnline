<?php
/**
 * GetProductByID.
 * php version 5.6
 *
 * @category GetProductByID
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\WooCommerce\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Integrations\WooCommerce\WooCommerce;
use SureTriggers\Traits\SingletonLoader;

/**
 * GetProductByID
 *
 * @category GetProductByID
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetProductByID extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'WooCommerce';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'wc_get_product_by_id';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Get Product By ID', 'suretriggers' ),
			'action'   => 'wc_get_product_by_id',
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
		$product_id = $selected_options['product_id'];
		
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

		return $product_data;
	}
}

GetProductByID::get_instance();
