<?php

namespace FSVendor;

/**
 * Onboarding container.
 *
 * @package Octolize\Onboarding
 */
/**
 * Params.
 *
 * @var $settings array .
 */
$container_class = 'onboarding-container-' . \FSVendor\Octolize\Onboarding\Onboarding::VERSION;
?><div
    class="<?php 
echo \esc_attr($container_class);
?>"
    onboarding_settings="<?php 
echo \esc_attr(\json_encode($settings, \JSON_UNESCAPED_UNICODE | \JSON_HEX_QUOT));
?>"
></div>
<?php 
