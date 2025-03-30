<?php
/**
 * CreateAttribute.
 * php version 5.6
 *
 * @category CreateAttribute
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Woocommerce\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Integrations\WooCommerce\WooCommerce;
use SureTriggers\Traits\SingletonLoader;

/**
 * CreateAttribute
 *
 * @category CreateAttribute
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateAttribute extends AutomateAction {

	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'WooCommerce';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'wc_create_attribute';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Create Attributes', 'suretriggers' ),
			'action'   => 'wc_create_attribute',
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
	 *
	 * @return object|array|void
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$attributes_args = [
			'name'         => $selected_options['attribute_name'],
			'slug'         => wc_sanitize_taxonomy_name( wp_unslash( $selected_options['attribute_slug'] ) ),
			'order_by'     => 'menu_order',
			'has_archives' => 1,
		];

		$attribute = wc_create_attribute( $attributes_args );

		if ( is_wp_error( $attribute ) ) {
			return [
				'status'  => 'error',
				'message' => $attribute->get_error_message(),
			];
		} else {
			$get_attribute = wc_get_attribute( $attribute );
			if ( $get_attribute ) {
				return [
					'status'         => 'success',
					'attribute_id'   => $attribute,
					'attribute_name' => $get_attribute->name,
					'attribute_slug' => $get_attribute->slug,
				];
			}
		}
	}
}

CreateAttribute::get_instance();
