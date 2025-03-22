<?php

namespace WPDesk\FS\TableRate\ShippingMethodsIntegration;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

class OrderMetaData implements Hookable {

	public const META_KEY   = 'fs_costs';

	private const  BASE       = 'base';
	private const  ADDITIONAL = 'additional';

	public function hooks(): void {
		add_filter(
			'woocommerce_order_item_display_meta_key',
			[ $this, 'get_meta_key_display_label' ],
			10,
			3
		);
		add_filter(
			'woocommerce_order_item_display_meta_value',
			[ $this, 'get_meta_key_display_value' ],
			10,
			3
		);
	}

	/**
	 * @param string        $display_key
	 * @param \WC_Meta_Data $meta
	 * @param array         $item
	 *
	 * @return string
	 */
	public function get_meta_key_display_label( $display_key, $meta, $item ): string {
		if ( self::META_KEY === $display_key ) {
			$display_key = __( 'Shipping Costs', 'flexible-shipping' );
		}

		return $display_key;
	}

	/**
	 * @param string        $display_value
	 * @param \WC_Meta_Data $meta
	 * @param array         $item
	 *
	 * @return string
	 */
	public function get_meta_key_display_value( $display_value, $meta, $item ): string {
		if ( ! $meta instanceof \WC_Meta_Data ) {
			return $display_value;
		}
		$data = $meta->get_data();
		if ( self::META_KEY === ( $data['key'] ?? '' ) ) {
			$meta_value = json_decode( $display_value, true );

			return sprintf(
				// Translators: %1$s - Original cost, %2$s - Additional cost.
				__( 'Base: %1$s, Additional: %2$s', 'flexible-shipping' ),
				$meta_value[ self::BASE ] ?? '',
				$meta_value[ self::ADDITIONAL ] ?? ''
			);
		}

		return $display_value;
	}

	public static function prepare_meta_value( float $base_cost, float $additional_cost ): string {
		return json_encode(
			[
				self::BASE       => wc_price( $base_cost ),
				self::ADDITIONAL => wc_price( $additional_cost ),
			]
		);
	}
}
