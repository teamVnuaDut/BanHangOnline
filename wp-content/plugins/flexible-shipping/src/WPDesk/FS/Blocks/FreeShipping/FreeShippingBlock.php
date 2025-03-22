<?php

namespace WPDesk\FS\Blocks\FreeShipping;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FS\TableRate\FreeShipping\FreeShippingNoticeData;

class FreeShippingBlock implements Hookable {

	const BLOCK_NAME = 'flexible-shipping/free-shipping-notice';

	/**
	 * @var string[]
	 */
	private $session_variable_names;

	/**
	 * FreeShippingBlock constructor.
	 *
	 * @param string[] $session_variable_names .
	 */
	public function __construct( $session_variable_names ) {
		if ( ! is_array( $session_variable_names ) ) {
			$session_variable_names = [];
		}
		$this->session_variable_names = $session_variable_names;
	}

	public function hooks() {
		add_action( 'init', [ $this, 'create_free_shipping_notice_block' ] );
		add_action( 'rest_api_init', [ $this, 'add_free_shipping_notice_endpoint' ] );
	}

	public function add_free_shipping_notice_endpoint() {
		register_rest_route(
			'flexible-shipping/v1',
			'/free-shipping-notice',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_free_shipping_notices' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	public function get_free_shipping_notices() {
		$free_shipping_notices = [];
		foreach ($this->get_free_shipping_notices_data() as $free_shipping_notice_data) {
			$free_shipping_notices[] = [
				'content' => apply_filters( 'flexible-shipping/free-shipping/render-notice', $free_shipping_notice_data ),
				'blocks'  => apply_filters( 'flexible-shipping/free-shipping-block/allowed-blocks', [
					'checkout',
					'cart'
				], $free_shipping_notice_data ),
			];
		}

		return $free_shipping_notices;
	}

	public function create_free_shipping_notice_block() {
		register_block_type(
			__DIR__ . '/../../../../../assets/blocks/free-shipping-notice',
			[
				'render_callback' => [ $this, 'render' ],
			]
		);
	}

	public function render() {
		return '<div class="flexible-shipping-free-shipping-root"></div>';
	}

	/**
	 * @return FreeShippingNoticeData[]
	 */
	private function get_free_shipping_notices_data() {
		$free_shipping_notice_data = [];
		$session_variable_names = apply_filters( 'flexible-shipping/free-shipping-block/session-variables', $this->session_variable_names );
		if ( ! is_array( $session_variable_names) ) {
			return [];
		}
		foreach ( $session_variable_names as $session_variable_name ) {
			$session_data = $this->get_session()->get( $session_variable_name, '' );
			if ( $session_data instanceof FreeShippingNoticeData ) {
				$free_shipping_notice_data[] = $session_data;
			}
			if ( is_array( $session_data ) ) {
				$free_shipping_notice_data[] = FreeShippingNoticeData::create_from_array( $session_data );
			}
		}

		return $free_shipping_notice_data;
	}

	protected function get_session() {
		if ( WC()->session === null ) {
			WC()->initialize_session();
		}
		return WC()->session;
	}

}
