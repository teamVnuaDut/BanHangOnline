<?php
/**
 * AddUserToBoard.
 * php version 5.6
 *
 * @category AddUserToBoard
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentBoards\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use FluentBoards\App\Services\BoardService;
/**
 * AddUserToBoard
 *
 * @category AddUserToBoard
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUserToBoard extends AutomateAction {


	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'FluentBoards';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'fbs_add_user_to_board';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add User to Board', 'suretriggers' ),
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
		$board_id = $selected_options['board_id'] ? sanitize_text_field( $selected_options['board_id'] ) : '';
		$assignee = $selected_options['assignee'] ? sanitize_text_field( $selected_options['assignee'] ) : '';
		if ( ! class_exists( 'FluentBoards\App\Services\BoardService' ) ) {
			return;
		}
		$board_service = new BoardService();
		$member        = $board_service->addMembersInBoard(
			$board_id,
			$assignee
		);

		return $member;
	}
}

AddUserToBoard::get_instance();
