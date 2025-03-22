<?php
/**
 * Mailchimp handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Convertkit {

	function bten_convertkit_action( $email, $sid, $fname, $lname ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		if ( isset( $blossomthemes_email_newsletter_settings['convertkit']['api-key'] ) && $blossomthemes_email_newsletter_settings['convertkit']['api-key'] != '' ) {
			$apikey = $blossomthemes_email_newsletter_settings['convertkit']['api-key'];
		}

		if ( ! empty( $apikey ) ) {
			$api    = new Convertkit( $apikey );
			$params = array(
				'email'      => $email,
				'first_name' => $fname,
				'fields'     => array(
					'last_name' => $lname,
				),
			);

			$obj  = new BlossomThemes_Email_Newsletter_Settings();
			$data = $obj->convertkit_lists();

			if ( ! empty( $data ) ) {
				$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

				if ( ! isset( $listids['convertkit']['list-id'] ) ) {
					$listids = $blossomthemes_email_newsletter_settings['convertkit']['list-id'];
					try {
						$res = $api->addToForm( $listids, $params );
						if ( isset( $res->subscription->id ) ) {
							$response = '200';
						} else {
							$api_error_msg = 'ConvertKit Problem: ' . var_export( $result );
							$response      = '404';
						}
					} catch ( Exception $e ) {
						$api_error_msg = 'ConvertKit Problem: ' . $e->getMessage();
						$response      = '404';
					}
				} else {
					try {
						foreach ( $listids['convertkit']['list-id'] as $key => $value ) {
							$res = $api->addToForm( $key, $params );
						}
						if ( isset( $res->subscription->id ) ) {
							$response = '200';
						} else {
							$api_error_msg = 'ConvertKit Problem: ' . var_export( $result );
							$response      = '404';
						}
					} catch ( Exception $e ) {
						$api_error_msg = 'ConvertKit Problem: ' . $e->getMessage();
						$response      = '404';
					}
				}
			}
		}
		return $response;
	}
}
new Blossomthemes_Email_Newsletter_Convertkit();
