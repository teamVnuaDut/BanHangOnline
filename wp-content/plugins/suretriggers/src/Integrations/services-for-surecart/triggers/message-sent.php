<?php
/**
 * MessageSent.
 * php version 5.6
 *
 * @category MessageSent
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\ServicesForSureCart\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Integrations\WordPress\WordPress;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'MessageSent' ) ) :

	/**
	 * MessageSent
	 *
	 * @category MessageSent
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class MessageSent {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'ServicesForSureCart';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'ss_message_sent';

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
				'label'         => __( 'Message Sent', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'surelywp_services_message_send',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;

		}

		/**
		 * Trigger listener
		 *
		 * @param array $message_data Message Data.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $message_data ) {
			
			$message_data_arr      = [
				'sender'               => WordPress::get_user_context( $message_data['sender_id'] ),
				'receiver'             => WordPress::get_user_context( $message_data['receiver_id'] ),
				'service_id'           => $message_data['service_id'],
				'message_text'         => $message_data['message_text'],
				'attachment_file_name' => $message_data['attachment_file_name'],
				'is_final_delivery'    => $message_data['is_final_delivery'],
			];
			$context               = $message_data_arr;
			$upload_dir            = wp_upload_dir();
			$attachment_file_names = json_decode( $message_data['attachment_file_name'], true );
			foreach ( (array) $attachment_file_names as $attachment_file_name ) {
				$context['attachment_file'][] = $upload_dir['baseurl'] . '/surelywp-services-data/' . $message_data['service_id'] . '/messages/' . $attachment_file_name;
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
	MessageSent::get_instance();

endif;
