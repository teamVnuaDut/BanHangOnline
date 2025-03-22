<?php

namespace WPDesk\FS\Blocks\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

class FreeShippingStoreEndpointData implements Hookable {

	/**
	 * @var string
	 */
	private $integration_name;

	public function __construct( string $integration_name ) {
		$this->integration_name = $integration_name;
	}

	public function hooks() {
		add_filter( 'octolize-checkout-block-integration-' . $this->integration_name .  '-data', [ $this, 'integration_data' ] );
		add_filter( 'octolize-checkout-block-integration-' . $this->integration_name .  '-schema', [ $this, 'integration_schema' ] );
	}

	/**
	 * @param array $data
	 *
	 * @return array <string, array<string, bool>>
	 */
	public function integration_data( $data ) {
		return [
			'page_type' => $this->get_page_type(),
		];
	}

	private function get_page_type() {
		return is_checkout() ? 'checkout' : ( is_cart() ? 'cart' : 'other' );
	}

	/**
	 * @param array $schema
	 *
	 * @return array
	 */
	public function integration_schema( $schema ) {
		$schema['page_type']['type'] = [ 'string' ];

		return $schema;
	}

}
