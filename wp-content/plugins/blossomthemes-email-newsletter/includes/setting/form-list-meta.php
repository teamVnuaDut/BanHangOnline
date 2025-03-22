<?php
	$blossomthemes_email_newsletter_setting = get_option( 'blossomthemes_email_newsletter_settings', true );
	$BlossomThemes_Email_Newsletters_lists = get_post_meta( get_the_ID(),'blossomthemes_email_newsletter_setting', true );
	if(isset($blossomthemes_email_newsletter_setting['platform']) && $blossomthemes_email_newsletter_setting['platform']!='')
	{
		$platform = $blossomthemes_email_newsletter_setting['platform'];
		$obj = new BlossomThemes_Email_Newsletter_Settings;
		if ($platform == 'mailerlite' && isset($blossomthemes_email_newsletter_setting['mailerlite']['api-key']) && $blossomthemes_email_newsletter_setting['mailerlite']['api-key']!='')
		{  
			$data = $obj->mailerlite_lists();
			if( ! empty( $data ) ) {
		        foreach ( $data as $listarray => $list ) { 
		        	$id = $list['id']; 
					$j = isset( $BlossomThemes_Email_Newsletters_lists['mailerlite']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['mailerlite']['list-id'][$id] ): '0';
					?>
		        	<div class="newsletter-list">
		        		<input value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[mailerlite][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[mailerlite][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[mailerlite][list-id][<?php echo $id;?>]"><?php echo esc_attr($list['name']);?></label>
		        	</div>
		  		<?php
		  		}
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'mailchimp' && isset($blossomthemes_email_newsletter_setting['mailchimp']['api-key']) && $blossomthemes_email_newsletter_setting['mailchimp']['api-key']!='' )
		{  
			$data = $obj->mailchimp_lists();
			if( isset( $data['lists'] ) ){
				$max = max(array_keys($data['lists']));
				for ($i=0; $i <= $max; $i++) { 
					$id = $data['lists'][$i]['id']?>
					<div class="newsletter-list">
						<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['mailchimp']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['mailchimp']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[mailchimp][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[mailchimp][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[mailchimp][list-id][<?php echo $id;?>]"><?php echo esc_attr($data['lists'][$i]['name']);?></label>
					</div>
				<?php
				}
			}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'convertkit' && isset($blossomthemes_email_newsletter_setting['convertkit']['api-key']) && $blossomthemes_email_newsletter_setting['convertkit']['api-key']!='')
		{  
			$data = $obj->convertkit_lists();
	        foreach ($data as $key => $value){
	        	$id = $key;?>
		  		<div class="newsletter-list">
		  			<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['convertkit']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['convertkit']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[convertkit][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[convertkit][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[convertkit][list-id][<?php echo $id;?>]"><?php echo esc_attr($value['name']);?></label>
		  		</div>
		  	<?php
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'getresponse' && isset($blossomthemes_email_newsletter_setting['getresponse']['api-key']) && $blossomthemes_email_newsletter_setting['getresponse']['api-key']!='')
		{  
			$data = $obj->getresponse_lists();
			$i = 0;
	        foreach ($data as $key => $value){
	        	$id = $key;?>
		  		<div class="newsletter-list">
		  			<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['getresponse']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['getresponse']['list-id'][$id] ): '';?> value="<?php echo esc_attr( $id ) ?>" class="getresponse-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[getresponse][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[getresponse][list-id][<?php echo $id;?>]" <?php echo checked($j,$id);?>><label for="blossomthemes_email_newsletter_setting[getresponse][list-id][<?php echo $id;?>]"><?php echo esc_attr($value['name']);?></label>
		  		</div>
		  	<?php
		  	$i++;
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'activecampaign' && $blossomthemes_email_newsletter_setting['activecampaign']['api-url']!='' && $blossomthemes_email_newsletter_setting['activecampaign']['api-key']!='')
		{  
			$data = $obj->activecampaign_lists();
	        foreach ($data as $key => $value)
	        {
	        	$id = $key;?>
		  		<div class="newsletter-list">
		  			<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['activecampaign']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['activecampaign']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[activecampaign][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[activecampaign][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[activecampaign][list-id][<?php echo $id;?>]"><?php echo esc_attr($value['name']);?></label>
		  		</div>
		  		<?php
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'aweber' && isset($blossomthemes_email_newsletter_setting['aweber']['app-id']) && $blossomthemes_email_newsletter_setting['aweber']['app-id']!='')
		{  
			$aw = new Blossomthemes_Email_Newsletter_AWeber;
			$data = $aw->bten_get_aw_lists();
	        foreach ($data as $key => $value)
	        {
	        	$id = $key;?>
		  		<div class="newsletter-list">
		  			<input <?php $j = isset( $BlossomThemes_Email_Newsletters_lists['aweber']['list-id'][$id] )  ? esc_attr( $BlossomThemes_Email_Newsletters_lists['aweber']['list-id'][$id] ): '0';?> value="<?php echo esc_attr($j);?>" <?php if($j=='1'){ echo "checked";}?> class="mailerlite-lists" type="checkbox" id="blossomthemes_email_newsletter_setting[aweber][list-id][<?php echo $id;?>]" name="blossomthemes_email_newsletter_setting[aweber][list-id][<?php echo $id;?>]"><label for="blossomthemes_email_newsletter_setting[aweber][list-id][<?php echo $id;?>]"><?php echo esc_attr($value['name']);?></label>
		  		</div>
		  		<?php
		  	}
		  	?>
		  	<div class="blossomthemes-email-newsletter-note"><?php _e( 'Users will be subscribed to the groups selected above. If no groups are selected then the group selected in the plugin settings page will be used as a default group.', 'blossomthemes-email-newsletter' ); ?></div>
		<?php
		}

		if ($platform == 'sendinblue' && isset($blossomthemes_email_newsletter_setting['sendinblue']['api-key']) && $blossomthemes_email_newsletter_setting['sendinblue']['api-key']!='') {

			$sendinblue = new Blossomthemes_Email_Newsletter_Sendinblue;
			$lists      = $sendinblue->get_lists();

			if ( ! empty( $lists ) ) {
				foreach( $lists as $key => $list ) {
					$cheched_val = isset( $BlossomThemes_Email_Newsletters_lists['sendinblue']['list-id'][$list['id']] ) ? esc_attr( $BlossomThemes_Email_Newsletters_lists['sendinblue']['list-id'][$list['id']] ) : 'no';
					?>
						<div class="newsletter-list">
							<input <?php checked( $cheched_val, $list['id'] ); ?> value="<?php echo esc_attr( $list['id'] ); ?>" id="btn_sendinblue_list_<?php echo esc_attr( $list['id'] ); ?>" name="blossomthemes_email_newsletter_setting[sendinblue][list-id][<?php echo esc_attr( $list['id'] );?>]" type="checkbox">
							<label for="btn_sendinblue_list_<?php echo esc_attr( $list['id'] ); ?>"><?php echo esc_html( $list['name'] ); ?></label>
						</div>
					<?php
				}
			} else {
				esc_html_e( 'Lists not available to fetch from your sendinblue account.', 'blossomthemes-email-newsletter' );
			}

		}

	}?>
	<div class="blossomthemes-email-newsletter-note"><?php echo sprintf( __('Please put your valid API key for the respective platform in %1$s BlossomThemes Email Newsletter > Settings > API Key %2$s if no groups/lists are displayed here.', 'blossomthemes-email-newsletter' ),'<b>','</b>'); ?></div>