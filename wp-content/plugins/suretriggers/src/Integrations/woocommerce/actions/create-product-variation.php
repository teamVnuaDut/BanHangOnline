<?php
/**
 * CreateProductVariation.
 * php version 5.6
 *
 * @category CreateProductVariation
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Woocommerce\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use WC_Product_Variation;
use SureTriggers\Traits\SingletonLoader;

/**
 * CreateProductVariation
 *
 * @category CreateProductVariation
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateProductVariation extends AutomateAction {

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
	public $action = 'wc_create_product_variation';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Product Variation', 'suretriggers' ),
			'action'   => 'wc_create_product_variation',
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
		$product_id               = $selected_options['product_id'];
		$variation_description    = $selected_options['product_description'];
		$variation_sku            = $selected_options['product_sku'];
		$variation_regular_price  = $selected_options['product_regular_price'];
		$sale_price               = $selected_options['sale_price'];
		$sale_price_date_from     = isset( $selected_options['sale_price_date_from'] ) ? $selected_options['sale_price_date_from'] : '';
		$sale_price_date_to       = isset( $selected_options['sale_price_date_to'] ) ? $selected_options['sale_price_date_to'] : '';
		$variation_weight         = $selected_options['product_weight'];
		$variation_length         = $selected_options['product_length'];
		$variation_width          = $selected_options['product_width'];
		$variation_height         = $selected_options['product_height'];
		$variation_shipping_class = $selected_options['product_shipping_class'];
		$stock_status             = $selected_options['stock_status'];
		$variation_image          = $selected_options['variation_image'];
		$attributes               = isset( $selected_options['variation_attributes'] ) ? $selected_options['variation_attributes'] : '';
		
		$attributes_data = [];
		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attribute => $value ) {
				$attributes_data[ $value['attribute'] ] = $value['attribute_value'];
			}
		}
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $product_id );
		$variation->set_attributes( $attributes_data );
		$variation->set_sku( $variation_sku );
		$variation->set_stock_status( $stock_status );
		$variation->set_regular_price( $variation_regular_price );
		$variation->set_sale_price( $sale_price );
		$variation->set_date_on_sale_from( $sale_price_date_from );
		$variation->set_date_on_sale_to( $sale_price_date_to );

		$variation->set_description( $variation_description );
		$variation->set_weight( $variation_weight );
		$variation->set_length( $variation_length );
		$variation->set_width( $variation_width );
		$variation->set_height( $variation_height );

		$variation->set_shipping_class_id( $variation_shipping_class );
		// Set variation image.
		if ( isset( $variation_image ) && '' !== $variation_image ) {

			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// Prevents double image downloading.
			$existing_media_id = absint( attachment_url_to_postid( $variation_image ) ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid

			if ( 0 !== $existing_media_id ) {
				$variation_image = $existing_media_id;
			}

			$attachment_id = media_sideload_image( $variation_image, $variation->get_id(), null, 'id' );
			if ( ! is_wp_error( $attachment_id ) ) {
				$variation->set_image_id( $attachment_id );
			}
		}
		$variation->save();
		return [
			'variation_id' => $variation->get_id(),
		];
	}
}

CreateProductVariation::get_instance();
