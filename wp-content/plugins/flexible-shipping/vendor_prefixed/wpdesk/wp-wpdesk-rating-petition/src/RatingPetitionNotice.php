<?php

/**
 * Repository rating.
 *
 * @package Flexible Shipping Fedex
 */
namespace FSVendor\WPDesk\RepositoryRating;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can display rating notices based on watcher time time.
 */
class RatingPetitionNotice implements Hookable
{
    const CLOSE_TEMPORARY_NOTICE = 'close-temporary-notice';
    const NOTICES_OFFSET = 1209600;
    // Two weeks in seconds.
    /**
     * First notice start time.
     *
     * @var string
     */
    private $first_notice_start_time;
    /**
     * Second notice start time.
     *
     * @var string
     */
    private $second_notice_start_time;
    /**
     * Unique namespace for id/option generation
     *
     * @var string
     */
    private $namespace;
    /**
     * Plugin name in notice content.
     *
     * @var string
     */
    private $plugin_name;
    /**
     * Url to redirect when user wants to send a rate.
     *
     * @var string
     */
    private $rate_url;
    /**
     * RatingPetitionNotice constructor.
     *
     * @param TimeWatcher $method_watcher Watcher to decide when should first notice be displayed.
     * @param string      $namespace Unique namespace for id/option generation.
     * @param string      $plugin_name Plugin name in notice content.
     * @param string      $rate_url Url to redirect when user wants to send a rate.
     */
    public function __construct(TimeWatcher $method_watcher, $namespace, $plugin_name, $rate_url = '')
    {
        $this->namespace = $namespace;
        $this->plugin_name = $plugin_name;
        $this->rate_url = $rate_url;
        $this->second_notice_start_time = get_option($this->prepare_notice_start_time_option_name(), '');
        $this->first_notice_start_time = '';
        if ('' === $this->second_notice_start_time && '' !== $method_watcher->get_creation_time()) {
            $this->first_notice_start_time = gmdate('Y-m-d H:i:s', strtotime($method_watcher->get_creation_time()) + self::NOTICES_OFFSET);
        }
    }
    /**
     * Return option name for second notice timer.
     *
     * @return string
     */
    private function prepare_notice_start_time_option_name()
    {
        return $this->namespace . '_second_notice_time';
    }
    /**
     * Init hooks (actions and filters).
     */
    public function hooks()
    {
        add_action('admin_notices', array($this, 'maybe_show_first_notice'));
        add_action('admin_notices', array($this, 'maybe_show_second_notice'));
        add_action('wpdesk_notice_dismissed_notice', array($this, 'maybe_start_second_notice_on_dismiss_first_notice'), 10, 2);
    }
    /**
     * Maybe reset counter.
     *
     * @param string $notice_name .
     * @param string $source .
     */
    public function maybe_start_second_notice_on_dismiss_first_notice($notice_name, $source = null)
    {
        if ($this->prepare_notice_name(1) === $notice_name && (empty($source) || self::CLOSE_TEMPORARY_NOTICE === $source)) {
            update_option($this->prepare_notice_start_time_option_name(), gmdate('Y-m-d H:i:s', intval(current_time('timestamp')) + self::NOTICES_OFFSET));
        }
    }
    /**
     * Returns notice name for Notice class.
     *
     * @param int $notice_number Notice number as there are two notices.
     *
     * @return string
     */
    private function prepare_notice_name($notice_number)
    {
        return $this->namespace . '_rating_' . $notice_number;
    }
    /**
     * Maybe show first notice.
     */
    public function maybe_show_first_notice()
    {
        if ($this->should_display_notice()) {
            if ('' !== $this->first_notice_start_time && current_time('mysql') > $this->first_notice_start_time) {
                $this->show_notice($this->prepare_notice_name(1));
            }
        }
    }
    /**
     * Should display notice.
     *
     * @return bool
     */
    private function should_display_notice()
    {
        $current_screen = get_current_screen();
        $display_on_screens = ['shop_order', 'edit-shop_order', 'woocommerce_page_wc-settings'];
        if (!empty($current_screen) && in_array($current_screen->id, $display_on_screens, \true)) {
            return \true;
        }
        return \false;
    }
    /**
     * Show notice.
     *
     * @param string $notice_name .
     */
    private function show_notice($notice_name)
    {
        new \FSVendor\WPDesk\Notice\PermanentDismissibleNotice($this->get_notice_content(), $notice_name, \FSVendor\WPDesk\Notice\Notice::NOTICE_TYPE_INFO);
    }
    /**
     * Get notice content.
     *
     * @return string
     */
    private function get_notice_content()
    {
        // Translators: plugin name.
        $content = sprintf(__('Awesome, you\'ve been using %s for more than 2 weeks. Could you please do me a BIG favor and give it a 5-star rating on WordPress? ~ Peter', 'flexible-shipping'), $this->plugin_name);
        $content .= '<br/>';
        $content .= implode(' | ', $this->action_links());
        return $content;
    }
    /**
     * Action links
     *
     * @return array
     */
    protected function action_links()
    {
        $actions[] = sprintf(
            // phpcs:ignore
            // Translators: link.
            __('%1$sOk, you deserved it%2$s', 'flexible-shipping'),
            '<a class="fs-response-deserved" target="_blank" href="' . esc_url($this->rate_url) . '">',
            '</a>'
        );
        $actions[] = sprintf(
            // Translators: link.
            __('%1$sNope, maybe later%2$s', 'flexible-shipping'),
            '<a class="fs-response-close-temporary-notice notice-dismiss-link" data-source="' . self::CLOSE_TEMPORARY_NOTICE . '" href="#">',
            '</a>'
        );
        $actions[] = sprintf(
            // Translators: link.
            __('%1$sI already did%2$s', 'flexible-shipping'),
            '<a class="fs-response-already-did notice-dismiss-link" data-source="already-did" href="#">',
            '</a>'
        );
        return $actions;
    }
    /**
     * Maybe show second notice.
     */
    public function maybe_show_second_notice()
    {
        if ($this->should_display_notice()) {
            if ('' !== $this->second_notice_start_time && current_time('mysql') > $this->second_notice_start_time) {
                $this->show_notice($this->prepare_notice_name(2));
            }
        }
    }
}
