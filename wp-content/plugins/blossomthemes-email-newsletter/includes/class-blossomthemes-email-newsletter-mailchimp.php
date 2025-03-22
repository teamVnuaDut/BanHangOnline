<?php
/**
 * Mailchimp handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Mailchimp {

	/*Function to add main mailchimp action*/
	function bten_mailchimp_action( $email, $sid, $fname ) {
		if ( ! empty( $email ) && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			// MailChimp API credentials
			$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );

			$apiKey = $blossomthemes_email_newsletter_settings['mailchimp']['api-key'];
			if ( ! empty( $apiKey ) ) {
				$rest    = new MC_Lists( $apiKey );
				$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

				$obj  = new BlossomThemes_Email_Newsletter_Settings();
				$data = $obj->mailchimp_lists();

				if ( ! empty( $data['lists'] ) ) {
					$args           = array();
					$args['status'] = 'subscribed';
					if ( isset( $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] ) && $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] == '1' ) {

						$args['status'] = 'pending';
					}
					$args['merge_fields']['FNAME'] = $fname;
					$args['email_address']         = $email;

					$merge_fields = array();

					if ( ! isset( $listids['mailchimp']['list-id'] ) ) {
						$listids  = $blossomthemes_email_newsletter_settings['mailchimp']['list-id'];
						$retval   = $rest->addMember( $listids, $args );
						$response = '200';
					} else {
						foreach ( $listids['mailchimp']['list-id'] as $key => $value ) {
							$retval = $rest->addMember( $key, $args );
						}
						$response = '200';
					}
				}
			}
			return $response;
		}
	}
}
new Blossomthemes_Email_Newsletter_Mailchimp();
