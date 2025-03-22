<?php
/**
 * Mailerlite handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */

use MailerLite\MailerLite;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;

class Blossomthemes_Email_Newsletter_Mailerlite {

	/*Function to add main mailchimp action*/
	function bten_mailerlite_action( $email, $sid, $fname ) {
		$response = null;
		if ( ! empty( $email ) && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			// mailerlite API credentials
			$blossomthemes_email_newsletter_setting = get_option( 'blossomthemes_email_newsletter_settings', true );
			$apiKey                                 = $blossomthemes_email_newsletter_setting[ 'mailerlite' ][ 'api-key' ];

			if ( ! empty( $apiKey ) ) {
				// Instantiate a new MailerLite client with the provided API key and the Guzzle adapter.
				$mailer_lite_client = new MailerLite( [ 'api_key' => $apiKey ] );

				$groupsApi = $mailer_lite_client->groups;

				$subscriber = array(
					'email' => $email,
					'name'  => $fname,
				);

				try {
					$subscriber = $mailer_lite_client->subscribers->create( $subscriber );

					$subscriber_id = $subscriber[ 'body' ][ 'data' ][ 'id' ];

					$obj  = new BlossomThemes_Email_Newsletter_Settings();
					$data = $obj->mailerlite_lists();

					if ( ! empty( $data ) ) {
						$list_ids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

						if ( ! isset( $list_ids[ 'mailerlite' ][ 'list-id' ] ) ) {
							$list_id         = $blossomthemes_email_newsletter_setting[ 'mailerlite' ][ 'list-id' ];
							$addedSubscriber = $groupsApi->assignSubscriber( $list_id, $subscriber_id, 1 ); // returns added subscriber
						} else {
							foreach ( $list_ids[ 'mailerlite' ][ 'list-id' ] as $key => $value ) {
								$addedSubscriber = $groupsApi->assignSubscriber( $key, $subscriber_id, 1 );
							}
						}
						$response = isset( $addedSubscriber->error ) ? $addedSubscriber->error->message : '200';
					}
				} catch ( \Exception $e ) {

				}

			}

		}

		return $response;
	}
}

new Blossomthemes_Email_Newsletter_Mailerlite();
