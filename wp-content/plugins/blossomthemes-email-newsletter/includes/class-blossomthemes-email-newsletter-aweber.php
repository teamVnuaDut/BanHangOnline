<?php
/**
 * AWeber handler of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_AWeber {

	function bten_get_aw_remove_auth() {
		$return = array();

		delete_option( 'bten_aw_auth_info' );

		$return['Ok'] = true;

		return $return;
	}

	function bten_get_aw_auth( $aw_auth_code ) {
		$return = array();

		$descr = '';

		try {
			list($consumer_key, $consumer_secret, $access_key, $access_secret) = AWeberAPI::getDataFromAweberID( $aw_auth_code );
		} catch ( AWeberAPIException $exc ) {
			list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;

			if ( isset( $exc->message ) ) {
				$descr = $exc->message;
				$descr = preg_replace( '/http.*$/i', '', $descr );     // strip labs.aweber.com documentation url from error message
				$descr = preg_replace( '/[\.\!:]+.*$/i', '', $descr ); // strip anything following a . : or ! character
				$descr = '(' . $descr . ')';
			}
		} catch ( AWeberOAuthDataMissing $exc ) {
			list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
		} catch ( AWeberException $exc ) {
			list($consumer_key, $consumer_secret, $access_key, $access_secret) = null;
		}

		if ( ! $access_secret ) {
			$return['Error'] = 'Unable to connect to your AWeber Account ' . $descr;

			$return['Ok'] = false;
		} else {
			$aw_auth_info = array(
				'consumer_key'    => $consumer_key,
				'consumer_secret' => $consumer_secret,
				'access_key'      => $access_key,
				'access_secret'   => $access_secret,
			);

			update_option( 'bten_aw_auth_info', $aw_auth_info );

			$return['Ok'] = true;
		}

		return $return;
	}

	// aweber
	function bten_get_aw_lists() {

		$list = array();

		if ( get_option( 'bten_aw_auth_info' ) ) {
			$aw = get_option( 'bten_aw_auth_info' );
			try {
				$aweber  = new AWeberAPI( $aw['consumer_key'], $aw['consumer_secret'] );
				$account = $aweber->getAccount( $aw['access_key'], $aw['access_secret'] );
				$res     = $account->lists;
				if ( $res ) {
					foreach ( (array) $res->data['entries'] as $v ) {
						$list[ $v['id'] ] = array( 'name' => $v['name'] );
					}
				}
			} catch ( AWeberException $e ) {
				$list[0] = array( 'name' => 'Connection problem' );

				return $list;
			}
		}

		if ( count( $list ) == 0 ) {
			$list[0] = array( 'name' => 'No Lists Found' );
		}

		return $list;
	}

	function bten_aweber_action( $email, $sid, $fname ) {
		if ( ! empty( $email ) && ! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
			if ( get_option( 'bten_aw_auth_info' ) ) {
				$aw = get_option( 'bten_aw_auth_info' );
				try {
					$aweber  = new AWeberAPI( $aw['consumer_key'], $aw['consumer_secret'] );
					$account = $aweber->getAccount( $aw['access_key'], $aw['access_secret'] );

					$data = $this->bten_get_aw_lists();

					$result = array(
						'status' => false,
						'log'    => array(
							'errorMessage' => '',
						),
					);

					$subscriber = array(
						'email'      => $email,
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'name'       => $fname,
					);

					$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

					if ( ! isset( $listids['aweber']['list-id'] ) ) {
						$listid           = absint( ( $blossomthemes_email_newsletter_settings['aweber']['list-id'] ) );
						$list             = $account->loadFromUrl( '/accounts/' . $account->id . '/lists/' . $listid );
						$r                = $list->subscribers->create( $subscriber );
						$result['status'] = true;
					} else {
						foreach ( $listids['aweber']['list-id'] as $key => $value ) {

							$list = $account->loadFromUrl( '/accounts/' . $account->id . '/lists/' . $key );
							$r    = $list->subscribers->create( $subscriber );
						}
						$result['status'] = true;
					}
				} catch ( AWeberException $e ) {
					$result['log']['errorMessage'] = 'AWEBER Error: ' . $e->getMessage();
				}
			}
		}
		return $result;
	}
}
new Blossomthemes_Email_Newsletter_AWeber();
