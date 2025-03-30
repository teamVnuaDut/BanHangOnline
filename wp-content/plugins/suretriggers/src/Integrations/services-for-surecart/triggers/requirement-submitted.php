<?php
/**
 * RequirementSubmitted.
 * php version 5.6
 *
 * @category RequirementSubmitted
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

if ( ! class_exists( 'RequirementSubmitted' ) ) :

	/**
	 * RequirementSubmitted
	 *
	 * @category RequirementSubmitted
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class RequirementSubmitted {


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
		public $trigger = 'ss_requirement_submitted';

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
				'label'         => __( 'Requirement Submitted', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'surelywp_services_requirement_submit',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;

		}

		/**
		 * Trigger listener
		 *
		 * @param string $requirements_data Requirements Data.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $requirements_data ) {
			global $wpdb;
			if ( is_array( $requirements_data ) && ! empty( $requirements_data ) ) {
				$service_result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}surelywp_sv_services WHERE service_id = %d", $requirements_data[0]['service_id'] ), ARRAY_A );
				$user_data      = WordPress::get_user_context( $service_result['user_id'] );
				unset( $service_result['user_id'] );
				$context = array_merge( $requirements_data, $service_result, $user_data );
				foreach ( $requirements_data as $value ) {
					if ( 'file' == $value['requirement_type'] ) {
						$upload_dir            = wp_upload_dir();
						$attachment_file_names = json_decode( $value['requirement'], true );
						foreach ( (array) $attachment_file_names as $attachment_file_name ) {
							$context['requirement_attachment_file'][] = $upload_dir['baseurl'] . '/surelywp-services-data/' . $value['service_id'] . '/requirement/' . $attachment_file_name;
						}
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
	}

	/**
	 * Ignore false positive
	 *
	 * @psalm-suppress UndefinedMethod
	 */
	RequirementSubmitted::get_instance();

endif;
