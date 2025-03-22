<?php
/**
 * GetResponse handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */

use Getresponse\Sdk\GetresponseClientFactory;
use Getresponse\Sdk\Operation\Model\NewContact;
use Getresponse\Sdk\Client\Operation\Pagination;
use Getresponse\Sdk\Operation\Model\CampaignReference;
use Getresponse\Sdk\Operation\Campaigns\GetCampaigns\GetCampaigns;
use Getresponse\Sdk\Operation\Contacts\CreateContact\CreateContact;

class Blossomthemes_Email_Newsletter_GetResponse {

	function bten_getresponse_action( $email, $sid, $fname ) {
		$list = array();
		if ( ! empty( $email ) && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
			$api_key                                 = $blossomthemes_email_newsletter_settings['getresponse']['api-key']; // Place API key here

			try {

				if ( ! empty( $api_key ) ) {
					$client = GetresponseClientFactory::createWithApiKey( $api_key );

					/**
					 * let search for campaign. f.e. the first one we could find
					 */
					$campaignsOperation = new GetCampaigns();
					$response           = $client->call( $campaignsOperation );

					if ( $response->isSuccess() ) {
						$campaignList = $response->getData();
						// $campaign = $campaignList[0];

						$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

						if ( ! isset( $listids['getresponse']['list-id'] ) ) {
							$listids = $blossomthemes_email_newsletter_settings['getresponse']['list-id'];
							/**
							 * first lets try to add single contact
							 */
							$newContact = new NewContact(
								new CampaignReference( $listids ),
								$email
							);
							if ( ! empty( $fname ) ) {
								$newContact->setName( $fname );
							}
							$newContact->setDayOfCycle( 0 );
							$createContact = new CreateContact( $newContact );
							$response      = $client->call( $createContact );
							// var_dump($response);
							if ( $response->isSuccess() ) {
								$list['response'] = '200';
							}
						} else {
							foreach ( $listids['getresponse']['list-id'] as $key => $value ) {
								/**
								 * first lets try to add single contact
								 */
								$newContact = new NewContact(
									new CampaignReference( $key ),
									$email
								);
								if ( ! empty( $fname ) ) {
									$newContact->setName( $fname );
								}
								$newContact->setDayOfCycle( 0 );
								$createContact = new CreateContact( $newContact );
								$response      = $client->call( $createContact );
								// var_dump($response);
							}
							if ( $response->isSuccess() ) {
								$list['response'] = '200';
							}
						}
					}
				}
			} catch ( Exception $e ) {
				$list['log']['errorMessage'] = $e->getMessage();
			}
		}
		return $list;
	}

	/**
	 * Get Response API
	 */
	function getresponse_lists( $api_key = '' ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$campaignsArray                          = array();

		if ( empty( $api_key ) && isset( $blossomthemes_email_newsletter_settings['getresponse']['api-key'] ) && $blossomthemes_email_newsletter_settings['getresponse']['api-key'] != '' ) {
			$api_key = $blossomthemes_email_newsletter_settings['getresponse']['api-key']; // Place API key here
		}

		if ( ! empty( $api_key ) ) {
			$client = GetresponseClientFactory::createWithApiKey( $api_key );
			/**
			 * How to get list of campaigns
			 */
			$campaignsOperation = new GetCampaigns();

			/**
			 * There could be pagination, so we have to send requests for each page.
			 */
			$pageNumber = 1;
			$finalPage  = 1;

			do {
				$campaignsOperation->setPagination( new Pagination( $pageNumber, 10 ) );

				$response = $client->call( $campaignsOperation );

				if ( $response->isSuccess() ) {
					/**
					 * note: as operations are asynchronous, pagination data could change during the execution
					 * of this code, so os better to adjust finalPage every call.
					 */
					if ( $response->isPaginated() ) {
						$paginationValues = $response->getPaginationValues();
						$finalPage        = $paginationValues->getTotalPages();
					}
					$campaignList = $response->getData();
					foreach ( $campaignList as $campaign ) {
						$campaignsArray[ $campaign['campaignId'] ] = array(
							'name'       => $campaign['name'],
							'campaignId' => $campaign['campaignId'],
						);
						// var_dump($campaign);
					}
					++$pageNumber;
				} else {
					/**
					 * put some error handling here
					 */
					$errorData = $response->getData();
					var_dump( $errorData['message'] );
					break;
				}
			} while ( $pageNumber <= $finalPage );

		}
		return $campaignsArray;
	}
}
new Blossomthemes_Email_Newsletter_GetResponse();
