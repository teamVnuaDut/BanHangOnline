<?php

namespace FSVendor\Octolize\ShippingExtensions;

use FSVendor\Octolize\ShippingExtensions\Tracker\Tracker;
use FSVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use FSVendor\WPDesk_Plugin_Info;
/**
 * .
 */
class ShippingExtensions implements Hookable
{
    use HookableParent;
    private const VERSION = 2;
    private const OCTOLIZE_WP_SHIPPING_EXTENSIONS_INITIATED_FILTER = 'octolize/shipping-extensions/initiated';
    /**
     * @var WPDesk_Plugin_Info .
     */
    private $plugin_info;
    /**
     * @var bool
     */
    private $add_plugin_links;
    /**
     * @param WPDesk_Plugin_Info $plugin_info .
     * @param bool               $add_plugin_links .
     */
    public function __construct(WPDesk_Plugin_Info $plugin_info, $add_plugin_links = \false)
    {
        $this->plugin_info = $plugin_info;
        $this->add_plugin_links = $add_plugin_links;
    }
    /**
     * @return void
     */
    public function hooks(): void
    {
        if ($this->add_plugin_links) {
            $this->add_hookable(new PluginLinks($this->plugin_info));
        }
        if (apply_filters(self::OCTOLIZE_WP_SHIPPING_EXTENSIONS_INITIATED_FILTER, \false) === \false) {
            add_filter(self::OCTOLIZE_WP_SHIPPING_EXTENSIONS_INITIATED_FILTER, '__return_true');
            $tracker = new ViewPageTracker();
            $this->add_hookable(new Page($this->get_assets_url(), $tracker));
            $this->add_hookable(new Assets($this->get_assets_url(), self::VERSION));
            $this->add_hookable(new Tracker($tracker));
            $this->add_hookable(new PageViewTracker($tracker));
            $this->add_hookable(new WooCommerceSuggestions());
        }
        $this->hooks_on_hookable_objects();
    }
    /**
     * @return string
     */
    private function get_assets_url(): string
    {
        return plugin_dir_url(__DIR__ . '/../../../') . 'assets/';
    }
}
