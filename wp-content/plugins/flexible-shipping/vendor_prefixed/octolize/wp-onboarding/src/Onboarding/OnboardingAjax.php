<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can handle onboarding Ajax actions.
 */
class OnboardingAjax implements Hookable
{
    const AJAX_ACTION_CLICK_PREFIX = 'octolize_onboarding_click_';
    const AJAX_ACTION_EVENT_PREFIX = 'octolize_onboarding_event_';
    const AJAX_ACTION_AUTO_SHOP_POPUP_PREFIX = 'octolize_onboarding_auto_show_popup_';
    const AJAX_ACTION_SAVE_FIELDS_PREFIX = 'octolize_onboarding_save_fields_';
    const NONCE_ACTION_PREFIX = 'octolize_onboarding_';
    const OCTOLIZE_ONBOARDING_SAVE_FIELDS_ACTION = 'octolize/onboarding/save_fields/';
    /**
     * @var OnboardingOption
     */
    protected $option;
    /**
     * OptionAjaxUpdater constructor.
     *
     * @param OnboardingOption $option .
     */
    public function __construct(OnboardingOption $option)
    {
        $this->option = $option;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        add_action('wp_ajax_' . $this->prepare_action_click(), [$this, 'handle_ajax_action_click']);
        add_action('wp_ajax_' . $this->prepare_action_event(), [$this, 'handle_ajax_action_event']);
        add_action('wp_ajax_' . $this->prepare_action_auto_show_popup(), [$this, 'handle_ajax_action_auto_show_popup']);
        add_action('wp_ajax_' . $this->prepare_action_save_fields(), [$this, 'handle_ajax_action_save_fields']);
    }
    public function prepare_action_click(): string
    {
        return self::AJAX_ACTION_CLICK_PREFIX . $this->option->get_option_name_suffix();
    }
    public function prepare_action_event(): string
    {
        return self::AJAX_ACTION_EVENT_PREFIX . $this->option->get_option_name_suffix();
    }
    public function prepare_action_auto_show_popup(): string
    {
        return self::AJAX_ACTION_AUTO_SHOP_POPUP_PREFIX . $this->option->get_option_name_suffix();
    }
    public function prepare_action_save_fields(): string
    {
        return self::AJAX_ACTION_SAVE_FIELDS_PREFIX . $this->option->get_option_name_suffix();
    }
    /**
     * Handle AJAX action OK.
     *
     * @internal
     */
    public function handle_ajax_action_event()
    {
        check_ajax_referer($this->prepare_nonce_action());
        $event = $this->filter_input(\INPUT_POST, 'event');
        $step = $this->filter_input(\INPUT_POST, 'step');
        if ($event) {
            $this->option->update_option(OnboardingOption::EVENT, sanitize_text_field($event));
            $this->option->update_option(OnboardingOption::STEP, sanitize_text_field($step));
            $this->option->update_option(OnboardingOption::EVENT_TIME, time());
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
    /**
     * Handle AJAX action Click.
     *
     * @internal
     */
    public function handle_ajax_action_click()
    {
        check_ajax_referer($this->prepare_nonce_action());
        $clicks = (int) $this->option->get_option_value('clicks');
        $step = $this->filter_input(\INPUT_POST, 'step');
        $this->option->update_option('clicks', $clicks + 1);
        $this->option->update_option(OnboardingOption::STEP, sanitize_text_field($step));
        wp_send_json_success();
    }
    /**
     * Handle AJAX action Click.
     *
     * @internal
     */
    public function handle_ajax_action_auto_show_popup()
    {
        check_ajax_referer($this->prepare_nonce_action());
        $page = $this->filter_input(\INPUT_POST, 'page');
        $step = $this->filter_input(\INPUT_POST, 'step');
        $this->option->update_option(OnboardingOption::PAGE, sanitize_text_field($page));
        $this->option->update_option(OnboardingOption::STEP, sanitize_text_field($step));
        $this->option->update_option(OnboardingOption::EVENT_TIME, time());
        $this->option->update_option(OnboardingOption::EVENT, 'auto_show');
        wp_send_json_success();
    }
    public function handle_ajax_action_save_fields()
    {
        check_ajax_referer($this->prepare_nonce_action());
        $data = wp_unslash($_POST);
        $this->option->update_option(OnboardingOption::SAVE_FIELDS, sanitize_text_field($data['popup_id']));
        do_action(self::OCTOLIZE_ONBOARDING_SAVE_FIELDS_ACTION . $this->option->get_option_name_suffix(), $data);
        wp_send_json_success();
    }
    /**
     * @param int    $type     .
     * @param string $var_name .
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    protected function filter_input(int $type, string $var_name)
    {
        return filter_input($type, $var_name);
    }
    public function prepare_nonce_action(): string
    {
        return self::NONCE_ACTION_PREFIX . $this->option->get_option_name_suffix();
    }
}
