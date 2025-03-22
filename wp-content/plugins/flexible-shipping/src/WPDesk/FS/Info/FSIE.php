<?php
/**
 * Class FSIE.
 *
 * @package WPDesk\FS\Info
 */

namespace WPDesk\FS\Info;

/**
 * FSIE metabox.
 */
class FSIE extends Metabox {
	/**
	 * WooCommerceABC constructor.
	 */
	public function __construct() {
		$title = __( 'Extend the Flexible Shipping capabilities with functional add-ons', 'flexible-shipping' );

		parent::__construct( 'fsie', $title, $this->get_body_content(), $this->get_footer_content() );
	}

	/**
	 * @return string
	 */
	private function get_body_content() {
		ob_start();

		include 'views/fsie.php';

		return ob_get_clean();
	}

	/**
	 * @return string
	 */
	private function get_footer_content() {
		$url = get_locale() === 'pl_PL' ? 'https://octol.io/fs-info-addons-pl' : 'https://octol.io/fs-info-addons';

		return '<a class="oct-metabox-btn" href="' . esc_url( $url ) . '" target="_blank">' . __( 'Buy Add-ons', 'flexible-shipping' ) . '</a>';
	}

}
