<?php
/**
 * NewServiceCreation.
 * php version 5.6
 *
 * @category NewServiceCreation
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

if ( ! class_exists( 'NewServiceCreation' ) ) :

	/**
	 * NewServiceCreation
	 *
	 * @category NewServiceCreation
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class NewServiceCreation {


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
		public $trigger = 'ss_new_service_created';

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
				'label'         => __( 'New Service Created', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'surelywp_services_create',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;

		}

		/**
		 * Trigger listener
		 *
		 * @param array $service_data Service Data.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $service_data ) {

			$service_data_arr = [
				'service_setting_id' => $service_data['service_id'],
				'order_id'           => $service_data['order_id'],
				'product_id'         => $service_data['product_id'],
				'service_status'     => $service_data['service_status'],
				'delivery_date'      => $service_data['delivery_date'],
			];
			$context          = array_merge( $service_data_arr, WordPress::get_user_context( $service_data['user_id'] ) );
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
	NewServiceCreation::get_instance();

endif;
