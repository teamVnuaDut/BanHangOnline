<?php
global $post;
$blossomthemes_email_newsletter_setting = get_post_meta( $post->ID, 'blossomthemes_email_newsletter_setting', true );
$val = isset($blossomthemes_email_newsletter_setting['field']['select']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['select']):'email';
$gdpr = isset( $blossomthemes_email_newsletter_setting['appearance']['gdpr'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['appearance']['gdpr'] ):'';
?>
<div class="newsletter-info-meta">
	<div class="newsletter-info-meta-wrap radio-check">
		<label for="blossomthemes_email_newsletter_setting[field][uname-email]"><?php _e('Name and Email: ','blossomthemes-email-newsletter');?>
			<input class="newsletter-field-option" type="radio" id="blossomthemes_email_newsletter_setting[field][uname-email]" name="blossomthemes_email_newsletter_setting[field][select]" value="uname-email" <?php if( $val == 'uname-email' ){echo "checked";}?>>
			<span class="check-mark"></span>
		</label>
	</div>
	<div class="newsletter-info-meta-wrap radio-check">
		<label for="blossomthemes_email_newsletter_setting[field][email]"><?php _e('Email: ','blossomthemes-email-newsletter');?>
			<input class="newsletter-field-option" type="radio" id="blossomthemes_email_newsletter_setting[field][email]" name="blossomthemes_email_newsletter_setting[field][select]" value="email" <?php if( $val == 'email' ){echo "checked";}?>>
			<span class="check-mark"></span>
		</label>
	</div>
	<div class="newsletter-info-meta-wrap name">
		<label for="blossomthemes_email_newsletter_setting[field][first_name_placeholder]"><?php _e('Name Placeholder: ','blossomthemes-email-newsletter');?></label>
		<input type="text" id="blossomthemes_email_newsletter_setting[field][first_name_placeholder]" name="blossomthemes_email_newsletter_setting[field][first_name_placeholder]" value="<?php echo isset($blossomthemes_email_newsletter_setting['field']['first_name_placeholder']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['first_name_placeholder']):'Your First Name';?>">
	</div>
	<div class="newsletter-info-meta-wrap email">
		<label for="blossomthemes_email_newsletter_setting[field][email_placeholder]"><?php _e('Email Placeholder: ','blossomthemes-email-newsletter');?></label>	
		<input type="text" id="blossomthemes_email_newsletter_setting[field][email_placeholder]" name="blossomthemes_email_newsletter_setting[field][email_placeholder]" value="<?php echo isset($blossomthemes_email_newsletter_setting['field']['email_placeholder']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['email_placeholder']):'Your Email';?>">
	</div>
	<div class="newsletter-info-meta-wrap submit-button-label">
		<label for="blossomthemes_email_newsletter_setting[field][submit_label]"><?php _e('Submit Button Label: ','blossomthemes-email-newsletter');?></label>	
		<input type="text" id="blossomthemes_email_newsletter_setting[field][submit_label]" name="blossomthemes_email_newsletter_setting[field][submit_label]" value="<?php echo isset($blossomthemes_email_newsletter_setting['field']['submit_label']) ? esc_attr($blossomthemes_email_newsletter_setting['field']['submit_label']):'Subscribe';?>">
	</div>
	<div class="newsletter-info-meta-wrap gdpr-checkbox">
        <input type="checkbox" class="blossomthemes-email-newsletter-gdpr" id="blossomthemes_email_newsletter_setting[appearance][gdpr]" name="blossomthemes_email_newsletter_setting[appearance][gdpr]" value="1" <?php echo checked($gdpr,1);?>>
        <label for="blossomthemes_email_newsletter_setting[appearance][gdpr]"><?php _e('Enable GDPR checkbox','blossomthemes-email-newsletter');?></label>       
    </div>
	<div class="newsletter-info-meta-wrap form-note">
		<label for="blossomthemes_email_newsletter_setting[appearance][note]"><?php _e('Form Note: ','blossomthemes-email-newsletter');?>
			<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Short note to show above the input fields of the form.', 'blossomthemes-email-newsletter' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>
		</label>	
		<textarea id="blossomthemes_email_newsletter_setting[appearance][note]" name="blossomthemes_email_newsletter_setting[appearance][note]"><?php echo isset($blossomthemes_email_newsletter_setting['appearance']['note']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['note']):''?></textarea>
	</div>
</div>
<?php
    echo 
        '<script>
        jQuery(document).ready(function($){
	        $( ".newsletter-field-option:checked" ).each(function() {
		        if( $(this).val() == "uname-email" )
		        {
		            $(".newsletter-info-meta-wrap.name").show();
		            $(".newsletter-info-meta-wrap.email").show();
		        }
		        if( $(this).val() == "email" )
		        {
		            $(".newsletter-info-meta-wrap.name").hide();
		            $(".newsletter-info-meta-wrap.email").show();
		        }
	    	});

	    	$(".newsletter-field-option").on("change", function () {
		       	if( $(this).val() == "uname-email" )
		       	{
		            $(".newsletter-info-meta-wrap.name").show();
		            $(".newsletter-info-meta-wrap.email").show();
		       	}
		       	if( $(this).val() == "email" )
		       	{
		            $(".newsletter-info-meta-wrap.name").hide();
		            $(".newsletter-info-meta-wrap.email").show();
		       	}
	    	});
    	});
        </script>'; 
