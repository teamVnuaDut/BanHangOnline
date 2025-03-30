<?php
/**
 * UpdateTask.
 * php version 5.6
 *
 * @category UpdateTask
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
/**
 * UpdateTask
 *
 * @category UpdateTask
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdateTask extends AutomateAction {


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
	public $action = 'fbs_update_task';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Update Task', 'suretriggers' ),
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
		$title    = $selected_options['title'] ? sanitize_text_field( $selected_options['title'] ) : '';
		$task_id  = $selected_options['task_id'] ? sanitize_text_field( $selected_options['task_id'] ) : '';
		$board_id = $selected_options['board_id'] ? sanitize_text_field( $selected_options['board_id'] ) : '';
		$stage_id = $selected_options['stage_id'] ? sanitize_text_field( $selected_options['stage_id'] ) : '';
		$priority = $selected_options['priority'] ? sanitize_text_field( $selected_options['priority'] ) : '';
		$status   = $selected_options['status'] ? sanitize_text_field( $selected_options['status'] ) : '';

		// Check if FluentBoardsApi function exists, if not, return early.
		if ( ! function_exists( 'FluentBoardsApi' ) ) {
			return;
		}
		$task_data = array_filter(
			[
				'title'    => $title,
				'board_id' => $board_id,
				'stage_id' => $stage_id,
				'priority' => $priority,
				'status'   => $status,
			],
			fn( $value) => '' !== $value
		);
			foreach ( $task_data as $key => $value ) {
				FluentBoardsApi( 'tasks' )->updateProperty( $task_id, $key, $value );
			}
			$task = FluentBoardsApi( 'tasks' )->getTask( $task_id );
			if ( empty( $task ) ) {
				throw new Exception( 'There is error while creating a Task.' );
			}
			return $task; 
	}
}

UpdateTask::get_instance();
