<?php
/**
 * ErrorInEmailDelivery.
 * php version 5.6
 *
 * @category ErrorInEmailDelivery
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentSMTP\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'ErrorInEmailDelivery' ) ) :

	/**
	 * ErrorInEmailDelivery
	 *
	 * @category ErrorInEmailDelivery
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class ErrorInEmailDelivery {

		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'FluentSMTP';

		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'fs_email_delivery_error';

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
				'label'         => __( 'Error in Email Delivery', 'suretriggers' ),
				'action'        => 'fs_email_delivery_error',
				'common_action' => 'fluentmail_email_sending_failed_no_fallback',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 3,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param int   $log_id ID.
		 * @param array $handler ID.
		 * @param array $data Data.
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function trigger_listener( $log_id, $handler, $data ) {
			if ( empty( $data ) ) {
				return;
			}
			
			$context['to']          = unserialize( $data['to'] );
			$context['from']        = $data['from'];
			$context['subject']     = $data['subject'];
			$context['body']        = $data['body'];
			$context['attachments'] = unserialize( $data['attachments'] );
			$context['status']      = $data['status'];
			$context['response']    = unserialize( $data['response'] );
			$context['headers']     = unserialize( $data['headers'] );
			$context['extra']       = unserialize( $data['extra'] );

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
	ErrorInEmailDelivery::get_instance();

endif;
