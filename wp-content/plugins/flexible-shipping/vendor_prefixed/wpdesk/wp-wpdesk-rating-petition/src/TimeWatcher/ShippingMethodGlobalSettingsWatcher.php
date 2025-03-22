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
 */
class ShippingMethodGlobalSettingsWatcher implements Hookable, TimeWatcher
{
    const NO_CREATION_TIME = '';
    /**
     * Unique id of shipping method used in WC_Shipping_Method::id.
     *
     * @var string
     */
    private $shipping_method_unique_id;
    /**
     * ShippingMethodGlobalSettingsWatcher constructor.
     *
     * @param string $shipping_method_unique_id Unique id of shipping method used in WC_Shipping_Method::id.
     */
    public function __construct($shipping_method_unique_id)
    {
        $this->shipping_method_unique_id = $shipping_method_unique_id;
    }
    /**
     * Init hooks (actions and filters).
     */
    public function hooks()
    {
        add_action("woocommerce_update_options_shipping_{$this->shipping_method_unique_id}", function () {
            $this->watch_saved_settings();
        });
    }
    /**
     * Watch saved settings and save time when first saved.
     */
    private function watch_saved_settings()
    {
        if (!$this->is_creation_time_saved()) {
            $this->update_creation_time();
        }
    }
    /**
     * Is creation time saved or it's first time?.
     *
     * @return bool
     */
    private function is_creation_time_saved()
    {
        return $this->get_creation_time() !== self::NO_CREATION_TIME;
    }
    /**
     * Set ccreation time to now.
     */
    private function update_creation_time()
    {
        update_option($this->prepare_creation_time_option_name(), current_time('mysql'));
    }
    /**
     * Option name to save time in db.
     *
     * @return string
     */
    private function prepare_creation_time_option_name()
    {
        return $this->shipping_method_unique_id . '_repository_creation_time';
    }
    /**
     * Get first method creation time.
     *
     * @return string
     */
    public function get_creation_time()
    {
        return get_option($this->prepare_creation_time_option_name(), self::NO_CREATION_TIME);
    }
}
