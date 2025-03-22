<?php

namespace FSVendor\Octolize\Onboarding\PluginUpgrade;

use FSVendor\Octolize\Onboarding\OnboardingAjax;
use FSVendor\Octolize\Onboarding\OnboardingOption;
/**
 * Can handle plugin upgrade onboarding Ajax actions.
 */
class PluginUpgradeAjax extends OnboardingAjax
{
    /**
     * @var string
     */
    private $plugin_version;
    /**
     * @param string $plugin_version
     */
    public function __construct(OnboardingOption $onboarding_option, string $plugin_version)
    {
        parent::__construct($onboarding_option);
        $this->plugin_version = $plugin_version;
    }
    public function handle_ajax_action_auto_show_popup()
    {
        $this->option->update_option('plugin_version', $this->plugin_version);
        parent::handle_ajax_action_auto_show_popup();
    }
}
