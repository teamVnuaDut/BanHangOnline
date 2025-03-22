<?php

namespace WPDesk\FS\TableRate\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FS\Blocks\FreeShipping\FreeShippingBlock;

/**
 * Can enqueue assets.
 */
class Assets implements Hookable {

	/**
	 * @var string
	 */
	private $assets_url;

	/**
	 * @var string
	 */
	private $scripts_version;

	/**
	 * @param string $assets_url
	 * @param        $scripts_version
	 */
	public function __construct( string $assets_url, $scripts_version ) {
		$this->assets_url      = $assets_url;
		$this->scripts_version = $scripts_version;
	}

	public function hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		if ( apply_filters( 'flexible-shipping/free-shipping/enqueue_css', is_page() || is_checkout() || is_cart() || is_product() || is_shop() || has_block( FreeShippingBlock::BLOCK_NAME ) ) ) {
			wp_enqueue_style(
				'flexible-shipping-free-shipping',
				trailingslashit( $this->assets_url ) . 'dist/css/free-shipping.css',
				[],
				$this->scripts_version
			);
		}
	}

}
