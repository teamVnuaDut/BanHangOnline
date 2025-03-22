<?php

/**
 * Currency converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers\Switcher\CurrencySwitcherWoocommerce
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\CurrencySwitcherWoocommerce;

use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\AbstractConverter;
/**
 * Can convert currency using Currency Switcher for WooCommerce plugin.
 * @see https://wordpress.org/plugins/currency-switcher-woocommerce/
 */
class Converter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    public function convert($value)
    {
        try {
            $currency_exchange_rate = alg_wc_cs_get_currency_exchange_rate(alg_get_current_currency_code());
            return $value * $currency_exchange_rate;
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->debug($e->getMessage());
            }
        }
        return $value;
    }
}
