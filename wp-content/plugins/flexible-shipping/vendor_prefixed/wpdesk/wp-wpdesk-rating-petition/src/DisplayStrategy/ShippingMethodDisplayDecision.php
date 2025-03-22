<?php

namespace FSVendor\WPDesk\RepositoryRating\DisplayStrategy;

class ShippingMethodDisplayDecision implements DisplayDecision
{
    /**
     * @var \WC_Shipping_Zones
     */
    private $shipping_zones;
    /**
     * @var string
     */
    private $shipping_method_id;
    /**
     * @param \WC_Shipping_Zones $shipping_zones
     * @param string $shipping_method_id
     */
    public function __construct(\WC_Shipping_Zones $shipping_zones, string $shipping_method_id)
    {
        $this->shipping_zones = $shipping_zones;
        $this->shipping_method_id = $shipping_method_id;
    }
    /**
     * @inheritDoc
     */
    public function should_display(): bool
    {
        if ($this->is_in_shipping_settings()) {
            if ($this->is_get_parameter_with_value('section', $this->shipping_method_id)) {
                return \true;
            }
            if (isset($_GET['instance_id'])) {
                $shipping_method = $this->shipping_zones::get_shipping_method(sanitize_key($_GET['instance_id']));
                if ($shipping_method instanceof \WC_Shipping_Method) {
                    return $shipping_method->id === $this->shipping_method_id;
                }
            }
        }
        return \false;
    }
    private function is_in_shipping_settings(): bool
    {
        if ($this->is_get_parameter_with_value('page', 'wc-settings') && $this->is_get_parameter_with_value('tab', 'shipping')) {
            return \true;
        }
        return \false;
    }
    private function is_get_parameter_with_value(string $parameter, string $value): bool
    {
        return isset($_GET[$parameter]) && $_GET[$parameter] === $value;
    }
}
