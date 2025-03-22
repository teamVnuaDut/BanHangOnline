<?php

namespace FSVendor\WPDesk\Tracker;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FSVendor\WPDesk\View\Resolver\DirResolver;
class OptInPage implements Hookable
{
    /**
     * @var string
     */
    private $plugin_file;
    /**
     * @var string
     */
    private $plugin_slug;
    /** @var Shop|null */
    private $shop;
    /**
     * @param string $plugin_file
     * @param string $plugin_slug
     * @param Shop|null $shop_url
     */
    public function __construct($plugin_file, $plugin_slug, $shop = null)
    {
        $this->plugin_file = $plugin_file;
        $this->plugin_slug = $plugin_slug;
        $this->shop = $shop ?? new Shop('');
    }
    public function hooks()
    {
        add_action('admin_menu', [$this, 'add_submenu_page']);
        add_action('admin_init', [$this, 'admin_init']);
    }
    public function add_submenu_page()
    {
        add_submenu_page('', 'WP Desk Tracker', 'WP Desk Tracker', 'manage_options', 'wpdesk_tracker_' . $this->plugin_slug, [$this, 'output']);
    }
    /** @return void */
    public function output()
    {
        $user = wp_get_current_user();
        $username = $user->first_name ? $user->first_name : $user->user_login;
        $allow_url = admin_url('admin.php?wpdesk_tracker=' . $this->plugin_slug);
        $allow_url = add_query_arg('security', wp_create_nonce($this->plugin_slug), $allow_url);
        $allow_url = add_query_arg('ctx', 'box', $allow_url);
        $allow_url = add_query_arg('plugin', $this->plugin_slug, $allow_url);
        $skip_url = $allow_url;
        $allow_url = add_query_arg('allow', '1', $allow_url);
        $skip_url = add_query_arg('allow', '0', $skip_url);
        if (isset($_GET['shop_url'])) {
            $shop = new Shop(sanitize_text_field(wp_unslash($_GET['shop_url'])));
        } else {
            $shop = $this->shop;
        }
        $terms_url = $shop->get_usage_tracking_page();
        $logo = $shop->get_shop_logo_file();
        $logo_url = plugin_dir_url(__FILE__) . '../../../assets/images/' . $logo;
        $renderer = new SimplePhpRenderer(new DirResolver(__DIR__ . '/views'));
        $renderer->output_render('tracker-connect', ['logo_url' => apply_filters('wpdesk/tracker/logo_url', $logo_url, $this->plugin_slug), 'shop_name' => $shop->get_shop_name(), 'username' => $username, 'allow_url' => $allow_url, 'skip_url' => $skip_url, 'terms_url' => $terms_url]);
    }
    /**
     * @deprecated Use OptInPage::output()
     */
    public function wpdesk_tracker_page()
    {
        $this->output();
    }
    public function admin_init()
    {
        if (isset($_GET['wpdesk_tracker']) && $_GET['wpdesk_tracker'] === $this->plugin_slug) {
            if (isset($_GET['allow']) && isset($_GET['security']) && wp_verify_nonce($_GET['security'], $this->plugin_slug)) {
                if ($_GET['allow'] === '1') {
                    $persistence = new \FSVendor\WPDesk_Tracker_Persistence_Consent();
                    $persistence->set_active(\true);
                    delete_option('wpdesk_tracker_notice');
                    update_option('wpdesk_tracker_agree', '1');
                }
                if (wp_safe_redirect($this->determine_redirect_point())) {
                    exit;
                }
            }
        }
    }
    /**
     * Quick and dirty way to guess, where user should be redirected after submitting consent.
     */
    private function determine_redirect_point(): string
    {
        $referer = wp_get_referer();
        if ($referer === \false) {
            return admin_url('plugins.php');
        }
        $query = parse_url($referer, \PHP_URL_QUERY) ?? '';
        if (str_contains($query, 'page=wpdesk_tracker')) {
            // If request came from dedicated page, redirect to plugins.
            return admin_url('plugins.php');
        }
        return $referer;
    }
}
