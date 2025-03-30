<?php
/**
 * NewMessageSent.
 * php version 5.6
 *
 * @category NewMessageSent
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\SupportPortalForSureCart\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Integrations\WordPress\WordPress;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'NewMessageSent' ) ) :

	/**
	 * NewMessageSent
	 *
	 * @category NewMessageSent
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class NewMessageSent {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'SupportPortalForSureCart';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'sps_new_message_sent';

		use SingletonLoader;


		/**
		 * Constructor
		 *
		 * @since  1.0.0
		 */
		public function __construct() {
			add_filter( 'sure_trigger_register_trigger', [ $this, 'register' ] );
		}

		/**
		 * Register action.
		 *
		 * @param array $triggers trigger data.
		 * @return array
		 */
		public function register( $triggers ) {

			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'New Message Sent', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'surelywp_sp_support_message_send',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;

		}

		/**
		 * Trigger listener
		 *
		 * @param array $message_data Message.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $message_data ) {
			$upload_dir          = wp_upload_dir();
			$context             = $message_data;
			$context['sender']   = WordPress::get_user_context( $message_data['sender_id'] );
			$context['receiver'] = WordPress::get_user_context( $message_data['receiver_id'] );
			if ( ! empty( $message_data['attachment_file_name'] ) ) {
				$attachment_file_names = json_decode( $message_data['attachment_file_name'], true );
				foreach ( (array) $attachment_file_names as $attachment_file_name ) {
					$context['attachment_file'][] = $upload_dir['baseurl'] . '/surelywp-support-portal-data/' . $message_data['support_id'] . '/messages/' . $attachment_file_name;
				}
			}
			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);

		}
	}

	/**
	 * Ignore false positive
	 *
	 * @psalm-suppress UndefinedMethod
	 */
	NewMessageSent::get_instance();

endif;
