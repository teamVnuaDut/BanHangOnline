<?php
/**
 * Free Shipping Notice.
 *
 * @package WPDesk\FS\TableRate\FreeShipping
 */

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WC_Cart;
use WC_Session;
use WPDesk\FS\Blocks\FreeShipping\FreeShippingBlock;

/**
 * Can display free shipping notice.
 */
class FreeShippingNotice implements Hookable {

	const FLEXIBLE_SHIPPING_FREE_SHIPPING_NOTICE = 'flexible_shipping_free_shipping_notice';
	const NOTICE_TYPE                            = 'notice';
	const NOTICE_CONTAINER_CLASS                 = 'flexible-shipping-notice-container';

	/**
	 * @var WC_Cart
	 */
	private $cart;

	/**
	 * @var WC_Session
	 */
	private $session;

	/**
	 * @var string
	 */
	private $session_variable_name;

	/**
	 * FreeShippingNotice constructor.
	 *
	 * @param WC_Cart    $cart    .
	 * @param WC_Session $session .
	 */
	public function __construct( WC_Cart $cart, WC_Session $session, string $session_variable_name ) {
		$this->cart                  = $cart;
		$this->session               = $session;
		$this->session_variable_name = $session_variable_name;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'woocommerce_after_calculate_totals', [ $this, 'add_notice_if_should' ] );
		add_action( 'wp_head', [ $this, 'add_notice_if_should_via_wp_head' ] );
	}

	public function add_notice_if_should_via_wp_head() {
		remove_action( 'woocommerce_after_calculate_totals', [ $this, 'add_notice_if_should' ] );
		$this->add_notice_if_should();
	}

	/**
	 * @return void
	 */
	public function add_checkout_notice_container() {
		echo wp_kses_post( $this->get_left_free_shipping_notice_container() );
	}

	public function add_notice_if_should(): void {
		if ( ! $this->cart->needs_shipping() ) {
			return;
		}

		$notice = '';
		$free_shipping_notice_data = $this->get_free_shipping_notice_data();
		if ( $free_shipping_notice_data instanceof FreeShippingNoticeData ) {
			$notice_message = $this->get_notice_message( $free_shipping_notice_data );
			if ( $notice_message ) {
				ob_start();
				$this->print_notice( $notice_message );
				$notice = ob_get_clean();
			}
			if ( ! is_string( $notice ) ) {
				$notice = '';
			}
		}
		if ( $notice && $this->should_add_notice_on_current_page( $free_shipping_notice_data ) && ! wc_has_notice( $notice_message, self::NOTICE_TYPE ) ) {
			wc_add_notice( $notice_message, self::NOTICE_TYPE );
		}
	}

	private function should_add_notice_on_current_page( FreeShippingNoticeData $free_shipping_notice_data ): bool {
		if ( is_ajax() ) {
			return false;
		}
		if (
			( ! is_checkout() && ! is_cart() && has_block( FreeShippingBlock::BLOCK_NAME ) )
			|| has_block( 'woocommerce/checkout' )
			|| has_block( 'woocommerce/cart' )
		) {
			return false;
		}

		return apply_filters( 'flexible-shipping/free-shipping/show-notice', is_checkout() || is_cart(), $free_shipping_notice_data );
	}

	/**
	 * @param array $fragments .
	 *
	 * @return array
	 */
	public function add_checkout_notice_to_fragments( $fragments ): array {
		$notice = '';
		if ( $this->cart->needs_shipping() ) {
			$free_shipping_notice_data = $this->get_free_shipping_notice_data();
			if ( $free_shipping_notice_data instanceof FreeShippingNoticeData ) {
				$notice_message = $this->get_notice_message( $free_shipping_notice_data );
				if ( $notice_message ) {
					ob_start();
					$this->print_notice( $notice_message );
					$notice = ob_get_clean();
				}
				if ( ! is_string( $notice ) ) {
					$notice = '';
				}
			}
		}

		$fragments[ $this->get_fragments_id() ] = ( $fragments[ '.' . self::NOTICE_CONTAINER_CLASS ] ?? '' ) . $this->get_left_free_shipping_notice_container( $notice );

		return $fragments;
	}

	/**
	 * @return FreeShippingNoticeData|null
	 */
	private function get_free_shipping_notice_data() {
		$session_data = $this->session->get( $this->session_variable_name, '' );
		if ( $session_data instanceof FreeShippingNoticeData ) {
			return $session_data;
		}
		if ( ! is_array( $session_data ) ) {
			return null;
		}

		return FreeShippingNoticeData::create_from_array( $session_data );
	}

	/**
	 * @param string $notice_message
	 *
	 * @return void
	 */
	private function print_notice( string $notice_message ) {
		if ( function_exists( 'wc_print_notice' ) ) {
			wc_print_notice( $notice_message, self::NOTICE_TYPE, [ self::FLEXIBLE_SHIPPING_FREE_SHIPPING_NOTICE => 'yes' ] );
		} else {
			echo wp_kses_post( $notice_message );
		}
	}

	/**
	 * @param string $content .
	 *
	 * @return string
	 */
	private function get_left_free_shipping_notice_container( $content = '' ): string {
		return sprintf( '<div class="%s">%s</div>', $this->get_container_class(), $content );
	}

	private function get_container_class(): string {
		return self::NOTICE_CONTAINER_CLASS . ' ' . $this->session_variable_name;
	}

	private function get_fragments_id(): string {
		return 'div.' . str_replace( ' ', '.', $this->get_container_class() );
	}

	/**
	 * @return string
	 */
	private function get_notice_message( FreeShippingNoticeData $free_shipping_notice_data ): string {
		$amount = $free_shipping_notice_data->get_missing_amount();

		if ( $amount === 0.0 ) {
			return '';
		}

		return $this->prepare_notice_text( $free_shipping_notice_data );
	}

	/**
	 * @param FreeShippingNoticeData $free_shipping_notice_data .
	 *
	 * @return string
	 */
	private function prepare_notice_text( FreeShippingNoticeData $free_shipping_notice_data ): string {
		$notice_text = apply_filters( 'flexible-shipping/free-shipping/render-notice', $free_shipping_notice_data );

		/**
		 * Notice text for Free Shipping.
		 *
		 * @param string $notice_text Notice text.
		 * @param float  $amount      Amount left to free shipping.
		 *
		 * @return string Message text.
		 */
		$notice_text = apply_filters( 'flexible_shipping_free_shipping_notice_text', $notice_text, $free_shipping_notice_data->get_missing_amount() );

		return is_string( $notice_text ) ? $notice_text : '';
	}

}
