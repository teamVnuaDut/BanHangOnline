<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can display onboarding.
 */
class Onboarding implements Hookable
{
    const VERSION = 2;
    const OCTOLIZE_WP_ONBOARDING_RENDERED_FILTER = 'octolize/wp-onboarding/rendered';
    const OCTOLIZE_WP_ONBOARDING_ALLOWED = 'octolize/wp-onboarding/allowed';
    /**
     * @var OnboardingAjax
     */
    private $tracker_ajax;
    /**
     * @var bool
     */
    private $autostart = \true;
    /**
     * @var OnboardingShouldShowGetParametersStrategy
     */
    private $onboarding_should_show_strategy;
    /**
     * @var OnboardingOption
     */
    private $onboarding_option;
    /**
     * @var OnboardingStep[]
     */
    private $steps = [];
    /**
     * @var bool
     */
    private $already_rendered = \false;
    /**
     * @var string
     */
    private $onboarding_id;
    /**
     * @param string $onboarding_id
     * @param bool $autostart
     * @param OnboardingShouldShowStrategy $onboarding_should_show_strategy
     * @param OnboardingStep[] $steps
     * @param OnboardingAjax $tracker_ajax
     * @param OnboardingOption $onboarding_option
     */
    public function __construct(string $onboarding_id, bool $autostart, OnboardingShouldShowStrategy $onboarding_should_show_strategy, array $steps, OnboardingAjax $tracker_ajax, OnboardingOption $onboarding_option)
    {
        $this->onboarding_id = $onboarding_id;
        $this->autostart = $autostart;
        $this->onboarding_should_show_strategy = $onboarding_should_show_strategy;
        $this->steps = $steps;
        $this->tracker_ajax = $tracker_ajax;
        $this->onboarding_option = $onboarding_option;
    }
    public function hooks()
    {
        add_action('current_screen', [$this, 'init_onboarding']);
        $this->tracker_ajax->hooks();
    }
    public function init_onboarding()
    {
        if ($this->onboarding_should_show_strategy->should_display()) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts_and_styles']);
            add_action('admin_footer', [$this, 'render_onboarding']);
        }
        add_filter(self::OCTOLIZE_WP_ONBOARDING_RENDERED_FILTER, [$this, 'is_or_already_rendered']);
    }
    /**
     * @param bool $already_rendered
     *
     * @return bool
     */
    public function is_or_already_rendered(bool $already_rendered): bool
    {
        return $already_rendered || $this->already_rendered;
    }
    public function enqueue_scripts_and_styles()
    {
        $handle = 'octolize-onboarding-' . self::VERSION;
        wp_register_style($handle, plugin_dir_url(__FILE__) . '../../assets/css/onboarding.css', [], self::VERSION);
        wp_enqueue_style($handle);
        wp_register_script($handle, plugin_dir_url(__FILE__) . '../../assets/js/onboarding.js', [], self::VERSION);
        wp_enqueue_script($handle);
    }
    public function render_onboarding()
    {
        $current_screen = get_current_screen();
        if (!$this->other_onboarding_already_rendered() && $this->is_onboarding_allowed()) {
            $settings = ['open_auto' => $this->autostart, 'logo_img' => plugins_url('/', dirname(__FILE__)) . '/../../assets/images/Octolize_logo_RGB_navy.svg', 'page' => $current_screen->base, 'ajax' => ['url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce($this->tracker_ajax->prepare_nonce_action()), 'action' => ['event' => $this->tracker_ajax->prepare_action_event(), 'click' => $this->tracker_ajax->prepare_action_click(), 'auto_show_popup' => $this->tracker_ajax->prepare_action_auto_show_popup(), 'save_fields' => $this->tracker_ajax->prepare_action_save_fields()]], 'popups' => $this->steps, 'steps' => count($this->steps)];
            include __DIR__ . '/views/html-onboarding-container.php';
            $this->already_rendered = \true;
            $this->update_option_render($current_screen->base);
        }
    }
    private function update_option_render(string $page): void
    {
        if ($this->autostart) {
            $this->onboarding_option->update_option(OnboardingOption::AUTO_SHOW_POPUP, 1);
            $this->onboarding_option->update_option(OnboardingOption::VIEWS, (int) $this->onboarding_option->get_option_value(OnboardingOption::VIEWS, 0) + 1);
        }
        $this->onboarding_option->update_option(OnboardingOption::PAGE, sanitize_text_field($page));
        $this->onboarding_option->update_option(OnboardingOption::EVENT_TIME, time());
        $this->onboarding_option->update_option(OnboardingOption::EVENT, 'render');
    }
    /**
     * @return bool
     */
    private function other_onboarding_already_rendered(): bool
    {
        return apply_filters(self::OCTOLIZE_WP_ONBOARDING_RENDERED_FILTER, \false);
    }
    /**
     * @return bool
     */
    protected function is_onboarding_allowed(): bool
    {
        return apply_filters(self::OCTOLIZE_WP_ONBOARDING_ALLOWED, apply_filters(self::OCTOLIZE_WP_ONBOARDING_ALLOWED . '/' . $this->onboarding_id, \true));
    }
}
