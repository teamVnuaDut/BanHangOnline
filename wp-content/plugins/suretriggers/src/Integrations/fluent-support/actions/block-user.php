<?php
/**
 * BlockUser.
 * php version 5.6
 *
 * @category BlockUser
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentSupport\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use FluentSupport\App\Models\Customer;

/**
 * BlockUser
 *
 * @category BlockUser
 * @package  SureTriggers
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class BlockUser extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'FluentSupport';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'block_user_fluent_support';

	use SingletonLoader;

	/**
	 * Register an action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Block User', 'suretriggers' ),
			'action'   => $this->action,
			'function' => [ $this, 'action_listener' ],
		];

		return $actions;

	}

	/**
	 * Action listener.
	 *
	 * @param int   $user_id user_id.
	 * @param int   $automation_id automation_id.
	 * @param array $fields fields.
	 * @param array $selected_options selected_options.
	 *
	 * @return array|void
	 *
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$email = sanitize_email( $selected_options['customer_email'] );

		if ( ! is_email( $email ) ) {
			throw new Exception( 'Invalid email.' );
		}

		if ( ! class_exists( 'FluentSupport\App\Models\Customer' ) ) {
			throw new Exception( 'Error: Fluent Support plugin is missing or not installed correctly.' );
		}

		$customer_record = Customer::where( 'email', $email )->first();

		if ( ! $customer_record ) {
			throw new Exception( 'User not found in Fluent Support.' );
		}

		// Update status to "Blocked".
		$customer_record->update( [ 'status' => 'blocked' ] );

		return [
			'message' => 'User successfully blocked.',
			'email'   => $email,
		];
	}
}

BlockUser::get_instance();
