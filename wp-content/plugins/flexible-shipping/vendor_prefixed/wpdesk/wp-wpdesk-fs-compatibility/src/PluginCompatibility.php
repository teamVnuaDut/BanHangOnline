<?php

/**
 * Class Plugin Compatibility
 *
 * @package WPDesk\FS\Compatibility
 */
namespace FSVendor\WPDesk\FS\Compatibility;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Class Plugin Compatibility
 */
class PluginCompatibility implements Hookable
{
    /**
     * PluginCompatibility constructor.
     */
    public function hooks()
    {
        add_action('plugins_loaded', array($this, 'init_plugin_checker'));
    }
    /**
     * Init plugin checker.
     */
    public function init_plugin_checker()
    {
        if (!is_admin()) {
            return;
        }
        $plugin_compatibility_checker = new PluginCompatibilityChecker();
        if (!$plugin_compatibility_checker->are_plugins_compatible()) {
            $notice = new Notice($plugin_compatibility_checker);
            $notice->hooks();
            $block_settings = new BlockSettings($plugin_compatibility_checker);
            $block_settings->hooks();
        }
    }
}
