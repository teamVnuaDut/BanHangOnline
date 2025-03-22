<?php
/**
 * Class FSPro.
 *
 * @package WPDesk\FS\Info
 */

namespace WPDesk\FS\Info;

/**
 * FS Pro metabox.
 */
class FSPro extends Metabox {
	/**
	 * WooCommerceABC constructor.
	 */
	public function __construct() {
		$title = __( 'Get Flexible Shipping PRO!', 'flexible-shipping' );

		parent::__construct( 'fs-pro', $title, $this->get_body_content(), $this->get_footer_content() );
	}

	/**
	 * @return string
	 */
	private function get_body_content() {
		ob_start();

		include 'views/fs-pro.php';

		return ob_get_clean();
	}

	/**
	 * @return string
	 */
	private function get_footer_content() {
		$url = get_user_locale() === 'pl_PL' ? 'https://octol.io/fs-info-pro-pl' : 'https://octol.io/fs-info-pro';

		return '<a class="oct-metabox-btn" href="' . esc_url( $url ) . '" target="_blank">' . __( 'Upgrade Now', 'flexible-shipping' ) . '</a>';
	}

}
