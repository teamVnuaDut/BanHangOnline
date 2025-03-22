<?php

namespace WPDesk\FS\TableRate;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\Psr\Log\LoggerInterface;

/**
 * Can convert from shop currency to current currency.
 */
class MultiCurrency implements Hookable {

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var string
	 */
	private $filter_prefix;

	/**
	 * @param LoggerInterface $logger
	 * @param string          $filter_prefix
	 */
	public function __construct( LoggerInterface $logger, string $filter_prefix ) {
		$this->logger        = $logger;
		$this->filter_prefix = $filter_prefix;
	}

	public function hooks() {
		add_filter( 'flexible_shipping_value_in_currency', [ $this, 'flexible_shipping_value_in_currency' ], PHP_INT_MAX, 2 );
	}

	public function flexible_shipping_value_in_currency( $amount, $base_currency_amount = null ) {
		if ( $base_currency_amount && $amount !== $base_currency_amount ) {
			// Already converted.
			return $amount;
		}

		return (float) apply_filters( $this->filter_prefix . '/currency-switchers/amount', $amount, $this->logger );
	}

}
