<?php

namespace FSVendor\Octolize\Onboarding\PluginUpgrade;

use FSVendor\Octolize\Onboarding\Field\Html;
use FSVendor\Octolize\Onboarding\Onboarding;
use FSVendor\Octolize\Onboarding\OnboardingButton;
use FSVendor\Octolize\Onboarding\OnboardingOption;
use FSVendor\Octolize\Onboarding\OnboardingShouldShowAlwaysStrategy;
use FSVendor\Octolize\Onboarding\OnboardingShouldShowStrategy;
use FSVendor\Octolize\Onboarding\OnboardingStep;
use FSVendor\Octolize\Onboarding\OnboardingTrackerData;
use FSVendor\WPDesk\Forms\Field;
/**
 * Can create popup with plugin upgrade information.
 */
class PluginUpgradeOnboardingFactory
{
    const PLUGIN_VERSION = 'plugin_version';
    const MINIMAL_VERSION = '0.0.1';
    /**
     * @var string
     */
    private $plugin_name;
    /**
     * @var PluginUpgradeMessage[]
     */
    private $upgrade_messages = [];
    /**
     * @var string
     */
    private $current_plugin_version;
    /**
     * @var string
     */
    private $plugin_file;
    /**
     * @var string
     */
    private $append_tracker_data_to;
    /**
     * @param string $plugin_name
     * @param string $current_plugin_version
     * @param string $plugin_file
     * @param string $append_tracker_data_to
     */
    public function __construct(string $plugin_name, string $current_plugin_version, string $plugin_file, string $append_tracker_data_to = '')
    {
        $this->plugin_name = $plugin_name;
        $this->current_plugin_version = $current_plugin_version;
        $this->plugin_file = $plugin_file;
        $this->append_tracker_data_to = $append_tracker_data_to;
    }
    /**
     * @param PluginUpgradeMessage $upgrade_message
     * @return PluginUpgradeOnboardingFactory
     */
    public function add_upgrade_message(PluginUpgradeMessage $upgrade_message): PluginUpgradeOnboardingFactory
    {
        $this->upgrade_messages[] = $upgrade_message;
        return $this;
    }
    /**
     * @return void
     */
    public function create_onboarding(): void
    {
        $onboarding_id = 'upgrade_' . $this->plugin_file;
        $onboarding_option = new OnboardingOption($onboarding_id);
        $default_plugin_version = $this->plugin_activated_hour_before_or_early() ? self::MINIMAL_VERSION : $this->current_plugin_version;
        $previous_version = $onboarding_option->get_option_value(self::PLUGIN_VERSION, $default_plugin_version);
        $plugin_upgrade_watcher = new PluginUpgradeWatcher($this->plugin_file, $onboarding_option);
        $plugin_upgrade_watcher->hooks();
        $onboarding_ajax = new PluginUpgradeAjax($onboarding_option, $this->current_plugin_version);
        $onboarding_ajax->hooks();
        if ($this->has_onboarding_messages($previous_version, $this->current_plugin_version)) {
            $onboarding_should_display_strategy = $this->prepare_display_strategy();
            $onboarding = new Onboarding($onboarding_id, \true, $onboarding_should_display_strategy, $this->prepare_steps($previous_version, $this->current_plugin_version), $onboarding_ajax, $onboarding_option);
            if (is_admin() && $onboarding_option->update_option(self::PLUGIN_VERSION, $this->current_plugin_version)) {
                $onboarding->hooks();
            }
        } else if ($onboarding_option->get_option_value(self::PLUGIN_VERSION, self::MINIMAL_VERSION) !== $this->current_plugin_version) {
            $onboarding_option->update_option(self::PLUGIN_VERSION, $this->current_plugin_version);
        }
        if ($this->append_tracker_data_to !== '') {
            $tracker = new OnboardingTrackerData($this->append_tracker_data_to, $onboarding_option, 'update_onboarging');
            $tracker->hooks();
        }
    }
    private function plugin_activated_hour_before_or_early(): bool
    {
        $plugin_activation = get_option('plugin_activation_' . $this->plugin_file, current_time('mysql'));
        return strtotime($plugin_activation) < current_time('timestamp') - 3600;
    }
    private function prepare_display_strategy(): OnboardingShouldShowStrategy
    {
        return new OnboardingShouldShowAlwaysStrategy();
    }
    /**
     * @param string $previous_version
     *
     * @return OnboardingStep[]
     */
    private function prepare_steps(string $previous_version, string $current_version): array
    {
        $onboarding_step = new OnboardingStep('step_1', 1, $this->plugin_name, $this->prepare_fields($previous_version, $current_version), $this->prepare_buttons());
        $onboarding_step->set_show(\true)->set_heading(sprintf(__('Thank you for updating %1$s!', 'flexible-shipping'), $this->plugin_name))->set_sub_heading(__('It is really important to keep the plugins up to date. We have implemented some improvements and new functionalities. Find out what has changed:', 'flexible-shipping'));
        return [$onboarding_step];
    }
    /**
     * @param string $previous_version
     *
     * @return Field[]
     */
    private function prepare_fields(string $previous_version, $current_version): array
    {
        $fields = [];
        foreach ($this->upgrade_messages as $upgrade_message) {
            if ($this->is_lower($previous_version, $upgrade_message->get_plugin_version()) && $this->is_grater_or_equal($current_version, $upgrade_message->get_plugin_version())) {
                $fields[] = (new Html())->set_default_value(sprintf('<div class="upgrade_message"><img class="icon" src="%1$s" /><div class="content"><div class="title">%2$s</div><div class="message">%3$s</div><div><a target="_blank" href="%4$s">%5$s</a></div></div>', esc_url($upgrade_message->get_image_url()), $upgrade_message->get_title(), $upgrade_message->get_message(), esc_url($upgrade_message->get_link_url()), $upgrade_message->get_link_text()));
            }
        }
        return $fields;
    }
    /**
     * @return OnboardingButton[]
     */
    private function prepare_buttons(): array
    {
        return [(new OnboardingButton())->set_label(__('I\'m not interested', 'flexible-shipping'))->set_classes(OnboardingButton::BTN_LINK), (new OnboardingButton())->set_label(__('Thanks for letting me know', 'flexible-shipping'))->set_type(OnboardingButton::TYPE_CLOSE)];
    }
    private function has_onboarding_messages($previous_version, $current_version): bool
    {
        foreach ($this->upgrade_messages as $upgrade_message) {
            if ($this->is_lower($previous_version, $upgrade_message->get_plugin_version()) && $this->is_grater_or_equal($current_version, $upgrade_message->get_plugin_version())) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param string $previous_version
     * @param string $current_version
     * @return bool
     */
    public function is_lower(string $previous_version, string $current_version)
    {
        return version_compare($previous_version, $current_version, '<');
    }
    /**
     * @param string $previous_version
     * @param string $current_version
     * @return bool
     */
    public function is_grater_or_equal(string $previous_version, string $current_version)
    {
        return version_compare($previous_version, $current_version, '>=');
    }
}
