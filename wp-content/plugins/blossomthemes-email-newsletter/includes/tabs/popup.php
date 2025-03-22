<?php

	$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
	$selected_newsletter                     = isset( $blossomthemes_email_newsletter_settings['appearance']['newsletter-id'] ) ? $blossomthemes_email_newsletter_settings['appearance']['newsletter-id'] : '';
?>

	<div class="bten-enable-popup-option">
		<label for="blossomthemes_email_newsletter_settings[appearance][enable-popup]">
		<?php _e( 'Enable Newsletter on Pop-up:', 'blossomthemes-email-newsletter' ); ?></label>
	
		<input class="newsletter-popup-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][enable-popup]" name="blossomthemes_email_newsletter_settings[appearance][enable-popup]" value="1" 
		<?php
		if ( isset( $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] ) && $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] != '' ) {
			echo 'checked';}
		?>
		>
		<label for="blossomthemes_email_newsletter_settings[appearance][enable-popup]" class="checkbox-label"></label>
	</div> 
	
	<div class="popup-newsletter-option-wrap" <?php echo ( isset( $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] ) && $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] != '' ) ? "style='display:block;'" : "style='display:none;'"; ?>>

		<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-id]"><?php _e( 'Newsletter List : ', 'blossomthemes-email-newsletter' ); ?>
			<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the Newsletter to display in Popup.', 'blossomthemes-email-newsletter' ); ?>">
				<i class="far fa-question-circle"></i>
			</span>      
		</label>
		<div class="select-holder">
			<select name="blossomthemes_email_newsletter_settings[appearance][newsletter-id]"> 
			<option selected="selected" disabled="disabled" value=""><?php esc_attr_e( 'Select Newsletter', 'blossomthemes-email-newsletter' ); ?></option>
			<?php
			$args        = array(
				'numberposts' => -1,
				'post_type'   => 'subscribe-form',
			);
			$newsletters = get_posts( $args );
			foreach ( $newsletters as $newsletter ) :
				?>
				<option value="<?php echo $newsletter->ID; ?>" <?php selected( $selected_newsletter, $newsletter->ID ); ?>><?php echo $newsletter->post_title; ?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="popup-pages-option-wrap" <?php echo ( isset( $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] ) && $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] != '' ) ? "style='display:block;'" : "style='display:none;'"; ?>> 

		<label><?php _e( 'Display Popup in:', 'blossomthemes-email-newsletter' ); ?></label> 

		<div class="popup-pages-option">                  
			<input class="newsletter-popup-page-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-home]" name="blossomthemes_email_newsletter_settings[appearance][popup-page][home]" value="1" 
			<?php
			if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['home'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['home'] != '' ) {
				echo 'checked';}
			?>
			>
			<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-home]">
			<?php _e( 'Home', 'blossomthemes-email-newsletter' ); ?></label>
		</div>

		<div class="popup-pages-option">                  
			<input class="newsletter-popup-page-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-blog]" name="blossomthemes_email_newsletter_settings[appearance][popup-page][blog]" value="1" 
			<?php
			if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['blog'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['blog'] != '' ) {
				echo 'checked';}
			?>
			>
			<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-blog]">
			<?php _e( 'Blog', 'blossomthemes-email-newsletter' ); ?></label>
		</div>

		<div class="popup-pages-option">                  
			<input class="newsletter-popup-page-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-pages]" name="blossomthemes_email_newsletter_settings[appearance][popup-page][pages]" value="1" 
			<?php
			if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['pages'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['pages'] != '' ) {
				echo 'checked';}
			?>
			>
			<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-pages]">
			<?php _e( 'Pages', 'blossomthemes-email-newsletter' ); ?></label>
		</div>

		<div class="popup-pages-option">                  
			<input class="newsletter-popup-page-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-archives]" name="blossomthemes_email_newsletter_settings[appearance][popup-page][archives]" value="1" 
			<?php
			if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['archives'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['archives'] != '' ) {
				echo 'checked';}
			?>
			>
			<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-popup-archives]">
			<?php _e( 'Archives', 'blossomthemes-email-newsletter' ); ?></label>
		</div> 

		<div class="popup-pages-option">                  
			<input class="newsletter-popup-page-option" type="checkbox" id="blossomthemes_email_newsletter_settings_popup_posts" name="blossomthemes_email_newsletter_settings[appearance][popup-page][posts]" value="1" 
			<?php
			if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['posts'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['posts'] != '' ) {
				echo 'checked';}
			?>
			>
			<label for="blossomthemes_email_newsletter_settings_popup_posts">
			<?php _e( 'Posts', 'blossomthemes-email-newsletter' ); ?>
				<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the Post-types to display Newsletter in Popup.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
				</span>
			</label>
		</div> 

		<div class="popup-post-pages-option" <?php echo ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['posts'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['posts'] != '' ) ? "style='display:block;'" : "style='display:none;'"; ?>> 		            	
			<input class="newsletter-post-pages-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][newsletter-post-type-post" name="blossomthemes_email_newsletter_settings[appearance][popup-page][post-type][post]" value="1" 
			<?php
			if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['post-type']['post'] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['post-type']['post'] != '' ) {
				echo 'checked';}
			?>
			>
				<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-post-type-post">
				<?php _e( 'post', 'blossomthemes-email-newsletter' ); ?></label>

			<?php
			$args       = array(
				'public'   => true,
				'_builtin' => false,
			);
			$post_types = get_post_types( $args );
			foreach ( $post_types  as $post_type ) {
				?>
				<input class="newsletter-post-pages-option" type="checkbox" id="blossomthemes_email_newsletter_settings[appearance][newsletter-post-type-<?php echo $post_type; ?>]" name="blossomthemes_email_newsletter_settings[appearance][popup-page][post-type][<?php echo $post_type; ?>]" value="1" 
				<?php
				if ( isset( $blossomthemes_email_newsletter_settings['appearance']['popup-page']['post-type'][ $post_type ] ) && $blossomthemes_email_newsletter_settings['appearance']['popup-page']['post-type'][ $post_type ] != '' ) {
					echo 'checked';}
				?>
				>
				<label for="blossomthemes_email_newsletter_settings[appearance][newsletter-post-type-<?php echo $post_type; ?>]">
				<?php _e( $post_type, 'blossomthemes-email-newsletter' ); ?></label>
				<?php
			}
			?>
		</div> 
	</div>

	<div class="popup-image-option bg-image-uploader" <?php echo ( isset( $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] ) && $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] != '' ) ? "style='display:block;'" : "style='display:none;'"; ?>> 

		<label><?php _e( 'Upload Popup Image:', 'blossomthemes-email-newsletter' ); ?></label>

		<?php
		$image      = ' button">' . __( 'Upload image', 'blossomthemes-email-newsletter' );
		$image_size = 'full';
		// it would be better to use thumbnail size here (150x150 or so)
		$display = 'none'; // display state ot the "Remove image" button
		$name    = 'blossomthemes_email_newsletter_settings[appearance][icon]';
		$value   = isset( $blossomthemes_email_newsletter_settings['appearance']['icon'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['appearance']['icon'] ) : '';
		if ( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
			$image   = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
			$display = 'inline-block';
		}

		echo '
        <div class="bg-image-uploader">
            <a href="#" class="bten_upload_image_button' . $image . '</a>
            <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
            <a href="#" class="bten_remove_image_button" style="display:inline-block;display:' . $display . '">
				<svg xml:space="preserve" viewBox="0 0 100 100" y="0" x="0" xmlns="http://www.w3.org/2000/svg" id="圖層_1" version="1.1" width="50px" height="50px" xmlns:xlink="http://www.w3.org/1999/xlink" style="width:100%;height:100%;background-size:initial;background-repeat-y:initial;background-repeat-x:initial;background-position-y:initial;background-position-x:initial;background-origin:initial;background-color:initial;background-clip:initial;background-attachment:initial;animation-play-state:paused" ><g class="ldl-scale" style="transform-origin:50% 50%;transform:rotate(0deg) scale(0.8, 0.8);animation-play-state:paused" ><circle fill="#333" r="40" cy="50" cx="50" style="fill:rgb(248, 62, 62);animation-play-state:paused" ></circle> <g style="animation-play-state:paused" ><path d="M25.3 45h49.5v10H25.3z" fill="#e15b64" transform="rotate(-44.996 49.997 49.997)" style="fill:rgb(255, 255, 255);animation-play-state:paused" ></path></g> <g style="animation-play-state:paused" ><path d="M45 25.3h10v49.5H45z" fill="#e15b64" transform="rotate(-45.001 50 50)" style="fill:rgb(255, 255, 255);animation-play-state:paused" ></path></g> </svg>
			</a>
        </div>';
		?>
	</div>

	<div class="popup-delay-option" <?php echo ( isset( $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] ) && $blossomthemes_email_newsletter_settings['appearance']['enable-popup'] != '' ) ? "style='display:block;'" : "style='display:none;'"; ?>>

		<label for="blossomthemes_email_newsletter_settings[appearance][poup_delay]"><?php _e( 'Popup Delay(in seconds): ', 'blossomthemes-email-newsletter' ); ?>
			<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Enter the time delay to display the popup.', 'blossomthemes-email-newsletter' ); ?>">
				<i class="far fa-question-circle"></i>
			</span> 
		</label>    
		<input type="number" class="popup-delay-seconds" id="blossomthemes_email_newsletter_settings[appearance][poup_delay]" name="blossomthemes_email_newsletter_settings[appearance][poup_delay]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['appearance']['poup_delay'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['appearance']['poup_delay'] ) : 60; ?>">
	</div>

	<?php
	$obj      = new Blossomthemes_Email_Newsletter_Functions();
	$popup_js = '<script>
    jQuery(document).ready(function($){
        $(".newsletter-popup-option").on("click", function() {
            var checked = $(this).is( ":checked" );
            if(checked){
                $(this).parent().siblings(".popup-newsletter-option-wrap").show();
                $(this).parent().siblings(".popup-pages-option-wrap").show();
                $(this).parent().siblings(".popup-image-option").show();
                $(this).parent().siblings(".popup-delay-option").show();
            }
            if(!checked){
                $(this).parent().siblings(".popup-newsletter-option-wrap").hide();
                $(this).parent().siblings(".popup-pages-option-wrap").hide();
                $(this).parent().siblings(".popup-image-option").hide();
                $(this).parent().siblings(".popup-delay-option").hide();
            }
        });
        $("#blossomthemes_email_newsletter_settings_popup_posts").on("click", function() {
            var checked = $(this).is( ":checked" );
            if(checked){
                $(this).parent().siblings(".popup-post-pages-option").show();
            }
            if(!checked){
                $(this).parent().siblings(".popup-post-pages-option").hide();
            }
        });
    });
    </script>';
	echo $obj->bten_minify_js( $popup_js );
