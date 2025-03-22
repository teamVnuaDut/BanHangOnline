<?php

/**
 * @package Octolize\Onboarding
 */
namespace FSVendor\Octolize\Onboarding;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Can append onboarding data to tracker.
 */
class OnboardingTrackerData implements Hookable
{
    const PRIORITY = 1000;
    /**
     * @var string
     */
    private $append_data_to;
    /**
     * @var OnboardingOption
     */
    private $onboarding_option;
    /**
     * @var string
     */
    private $append_as;
    /**
     * @param string $append_data_to
     */
    public function __construct(string $append_data_to, OnboardingOption $onboarding_option, $append_as = 'octolize_onboarding')
    {
        $this->append_data_to = $append_data_to;
        $this->onboarding_option = $onboarding_option;
        $this->append_as = $append_as;
    }
    public function hooks()
    {
        add_filter('wpdesk_tracker_data', [$this, 'append_data_to_tracker'], self::PRIORITY);
    }
    public function append_data_to_tracker($data)
    {
        if (empty($data[$this->append_data_to])) {
            $data[$this->append_data_to] = [];
        }
        $data[$this->append_data_to][$this->append_as] = $this->onboarding_option->get_raw_option_data();
        return $data;
    }
}
