<?php
/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    BlossomThemes Email Newsletters
 * @subpackage BlossomThemes_Email_Newsletters/includes
 * @author    blossomthemes
 */
use MailerLiteApi\MailerLite;
use GuzzleHttp\Client as GuzzleClient;

class BlossomThemes_Email_Newsletter_Settings {

	function __construct() {
		add_action( 'wp_ajax_bten_get_platform', array( $this, 'bten_get_platform' ) );
	}

	function bten_get_platform() {
		// Check if our nonce is set.
		if ( ! isset( $_POST['nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['nonce'], 'bten_admin_settings' ) ) {
			return;
		}

		// Sanitize user input.
		$calling_action = isset( $_POST['calling_action'] ) ? sanitize_text_field( wp_unslash( $_POST['calling_action'] ) ) : '';
		$platform       = isset( $_POST['platform'] ) ? sanitize_text_field( wp_unslash( $_POST['platform'] ) ) : '';

		// Check if the action is what we want.
		if ( $calling_action == 'bten_platform_settings' ) {
			echo $this->bten_platform_settings( $platform );
			exit;
		}
	}


	/**
	 * Get MailerLite lists.
	 *
	 * @param string $api_key API key.
	 *
	 * @return array
	 */
	function mailerlite_lists( $api_key = '' ) {
		$lists = array();
		$body  = array();

		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );

		if ( empty( $api_key ) && isset( $blossomthemes_email_newsletter_settings['mailerlite']['api-key'] ) ) {
			$api_key = $blossomthemes_email_newsletter_settings['mailerlite']['api-key'];
		}

		// Sanitize the API key.
		$api_key = sanitize_text_field( $api_key );

		// Check if server is local.
		$is_local = ( $_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_ADDR'] === '::1' );

		// Create an options array for the Guzzle HTTP client. If the server is localhost, disable SSL verification by setting 'verify' to false.
		$guzzle_client_options = array(
			'verify' => ! $is_local,
		);

		// Instantiate a new Guzzle HTTP client with the specified options.
		$guzzle_client = new GuzzleClient( $guzzle_client_options );

		// Create a new Guzzle adapter. This adapter allows the MailerLite client to send HTTP requests using the Guzzle HTTP client.
		$http_adapter = new \Http\Adapter\Guzzle6\Client( $guzzle_client );

		// Instantiate a new MailerLite client with the provided API key and the Guzzle adapter.
		$mailer_lite_client = new MailerLite( $api_key, $http_adapter );
		$groups_api         = $mailer_lite_client->groups();

		try {
			$groups = $groups_api->get();

			if ( ! empty( $groups ) && ! isset( $groups['0']->error ) ) {
				foreach ( $groups as $value ) {
					$lists[] = array(
						'id'   => (string) $value->id,
						'name' => $value->name,
					);
				}
			} elseif ( ! empty( $api_key ) ) {
					$mailer_lite = new MailerLite( array( 'api_key' => $api_key ) );
					$groups_api  = $mailer_lite->groups();
					$groups      = $groups_api->get();
				if ( ! empty( $groups ) && isset( $groups['status_code'] ) && 200 === $groups['status_code'] ) {
					$body = $groups['body']['data'];
					foreach ( $body as $value ) {
						$lists[] = array(
							'id'   => (string) $value['id'],
							'name' => $value['name'],
						);
					}
				}
			} else {
				throw new Exception( 'Unauthorized access. Please check your API key.' );
			}
		} catch ( Exception $e ) {
			// Log the error instead of echoing it.
			error_log( 'MailerLite API Error: ' . $e->getMessage() );
			if ( $e->getCode() === 401 ) {
				error_log( 'Error: Unauthorized access. Please check your API key.' );
			}
		}

		return $lists;
	}

	/**
	 * Get Mailchimp lists.
	 *
	 * @param string $api_key API key.
	 *
	 * @return array
	 */
	function mailchimp_lists( $api_key = '' ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );

		if ( empty( $api_key ) && isset( $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] ) && $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] != '' ) {
			$api_key = $blossomthemes_email_newsletter_settings['mailchimp']['api-key'];
		}

		// Sanitize the API key.
		$api_key = sanitize_text_field( $api_key );

		$MC_Lists = new MC_Lists( $api_key );
		$lists    = $MC_Lists->getAll();
		$data     = json_decode( $lists, true );
		return $data;
	}

	/**
	 * Get Sendinblue lists.
	 *
	 * @return array
	 */
	function sendinblue_lists() {
		$lists = array();

		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', false );

		if ( ! $blossomthemes_email_newsletter_settings ) {
			return $lists;
		}

		$api_key = isset( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) : '';

		// Sanitize the API key
		$api_key = sanitize_text_field( $api_key );

		if ( ! $api_key ) {
			return $lists;
		}
		// get lists.
		$lists = get_transient( 'bten_sib_list_' . md5( $api_key ) );
		if ( false === $lists || false == $lists ) {

			$mailin    = new Blossom_Sendinblue_API_Client();
			$lists     = array();
			$list_data = $mailin->getAllLists();

			if ( ! empty( $list_data['lists'] ) ) {
				foreach ( $list_data['lists'] as $value ) {
					if ( 'Temp - DOUBLE OPTIN' == $value['name'] ) {
						$tempList = $value['id'];
						update_option( 'bten_sib_temp_list', $tempList );
						continue;
					}
					$lists[] = array(
						'id'   => $value['id'],
						'name' => $value['name'],
					);
				}
			}
		}
		if ( count( $lists ) > 0 ) {
			set_transient( 'bten_sib_list_' . md5( $api_key ), $lists, 4 * HOUR_IN_SECONDS );
		}
		return $lists;
	}

	/**
	 * Get MailerLite lists.
	 *
	 * @param string $api_key API key.
	 *
	 * @return array
	 */
	function convertkit_lists( $apikey = '' ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$list                                    = array();
		if ( empty( $apikey ) && isset( $blossomthemes_email_newsletter_settings['convertkit']['api-key'] ) && $blossomthemes_email_newsletter_settings['convertkit']['api-key'] != '' ) {
			$apikey = $blossomthemes_email_newsletter_settings['convertkit']['api-key'];
		}
			// Sanitize the API key.
			$api_key = sanitize_text_field( $api_key );

			$api = new Convertkit( $apikey );

		try {
			$result = $api->getForms();

			if ( isset( $result->forms ) ) {
				foreach ( $result->forms as $l ) {
					$list[ $l->id ] = array( 'name' => $l->name );
				}
			}
		} catch ( Exception $e ) {
			echo $e->getMessage();
		}

		return $list;
	}

	/**
	 * Get GetResponse lists.
	 *
	 * @param string $api_key API key.
	 * @return array
	 */
	function getresponse_lists( $api_key = '' ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$list                                    = array();

		if ( empty( $api_key ) && isset( $blossomthemes_email_newsletter_settings['getresponse']['api-key'] ) && $blossomthemes_email_newsletter_settings['getresponse']['api-key'] != '' ) {
			$api_key = $blossomthemes_email_newsletter_settings['getresponse']['api-key']; // Place API key here
		}

		// Sanitize the API key.
		$api_key = sanitize_text_field( $api_key );

		if ( ! empty( $api_key ) ) {
			$getres = new Blossomthemes_Email_Newsletter_GetResponse();
			$list   = $getres->getresponse_lists( $api_key );
		}
		return $list;
	}

	/**
	 * Get ActiveCampaign lists.
	 *
	 * @param string $api_key API key.
	 * @param string $url URL.
	 * @return array
	 */
	function activecampaign_lists( $api_key = '', $url = '' ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$list                                    = array();
		if ( empty( $api_key ) && empty( $url ) && isset( $blossomthemes_email_newsletter_settings['activecampaign']['api-url'] ) && $blossomthemes_email_newsletter_settings['activecampaign']['api-url'] != '' && isset( $blossomthemes_email_newsletter_settings['activecampaign']['api-key'] ) && $blossomthemes_email_newsletter_settings['activecampaign']['api-key'] != '' ) {
			$api_key = $blossomthemes_email_newsletter_settings['activecampaign']['api-key']; // Place API key here
			$url     = $blossomthemes_email_newsletter_settings['activecampaign']['api-url'];
		}

		// Sanitize the API key.
		$api_key = sanitize_text_field( $api_key );

		// Sanitize the URL.
		$url = esc_url_raw( $url );

		if ( ! empty( $api_key ) && ! empty( $url ) ) {
			if ( $this->is_internal_IP( $url ) ) {
				return array(
					'errorMessage' => sprintf( __( 'The provided URL "%s" appears to be an internal IP address, which is not accessible. Please provide a public IP address or domain name.', 'blossomthemes-email-newsletter' ), $url ),
				);
			} elseif ( ! $this->is_valid_url( $url ) ) {
				return array(
					'errorMessage' => sprintf( __( "Access denied: The API URL \"%s\" is invalid. Please ensure that the URL starts with 'https', does not contain any extra whitespace, and is not an internal IP address.", 'blossomthemes-email-newsletter' ), $url ),
				);
			} else {
				// Check the URL scheme
				$scheme = parse_url( $url, PHP_URL_SCHEME );
				if ( $scheme !== 'http' && $scheme !== 'https' ) {
					return array(
						'errorMessage' => __( 'Invalid URL scheme. Only http and https are allowed.', 'blossomthemes-email-newsletter' ),
					);
				}
				$ac = new ActiveCampaign( $url, $api_key );
				try {
					$response = $ac->api( 'list/list', array( 'ids' => 'all' ) );
					if ( is_object( $response ) && ! empty( $response ) && $response->success == 1 ) {
						foreach ( $response as $v ) {
							if ( is_object( $v ) ) {
								$list[ $v->id ] = array( 'name' => $v->name );
							}
						}
					}
				} catch ( Exception $e ) {
					error_log( $e->getMessage() );
					return array(
						'errorMessage' => __( 'An error occurred while trying to connect to the ActiveCampaign API. Please check the server logs for more details.', 'blossomthemes-email-newsletter' ),
					);
				}
			}
		}
		return $list;
	}

	/**
	 * Check if the URL is valid
	 *
	 * @param string $url URL to check.
	 *
	 * @return bool
	 */
	private function is_valid_url( $url ) {
		return preg_match( '/^https:\/\/([a-zA-Z0-9_\-]+)\.(api\-us1|activehosted)\.com$/', $url );
	}

	/**
	 * Check if the IP is internal
	 *
	 * @param string $url URL to check.
	 *
	 * @return bool
	 */
	private function is_internal_ip( $url ) {
		$host    = parse_url( $url, PHP_URL_HOST );
		$ip      = gethostbyname( $host );
		$long_ip = ip2long( $ip );

		// Define the private IP ranges.
		$private_ranges = array(
			array(
				'min' => ip2long( '10.0.0.0' ),
				'max' => ip2long( '10.255.255.255' ),
			),
			array(
				'min' => ip2long( '172.16.0.0' ),
				'max' => ip2long( '172.31.255.255' ),
			),
			array(
				'min' => ip2long( '192.168.0.0' ),
				'max' => ip2long( '192.168.255.255' ),
			),
			array(
				'min' => ip2long( '127.0.0.0' ),
				'max' => ip2long( '127.255.255.255' ),
			),
		);

		// Check if the IP is within any of the private ranges
		foreach ( $private_ranges as $range ) {
			if ( $long_ip >= $range['min'] && $long_ip <= $range['max'] ) {
				return true;
			}
		}

		return false;
	}


	function get_status( $url ) {
		// must set $url first.
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		// do your curl thing here
		$data        = curl_exec( $ch );
		$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		return $http_status;
	}

	function blossomthemes_email_newsletter_settings_tabs() {

		$tabs = array(
			'general' => 'general.php',
			'popup'   => 'popup.php',
		);
		$tabs = apply_filters( 'blossomthemes_email_newsletter_settings_tabs', $tabs );
		return $tabs;
	}

	/**
	 * Display notice.
	 *
	 * @param string $type Type of notice.
	 * @param string $message Message to display.
	 * @param string $platform Platform.
	 *
	 * @return void
	 */
	function display_notice( $type, $message, $platform = '' ) {
		?>
		<div id="setting-error-settings_updated" class="<?php echo esc_attr( $type . ' ' . $platform ); ?> settings-error notice is-dismissible"> 
		<p><strong><?php echo esc_html( $message ); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'blossomthemes-email-newsletter' ); ?></span></button>
		</div>
		<?php
	}

	function blossomthemes_email_newsletter_backend_settings() {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$platform                                = $blossomthemes_email_newsletter_settings['platform'];
		$data                                    = $this->activecampaign_lists();
		?>
		<div class="wrap">
			<div class="btnb-header">
				<h3><?php _e( 'Settings', 'blossomthemes-email-newsletter' ); ?></h3>
			</div>
			<?php
			if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {
				$this->display_notice( 'updated', __( 'Settings updated.', 'blossomthemes-email-newsletter' ), '' );
			}
			if ( isset( $platform ) && 'activecampaign' === $platform && isset( $data ) && isset( $data['errorMessage'] ) && '' !== $data['errorMessage'] ) {
				$this->display_notice( 'error', $data['errorMessage'], 'activecampaign' );
			}
			?>
			<div id="tabs-container">
				<ul class="tabs-menu">
				<?php
				$settings_tab = $this->blossomthemes_email_newsletter_settings_tabs();
					$count    = 0;
				foreach ( $settings_tab as $key => $value ) {
					$tab_label = preg_replace( '/_/', ' ', $key );
					?>
						<li 
						<?php
						if ( $count == 0 ) {
							?>
							class="current"<?php } ?>><a href="<?php echo $key; ?>"><?php echo $tab_label; ?></a></li>
					<?php
					++$count;
				}
				?>
				</ul>
				<div class="tab">
					<form method="POST" name="form1" action="options.php" id="form1" class="btemn-settings-form">
						<?php
							settings_fields( 'blossomthemes_email_newsletter_settings' );
							do_settings_sections( __FILE__ );

							$counter = 0;
						foreach ( $settings_tab as $key => $value ) {
							?>
							<div id="<?php echo $key; ?>" class="tab-content" 
												<?php
												if ( $counter == 0 ) {
													?>
								style="display: block;" 
													<?php
												} else {
													?>
											style="display: none;" <?php } ?>>
								<?php
								include_once BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/tabs/' . $value;
								?>
							</div>
							<?php
							++$counter; }
						?>

						<div class="blossomthemes_email_newsletter_settings-settings-submit">
							<?php echo submit_button(); ?>
						</div>
					</form>
				</div>
			</div>	
		</div>
		<?php
	}

	function bten_platform_settings( $platform = '' ) {
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		switch ( $platform ) {
			case 'mailchimp':
				ob_start();
				?>
				<div id="mailchimp" class="newsletter-settings
				<?php
				if ( $platform == 'mailchimp' || ! isset( $platform ) ) {
					echo ' current'; }
				?>
					">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailchimp][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_mailchimp_api_key" name="blossomthemes_email_newsletter_settings[mailchimp][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['mailchimp']['api-key'] ) : ''; ?>">
						<?php echo '<div class="blossomthemes-email-newsletter-note">' . sprintf( __( 'Get your API key %1$shere%2$s', 'blossomthemes-email-newsletter' ), '<a href="https://us15.admin.mailchimp.com/account/api/" target="_blank">', '</a>' ) . '</div>'; ?>
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailchimp][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
							<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
							</span>
						</label> 
							<?php
							$data = $this->mailchimp_lists();
							?>
							<div class="select-holder">
								<select id="bten_mailchimp_list" name="blossomthemes_email_newsletter_settings[mailchimp][list-id]">
									<?php
									$mailchimp_list = isset( $blossomthemes_email_newsletter_settings['mailchimp']['list-id'] ) ? $blossomthemes_email_newsletter_settings['mailchimp']['list-id'] : '';
									if ( empty( $data['lists'] ) ) {
										?>
									<option value="-"><?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?></option>
										<?php
									} else {
										$max = max( array_keys( $data['lists'] ) );
										for ( $i = 0; $i <= $max; $i++ ) {
											?>
											<option <?php selected( $mailchimp_list, esc_attr( $data['lists'][ $i ]['id'] ) ); ?> value="<?php echo esc_attr( $data['lists'][ $i ]['id'] ); ?>"><?php echo esc_attr( $data['lists'][ $i ]['name'] ); ?></option>
									
											<?php
										}
									}
									?>
								</select>
							</div>
							<input type="button" rel-id="bten_mailchimp_list" class="button bten_get_mailchimp_lists" name="" value="Grab Lists" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_mailchimp_list' ) ); ?>">
							
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field"> 
						<input type="checkbox" class="enable_notif_opt" name="blossomthemes_email_newsletter_settings[mailchimp][enable_notif]" <?php $j = isset( $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] ) : '0'; ?> id="blossomthemes_email_newsletter_settings[mailchimp][enable_notif]" value="<?php echo esc_attr( $j ); ?>" 
																																						<?php
																																						if ( $j == '1' ) {
																																							echo 'checked';}
																																						?>
							/>

						<label for="blossomthemes_email_newsletter_settings[mailchimp][enable_notif]"><?php _e( 'Confirmation', 'blossomthemes-email-newsletter' ); ?>
							<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Check this box if you want subscribers to receive confirmation mail before they are added to list.', 'blossomthemes-email-newsletter' ); ?>">
								<i class="far fa-question-circle"></i>
							</span>
						</label>
					</div>
				</div>
				<?php
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
				break;

			case 'mailerlite':
				ob_start();
				?>
				<div id="mailerlite" class="newsletter-settings
				<?php
				if ( 'mailerlite' === $platform ) {
					echo ' current'; }
				?>
					">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailerlite][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_mailerlite_api_key" name="blossomthemes_email_newsletter_settings[mailerlite][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['mailerlite']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['mailerlite']['api-key'] ) : ''; ?>">
						<?php echo '<div class="blossomthemes-email-newsletter-note">' . sprintf( __( 'Get your api key %1$shere%2$s', 'blossomthemes-email-newsletter' ), '<a href="https://app.mailerlite.com/subscribe/api" target="_blank">', '</a>' ) . '</div>'; ?>  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[mailerlite][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
							<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
							</span>
						</label> 
						<?php
						$data = $this->mailerlite_lists();
						?>
						<div class="select-holder">

							<select id="bten_mailerlite_list" name="blossomthemes_email_newsletter_settings[mailerlite][list-id]">
								<?php
								$mailerlite_list = isset( $blossomthemes_email_newsletter_settings['mailerlite']['list-id'] ) ? $blossomthemes_email_newsletter_settings['mailerlite']['list-id'] : '';
								if ( empty( $data ) ) {
									?>
									<option value="-"><?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?></option>
									<?php
								} else {
									echo '<option>' . esc_html( 'Choose mailerlite list' ) . '</option>';
									foreach ( $data as $listarray => $list ) {
										?>
										<option <?php selected( $mailerlite_list, esc_attr( $list['id'] ) ); ?> value="<?php echo esc_attr( $list['id'] ); ?>"><?php echo esc_attr( $list['name'] ); ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
						<input type="button" rel-id="bten_mailerlite_list" class="button bten_get_mailerlite_lists" name="" value="Grab Lists" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_mailerlite_list' ) ); ?>">
					</div>
				</div>
				<?php
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
				break;

			case 'convertkit':
				ob_start();
				?>
				<div id="convertkit" class="newsletter-settings
				<?php
				if ( $platform == 'convertkit' ) {
					echo ' current'; }
				?>
					">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[convertkit][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_convertkit_api_key" name="blossomthemes_email_newsletter_settings[convertkit][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['convertkit']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['convertkit']['api-key'] ) : ''; ?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[convertkit][api-secret]"><?php _e( 'API Secret : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_convertkit_api_secret" name="blossomthemes_email_newsletter_settings[convertkit][api-secret]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['convertkit']['api-secret'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['convertkit']['api-secret'] ) : ''; ?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[convertkit][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
							<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
							</span>
						</label> 
							<?php
							$data = $this->convertkit_lists();
							?>
							<div class="select-holder">
								<select id="bten_convertkit_list" name="blossomthemes_email_newsletter_settings[convertkit][list-id]">
									<?php
									$convertkit_list = isset( $blossomthemes_email_newsletter_settings['convertkit']['list-id'] ) ? $blossomthemes_email_newsletter_settings['convertkit']['list-id'] : '';
									if ( sizeof( $data ) < 1 ) {
										?>
										<option value="-"><?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?></option>
										<?php
									} else {
										foreach ( $data as $key => $value ) {
											?>
											<option <?php selected( $convertkit_list, esc_attr( $key ) ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value['name'] ); ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
						<input type="button" rel-id="bten_convertkit_list" class="button bten_get_convertkit_lists" name="" value="Grab Lists" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_convertkit_list' ) ); ?>">
					</div>
				</div>
				<?php
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
				break;

			case 'getresponse':
				ob_start();
				?>
				<div id="getresponse" class="newsletter-settings
				<?php
				if ( $platform == 'getresponse' ) {
					echo ' current'; }
				?>
					">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[getresponse][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_getresponse_api_key" name="blossomthemes_email_newsletter_settings[getresponse][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['getresponse']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['getresponse']['api-key'] ) : ''; ?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[getresponse][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
							<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
							</span>
						</label> 
						<?php
						$data = $this->getresponse_lists();
						?>
						<div class="select-holder">
							<select id="bten_getresponse_list" name="blossomthemes_email_newsletter_settings[getresponse][list-id]">
							<?php
							$getresponse_list = isset( $blossomthemes_email_newsletter_settings['getresponse']['list-id'] ) ? $blossomthemes_email_newsletter_settings['getresponse']['list-id'] : '';
							if ( empty( $data ) ) {
								?>
								<option value="-"><?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?></option>
							<?php } else { ?>
							<option value=""><?php _e( 'Choose Campaign ID', 'blossomthemes-email-newsletter' ); ?></option>
								<?php
								foreach ( $data as $key => $value ) {
									?>
									<option <?php selected( $getresponse_list, esc_attr( $key ) ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value['name'] ); ?></option>
									<?php
								}
							}
							?>
						</select></div>
						<input type="button" rel-id="bten_getresponse_list" class="button bten_get_getresponse_lists" name="" value="Grab Lists" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_getresponse_list' ) ); ?>">
					</div>
				</div>
				<?php
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
				break;

			case 'activecampaign':
				ob_start();
				?>
				<div id="activecampaign" class="newsletter-settings
				<?php
				if ( $platform == 'activecampaign' ) {
					echo ' current'; }
				?>
					">
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[activecampaign][api-url]"><?php _e( 'API Url : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_activecampaign_api_url" name="blossomthemes_email_newsletter_settings[activecampaign][api-url]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['activecampaign']['api-url'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['activecampaign']['api-url'] ) : ''; ?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">
						<label for="blossomthemes_email_newsletter_settings[activecampaign][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
						<input type="text" id="bten_activecampaign_api_key" name="blossomthemes_email_newsletter_settings[activecampaign][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['activecampaign']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['activecampaign']['api-key'] ) : ''; ?>">  
					</div>
					<div class="blossomthemes-email-newsletter-wrap-field">				
						<label for="blossomthemes_email_newsletter_settings[activecampaign][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
							<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>"><i class="far fa-question-circle"></i>
							</span>
						</label> 
						<?php
						$data = $this->activecampaign_lists();
						?>
						<div class="select-holder">
							<select id="bten_activecampaign_list" name="blossomthemes_email_newsletter_settings[activecampaign][list-id]">
								<?php
								$activecampaign_list = isset( $blossomthemes_email_newsletter_settings['activecampaign']['list-id'] ) ? $blossomthemes_email_newsletter_settings['activecampaign']['list-id'] : '';
								if ( sizeof( $data ) < 1 ) {
									?>
									<option value="-"><?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?></option>
								<?php } else { ?>
								<option value=""><?php _e( 'Choose Campaign ID', 'blossomthemes-email-newsletter' ); ?></option>
									<?php
									foreach ( $data as $key => $value ) {
										if ( ! isset( $data['errorMessage'] ) ) :
											?>
										<option <?php selected( $activecampaign_list, esc_attr( $key ) ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value['name'] ); ?></option>
											<?php
										endif;
									}
								}
								?>
							</select>
						</div>
						<input type="button" rel-id="bten_activecampaign_list" class="button bten_get_activecampaign_lists" name="" value="Grab Lists" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_activecampaign_list' ) ); ?>">
					</div>
				</div>
				<?php
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
				break;

			case 'aweber':
				ob_start();
				?>
				<div id="aweber" class="newsletter-settings
				<?php
				if ( $platform == 'aweber' ) {
					echo ' current'; }
				?>
					">

					<?php
					$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
					$bten_aw_auth_info                       = get_option( 'bten_aw_auth_info' );

					$appId             = '9c213c43'; // Place APP ID here
					$url               = 'https://auth.aweber.com/1.0/oauth/authorize_app/' . $appId;
					$status            = $this->get_status( $url );
					$auth_nonce        = wp_create_nonce( 'bten_aweber_auth' );
					$remove_auth_nonce = wp_create_nonce( 'bten_aweber_remove_auth' );

					if ( $status != 200 ) {
						echo '<div class="blossomthemes-email-newsletter-note">' . __( 'The APP ID does not seem to exist. Please enter a valid AWeber APP ID to connect to the mailing lists.', 'blossomthemes-email-newsletter' ) . '</div>';
					} else {
						echo '<div id="bten_aweber_connect_div"' . ( $bten_aw_auth_info ? 'style="display:none;"' : '' ) . '>';
						echo '<label>' . __( 'AWeber Connection : ', 'blossomthemes-email-newsletter' ) . '</label> ';
						echo '<b>Step 1:</b> <a href="https://auth.aweber.com/1.0/oauth/authorize_app/' . $appId . '" target="_blank">Click here to get your authorization code.</a><br />';
						echo '<b>Step 2:</b> Paste in your authorization code:<br />';
						echo '<textarea id="bten_aweber_auth_code" rows="3"></textarea><br />';
						echo '<input type="button" class="button-primary bten_aweber_auth" name="" value="Connect" data-nonce="' . $auth_nonce . '" />';
						echo '</div>';

						echo '<div id="bten_aweber_disconnect_div"' . ( $bten_aw_auth_info ? '' : ' style="display:none;"' ) . '>';
						echo '<label>' . __( 'AWeber Connection : ', 'blossomthemes-email-newsletter' ) . '</label> ';
						echo '<input type="button" class="button-primary bten_aweber_remove_auth" name="" value="Remove Connection" data-nonce="' . $remove_auth_nonce . '" />';
						echo '</div>';
						?>
						<div class="blossomthemes-email-newsletter-wrap-field">				
							<label for="blossomthemes_email_newsletter_settings[aweber][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
								<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>">
									<i class="far fa-question-circle"></i>
								</span>
							</label>
							<div class="select-holder"> 
								<select id="bten_aweber_list" name="blossomthemes_email_newsletter_settings[aweber][list-id]">
									<?php
									$aw          = new Blossomthemes_Email_Newsletter_AWeber();
									$aweber_list = isset( $blossomthemes_email_newsletter_settings['aweber']['list-id'] ) ? $blossomthemes_email_newsletter_settings['aweber']['list-id'] : '';
									$data        = $aw->bten_get_aw_lists();
									if ( sizeof( $data ) < 1 ) {
										?>
										<option value="-"><?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?></option>
									<?php } else { ?>
									<option value=""><?php _e( 'Choose Campaign ID', 'blossomthemes-email-newsletter' ); ?></option>
										<?php
										foreach ( $data as $key => $value ) {
											?>
											<option <?php selected( $aweber_list, esc_attr( $key ) ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value['name'] ); ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
							<input type="button" rel-id="bten_aweber_list" class="button bten_get_aweber_lists" name="" value="Grab Lists" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_aweber_list' ) ); ?>">
						</div>
						<?php
					}

					?>
				</div>
				<?php
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
				break;

			case 'sendinblue':
				ob_start();
				?>
					<div id="sendinblue-settings" class="newsletter-settings <?php echo 'sendinblue' === $platform ? esc_attr( 'current' ) : ''; ?>">
						<div class="blossomthemes-email-newsletter-wrap-field">
							<label for="blossomthemes_email_newsletter_settings[sendinblue][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
							<input type="text" id="bten_sendinblue_api_key" name="blossomthemes_email_newsletter_settings[sendinblue][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) : ''; ?>">
							<?php echo '<div class="blossomthemes-email-newsletter-note">' . sprintf( __( 'Get your API key %1$shere%2$s', 'blossomthemes-email-newsletter' ), '<a href="https://app.brevo.com/settings/keys/api" target="_blank">', '</a>' ) . '</div>'; ?>
						</div>
						<div class="blossomthemes-email-newsletter-wrap-field">
							<label for="blossomthemes_email_newsletter_settings[sendinblue][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
								<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>">
								<i class="far fa-question-circle"></i>
								</span>
							</label> 
							<?php
								$data = $this->sendinblue_lists();
							?>
								<div class="select-holder">
									<select id="bten_sendinblue_list" name="blossomthemes_email_newsletter_settings[sendinblue][list-id]">
										<?php
											$sendinblue_list = isset( $blossomthemes_email_newsletter_settings['sendinblue']['list-id'] ) ? $blossomthemes_email_newsletter_settings['sendinblue']['list-id'] : '';
										if ( empty( $data ) ) {
											?>
												<option value="-">
													<?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?>
												</option>
											<?php
										} else {
											echo '<option>' . esc_html( 'Choose sendinblue list' ) . '</option>';
											foreach ( $data as $listarray => $list ) {
												echo '<option ' . selected( $sendinblue_list, $list['id'] ) . ' value="' . esc_attr( $list['id'] ) . '" >' . esc_html( $list['name'] ) . '</option>';
											}
										}
										?>
									</select>
								</div>
								<input type="button" rel-id="bten_sendinblue_list" class="button bten_get_sendinblue_lists" name="" value="<?php esc_attr_e( 'Grab Lists', 'blossomthemes-email-newsletter' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_sendinblue_list' ) ); ?>">
						</div>
					</div>
				<?php
				$output = ob_get_clean();
				return $output;
				break;

			default:
				ob_start();
				?>
					<div id="sendinblue-settings" class="newsletter-settings current">
						<div class="blossomthemes-email-newsletter-wrap-field">
							<label for="blossomthemes_email_newsletter_settings[sendinblue][api-key]"><?php _e( 'API Key : ', 'blossomthemes-email-newsletter' ); ?></label> 
							<input type="text" id="bten_sendinblue_api_key" name="blossomthemes_email_newsletter_settings[sendinblue][api-key]" value="<?php echo isset( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) : ''; ?>">
							<?php echo '<div class="blossomthemes-email-newsletter-note">' . sprintf( __( 'Get your API key %1$shere%2$s', 'blossomthemes-email-newsletter' ), '<a href="https://app.brevo.com/settings/keys/api" target="_blank">', '</a>' ) . '</div>'; ?>
						</div>
						<div class="blossomthemes-email-newsletter-wrap-field">
							<label for="blossomthemes_email_newsletter_settings[sendinblue][list-id]"><?php _e( 'List Id : ', 'blossomthemes-email-newsletter' ); ?>
								<span class="blossomthemes-email-newsletter-tooltip" title="<?php esc_html_e( 'Choose the default list. If no groups/lists are selected in the newsletter posts, users will be subscribed to the list selected above.', 'blossomthemes-email-newsletter' ); ?>">
								<i class="far fa-question-circle"></i>
								</span>
							</label> 
							<?php
								$data = $this->sendinblue_lists();
							?>
								<div class="select-holder">
									<select id="bten_sendinblue_list" name="blossomthemes_email_newsletter_settings[sendinblue][list-id]">
										<?php
											$sendinblue_list = isset( $blossomthemes_email_newsletter_settings['sendinblue']['list-id'] ) ? $blossomthemes_email_newsletter_settings['sendinblue']['list-id'] : '';
										if ( empty( $data ) ) {
											?>
												<option value="-">
													<?php _e( 'No Lists Found', 'blossomthemes-email-newsletter' ); ?>
												</option>
											<?php
										} else {
											echo '<option>' . esc_html( 'Choose sendinblue list' ) . '</option>';
											foreach ( $data as $listarray => $list ) {
												echo '<option ' . selected( $sendinblue_list, $list['id'] ) . ' value="' . esc_attr( $list['id'] ) . '" >' . esc_html( $list['name'] ) . '</option>';
											}
										}
										?>
									</select>
								</div>
								<input type="button" rel-id="bten_sendinblue_list" class="button bten_get_sendinblue_lists" name="" value="<?php esc_attr_e( 'Grab Lists', 'blossomthemes-email-newsletter' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'bten_sendinblue_list' ) ); ?>">
						</div>
					</div>
				<?php
				$output = ob_get_clean();
				return $output;
				break;
		}
	}
}
new BlossomThemes_Email_Newsletter_Settings();
