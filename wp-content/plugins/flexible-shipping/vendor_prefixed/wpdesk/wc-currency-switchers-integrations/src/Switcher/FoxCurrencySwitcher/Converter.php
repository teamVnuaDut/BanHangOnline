<?php

/**
 * Currency converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers\Switcher\FoxCurrencySwitcher
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\FoxCurrencySwitcher;

use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\AbstractConverter;
use function FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\CurrencySwitcherWoocommerce\alg_get_current_currency_code;
use function FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\CurrencySwitcherWoocommerce\alg_wc_cs_get_currency_exchange_rate;
/**
 * Can convert currency using FOX – Currency Switcher Professional for WooCommerce plugin.
 * @see https://wordpress.org/plugins/woocommerce-currency-switcher/
 */
class Converter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    public function convert($value)
    {
        try {
            return $GLOBALS['WOOCS'] ? $GLOBALS['WOOCS']->woocs_exchange_value($value) : $value;
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->debug($e->getMessage());
            }
        }
        return $value;
    }
}
