<?php

/**
 * Abstract converter.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers;

use FSVendor\Psr\Log\LoggerAwareInterface;
use FSVendor\Psr\Log\LoggerAwareTrait;
/**
 * Abstract class for converters.
 */
abstract class AbstractConverter implements SwitcherConverter, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @inheritDoc
     */
    abstract function convert($value);
    /**
     * @inheritDoc
     */
    public function convert_array($values)
    {
        foreach ($values as $key => $value) {
            if ($value) {
                $values[$key] = $this->convert($value);
            }
        }
        return $values;
    }
}
