<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

/**
 * Can update option when onboarding is finish.
 */
class OnboardingOption
{
    const OPTION_PREFIX = 'octolize_onboarding_';
    const VIEWS = 'views';
    const STEP = 'step';
    const EVENT = 'event';
    const PAGE = 'page';
    const EVENT_TIME = 'event_time';
    const AUTO_SHOW_POPUP = 'auto_show_popup';
    const SAVE_FIELDS = 'save_fields';
    /**
     * @var string
     */
    private $option_name_suffix;
    /**
     * @var string
     */
    private $option_name_prefix = self::OPTION_PREFIX;
    /**
     * @param string $option_name_suffix
     * @param string $option_name_prefix
     */
    public function __construct(string $option_name_suffix, string $option_name_prefix = self::OPTION_PREFIX)
    {
        $this->option_name_suffix = $option_name_suffix;
        $this->option_name_prefix = $option_name_prefix;
    }
    /**
     * @return string
     */
    public function get_option_name_suffix(): string
    {
        return $this->option_name_suffix;
    }
    /**
     * Get option value.
     *
     * @param string $key     .
     * @param mixed  $default .
     *
     * @return mixed
     */
    public function get_option_value(string $key = '', $default = \false)
    {
        $options = $this->get_options();
        if ($key) {
            return $options[$key] ?? $default;
        }
        return $options;
    }
    /**
     * Checks if option is set.
     *
     * @return bool Option status.
     */
    public function is_option_set(): bool
    {
        return \false !== get_option($this->prepare_option_name(), \false);
    }
    /**
     * @param string $option_key   .
     * @param mixed  $option_value .
     *
     * @return bool
     */
    public function update_option(string $option_key, $option_value): bool
    {
        $options = $this->get_options();
        $options[$option_key] = $option_value;
        return update_option($this->prepare_option_name(), $options);
    }
    public function get_raw_option_data(): array
    {
        return $this->get_options();
    }
    /**
     * @return array
     */
    private function get_options(): array
    {
        $options = get_option($this->prepare_option_name(), []);
        if (!is_array($options)) {
            $options = [];
        }
        return wp_parse_args($options, $this->get_default_option_values());
    }
    /**
     * @return array
     */
    private function get_default_option_values(): array
    {
        return [self::AUTO_SHOW_POPUP => 0, self::VIEWS => 0, self::STEP => 'step_0', self::EVENT => '', self::PAGE => '', self::EVENT_TIME => 0, self::SAVE_FIELDS => ''];
    }
    private function prepare_option_name(): string
    {
        return $this->option_name_prefix . $this->option_name_suffix;
    }
}
