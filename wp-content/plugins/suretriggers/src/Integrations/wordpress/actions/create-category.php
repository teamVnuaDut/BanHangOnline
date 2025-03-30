<?php
/**
 * CreateCategory.
 * php version 5.6
 *
 * @category CreateCategory
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Wordpress\Actions;

use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use WP_User;
use Exception;

/**
 * CreateCategory
 *
 * @category CreateCategory
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateCategory extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'WordPress';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'create_category';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create New Category', 'suretriggers' ),
			'action'   => 'create_category',
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
	 * @param array $selected_options selectedOptions.
	 * @return array|string
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$category_name        = $selected_options['cat_name'];
		$category_description = $selected_options['cat_description'];
		$category_slug        = $selected_options['cat_slug'];
		$parent_category      = isset( $selected_options['parent_category'] ) && ! empty( $selected_options['parent_category'] ) ? $selected_options['parent_category'] : 0;
		$taxonomy             = ! empty( $selected_options['taxonomy'] ) ? $selected_options['taxonomy'] : 'category';
		
		$category = wp_insert_term(
			$category_name,
			$taxonomy,
			[
				'description' => $category_description,
				'slug'        => $category_slug,
				'parent'      => $parent_category,
			]
		);

		if ( $category ) {
			return [
				$category,
				$taxonomy,
				$category_name,
			];
		} else {
			throw new Exception( 'Not able to create a category.' );
		}
		
	}
}

CreateCategory::get_instance();
