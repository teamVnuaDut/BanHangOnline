<?php
/**
 * ActiveCampaign handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_ActiveCampaign {

	function bten_activecampaign_action( $email, $sid, $fname ) {
		if ( ! empty( $email ) && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );

			$api_key = $blossomthemes_email_newsletter_settings['activecampaign']['api-key'];
			$url     = $blossomthemes_email_newsletter_settings['activecampaign']['api-url'];

			$list = array();
			if ( ! empty( $api_key ) && ! empty( $url ) ) {
				$ac = new ActiveCampaign( $url, $api_key );

				$obj  = new BlossomThemes_Email_Newsletter_Settings();
				$data = $obj->activecampaign_lists();

				if ( ! empty( $data ) ) {
					$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

					if ( ! isset( $listids['activecampaign']['list-id'] ) ) {
						$listid       = $blossomthemes_email_newsletter_settings['activecampaign']['list-id'];
						$contact      = array(
							'email'             => $email,
							'first_name'        => $fname,
							"p[{$listid}]"      => $listid,
							"status[{$listid}]" => 1, // "Active" status
						);
						$contact_sync = $ac->api( 'contact/sync', $contact );

						if ( (int) $contact_sync->success ) {
							$list['response'] = '200';
						}
					} else {
						foreach ( $listids['activecampaign']['list-id'] as $key => $value ) {
							$contact      = array(
								'email'          => $email,
								'first_name'     => $fname,
								"p[{$key}]"      => $key,
								"status[{$key}]" => 1, // "Active" status
							);
							$contact_sync = $ac->api( 'contact/sync', $contact );
						}

						if ( (int) $contact_sync->success ) {
							$list['response'] = '200';
						}
					}
				}
			}
		}
		return $list;
	}
}
new Blossomthemes_Email_Newsletter_ActiveCampaign();
