<?php
/**
 * CreateSubTask.
 * php version 5.6
 *
 * @category CreateSubTask
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
use FluentBoardsPro\App\Services\SubtaskService;

/**
 * CreateSubTask
 *
 * @category CreateSubTask
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateSubTask extends AutomateAction {


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
	public $action = 'fbs_create_sub_task';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Sub Task', 'suretriggers' ),
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
		$task_id     = $selected_options['task_id'] ? sanitize_text_field( $selected_options['task_id'] ) : '';
		$title       = $selected_options['title'] ? sanitize_text_field( $selected_options['title'] ) : '';
		$description = $selected_options['description'] ? sanitize_text_field( $selected_options['description'] ) : '';
		$board_id    = $selected_options['board_id'] ? sanitize_text_field( $selected_options['board_id'] ) : '';
		$stage_id    = $selected_options['stage_id'] ? sanitize_text_field( $selected_options['stage_id'] ) : '';
		$priority    = $selected_options['priority'] ? sanitize_text_field( $selected_options['priority'] ) : '';
		$status      = $selected_options['status'] ? sanitize_text_field( $selected_options['status'] ) : '';
		$labels      = $selected_options['labels'] ? explode( ',', sanitize_text_field( $selected_options['labels'] ) ) : '';
		$created_by  = $selected_options['created_by'] ? sanitize_text_field( $selected_options['created_by'] ) : '';
		$task_data   = array_filter(
			[
				'title'       => $title,
				'description' => $description,
				'board_id'    => $board_id,
				'stage_id'    => $stage_id,
				'priority'    => $priority,
				'status'      => $status,
				'labels'      => $labels,
				'created_by'  => $created_by,
			],
			fn( $value) => '' !== $value
		);
			if ( ! class_exists( '\FluentBoardsPro\App\Services\SubtaskService' ) ) {
				return;
			}
			
			$task_service = new SubtaskService();
			$sub_task     = $task_service->createSubtask(
				$task_id,
				$task_data
			);
		if ( empty( $sub_task ) ) {
			throw new Exception( 'There is error while creating a Sub Task.' );
		}
		return $sub_task;
	}
}

CreateSubTask::get_instance();
