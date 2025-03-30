<?php
/**
 * AddAttributeTerms.
 * php version 5.6
 *
 * @category AddAttributeTerms
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
 * AddAttributeTerms
 *
 * @category AddAttributeTerms
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddAttributeTerms extends AutomateAction {

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
	public $action = 'wc_add_attribute_terms';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add Attribute Terms', 'suretriggers' ),
			'action'   => 'wc_add_attribute_terms',
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
	 * @return object|array|bool|\WP_Error|\WP_Term
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$term_args = [];

		if ( '' !== $selected_options['term_description'] ) {
			$term_args = [
				'description' => $selected_options['term_description'],
			];
		}

		$term_name = $selected_options['term_name'];
		$term_tax  = $selected_options['term_slug'];

		$attribute_terms = wp_insert_term( $term_name, $term_tax, $term_args );

		if ( is_wp_error( $attribute_terms ) ) {
			return [
				'status'  => 'error',
				'message' => $attribute_terms->get_error_message(),
			];
		} else {
			$terms = get_term_by( 'id', $attribute_terms['term_taxonomy_id'], $term_tax );
			return $terms;
		}
	}
}

AddAttributeTerms::get_instance();
