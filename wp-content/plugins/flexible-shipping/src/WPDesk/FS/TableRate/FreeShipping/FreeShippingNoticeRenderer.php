<?php

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\View\Renderer\Renderer;
use FSVendor\WPDesk\View\Renderer\SimplePhpRenderer;

/**
 * Can render free shipping notice.
 */
class FreeShippingNoticeRenderer implements Hookable {

	/**
	 * @var SimplePhpRenderer
	 */
	private $renderer;

	/**
	 * @param Renderer $renderer
	 */
	public function __construct( Renderer $renderer ) {
		$this->renderer = $renderer;
	}

	public function hooks() {
		add_filter( 'flexible-shipping/free-shipping/render-notice', [ $this, 'render_notice' ] );
	}

	/**
	 * @param FreeShippingNoticeData $free_shipping_notice_data
	 *
	 * @return string
	 */
	public function render_notice( $free_shipping_notice_data ): string {
		if ( ! $free_shipping_notice_data instanceof FreeShippingNoticeData ) {
			return '';
		}

		$notice_text = $this->renderer->render(
			'free-shipping/notice',
			[
				'notice_text'             => $free_shipping_notice_data->get_notice_text(),
				'zero_value'              => $free_shipping_notice_data->get_zero_display_value(),
				'show_progress_bar'       => $free_shipping_notice_data->is_show_progress_bar(),
				'percentage'              => $free_shipping_notice_data->get_percentage(),
				'free_shipping_threshold' => $free_shipping_notice_data->get_threshold_display_value(),
				'button_url'              => $free_shipping_notice_data->get_button_url(),
				'button_label'            => $free_shipping_notice_data->get_button_label(),
			]
		);

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
