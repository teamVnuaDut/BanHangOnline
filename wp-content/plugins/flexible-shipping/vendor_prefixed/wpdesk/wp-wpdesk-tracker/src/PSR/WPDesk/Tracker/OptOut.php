<?php

namespace FSVendor\WPDesk\Tracker;

use FSVendor\WPDesk\Notice\Notice;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class OptOut implements Hookable
{
    /**
     * @var string
     */
    private $plugin_slug;
    /**
     * @var string
     */
    private $plugin_name;
    /**
     * @param string $plugin_slug
     * @param string $plugin_name
     */
    public function __construct($plugin_slug, $plugin_name)
    {
        $this->plugin_slug = $plugin_slug;
        $this->plugin_name = $plugin_name;
    }
    public function hooks()
    {
        add_action('admin_notices', [$this, 'handle_opt_out']);
    }
    /**
     * @internal
     */
    public function handle_opt_out()
    {
        $screen = get_current_screen();
        if ('plugins' === $screen->id) {
            if (isset($_GET['wpdesk_tracker_opt_out_' . $this->plugin_slug]) && isset($_GET['security']) && wp_verify_nonce($_GET['security'], $this->plugin_slug)) {
                $persistence = new \FSVendor\WPDesk_Tracker_Persistence_Consent();
                $persistence->set_active(\false);
                delete_option('wpdesk_tracker_notice');
                new Notice(sprintf(esc_html__('You successfully opted out of collecting usage data by %1$s. If you change your mind, you can always opt in later in the plugin\'s quick links.', 'flexible-shipping'), esc_html($this->plugin_name)));
            }
        }
    }
}
