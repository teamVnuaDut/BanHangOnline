<?php
/**
 * CustomerRequestRevision.
 * php version 5.6
 *
 * @category CustomerRequestRevision
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

if ( ! class_exists( 'CustomerRequestRevision' ) ) :

	/**
	 * CustomerRequestRevision
	 *
	 * @category CustomerRequestRevision
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class CustomerRequestRevision {


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
		public $trigger = 'ss_customer_request_revision';

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
				'label'         => __( 'Customer Request Revision', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'surelywp_services_customer_request_revision',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 2,
			];
			return $triggers;

		}

		/**
		 * Trigger listener
		 *
		 * @param int   $service_id Service ID.
		 * @param array $revision_message Revision Message.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $service_id, $revision_message ) {
			global $wpdb;

			$service_result        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}surelywp_sv_services WHERE service_id = %d", $service_id ), ARRAY_A );
			$context               = array_merge( $service_result, $revision_message );
			$context['sender']     = WordPress::get_user_context( $revision_message['sender_id'] );
			$context['receiver']   = WordPress::get_user_context( $revision_message['receiver_id'] );
			$upload_dir            = wp_upload_dir();
			$attachment_file_names = json_decode( $revision_message['attachment_file_name'], true );
			foreach ( (array) $attachment_file_names as $attachment_file_name ) {
				$context['attachment_file'][] = $upload_dir['baseurl'] . '/surelywp-services-data/' . $revision_message['service_id'] . '/messages/' . $attachment_file_name;
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
	CustomerRequestRevision::get_instance();

endif;
