<?php

namespace FSVendor\Octolize\Onboarding\PluginUpgrade\MessageFactory;

use FSVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeMessage;
class LiveRatesFsRulesTable
{
    public function create_message(string $plugin_version, string $plugin_url)
    {
        return new PluginUpgradeMessage($plugin_version, trailingslashit($plugin_url) . 'vendor_prefixed/octolize/wp-onboarding/assets/images/icon-complex-solution.svg', __('We\'ve added integration with Flexible Shipping Table Rate', 'flexible-shipping'), sprintf(
            // Translators: %1$s - <br/><br/>, %2$s - <a href="https://octol.io/fs-tr-adv-live-rates-popup-free" target="_blank">, %3$s - </a>, %4$s - <a href="https://octol.io/fs-tr-adv-live-rates-popup-pro" target="_blank">, %5$s - </a>.
            __('The new feature allows you to combine Live Rates and Table Rate, providing the ability to use automatically calculated Live Rates while also adjusting shipping costs using Table Rate with the free version of %1$sFlexible Shipping%2$s or %3$sFlexible Shipping PRO%4$s.%5$sThis way, you can now have greater control over the final delivery cost, precisely defining additional charges or discounts for each shipping method.', 'flexible-shipping'),
            '<a href="https://octol.io/fs-tr-adv-live-rates-popup-free" target="_blank">',
            '</a>',
            '<a href="https://octol.io/fs-tr-adv-live-rates-popup-pro" target="_blank">',
            '</a>',
            '<br/><br/>'
        ), '', '');
    }
}
