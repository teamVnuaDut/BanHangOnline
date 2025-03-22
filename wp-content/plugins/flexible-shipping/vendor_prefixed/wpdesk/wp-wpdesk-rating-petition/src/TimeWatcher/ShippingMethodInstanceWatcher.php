<?php

/**
 * Shipping method watcher.
 *
 * @package Flexible Shipping Fedex
 */
namespace FSVendor\WPDesk\RepositoryRating\TimeWatcher;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\RepositoryRating\TimeWatcher;
/**
 * Can watch shipping method creation.
 *
 * @todo This class is not tested yet. Should be used in UPS.
 */
class ShippingMethodInstanceWatcher implements Hookable, TimeWatcher
{
    /**
     * First method added time.
     *
     * @var string
     */
    private $first_method_creation_time = '';
    /**
     * First method watching.
     *
     * @var int
     */
    private $first_method_watching = 0;
    /**
     * Unique namespace for id/option generation
     *
     * @var string
     */
    private $namespace;
    /**
     * Name of shipping method ::class
     *
     * @var string
     */
    private $shipping_method_name;
    /**
     * Name of WP option with plugin activation time
     *
     * @var string
     */
    private $plugin_activation_time_option_name;
    /**
     * Date in string when functionality started and we can start watching
     *
     * @var string
     */
    private $zero_date;
    /**
     * ShippingMethodInstanceWatcher constructor.
     *
     * @param string $namespace Unique namespace for id/option generation.
     * @param string $plugin_activation_time_option_name Where in options is plugin activation time.
     * @param string $zero_date When functionality started and we can start watching.
     * @param string $shipping_method_name What method name is watched.
     */
    public function __construct($namespace, $plugin_activation_time_option_name, $zero_date, $shipping_method_name)
    {
        $this->namespace = $namespace;
        $this->plugin_activation_time_option_name = $plugin_activation_time_option_name;
        $this->zero_date = $zero_date;
        $this->shipping_method_name = $shipping_method_name;
    }
    /**
     * Init hooks (actions and filters).
     */
    public function hooks()
    {
        add_action('admin_init', array($this, 'maybe_init_watching'), 10, 3);
        add_action('woocommerce_shipping_zone_method_added', array($this, 'watch_added_shipping_method'), 10, 3);
    }
    /**
     * Init watching.
     */
    public function maybe_init_watching()
    {
        $this->first_method_watching = intval(get_option($this->prepare_option_name_watching(), 0));
        if (0 === $this->first_method_watching) {
            $ups_free_activation_time = get_option($this->plugin_activation_time_option_name, current_time('mysql'));
            if (strtotime($ups_free_activation_time) < strtotime($this->zero_date)) {
                $this->init_watching_from_existing_shipping_methods();
            }
            update_option($this->prepare_option_name_watching(), 1);
            $this->first_method_watching = 1;
        }
    }
    /**
     * Returns options name for no idea what.
     *
     * @return string
     */
    private function prepare_option_name_watching()
    {
        return $this->namespace . '_method_watching';
    }
    /**
     * First time init watching.
     * Sets first time to current time when Fedex shipping method already exists.
     */
    private function init_watching_from_existing_shipping_methods()
    {
        $shipping_zones = \WC_Shipping_Zones::get_zones();
        /** @var \WC_Shipping_Zone $shipping_zone */
        // phpcs:ignore
        foreach ($shipping_zones as $shipping_zone_data) {
            $shipping_zone = \WC_Shipping_Zones::get_zone($shipping_zone_data['id']);
            if ($shipping_zone && $shipping_zone instanceof \WC_Shipping_Zone) {
                $shipping_methods = $shipping_zone->get_shipping_methods();
                foreach ($shipping_methods as $shipping_method) {
                    if ($shipping_method instanceof $this->shipping_method_name) {
                        update_option($this->prepare_option_name_method_created(), current_time('mysql'));
                    }
                }
            }
        }
    }
    /**
     * Returns options name for info when shipping method was created.
     *
     * @return string
     */
    private function prepare_option_name_method_created()
    {
        return $this->namespace . '_method_created';
    }
    /**
     * Watch added shipping method.
     * Set first time to current time when first UPS shipping method created.
     *
     * @param int    $instance_id .
     * @param string $type .
     * @param int    $zone_id .
     */
    public function watch_added_shipping_method($instance_id, $type, $zone_id)
    {
        $this->first_method_creation_time = get_option($this->prepare_option_name_method_created(), '');
        if ($this->namespace === $type) {
            if ('' === $this->first_method_creation_time) {
                $this->first_method_creation_time = (string) current_time('mysql');
                update_option($this->prepare_option_name_method_created(), $this->first_method_creation_time);
            }
        }
    }
    /**
     * Get first method creation time.
     *
     * @return string
     */
    public function get_creation_time()
    {
        $this->first_method_creation_time = get_option($this->prepare_option_name_method_created(), '');
        return $this->first_method_creation_time;
    }
    /**
     * Returns date when functionality started and we can start watching.
     *
     * @return string
     */
    private function get_watcher_zero_date()
    {
        return $this->zero_date;
    }
}
