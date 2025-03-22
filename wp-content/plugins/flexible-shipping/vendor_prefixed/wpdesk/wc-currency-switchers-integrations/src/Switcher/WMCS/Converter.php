<?php

/**
 * Currency converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers\Switcher\WMCS
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher\WMCS;

use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\AbstractConverter;
/**
 * Can convert currency using WMCS plugin.
 * Unknown plugin URL. This is legacy from Flexible Shipping
 */
class Converter extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    public function convert($value)
    {
        try {
            return wmcs_convert_price($value);
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->debug($e->getMessage());
            }
        }
        return $value;
    }
}
