<?php

namespace FSVendor\Octolize\Onboarding;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can append onboarding data to deactivation tracker.
 */
class OnboardingDeactivationData implements Hookable
{
    const ADDITIONAL_DATA = 'additional_data';
    /**
     * @var string
     */
    private $plugin_file;
    /**
     * @var OnboardingOption
     */
    private $onboarding_option;
    /**
     * @param string $plugin_file
     * @param OnboardingOption $onboarding_option
     */
    public function __construct(string $plugin_file, OnboardingOption $onboarding_option)
    {
        $this->plugin_file = $plugin_file;
        $this->onboarding_option = $onboarding_option;
    }
    public function hooks()
    {
        add_filter('wpdesk_tracker_deactivation_data', array($this, 'append_onboarding_option_to_data'));
    }
    /**
     * @param array $data
     * @return array
     */
    public function append_onboarding_option_to_data($data)
    {
        if (is_array($data) && isset($data['plugin']) && $this->plugin_file === $data['plugin']) {
            if (empty($data[self::ADDITIONAL_DATA])) {
                $data[self::ADDITIONAL_DATA] = [];
            }
            $data[self::ADDITIONAL_DATA]['octolize_onboarding'] = $this->onboarding_option->get_raw_option_data();
        }
        return $data;
    }
}
