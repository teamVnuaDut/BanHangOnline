<?php
/**
 * CreateProduct.
 * php version 5.6
 *
 * @category CreateProduct
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Woocommerce\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Integrations\WooCommerce\WooCommerce;
use SureTriggers\Traits\SingletonLoader;

/**
 * CreateProduct
 *
 * @category CreateProduct
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateProduct extends AutomateAction {

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
	public $action = 'wc_create_product';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Product', 'suretriggers' ),
			'action'   => 'wc_create_product',
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

		$product_name              = $selected_options['product_name'];
		$product_slug              = $selected_options['product_slug'];
		$product_description       = $selected_options['product_description'];
		$product_short_description = $selected_options['product_short_description'];
		$product_type              = $selected_options['product_type'];
		$product_status            = isset( $selected_options['product_status'] ) ? $selected_options['product_status'] : '';
		$product_featured          = isset( $selected_options['product_featured'] ) ? $selected_options['product_featured'] : '';
		$catalog_visibility        = $selected_options['catalog_visibility'];
		$product_sku               = $selected_options['product_sku'];
		$product_regular_price     = $selected_options['product_regular_price'];
		$sale_price                = $selected_options['sale_price'];
		$sale_price_date_from      = isset( $selected_options['sale_price_date_from'] ) ? $selected_options['sale_price_date_from'] : '';
		$sale_price_date_to        = isset( $selected_options['sale_price_date_to'] ) ? $selected_options['sale_price_date_to'] : '';
		$product_sold_individually = isset( $selected_options['product_sold_individually'] ) ? 'yes' : 'no';
		$product_manage_stock      = isset( $selected_options['product_manage_stock'] ) ? 'yes' : 'no';
		$product_stock_quantity    = isset( $selected_options['product_stock_quantity'] ) ? $selected_options['product_stock_quantity'] : '';
		$product_categories        = $selected_options['product_categories'];
		$product_tags              = $selected_options['product_tags'];
		$product_weight            = $selected_options['product_weight'];
		$product_length            = $selected_options['product_length'];
		$product_width             = $selected_options['product_width'];
		$product_height            = $selected_options['product_height'];
		$product_shipping_class    = $selected_options['product_shipping_class'];
		$product_reviews           = isset( $selected_options['product_reviews'] ) ? 'open' : 'closed';
		$stock_status              = $selected_options['stock_status'];
		$product_image             = $selected_options['product_image'];
		$product_gallery           = isset( $selected_options['product_gallery'] ) ? $selected_options['product_gallery'] : '';

		$post_id = wp_insert_post(
			[
				'post_title'     => $product_name,
				'post_content'   => $product_description,
				'post_excerpt'   => $product_short_description,
				'post_status'    => $product_status,
				'post_name'      => $product_slug,
				'comment_status' => $product_reviews,
				'post_type'      => 'product',
			]
		);

		if ( $post_id ) {
			// Set product type.
			wp_set_object_terms( $post_id, $product_type, 'product_type' );

			// Update product meta.
			update_post_meta( $post_id, '_visibility', $catalog_visibility );
			update_post_meta( $post_id, '_sold_individually', $product_sold_individually );
			update_post_meta( $post_id, '_stock_status', $stock_status );
			update_post_meta( $post_id, '_regular_price', $product_regular_price );
			update_post_meta( $post_id, '_price', $product_regular_price );
			update_post_meta( $post_id, '_sale_price', $sale_price );
			update_post_meta( $post_id, '_sku', $product_sku );
			update_post_meta( $post_id, '_manage_stock', $product_manage_stock );
			update_post_meta( $post_id, '_stock', $product_stock_quantity );

			if ( '' !== $product_weight ) {
				update_post_meta( $post_id, '_weight', $product_weight );
			}

			if ( '' !== $product_length ) {
				update_post_meta( $post_id, '_length', $product_length );
			}

			if ( '' !== $product_width ) {
				update_post_meta( $post_id, '_width', $product_width );
			}

			if ( '' !== $product_height ) {
				update_post_meta( $post_id, '_height', $product_height );
			}

			// Set product categories.
			if ( '' !== $product_categories ) {
				$cat_ids   = explode( ',', $product_categories );
				$prod_cats = [];
				foreach ( $cat_ids as $cat_id ) {
					$prod_cats[] = (int) $cat_id;
				}

				wp_set_post_terms( $post_id, $prod_cats, 'product_cat' );
			}

			// Set product tags.
			if ( '' !== $product_tags ) {
				$tag_ids   = explode( ',', $product_tags );
				$prod_tags = [];
				foreach ( $tag_ids as $tag_id ) {
					$prod_tags[] = (int) $tag_id;
				}

				wp_set_post_terms( $post_id, $prod_tags, 'product_tag' );
			}
			wp_set_post_terms( $post_id, $product_shipping_class, 'product_shipping_class' );

			// Set product featured image.
			if ( isset( $product_image ) && '' !== $product_image ) {

				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';

				// Prevents double image downloading.
				$existing_media_id = absint( attachment_url_to_postid( $product_image ) ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid

				if ( 0 !== $existing_media_id ) {
					$product_image = $existing_media_id;
				}

				$attachment_id = media_sideload_image( $product_image, $post_id, null, 'id' );
				if ( ! is_wp_error( $attachment_id ) ) {
					set_post_thumbnail( $post_id, (int) $attachment_id );
				}
			}
			// Set product gallery images.
			if ( ! empty( $product_gallery ) ) {
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$gallery_image_ids = [];
				foreach ( $product_gallery as $gallery_image_url ) {
					// Prevents double image downloading.
					$gallery_existing_media_id = absint( attachment_url_to_postid( $gallery_image_url['gallery_images'] ) ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.attachment_url_to_postid_attachment_url_to_postid

					if ( 0 !== $gallery_existing_media_id ) {
						$gallery_image_url['gallery_images'] = $gallery_existing_media_id;
					}

					$gallery_attachment_id = media_sideload_image( $gallery_image_url['gallery_images'], $post_id, null, 'id' );
					if ( ! is_wp_error( $gallery_attachment_id ) ) {
						set_post_thumbnail( $post_id, (int) $gallery_attachment_id );
						$gallery_image_ids[ $gallery_attachment_id ] = $gallery_attachment_id;
					}
				}
				update_post_meta( $post_id, '_product_image_gallery', implode( ',', $gallery_image_ids ) );
			}

			$wc_product = wc_get_product( $post_id );
			if ( $wc_product instanceof \WC_Product ) {
				$wc_product->set_featured( $product_featured );
				$wc_product->set_date_on_sale_from( $sale_price_date_from );
				$wc_product->set_date_on_sale_to( $sale_price_date_to );
				$wc_product->save();
			}
			return [
				'product_id' => $post_id,
			];
		} else {
			return [
				'status'  => 'error',
				'message' => 'Error during product creation.',
			];
		}
	}
}

CreateProduct::get_instance();
