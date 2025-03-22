<?php

namespace FSVendor\Octolize\Tracker\OptInNotice;

/**
 * Should display shipping method settings page.
 */
class ShouldDisplayShippingMethodInstanceSettings implements ShouldDisplay
{
    /**
     * @var string
     */
    private $shipping_method_id;
    /**
     * @param string $shipping_method_id
     */
    public function __construct(string $shipping_method_id)
    {
        $this->shipping_method_id = $shipping_method_id;
    }
    /**
     * @inheritDoc
     */
    public function should_display()
    {
        $shipping_method = \WC_Shipping_Zones::get_shipping_method($_GET['instance_id'] ?? 0);
        return $shipping_method && $shipping_method instanceof \WC_Shipping_Method && $shipping_method->id === $this->shipping_method_id;
    }
}
