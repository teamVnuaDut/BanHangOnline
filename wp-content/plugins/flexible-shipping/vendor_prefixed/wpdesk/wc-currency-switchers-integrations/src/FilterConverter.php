<?php

/**
 * Filter converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers;

use FSVendor\Psr\Log\LoggerInterface;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can add filter to convert currency.
 */
class FilterConverter implements Hookable
{
    /**
     * @var AbstractConverter
     */
    private $converter;
    /**
     * @var string
     */
    private $shipping_method_id;
    /**
     * @param SwitcherConverter $converter
     * @param string $shipping_method_id
     */
    public function __construct(SwitcherConverter $converter, $shipping_method_id)
    {
        $this->converter = $converter;
        $this->shipping_method_id = $shipping_method_id;
    }
    public function hooks()
    {
        add_filter($this->shipping_method_id . '/currency-switchers/amount', [$this, 'convert'], 10, 2);
    }
    /**
     * @param float $amount_in_shop_currency
     * @param LoggerInterface $logger
     *
     * @return float
     */
    public function convert($amount_in_shop_currency, $logger = null)
    {
        if ($logger instanceof LoggerInterface) {
            $this->converter->setLogger($logger);
        }
        return $this->converter->convert($amount_in_shop_currency);
    }
}
