<?php
/**
 * Free Shipping Notice Generator.
 *
 * @package WPDesk\FS\TableRate\FreeShipping
 */

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WC_Cart;
use WC_Session;
use WC_Shipping_Rate;
use WPDesk\FS\TableRate\ShippingMethodSingle;
use WPDesk_Flexible_Shipping;

/**
 * Can generate free shipping notice and save it on session.
 */
class FreeShippingNoticeGenerator implements Hookable {

	const SETTING_METHOD_FREE_SHIPPING = 'method_free_shipping';
	const META_DATA_FS_METHOD = '_fs_method';
	const PRIORITY = 10;

	/**
	 * @var WC_Cart|null
	 */
	protected $cart;

	/**
	 * @var WC_Session|null
	 */
	private $session;

	/**
	 * @var string
	 */
	private $session_variable_name;

	/**
	 * FreeShippingNotice constructor.
	 *
	 * @param WC_Cart|null    $cart    .
	 * @param WC_Session|null $session .
	 * @param string          $session_variable_name .
	 */
	public function __construct( $cart, $session, string $session_variable_name ) {
		$this->cart                  = $cart;
		$this->session               = $session;
		$this->session_variable_name = $session_variable_name;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_filter( 'woocommerce_package_rates', [ $this, 'add_free_shipping_notice_if_should' ], self::PRIORITY );
		add_action( 'woocommerce_after_calculate_totals', [ $this, 'clear_notices_if_cart_not_needs_shipping' ], self::PRIORITY );
	}

	/**
	 * @param WC_Cart $cart
	 *
	 * @return void
	 */
	public function clear_notices_if_cart_not_needs_shipping( $cart ) {
		if ( ! $cart->needs_shipping() ) {
			$this->get_session()->set( $this->session_variable_name, null );
		}
	}

	/**
	 * Triggered by filter. Must return $package_rates.
	 *
	 * @param array $package_rates .
	 * @param array $package       .
	 *
	 * @return array
	 */
	public function add_free_shipping_notice_if_should( $package_rates ) {
		$this->cart = WC()->cart;
		if ( $this->cart->needs_shipping() && $this->has_shipping_rate_with_free_shipping( $package_rates ) && ! $this->has_free_shipping_rate( $package_rates ) && $this->get_shipping_packages_count() === 1 ) {
			$this->add_free_shipping_amount_to_session( $package_rates );
		} else {
			$this->get_session()->set( $this->session_variable_name, null );
		}

		return $package_rates;
	}

	protected function get_session() {
		return WC()->session;
	}

	/**
	 * @return int
	 */
	private function get_shipping_packages_count() {
		return count( $this->cart->get_shipping_packages() );
	}

	/**
	 * Add free shipping notice.
	 *
	 * @param array $package_rates .
	 */
	private function add_free_shipping_amount_to_session( $package_rates ) {
		$shipping_method_with_lowest_free_shipping_limit = $this->get_shipping_method_with_lowest_free_shipping_limit( $package_rates );
		$lowest_free_shipping_limit                      = floatval( $shipping_method_with_lowest_free_shipping_limit[ self::SETTING_METHOD_FREE_SHIPPING ] );
		$lowest_free_shipping_limit                      = round(
			(float) apply_filters( 'flexible_shipping_value_in_currency', $lowest_free_shipping_limit, $lowest_free_shipping_limit ),
			wc_get_rounding_precision()
		);
		$amount                                          = $lowest_free_shipping_limit - $this->get_cart_value();
		$free_shipping_notice_text                       = $shipping_method_with_lowest_free_shipping_limit[ NoticeTextSettings::FIELD_NAME ] ?? '';
		$meta_data                                       = apply_filters( 'flexible-shipping/free-shipping/metadata', [ 'method_settings' => $shipping_method_with_lowest_free_shipping_limit ], $shipping_method_with_lowest_free_shipping_limit );

		$free_shipping_notice_data = $this->prepare_free_shipping_notice_data(
			( $shipping_method_with_lowest_free_shipping_limit[ ProgressBarSettings::FIELD_NAME ] ?? 'no' ) === 'yes',
			round( $this->get_cart_value() / $lowest_free_shipping_limit * 100 ),
			$lowest_free_shipping_limit,
			$amount,
			$free_shipping_notice_text,
			$this->get_notice_text_button_url(),
			$this->get_notice_text_button_label(),
			$meta_data
		);

		$this->get_session()->set( $this->session_variable_name, $free_shipping_notice_data->jsonSerialize() );
	}

	/**
	 * @param bool   $show_progress_bar
	 * @param float  $percentage
	 * @param float  $lowest_free_shipping_limit
	 * @param float  $amount
	 * @param string $free_shipping_notice_text
	 * @param string $button_url
	 * @param string $button_label
	 * @param array  $meta_data
	 *
	 * @return FreeShippingNoticeData
	 */
	protected function prepare_free_shipping_notice_data(
		bool $show_progress_bar,
		float $percentage,
		float $lowest_free_shipping_limit,
		float $amount,
		string $free_shipping_notice_text,
		string $button_url,
		string $button_label,
		array $meta_data
	): FreeShippingNoticeData {
		return new FreeShippingNoticeData(
			$show_progress_bar,
			$percentage,
			$lowest_free_shipping_limit,
			wc_price( $lowest_free_shipping_limit ),
			wc_price( 0 ),
			$amount,
			$this->get_notice_text_message( wc_price( $amount ), $free_shipping_notice_text ),
			$button_url,
			$button_label,
			$meta_data
		);
	}

	/**
	 * @return string
	 */
	protected function get_notice_text_message( string $amount, string $message ): string {
		// Translators: amount with currency or number of items.
		$message = sprintf(
			empty( $message ) ? __( 'You only need %1$s more to get free shipping!', 'flexible-shipping' ) : wpdesk__( $message, 'flexible-shipping' ),
			$amount
		);

		$text_message = apply_filters( 'flexible_shipping_free_shipping_notice_text_message', $message, $amount );

		return is_string( $text_message ) ? $text_message : '';
	}

	/**
	 * @return string
	 */
	private function get_notice_text_button_url(): string {
		return apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) );
	}

	/**
	 * @return string
	 */
	private function get_notice_text_button_label(): string {
		return apply_filters( 'flexible_shipping_free_shipping_notice_text_button_label', __( 'Continue shopping', 'flexible-shipping' ) );
	}

	/**
	 * Has package free shipping rate?
	 *
	 * @param array $package_rates .
	 *
	 * @return bool
	 */
	private function has_free_shipping_rate( $package_rates ): bool {
		/** @var WC_Shipping_Rate $package_rate */
		foreach ( $package_rates as $package_rate ) {
			if ( floatval( $package_rate->get_cost() ) === 0.0 && ! $this->is_excluded_shipping_method( $package_rate->get_method_id() ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is shipping method excluded from free shipping?
	 *
	 * @param string $method_id .
	 *
	 * @return bool
	 */
	private function is_excluded_shipping_method( $method_id ): bool {
		/**
		 * Exclude methods from free shipping.
		 *
		 * @param array $excluded_methods
		 *
		 * @return array
		 */
		$excluded_methods = apply_filters( 'flexible_shipping_free_shipping_notice_excluded_methods', [ 'local_pickup' ] );

		$excluded_methods = is_array( $excluded_methods ) ? $excluded_methods : [];

		return in_array( $method_id, $excluded_methods, true );
	}

	/**
	 * Has package rate with free shipping?
	 *
	 * @param array $package_rates .
	 *
	 * @return bool
	 */
	private function has_shipping_rate_with_free_shipping( $package_rates ) {
		if ( ! is_array( $package_rates ) ) {
			return false;
		}
		/** @var WC_Shipping_Rate $package_rate */
		foreach ( $package_rates as $package_rate ) {
			if ( $this->is_package_rate_from_flexible_shipping( $package_rate ) ) {
				$meta_data = $package_rate->get_meta_data();
				if ( isset( $meta_data[ self::META_DATA_FS_METHOD ] ) ) {
					if ( $this->has_shipping_method_free_shipping_notice_enabled( $meta_data[ self::META_DATA_FS_METHOD ] ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * @param array $fs_method .
	 *
	 * @return bool
	 */
	protected function has_shipping_method_free_shipping_notice_enabled( array $fs_method ): bool {
		return ! empty( $fs_method[ self::SETTING_METHOD_FREE_SHIPPING ] )
			&& isset( $fs_method[ WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE ] )
			&& 'yes' === $fs_method[ WPDesk_Flexible_Shipping::SETTING_METHOD_FREE_SHIPPING_NOTICE ]
			&& apply_filters( 'flexible-shipping/shipping-method/free-shipping-notice-allowed', true, $fs_method );
	}

	/**
	 * Returns current cart value.
	 *
	 * @return float
	 */
	protected function get_cart_value(): float {
		return $this->cart->display_prices_including_tax() ? $this->cart->get_cart_contents_total() + $this->cart->get_cart_contents_tax() : $this->cart->get_cart_contents_total();
	}

	/**
	 * @param $package_rates
	 *
	 * @return array
	 */
	protected function get_shipping_method_with_lowest_free_shipping_limit( $package_rates ): array {
		$lowest_free_shipping_limit = null;
		$shipping_method = [];
		/** @var WC_Shipping_Rate $package_rate */
		foreach ( $package_rates as $package_rate ) {
			if ( $this->is_package_rate_from_flexible_shipping( $package_rate ) ) {
				$meta_data = $package_rate->get_meta_data();
				$fs_method = $meta_data[ self::META_DATA_FS_METHOD ] ?? [];
				if ( $this->has_shipping_method_free_shipping_notice_enabled( $fs_method ) ) {
					$method_free_shipping_limit = round( floatval( $fs_method[ self::SETTING_METHOD_FREE_SHIPPING ] ), wc_get_rounding_precision() );
					if ( $lowest_free_shipping_limit === null || $method_free_shipping_limit < $lowest_free_shipping_limit ) {
						$lowest_free_shipping_limit = $method_free_shipping_limit;
						$shipping_method = $fs_method;
					}
				}
			}
		}

		return $shipping_method;
	}

	/**
	 * @param WC_Shipping_Rate $package_rate .
	 *
	 * @return bool
	 */
	protected function is_package_rate_from_flexible_shipping( WC_Shipping_Rate $package_rate ): bool {
		$shipping_methods = [ WPDesk_Flexible_Shipping::METHOD_ID, ShippingMethodSingle::SHIPPING_METHOD_ID ];

		return in_array( $package_rate->get_method_id(), $shipping_methods, true );
	}
}
