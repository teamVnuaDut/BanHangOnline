<?php

namespace FSVendor;

/**
 * @var $plugin_title string
 * @var $plugin_file string
 * @var $plugin_slug string
 * @var $thickbox_id string
 * @var $ajax_action string
 * @var $ajax_nonce string
 * @var $reasons Reason[]
 */
use FSVendor\WPDesk\Tracker\Deactivation\Reason;
if (!\defined('ABSPATH')) {
    exit;
}
$deactivate_button_disabled = \true;
?><div id="<?php 
echo $thickbox_id;
?>" style="display:none;">
	<h2><?php 
echo \sprintf(\__('You are deactivating %s plugin.', 'flexible-shipping'), $plugin_title);
?></h2>
	<div class="wpdesk_tracker_deactivate <?php 
echo $thickbox_id;
?>">
		<div class="body">
			<div class="panel active" data-panel-id="reasons">
				<p><?php 
\_e('Before you proceed, please take 30 seconds to let us know what brought you to this decision. Your answers are anonymous.', 'flexible-shipping');
?></p>
				<ul class="reasons-list">
                    <?php 
foreach ($reasons as $reason) {
    ?>
                    <?php 
    $deactivate_button_disabled = $deactivate_button_disabled && !$reason->isSelected();
    ?>
                    <li class="reason" style="<?php 
    echo \esc_attr($reason->isHidden() ? 'display:none;' : '');
    ?>">
                        <label>
	            	        <span>
	            		        <input type="radio" name="selected-reason" value="<?php 
    echo \esc_attr($reason->getValue());
    ?>" <?php 
    \checked($reason->isSelected());
    ?>>
                            </span>
                            <span style="font-weight: 600; font-size: 14px"><?php 
    echo \esc_html($reason->getLabel());
    ?></span>
                        </label>
                        <?php 
    if ($reason->getDescription()) {
        ?>
                            <div class="description" style="display: none; padding: 5px 10px 10px 25px;"><p style="padding: 0px; margin: 0px; font-size: 14px"><?php 
        echo \wp_kses_post($reason->getDescription());
        ?></p></div>
                        <?php 
    }
    ?>
                        <?php 
    if ($reason->hasAdditionalInfo()) {
        ?>
                            <div class="reason-input" style="display: none; padding: 5px 10px 10px 25px; width: 95%;">
                                <textarea
                                        rows="3"
                                        name="<?php 
        echo \esc_attr($reason->getValue());
        ?>-additional-info"
                                        class="additional-info"
                                        placeholder="<?php 
        echo \esc_attr($reason->getAdditionalInfoPlaceholder());
        ?>"
                                        style="width: 100%;"
                                ></textarea>
                            </div>
                        <?php 
    }
    ?>
                    </li>
                    <?php 
}
?>
				</ul>
			</div>
            <div class="footer" style="padding-top: 15px;">
                <div style="float:left;">
                    <a href="#" class="button-skip-and-deactivate"><?php 
\_e('Skip &amp; Deactivate', 'flexible-shipping');
?></a>
                </div>
                <div style="float:right;">
                    <button href="#" class="button button-primary button-deactivate allow-deactivate" <?php 
\disabled($deactivate_button_disabled);
?>><?php 
\_e('Submit &amp; Deactivate', 'flexible-shipping');
?></button>
                    <button href="#" class="button button-secondary button-close tracker-button-close"><?php 
\_e('Cancel', 'flexible-shipping');
?></button>
                </div>
                <div style="clear:both;"></div>
            </div>
		</div>
	</div>
</div>
<?php 
