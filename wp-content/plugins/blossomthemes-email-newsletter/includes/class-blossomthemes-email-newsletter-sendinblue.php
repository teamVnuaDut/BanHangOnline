<?php
/**
 * Sendinblue Handler.
 */
class Blossomthemes_Email_Newsletter_Sendinblue {

	/**
	 * Get Seninblue lists.
	 *
	 * @return void
	 */
	public function get_lists() {
		$lists = array();

		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', false );

		if ( ! $blossomthemes_email_newsletter_settings ) {
			return $lists;
		}

		$api_key = isset( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) : '';
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
	 * Action for form submissions.
	 *
	 * @return void
	 */
	public function action_form_submission( $email, $sid, $fname ) {

		// MailChimp API credentials
		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );

		$listids = get_post_meta( $sid, 'blossomthemes_email_newsletter_setting', true );

		if ( ! isset( $listids['sendinblue']['list-id'] ) ) {
			$listids = $blossomthemes_email_newsletter_settings['sendinblue']['list-id'];
			$result  = $this->create_subscriber( $email, $listids, $info = array( 'name' => $fname ), 'simple', $unlinkedLists = array() );
		} else {
			// foreach ( $listids['sendinblue']['list-id'] as $key => $value ) {
				$result = $this->create_subscriber( $email, $listids['sendinblue']['list-id'], $info = array( 'name' => $fname ), 'simple', $unlinkedLists = array() );
			// }
		}

		return $result;
	}

	/**
	 * Signup process
	 *
	 * @param string                     $type - simple, confirm, double-optin / subscribe.
	 * @param $email - subscriber email.
	 * @param $list_id - desired list ids.
	 * @param $info - user's attributes.
	 * @param null                       $list_unlink - remove temp list.
	 * @return string
	 */
	public function create_subscriber( $email, $list_id, $info, $type = 'simple', $list_unlink = null ) {
		$mailin = new Blossom_Sendinblue_API_Client();
		$user   = $mailin->getUser( $email );

		$response = $this->validation_email( $user, $email, $list_id, $type );
		$exist    = '';

		if ( 'already_exist' == $response['code'] ) {
			$exist = 'already_exist';
		}

		if ( 'subscribe' == $type ) {
			$info['DOUBLE_OPT-IN'] = '1'; // Yes.
		} elseif ( 'double-optin' == $type ) {
			if ( ( 'new' == $response['code'] && ! $response['isDopted'] ) || ( 'update' == $response['code'] && ! $response['isDopted'] ) ) {
				$info['DOUBLE_OPT-IN'] = '2'; // No.
			}
		}

		$listid = $response['listid'];
		if ( $list_unlink != null ) {
			$listid = array_diff( $listid, $list_unlink );
		}

		$attributes = $this->get_attributes();
		if ( ! empty( $attributes['attributes']['normal_attributes'] ) ) {
			foreach ( $attributes['attributes']['normal_attributes'] as $key => $value ) {
				if ( 'boolean' == $value['type'] && array_key_exists( $value['name'], $info ) ) {
					if ( in_array( $info[ $value['name'] ], array( 'true', 'True', 'TRUE', 1 ) ) ) {
						$info[ $value['name'] ] = true;
					} else {
						$info[ $value['name'] ] = false;
					}
				}
			}
		}

		$info = array(
			'email'     => $email,
			'FIRSTNAME' => $info['name'],
		);

		if ( $mailin->getLastResponseCode() === Blossom_Sendinblue_API_Client::RESPONSE_CODE_OK && isset( $user['email'] ) ) {
			unset( $info['email'] );
			if ( isset( $info['internalUserHistory'] ) && is_array( $info['internalUserHistory'] ) ) {
					$info['internalUserHistory'][] = array(
						'action' => 'SUBSCRIBE_BY_PLUGIN',
						'id'     => 1,
						'name'   => 'Blossomthemes Email Newsletter',
					);
			} else {
					$info['internalUserHistory'] = array(
						array(
							'action' => 'SUBSCRIBE_BY_PLUGIN',
							'id'     => 1,
							'name'   => 'Blossomthemes Email Newsletter',
						),
					);
			}
			$data = array(
				'email'            => $email,
				'attributes'       => $info,
				'emailBlacklisted' => false,
				'smsBlacklisted'   => false,
				'listIds'          => $listid,
				'unlinkListIds'    => $list_unlink,
			);
			$mailin->updateUser( $email, $data );
			$exist = $mailin->getLastResponseCode() == 204 ? 'success' : '';
		} else {
			$info['internalUserHistory'] = array(
				array(
					'action' => 'SUBSCRIBE_BY_PLUGIN',
					'id'     => 1,
					'name'   => 'Blossomthemes Email Newsletter',
				),
			);
			$data                        = array(
				'email'            => $email,
				'attributes'       => $info,
				'emailBlacklisted' => false,
				'smsBlacklisted'   => false,
				'listIds'          => $listid,
			);

			$created_user = $mailin->createUser( $data );
		}

		if ( '' != $exist ) {
			$response['code'] = $exist;
		} elseif ( isset( $created_user['id'] ) ) {
			$response['code'] = 'success';
		}

		return $response['code'];
	}

	/**
	 * Validation the email if it exist in contact list
	 *
	 * @param $res
	 * @param string $type - form type.
	 * @param string $email - email.
	 * @param array  $list_id - list ids.
	 * @return array
	 */
	static function validation_email( $res, $email, $list_id, $type = 'simple' ) {

		$isDopted = false;

		$temp_dopt_list = get_option( 'bten_sib_temp_list' );
		$desired_lists  = $list_id;

		if ( 'double-optin' == $type ) {
			$list_id = array( $temp_dopt_list );
		}

		// new user.
		if ( isset( $res['code'] ) && $res['code'] == 'document_not_found' ) {
			$ret = array(
				'code'     => 'new',
				'isDopted' => $isDopted,
				'listid'   => $list_id,
			);
			return $ret;
		}

		// check if list exists and add.
		if ( isset( $res['listIds'] ) && ! empty( $res['listIds'] ) ) {
			if ( is_array( $list_id ) ) {
				$add_list  = array_keys( $list_id );
				$diff_list = array_diff( $add_list, $res['listIds'] );
				if ( ! empty( $diff_list ) ) {
					$ret = array(
						'code'     => 'new',
						'isDopted' => $isDopted,
						'listid'   => $diff_list,
					);
					return $ret;
				}
			} elseif ( ! in_array( $list_id, $res['listIds'] ) ) {
				$ret = array(
					'code'     => 'new',
					'isDopted' => $isDopted,
					'listid'   => $list_id,
				);
				return $ret;
			}
		}

		$listid = $res['listIds'];

		// update user when listid is empty.
		if ( ! isset( $listid ) || ! is_array( $listid ) ) {
			$ret = array(
				'code'     => 'update',
				'isDopted' => $isDopted,
				'listid'   => $list_id,
			);
			return $ret;
		}

		$attrs = $res['attributes'];
		if ( isset( $attrs['DOUBLE_OPT-IN'] ) && '1' == $attrs['DOUBLE_OPT-IN'] ) {
			$isDopted = true;
		}
		// remove dopt temp list from $listid.
		if ( ( $key = array_search( $temp_dopt_list, $listid ) ) !== false ) {
			unset( $listid[ $key ] );
		}

		// $diff = array_diff( $desired_lists, $listid );
		// if ( ! empty( $diff ) ) {
		// $status = 'update';
		// if ( 'double-optin' != $type ) {
		// $listid = array_unique( array_merge( $listid, $list_id ) );
		// }
		// if ( ( 'double-optin' == $type && ! $isDopted) ) {
		// array_push( $listid, $temp_dopt_list );
		// }
		// } else {
		if ( '1' == $res['emailBlacklisted'] ) {
			$status = 'update';
		} else {
			$status = 'already_exist';
		}
			// }

			$ret = array(
				'code'     => $status,
				'isDopted' => $isDopted,
				'listid'   => $listid,
			);
			return $ret;
	}

	/**
	 * Get Attributes.
	 *
	 * @return void
	 */
	public function get_attributes() {

		$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', false );

		$api_key = isset( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) : '';
		// get attributes.
		$attrs = get_transient( 'sib_attributes_' . md5( $api_key ) );

		if ( false === $attrs || false == $attrs ) {
			$mailin     = new Blossom_Sendinblue_API_Client();
			$response   = $mailin->getAttributes();
			$attributes = $response['attributes'];
			$attrs      = array(
				'attributes' => array(
					'normal_attributes'   => array(),
					'category_attributes' => array(),
				),
			);

			if ( count( $attributes ) > 0 ) {
				foreach ( $attributes as $key => $value ) {
					if ( $value['category'] == 'normal' ) {
						$attrs['attributes']['normal_attributes'][] = $value;
					} elseif ( $value['category'] == 'category' ) {
						$value['type']                                = 'category';
						$attrs['attributes']['category_attributes'][] = $value;
					}
				}
			}

			set_transient( 'sib_attributes_' . md5( $api_key ), $attrs, 4 * HOUR_IN_SECONDS );
		}

		return $attrs;
	}
}
new Blossomthemes_Email_Newsletter_Sendinblue();
