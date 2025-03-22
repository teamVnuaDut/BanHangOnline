<?php

/**
 * Filter converters.
 *
 * @package WPDesk\WooCommerce\CurrencySwitchers
 */
namespace FSVendor\WPDesk\WooCommerce\CurrencySwitchers;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\WooCommerce\CurrencySwitchers\Switcher;
/**
 * Can create filter converters.
 */
class FilterConvertersFactory implements Hookable
{
    const PRIORITY_AFTER_DEFAULT = 100;
    /**
     * @var string
     */
    private $shipping_method_id;
    /**
     * @param string $shipping_method_id .
     */
    public function __construct($shipping_method_id)
    {
        $this->shipping_method_id = $shipping_method_id;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        add_action('woocommerce_multicurrency_loaded', array($this, 'create_woocommerce_multicurrency_filter_converter'));
        add_action('woocommerce_init', [$this, 'create_currency_switcher_woocommerce_filter_converter'], self::PRIORITY_AFTER_DEFAULT);
        add_action('woocommerce_init', [$this, 'create_wcml_filter_converter'], self::PRIORITY_AFTER_DEFAULT);
        add_action('woocommerce_init', [$this, 'create_aelia_filter_converter'], self::PRIORITY_AFTER_DEFAULT);
        add_action('woocommerce_init', [$this, 'create_fox_currency_switcher_filter_converter'], self::PRIORITY_AFTER_DEFAULT);
        add_action('woocommerce_init', [$this, 'create_wmcs_filter_converter'], self::PRIORITY_AFTER_DEFAULT);
    }
    public function create_woocommerce_multicurrency_filter_converter()
    {
        (new FilterConverter(new Switcher\WooCommerceMultiCurrency\Converter(), $this->shipping_method_id))->hooks();
    }
    public function create_currency_switcher_woocommerce_filter_converter()
    {
        $alg_get_current_currency_code = 'alg_get_current_currency_code';
        // php scoper faker.
        $alg_wc_cs_get_currency_exchange_rate = 'alg_wc_cs_get_currency_exchange_rate';
        // php scoper faker.
        if (function_exists($alg_get_current_currency_code) && function_exists($alg_wc_cs_get_currency_exchange_rate)) {
            (new FilterConverter(new Switcher\CurrencySwitcherWoocommerce\Converter(), $this->shipping_method_id))->hooks();
        }
    }
    public function create_wcml_filter_converter()
    {
        (new FilterConverter(new Switcher\WCML\Converter(), $this->shipping_method_id))->hooks();
    }
    public function create_aelia_filter_converter()
    {
        $class = 'Aelia' . '\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher';
        // php scoper faker
        if (class_exists($class)) {
            (new FilterConverter(new Switcher\Aelia\Converter(), $this->shipping_method_id))->hooks();
        }
    }
    public function create_fox_currency_switcher_filter_converter()
    {
        if (isset($GLOBALS['WOOCS'])) {
            (new FilterConverter(new Switcher\FoxCurrencySwitcher\Converter(), $this->shipping_method_id))->hooks();
        }
    }
    public function create_wmcs_filter_converter()
    {
        $wmcs_convert_price = 'wmcs_convert_price';
        // php scoper faker.
        if (function_exists($wmcs_convert_price)) {
            (new FilterConverter(new Switcher\WMCS\Converter(), $this->shipping_method_id))->hooks();
        }
    }
}
