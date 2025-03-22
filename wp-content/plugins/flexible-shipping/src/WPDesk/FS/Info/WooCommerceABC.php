<?php
/**
 * Info WooCommerceABC.
 *
 * @package WPDesk\FS\Info
 */

namespace WPDesk\FS\Info;

use WPDesk\FS\Info\Metabox\Links;

/**
 * WooCommerceABC in FS Info.
 */
class WooCommerceABC extends Links {
	/**
	 * WooCommerceABC constructor.
	 */
	public function __construct() {
		$title        = __( 'WooCommerce ABCs', 'flexible-shipping' );
		$footer_label = __( 'More articles', 'flexible-shipping' );
		$footer_url   = 'https://octol.io/fs-info-blog';

		parent::__construct( 'woocommerce-abc', $title, $this->generate_footer( $footer_url, $footer_label ) );
	}

	/**
	 * @return array[]
	 */
	protected function get_links() {
		return array(
			array(
				'label' => __( 'Shipping Zones', 'flexible-shipping' ),
				'href'  => 'https://octol.io/fs-info-zones',
			),
			array(
				'label' => __( 'Shipping Tax', 'flexible-shipping' ),
				'href'  => 'https://octol.io/fs-info-tax',
			),
			array(
				'label' => __( 'Shipping Methods', 'flexible-shipping' ),
				'href'  => 'https://octol.io/fs-info-methods',
			),
			array(
				'label' => __( 'Shipping Classes', 'flexible-shipping' ),
				'href'  => 'https://octol.io/fs-info-classes',
			),
			array(
				'label' => __( 'Table Rate Shipping', 'flexible-shipping' ),
				'href'  => 'https://octol.io/fs-info-table-rate',
			),
		);
	}
}
