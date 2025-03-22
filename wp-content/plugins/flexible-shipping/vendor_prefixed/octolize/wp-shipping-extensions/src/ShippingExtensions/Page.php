<?php

namespace FSVendor\Octolize\ShippingExtensions;

use FSVendor\Octolize\ShippingExtensions\Plugin\Plugin;
use FSVendor\Octolize\ShippingExtensions\Plugin\PluginFactory;
use FSVendor\Octolize\ShippingExtensions\Plugin\PluginSorter;
use FSVendor\Octolize\ShippingExtensions\Tracker\ViewPageTracker;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * .
 */
class Page implements Hookable
{
    private const PARENT_SLUG = 'woocommerce';
    public const MENU_SLUG = 'octolize-shipping-extensions';
    public const SCREEN_ID = 'woocommerce_page_' . self::MENU_SLUG;
    /**
     * @var string
     */
    private $assets_url;
    /**
     * @var ViewPageTracker
     */
    private $view_page_tracker;
    /**
     * @param string $assets_url .
     * @param ViewPageTracker $view_page_tracker .
     */
    public function __construct(string $assets_url, ViewPageTracker $view_page_tracker)
    {
        $this->assets_url = $assets_url;
        $this->view_page_tracker = $view_page_tracker;
    }
    /**
     * @return void
     */
    public function hooks(): void
    {
        add_action('admin_menu', [$this, 'add_page'], 100);
    }
    /**
     * @return void
     */
    public function add_page(): void
    {
        add_submenu_page(self::PARENT_SLUG, _x('Shipping Extensions', 'Page title', 'flexible-shipping'), $this->get_menu_title(), 'manage_options', self::MENU_SLUG, [$this, 'render_page']);
    }
    /**
     * @return void
     */
    public function render_page(): void
    {
        wp_enqueue_style(Assets::HANDLE);
        $assets_url = $this->assets_url;
        $plugins = $this->get_plugins();
        $categories = $this->get_categories();
        $header_promo = $this->get_header_promo();
        require_once __DIR__ . '/views/html-shipping-extensions-page.php';
    }
    /**
     * @return string
     */
    private function get_menu_title(): string
    {
        $menu_title = nl2br(_x("Shipping\nExtensions", 'Menu Title', 'flexible-shipping'));
        if ($this->should_add_badge()) {
            $menu_title .= ' <span class="update-plugins"><span class="update-count">1</span></span>';
        }
        return $menu_title;
    }
    /**
     * @return bool
     */
    private function should_add_badge(): bool
    {
        return !$this->view_page_tracker->option_exists();
    }
    /**
     * @return Plugin[]
     */
    private function get_plugins(): array
    {
        $plugins = PluginFactory::get_plugins();
        list($plugins_priority, $categories_priority) = $this->get_plugins_and_categorites_priority();
        $sorter = new PluginSorter($plugins, $plugins_priority, $categories_priority);
        return array_values($sorter->sort());
    }
    /**
     * @return array
     */
    private function get_plugins_and_categorites_priority(): array
    {
        $active_plugins = get_option('active_plugins');
        $categories = PluginFactory::get_categories();
        $plugins_priority = [];
        $categories_priority = [];
        if (in_array('flexible-shipping/flexible-shipping.php', $active_plugins, \true)) {
            $plugins_priority[] = 'flexible-shipping-pro/flexible-shipping-pro.php';
            $plugins_priority[] = 'fs-bundle';
            $categories_priority[] = $categories[PluginFactory::CATEGORY_CUSTOMIZABLE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_SHIPPING_LABELS];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_LIVE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_BUNDLES];
        }
        if (in_array('flexible-shipping-ups/flexible-shipping-ups.php', $active_plugins, \true)) {
            $plugins_priority[] = 'flexible-shipping-ups-pro/flexible-shipping-ups-pro.php';
            $plugins_priority[] = 'flexible-shipping-ups-labels/flexible-shipping-ups-labels.php';
            $plugins_priority[] = 'ups-bundle';
            $plugins_priority[] = 'flexible-shipping-conditional-methods/flexible-shipping-conditional-methods.php';
            $categories_priority[] = $categories[PluginFactory::CATEGORY_LIVE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_CUSTOMIZABLE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_SHIPPING_LABELS];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_BUNDLES];
        }
        if (in_array('flexible-shipping-fedex/flexible-shipping-fedex.php', $active_plugins, \true)) {
            $plugins_priority[] = 'flexible-shipping-fedex-pro/flexible-shipping-fedex-pro.php';
            $plugins_priority[] = 'flexible-shipping-conditional-methods/flexible-shipping-conditional-methods.php';
            $categories_priority[] = $categories[PluginFactory::CATEGORY_LIVE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_CUSTOMIZABLE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_SHIPPING_LABELS];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_BUNDLES];
        }
        if (in_array('flexible-shipping-dhl-express/flexible-shipping-dhl-express.php', $active_plugins, \true)) {
            $plugins_priority[] = 'flexible-shipping-dhl-express-pro/flexible-shipping-dhl-express-pro.php';
            $plugins_priority[] = 'flexible-shipping-conditional-methods/flexible-shipping-conditional-methods.php';
            $categories_priority[] = $categories[PluginFactory::CATEGORY_LIVE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_CUSTOMIZABLE_RATES];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_SHIPPING_LABELS];
            $categories_priority[] = $categories[PluginFactory::CATEGORY_BUNDLES];
        }
        return [$plugins_priority, $categories_priority];
    }
    /**
     * @return array
     */
    private function get_categories(): array
    {
        return array_values(PluginFactory::get_categories());
    }
    private function get_header_promo(): array
    {
        $promo = [];
        if (current_time('timestamp') < strtotime('2025-04-11')) {
            $promo['new10'] = '<span><strong>Get an Extra 10% OFF your First Order!</strong> New customers only | Valid until April 10</span><span class="oct-code">NEW10 <span class="oct-copy-to-clipboard" data-value="NEW10">Copy code</span></span>';
        }
        return array_values(apply_filters('octolize/shipping-extensions/header-promo', $promo));
    }
}
