<?php

	$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
	$platform                                = isset( $blossomthemes_email_newsletter_settings['platform'] ) ? $blossomthemes_email_newsletter_settings['platform'] : '';
	$selected_page                           = isset( $blossomthemes_email_newsletter_settings['page'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['page'] ) : '';
?>
	<div class="blossomthemes-email-newsletter-settings-platform">
		<label for="blossomthemes_email_newsletter_settings[platform]"><?php _e( 'Platform : ', 'blossomthemes-email-newsletter' ); ?>
			<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose your newsletter platform.', 'blossomthemes-email-newsletter' ); ?>">
					<i class="far fa-question-circle"></i>
			</span>
		</label>

		<div class="select-holder">
			<select id="platform-select" name="blossomthemes_email_newsletter_settings[platform]">
				<option value="sendinblue" <?php selected( $platform, 'sendinblue' ); ?>><?php _e( 'Brevo (formerly Sendinblue)', 'blossomthemes-email-newsletter' ); ?></option>
				  <option value="mailchimp" <?php selected( $platform, 'mailchimp' ); ?>><?php _e( 'Mailchimp', 'blossomthemes-email-newsletter' ); ?></option>
				  <option value="mailerlite" <?php selected( $platform, 'mailerlite' ); ?>><?php _e( 'Mailerlite', 'blossomthemes-email-newsletter' ); ?></option>
				  <option value="convertkit" <?php selected( $platform, 'convertkit' ); ?>><?php _e( 'ConvertKit', 'blossomthemes-email-newsletter' ); ?></option>
				  <option value="getresponse" <?php selected( $platform, 'getresponse' ); ?>><?php _e( 'GetReponse', 'blossomthemes-email-newsletter' ); ?></option>
				  <option value="activecampaign" <?php selected( $platform, 'activecampaign' ); ?>><?php _e( 'ActiveCampaign', 'blossomthemes-email-newsletter' ); ?></option>
				  <option value="aweber" <?php selected( $platform, 'aweber' ); ?>><?php _e( 'AWeber', 'blossomthemes-email-newsletter' ); ?></option>
			</select>
		</div>
	
	</div>
	<div id="platform-switch-holder">
		<?php
			$platform_settings = new BlossomThemes_Email_Newsletter_Settings();
			echo $platform_settings->bten_platform_settings( $platform );
		?>
	</div>
	<div id="ajax-loader" style="display: none">
		<div class="table">
			<div class="table-row">
				<div class="table-cell">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="51px" height="51px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"> <g transform="rotate(0 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.9166666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(30 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.8333333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(60 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(90 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.6666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(120 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5833333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(150 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(180 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.4166666666666667s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(210 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.3333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(240 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(270 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.16666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(300 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.08333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(330 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animate> </rect> </g> <!-- [ldio] generated by https://loading.io/ --></svg>
				</div>
			</div>
		</div>
	</div>
	
	<div class="bten-color-wrap form-bg-color">
		<div class="form-bg-color">
		<label for="blossomthemes_email_newsletter_settings[appearance][bgcolor]"><?php _e( 'Background Color: ', 'blossomthemes-email-newsletter' ); ?></label>	
		<input type="text" class="blossomthemes-email-newsletter-color-form" id="blossomthemes_email_newsletter_settings[appearance][bgcolor]" name="blossomthemes_email_newsletter_settings[appearance][bgcolor]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['appearance']['bgcolor'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['appearance']['bgcolor'] ) : apply_filters( 'bt_newsletter_bg_color_setting', '#ffffff' ); ?>">
		</div>
	</div>

	<div class="bten-color-wrap font-bg-color">
		<div class="font-bg-color">
		<label for="blossomthemes_email_newsletter_settings[appearance][fontcolor]"><?php _e( 'Font Color: ', 'blossomthemes-email-newsletter' ); ?></label> 
		<input type="text" class="blossomthemes-email-newsletter-color-form" id="blossomthemes_email_newsletter_settings[appearance][fontcolor]" name="blossomthemes_email_newsletter_settings[appearance][fontcolor]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['appearance']['fontcolor'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['appearance']['fontcolor'] ) : apply_filters( 'bt_newsletter_font_color_setting', '#ffffff' ); ?>">
		</div>
	</div>   

	<div class="success-msg-option">
		<label><?php _e( 'Display Successful Subscription Message From:', 'blossomthemes-email-newsletter' ); ?>
			<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Set how to show the confirmation message to the subscribers after successful subscription.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
			</span>
		</label> 
	<?php
		$option = isset( $blossomthemes_email_newsletter_settings['thankyou-option'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['thankyou-option'] ) : 'text';
	?>
		<br>

		<div class="success-msg-option-text">
			<label><?php _e( 'Popup text', 'blossomthemes-email-newsletter' ); ?><input class="newsletter-success-option" type="radio" name="blossomthemes_email_newsletter_settings[thankyou-option]" value="text" 
							 <?php
								if ( $option == 'text' ) {
									echo 'checked';}
								?>
			><span class="check-mark"></span></label>

			<div class="blossomthemes-email-newsletter-settings-wrap message">
				<label for="blossomthemes_email_newsletter_settings[msg]"><?php _e( 'Success Message : ', 'blossomthemes-email-newsletter' ); ?>
					<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Set what message to show when the subscriber is successfully subscribed.', 'blossomthemes-email-newsletter' ); ?>">
						<i class="far fa-question-circle"></i>
					</span>
				</label>
				<textarea name="blossomthemes_email_newsletter_settings[msg]" id="blossomthemes_email_newsletter_settings[msg]"><?php echo isset( $blossomthemes_email_newsletter_settings['msg'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['msg'] ) : 'Successfully subscribed.'; ?></textarea>
			</div>

			<div class="blossomthemes-email-newsletter-settings-wrap message">
				<label for="blossomthemes_email_newsletter_settings[gdpr-msg]"><?php _e( 'GDPR Message : ', 'blossomthemes-email-newsletter' ); ?>
					<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Set GDPR message to show on the subscription form.', 'blossomthemes-email-newsletter' ); ?>">
						<i class="far fa-question-circle"></i>
					</span>
				</label>
				<textarea name="blossomthemes_email_newsletter_settings[gdpr-msg]" id="blossomthemes_email_newsletter_settings[gdpr-msg]"><?php echo isset( $blossomthemes_email_newsletter_settings['gdpr-msg'] ) ? wp_kses_post( $blossomthemes_email_newsletter_settings['gdpr-msg'] ) : 'By checking this, you agree to our Privacy Policy.'; ?></textarea>
					
			</div>
		</div>

		<div class="success-msg-option-page">
			<label><?php _e( 'Page', 'blossomthemes-email-newsletter' ); ?><input class="newsletter-success-option" type="radio" name="blossomthemes_email_newsletter_settings[thankyou-option]" value="page" 
							 <?php
								if ( $option == 'page' ) {
									echo 'checked';}
								?>
			><span class="check-mark"></span></label>
			<div class="blossomthemes-email-newsletter-settings-wrap page">
				<label for="blossomthemes_email_newsletter_settings[page]"><?php _e( 'Thank You Page : ', 'blossomthemes-email-newsletter' ); ?>
					<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Set the page user will be redirected to after successful subscription.', 'blossomthemes-email-newsletter' ); ?>">
						<i class="far fa-question-circle"></i>
					</span>
				</label>
				<div class="select-holder">
					<select name="blossomthemes_email_newsletter_settings[page]"> 
						<option selected="selected" disabled="disabled" value=""><?php esc_attr_e( 'Select page', 'blossomthemes-email-newsletter' ); ?></option> 
						<?php
							$pages = get_pages();
						foreach ( $pages as $page ) {
							$option  = '<option value="' . $page->ID . '" ';
							$option .= ( $page->ID == $selected_page ) ? 'selected="selected"' : '';
							$option .= '>';
							$option .= $page->post_title;
							$option .= '</option>';
							echo $option;
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
