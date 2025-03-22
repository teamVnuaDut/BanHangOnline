<?php

namespace FSVendor\WPDesk\ShowDecision\WooCommerce;

use FSVendor\WPDesk\ShowDecision\ShouldShowStrategy;
class ShippingMethodInstanceStrategy implements ShouldShowStrategy
{
    private \WC_Shipping_Zones $shipping_zones;
    private string $method_id;
    public function __construct(\WC_Shipping_Zones $shipping_zones, string $method_id)
    {
        $this->shipping_zones = $shipping_zones;
        $this->method_id = $method_id;
    }
    public function shouldDisplay(): bool
    {
        if ($this->isInShippingSettings()) {
            if (isset($_GET['instance_id'])) {
                $shipping_method = $this->shipping_zones::get_shipping_method(sanitize_key($_GET['instance_id']));
                if ($shipping_method instanceof \WC_Shipping_Method) {
                    return $shipping_method->id === $this->method_id;
                }
            }
        }
        return \false;
    }
    private function isInShippingSettings(): bool
    {
        if ($this->isGetParameterWithValue('page', 'wc-settings') && $this->isGetParameterWithValue('tab', 'shipping')) {
            return \true;
        }
        return \false;
    }
    private function isGetParameterWithValue(string $parameter, string $value): bool
    {
        return isset($_GET[$parameter]) && $_GET[$parameter] === $value;
    }
}
