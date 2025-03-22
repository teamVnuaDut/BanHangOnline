<?php

/**
 * Currency converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers\Switcher\WooCommerceMultiCurrency
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\WooCommerceMultiCurrency;

use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\AbstractConverter;
/**
 * Can convert currency using WooCommerce MultiCurrency plugin.
 * @see https://woocommerce.com/products/multi-currency/
 */
class Converter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    public function convert($value)
    {
        try {
            $rate_storage = new \FSVendor\WOOMC\Rate\Storage();
            $price_rounder = new \FSVendor\WOOMC\Price\Rounder();
            $currency_detector = new \FSVendor\WOOMC\Currency\Detector();
            $price_calculator = new \FSVendor\WOOMC\Price\Calculator($rate_storage, $price_rounder);
            $price_controller = new \FSVendor\WOOMC\Price\Controller($price_calculator, $currency_detector);
            return $price_controller->convert($value);
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->debug($e->getMessage());
            }
        }
        return $value;
    }
}
