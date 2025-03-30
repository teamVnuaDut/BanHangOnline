<?php
/**
 * AddProductAttributes.
 * php version 5.6
 *
 * @category AddProductAttributes
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Woocommerce\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;

/**
 * AddProductAttributes
 *
 * @category AddProductAttributes
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddProductAttributes extends AutomateAction {

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
	public $action = 'wc_add_product_attribute';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add Product Attributes', 'suretriggers' ),
			'action'   => 'wc_add_product_attribute',
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
		$product_id         = $selected_options['product_id'];
		$attributes         = isset( $selected_options['product_attributes'] ) ? $selected_options['product_attributes'] : '';
		$variation_enabled  = isset( $selected_options['variation_enabled'] ) && ! empty( $selected_options['variation_enabled'] ) ? 1 : 0;
		$product_attributes = [];

		$product = wc_get_product( $product_id );

		if ( ! $product instanceof \WC_Product ) {
			return [
				'status'  => 'error',
				'message' => 'The provided ID is not a valid Product ID.',
			];
		}

		$product_attribute_data = $product->get_attributes();

		foreach ( $attributes as $attribute => $value ) {
			if ( isset( $product_attribute_data[ $attribute ] ) ) {
				$attribute_item = $product_attribute_data[ $attribute ];
				$attribute_item->set_variation( true );
			}
			wp_set_object_terms( $product_id, $value['attribute_value'], $value['attribute'], true );
			$attribute_data = [
				'name'         => $value['attribute'],
				'value'        => $value['attribute_value'],
				'is_visible'   => 1,
				'is_taxonomy'  => 1,
				'is_variation' => $variation_enabled,
			];

			$product_attributes[ $value['attribute'] ] = $attribute_data;
		}
		$product->set_attributes( $product_attribute_data );

		$product_attributes = get_post_meta( $product_id, '_product_attributes', false );
		if ( is_array( $product_attributes ) ) {
			$product_attributes = ( ! empty( $product_attributes[0] ) ) ? array_merge( $product_attributes[0], $product_attributes ) : $product_attributes;

			update_post_meta( $product_id, '_product_attributes', $product_attributes );
	
			return [
				'status'  => 'success',
				'message' => esc_html__( 'Attributes added succcessfully.', 'suretriggers' ),
			];
		}
	}
}

AddProductAttributes::get_instance();
