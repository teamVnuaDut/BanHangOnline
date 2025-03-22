<?php

/**
 * Currency converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers\Switcher\Aelia
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\Aelia;

use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\AbstractConverter;
/**
 * Can convert currency using Aelia  Currency Switcher plugin.
 * @see https://aelia.co/shop/currency-switcher-woocommerce/
 */
class Converter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    public function convert($value)
    {
        try {
            $class = 'Aelia' . '\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher';
            // php scoper faker
            $aelia = $class::instance();
            $aelia_settings = $class::settings();
            $from_currency = $aelia_settings->base_currency();
            $to_currency = $aelia->get_selected_currency();
            return $aelia->convert($value, $from_currency, $to_currency);
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->debug($e->getMessage());
            }
        }
        return $value;
    }
}
