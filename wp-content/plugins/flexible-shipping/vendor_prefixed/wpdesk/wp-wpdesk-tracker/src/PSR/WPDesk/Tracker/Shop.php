<?php

namespace FSVendor\WPDesk\Tracker;

/**
 * Provides shop data.
 */
class Shop
{
    /**
     * @var string
     */
    private $default_shop = 'wpdesk.pl';
    /**
     * @var string
     */
    private $default_shop_name = 'WP Desk';
    /**
     * @var string
     */
    private $default_logo = 'logo.png';
    /**
     * @var array<string, string>
     */
    private $shops_usage_tracking_pages = ['wpdesk.pl' => 'https://www.wpdesk.pl/dane-uzytkowania/', 'wpdesk.net' => 'https://www.wpdesk.net/usage-tracking/', 'octolize.com' => 'https://octolize.com/usage-tracking/', 'shopmagic.app' => 'https://www.shopmagic.app/usage-tracking/', 'flexibleinvoices.com' => 'https://www.flexibleinvoices.com/usage-tracking/'];
    /**
     * @var array<string, string>
     */
    private $shops_usage_tracking_names = ['octolize.com' => 'Octolize'];
    /**
     * @var string
     */
    private $shop;
    /**
     * @param string $shop_url
     */
    public function __construct($shop_url)
    {
        $this->shop = $this->prepare_shop_from_shop_url($shop_url);
    }
    /**
     * @return string
     */
    public function get_usage_tracking_page()
    {
        $usage_tracking_page = isset($this->shops_usage_tracking_pages[$this->shop]) ? $this->shops_usage_tracking_pages[$this->shop] : $this->shops_usage_tracking_pages[$this->default_shop];
        return apply_filters('wpdesk/tracker/usage_tracking_page', $usage_tracking_page, $this->shop);
    }
    /**
     * @return string
     */
    public function get_shop_name()
    {
        return apply_filters('wpdesk/tracker/shop_name', $this->shops_usage_tracking_names[$this->shop] ?? $this->default_shop_name, $this->shop);
    }
    /**
     * @return string
     */
    public function get_shop_logo_file()
    {
        $logo_file = isset($this->shops_usage_tracking_pages[$this->shop]) ? $this->shop : $this->default_shop;
        $logo_file .= '.png';
        $logo_file = apply_filters('wpdesk/tracker/logo_file', $logo_file, $this->shop);
        // Look for our assets folder from package root directory.
        if (!file_exists(dirname(__DIR__, 3) . '/assets/images/' . $logo_file)) {
            $logo_file = $this->default_logo;
        }
        return $logo_file;
    }
    /**
     * @param string $shop_url
     */
    private function prepare_shop_from_shop_url($shop_url)
    {
        $host = parse_url($shop_url, \PHP_URL_HOST);
        return str_replace('www.', '', $host ?? '');
    }
}
