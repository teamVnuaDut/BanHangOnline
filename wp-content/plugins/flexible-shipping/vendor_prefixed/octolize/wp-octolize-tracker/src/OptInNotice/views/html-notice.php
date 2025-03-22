<?php

namespace FSVendor;

/**
 * @var string $username
 * @var string $terms_url
 * @var string $plugin_slug
 */
?>
		<strong><?php 
echo \esc_html(\__('Help us improve Octolize plugins\' experience', 'flexible-shipping'));
?></strong><br/>
        <?php 
echo \wp_kses_post(\sprintf(\__('Hi %1$s, with your helping hand we can build effective solutions, launch the new features and shape better plugins experience. By agreeing to anonymously share non-sensitive %2$susage data%3$s of our plugins, you will help us develop them in the right direction. No personal data is tracked or stored and you can opt-out any time. Will you give the thumbs up to our efforts?', 'flexible-shipping'), $username, '<a href="' . \esc_url($terms_url) . '" target="_blank">', '</a>'));
?><br/>
    </p>
    <p>
        <button id="wpdesk_tracker_allow_button_notice-<?php 
echo \esc_attr($plugin_slug);
?>" class="button button-primary"><?php 
\esc_html_e('Allow', 'flexible-shipping');
?></button>

		<script type="text/javascript">
			jQuery(document).on('click', '#wpdesk_tracker_allow_button_notice-<?php 
echo \esc_attr($plugin_slug);
?>',function(e){
				e.preventDefault();
				jQuery.ajax( '<?php 
echo \admin_url('admin-ajax.php');
?>',
					{
						type: 'POST',
						data: {
							security: '<?php 
echo \wp_create_nonce(WPDesk_Tracker::WPDESK_TRACKER_NOTICE);
?>',
							action: 'wpdesk_tracker_notice_handler',
							type: 'allow',
						}
					}
				);
				jQuery('#wpdesk-notice-octolize_opt_in_<?php 
echo \esc_attr($plugin_slug);
?>').toggle( false );
			});
		</script>
<?php 
