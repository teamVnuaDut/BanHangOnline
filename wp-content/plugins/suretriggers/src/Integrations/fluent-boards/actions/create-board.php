<?php
/**
 * CreateBoard.
 * php version 5.6
 *
 * @category CreateBoard
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
 * CreateBoard
 *
 * @category CreateBoard
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateBoard extends AutomateAction {


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
	public $action = 'fbs_create_board';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Board', 'suretriggers' ),
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
		$title          = $selected_options['title'] ? sanitize_text_field( $selected_options['title'] ) : '';
		$description    = $selected_options['description'] ? sanitize_text_field( $selected_options['description'] ) : '';
		$crm_contact_id = $selected_options['crm_contact_id'] ? sanitize_text_field( $selected_options['crm_contact_id'] ) : '';
		$created_by     = $selected_options['created_by'] ? sanitize_text_field( $selected_options['created_by'] ) : '';
		$board_data     = array_filter(
			[
				'title'          => $title,
				'description'    => $description,
				'crm_contact_id' => $crm_contact_id,
				'created_by'     => $created_by,
			],
			fn( $value) => '' !== $value
		);
			if ( ! function_exists( 'FluentBoardsApi' ) ) {
				return;
			}
			$board = FluentBoardsApi( 'boards' )->create( $board_data );
			if ( empty( $board ) ) {
				throw new Exception( 'There is error while creating a Board.' );
			}
			return $board;
	}
}

CreateBoard::get_instance();
