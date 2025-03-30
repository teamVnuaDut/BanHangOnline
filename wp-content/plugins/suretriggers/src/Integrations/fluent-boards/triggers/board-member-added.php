<?php
/**
 * BoardMemberAdded.
 * php version 5.6
 *
 * @category BoardMemberAdded
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentBoards\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'BoardMemberAdded' ) ) :

	/**
	 * BoardMemberAdded
	 *
	 * @category BoardMemberAdded
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class BoardMemberAdded {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'FluentBoards';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'fbs_board_member_added';

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
				'label'         => __( 'Board Member Added', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'fluent_boards/board_member_added',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 2,
			];

			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param int    $board_id Board ID.
		 * @param object $board_member Board Member.
		 * @return void
		 */
		public function trigger_listener( $board_id, $board_member ) {

			$context['board_id']     = $board_id;
			$context['board_member'] = $board_member;
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
	BoardMemberAdded::get_instance();

endif;
