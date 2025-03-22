<?php
global $post;
$blossomthemes_email_newsletter_setting = get_post_meta( $post->ID, 'blossomthemes_email_newsletter_setting', true );
?>
<div class="newsletter-info-meta">
    <div class="newsletter-info-meta-wrap">
        <span class="label"><?php _e('Display Background From:','blossomthemes-email-newsletter'); ?></span> 
        <?php $option = isset($blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option']):'bg-color';?>

        <div class="bg-color-option">
            <label for="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-color]"><?php _e('Background Color','blossomthemes-email-newsletter');?></label>
            <input class="newsletter-bg-option" type="radio" id="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-color]" name="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option]" value="bg-color" <?php if( $option == 'bg-color' ) echo 'checked'; ?>>
            <label for="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-color]" class="check-mark"></label>
        </div>

        <div class="bg-color-option">
            <label for="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-image]"><?php _e('Background Image','blossomthemes-email-newsletter');?></label>
            <input class="newsletter-bg-option" type="radio" id="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-image]" name="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option]" value="image" <?php if( $option == 'image' ) echo 'checked'; ?>>
            <label for="blossomthemes_email_newsletter_setting[appearance][newsletter-bg-option-image]" class="check-mark"></label>
            
        </div>
    </div>
    <div class="newsletter-info-meta-wrap bg-image-uploader">
    	<?php
        $image = ' button">'.__('Upload image','blossomthemes-email-newsletter');
        $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
        $display = 'none'; // display state ot the "Remove image" button
        $name = 'blossomthemes_email_newsletter_setting[appearance][bg]';
        $value = isset($blossomthemes_email_newsletter_setting['appearance']['bg']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['bg']):'';
        if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
            $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
            $display = 'inline-block';
        } 

        echo '
        <div class="bg-image-uploader">
            <a href="#" class="bten_upload_image_button' . $image . '</a>
            <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
            <a href="#" class="bten_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
        </div>';
        ?>
    </div>
    <div class="newsletter-info-meta-wrap enable-overlay-option">
        <label for="blossomthemes_email_newsletter_setting[appearance][overlay]">
        <?php _e('Enable Overlay on Background:','blossomthemes-email-newsletter'); ?>
            <span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Enable to display overlay on email newsletter background.','blossomthemes-email-newsletter');?>">
                    <i class="far fa-question-circle"></i>
            </span>
        </label>
        <input class="newsletter-overlay-option" type="checkbox" id="blossomthemes_email_newsletter_setting[appearance][overlay]" name="blossomthemes_email_newsletter_setting[appearance][overlay]" value="1" <?php if( isset($blossomthemes_email_newsletter_setting['appearance']['overlay']) && $blossomthemes_email_newsletter_setting['appearance']['overlay'] !='' ) echo 'checked'; ?>>
        <label for="blossomthemes_email_newsletter_setting[appearance][overlay]" class="checkbox-label"></label>
    </div>
    <div class="newsletter-info-meta-wrap form-bg-color">
        <div class="form-bg-color">
        <label for="blossomthemes_email_newsletter_setting[appearance][bgcolor]"><?php _e('Background Color: ','blossomthemes-email-newsletter');?></label>	
    	<input type="text" class="blossomthemes-email-newsletter-color-form" id="blossomthemes_email_newsletter_setting[appearance][bgcolor]" name="blossomthemes_email_newsletter_setting[appearance][bgcolor]" value="<?php echo isset($blossomthemes_email_newsletter_setting['appearance']['bgcolor']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['bgcolor']):''?>">
        </div>
    </div>
    <div class="newsletter-info-meta-wrap font-bg-color">
        <div class="font-bg-color">
        <label for="blossomthemes_email_newsletter_setting[appearance][fontcolor]"><?php _e('Font Color: ','blossomthemes-email-newsletter');?></label> 
        <input type="text" class="blossomthemes-email-newsletter-color-form" id="blossomthemes_email_newsletter_setting[appearance][fontcolor]" name="blossomthemes_email_newsletter_setting[appearance][fontcolor]" value="<?php echo isset($blossomthemes_email_newsletter_setting['appearance']['fontcolor']) ? esc_attr($blossomthemes_email_newsletter_setting['appearance']['fontcolor']):''?>">
        </div>
    </div>
</div>
