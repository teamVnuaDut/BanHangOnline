<?php

/**
 * Currency converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers\Switcher\WCML
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\WCML;

use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\AbstractConverter;
/**
 * Can convert currency using Currency Switcher for WooCommerce plugin.
 * @see https://wordpress.org/plugins/woocommerce-multilingual/
 */
class Converter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    public function convert($value)
    {
        try {
            return apply_filters('wcml_raw_price_amount', $value);
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->debug($e->getMessage());
            }
        }
        return $value;
    }
}
