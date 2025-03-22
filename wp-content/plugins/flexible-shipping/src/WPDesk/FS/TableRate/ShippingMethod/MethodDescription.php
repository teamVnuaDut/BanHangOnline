<?php
/**
 * Class MethodDescription
 *
 * @package WPDesk\FS\TableRate\ShippingMethod
 */

namespace WPDesk\FS\TableRate\ShippingMethod;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\View\Renderer\Renderer;
use WC_Shipping_Rate;
use WC_Shipping_Zones;
use WPDesk\FS\TableRate\ShippingMethodSingle;
use WPDesk_Flexible_Shipping;

/**
 * Can display method description.
 */
class MethodDescription implements Hookable {

	/**
	 * Renderer.
	 *
	 * @var Renderer;
	 */
	private $renderer;

	/**
	 * MethodDescription constructor.
	 *
	 * @param Renderer $renderer .
	 */
	public function __construct( Renderer $renderer ) {
		$this->renderer = $renderer;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'woocommerce_after_shipping_rate', [ $this, 'display_description_if_present' ], 10, 2 );
		add_filter( 'woocommerce_package_rates', [ $this, 'add_description_to_rate_if_present' ] );
	}

	/**
	 * @param WC_Shipping_Rate[] $rates .
	 *
	 * @return WC_Shipping_Rate[]
	 */
	public function add_description_to_rate_if_present( $rates ) {
		foreach ( $rates as $rate ) {
			if ( ! $rate instanceof WC_Shipping_Rate || ! $this->should_display_method_description( $rate ) ) {
				continue;
			}
			$rate->description = $this->get_method_description( $rate );
		}

		return $rates;
	}

	/**
	 * @param WC_Shipping_Rate $method .
	 * @param int              $index  .
	 */
	public function display_description_if_present( $method, $index ) {
		if ( ! $method instanceof WC_Shipping_Rate || ! $this->should_display_method_description( $method ) ) {
			return;
		}

		$description = $this->get_method_description( $method );

		if ( '' !== $description ) {
			echo $this->renderer->render(
				'cart/flexible-shipping/after-shipping-rate',
				array(
					'method_description' => $description,
				)
			); // WPCS: XSS OK.
		}
	}

	/**
	 * @param WC_Shipping_Rate $method .
	 *
	 * @return string
	 */
	private function get_method_description( $method ) {
		$meta_data = $method->get_meta_data();

		if ( isset( $meta_data[ RateCalculator::DESCRIPTION_BASE64ENCODED ] ) && ! empty( $meta_data[ RateCalculator::DESCRIPTION_BASE64ENCODED ] ) ) {
			$description = base64_decode( $meta_data[ RateCalculator::DESCRIPTION_BASE64ENCODED ] );

			if ( $description ) {
				return $description;
			}
		}

		if ( isset( $meta_data[ RateCalculator::DESCRIPTION ] ) ) {
			return $meta_data[ RateCalculator::DESCRIPTION ];
		}

		return '';
	}

	/**
	 * @param WC_Shipping_Rate $method .
	 *
	 * @return bool
	 */
	private function should_display_method_description( $method ) {
		return in_array(
			$method->get_method_id(),
			array(
				WPDesk_Flexible_Shipping::METHOD_ID,
				ShippingMethodSingle::SHIPPING_METHOD_ID,
			),
			true
		);
	}
}
