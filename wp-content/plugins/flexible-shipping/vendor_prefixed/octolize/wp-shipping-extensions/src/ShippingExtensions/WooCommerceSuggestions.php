<?php

namespace FSVendor\Octolize\ShippingExtensions;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class WooCommerceSuggestions implements Hookable
{
    const SHIPPING_METHODS = ['dpd', 'dpd_uk', 'dhl', 'dpd_uk', 'enadawca', 'furgonetka', 'paczka_w_ruchu', 'paczkomaty_shipping_method', 'gls', 'gls-settings'];
    const STYLE = '<style>.woocommerce-recommended-shipping-extensions { display: none; }</style>';
    public function hooks()
    {
        add_action('admin_head', [$this, 'add_css_on_octolize_pages']);
    }
    public function add_css_on_octolize_pages(): void
    {
        if ($this->is_octolize_page()) {
            echo self::STYLE;
        }
    }
    private function is_octolize_page(): bool
    {
        return isset($_GET['octolize']) || $this->is_octolize_shipping_method_page();
    }
    private function is_octolize_shipping_method_page(): bool
    {
        return isset($_GET['page']) && 'wc-settings' === $_GET['page'] && isset($_GET['tab']) && 'shipping' === $_GET['tab'] && (isset($_GET['section']) && $this->is_octolize_shipping_method_settings($_GET['section']) || isset($_GET['instance_id']) && $this->is_octolize_shipping_method_instance_settings((int) $_GET['instance_id']));
    }
    private function is_octolize_shipping_method_settings(string $section): bool
    {
        return in_array($section, self::SHIPPING_METHODS, \true) || strpos($section, 'flexible_shipping') === 0 || strpos($section, 'octolize') === 0;
    }
    private function is_octolize_shipping_method_instance_settings(int $instance_id): bool
    {
        $shipping_method = \WC_Shipping_Zones::get_shipping_method($instance_id);
        return $shipping_method && $this->is_octolize_shipping_method_settings($shipping_method->id);
    }
}
