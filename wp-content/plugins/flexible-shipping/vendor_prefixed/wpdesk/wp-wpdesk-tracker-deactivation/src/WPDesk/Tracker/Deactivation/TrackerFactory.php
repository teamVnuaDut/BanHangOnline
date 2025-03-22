<?php

namespace FSVendor\WPDesk\Tracker\Deactivation;

/**
 * Can create tracker.
 */
class TrackerFactory
{
    /**
     * Create default tracker.
     *
     * @param string $plugin_slug .
     * @param string $plugin_file .
     * @param string $plugin_title .
     *
     * @return Tracker
     */
    public static function createDefaultTracker($plugin_slug, $plugin_file, $plugin_title)
    {
        $plugin_data = new PluginData($plugin_slug, $plugin_file, $plugin_title);
        return self::createCustomTracker($plugin_data);
    }
    /**
     * Create default tracker.
     *
     * @param PluginData $plugin_data .
     *
     * @return Tracker
     */
    public static function createDefaultTrackerFromPluginData(PluginData $plugin_data)
    {
        return self::createCustomTracker($plugin_data);
    }
    /**
     * Create custom tracker.
     *
     * @param PluginData $plugin_data .
     * @param Scripts|null $scripts .
     * @param Thickbox|null $thickbox .
     * @param AjaxDeactivationDataHandler|null $ajax
     *
     * @return Tracker
     */
    public static function createCustomTracker(PluginData $plugin_data, $scripts = null, $thickbox = null, $ajax = null, ReasonsFactory $reasons_factory = null)
    {
        $reasons_factory = $reasons_factory ?? new DefaultReasonsFactory();
        if (empty($scripts)) {
            $scripts = new Scripts($plugin_data, $reasons_factory);
        }
        if (empty($thickbox)) {
            $thickbox = new Thickbox($plugin_data, $reasons_factory);
        }
        if (empty($ajax)) {
            $sender = apply_filters('wpdesk/tracker/sender/' . $plugin_data->getPluginSlug(), new \FSVendor\WPDesk_Tracker_Sender_Wordpress_To_WPDesk());
            $sender = new \FSVendor\WPDesk_Tracker_Sender_Logged($sender instanceof \WPDesk_Tracker_Sender ? $sender : new \FSVendor\WPDesk_Tracker_Sender_Wordpress_To_WPDesk());
            $sender = new \FSVendor\WPDesk_Tracker_Sender_Logged($sender);
            $ajax = new AjaxDeactivationDataHandler($plugin_data, $sender);
        }
        return new Tracker($plugin_data, $scripts, $thickbox, $ajax);
    }
}
