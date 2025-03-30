<?php
/**
 * AddTaxonomyToPost.
 * php version 5.6
 *
 * @category AddTaxonomyToPost
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
 * AddTaxonomyToPost
 *
 * @category AddTaxonomyToPost
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddTaxonomyToPost extends AutomateAction {

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
	public $action = 'add_taxonomy_to_post';

	use SingletonLoader;

	/**
	 * Register action.
	 *
	 * @param array $actions action data.
	 * @return array
	 */
	public function register( $actions ) {
		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add Taxonomy', 'suretriggers' ),
			'action'   => 'add_taxonomy_to_post',
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
		$post_id       = $selected_options['post_id'];
		$last_response = get_post( $post_id );
		if ( ! $last_response ) {
			throw new Exception( 'Invalid post ID or post not found.' );
		}
		$post_type = get_post_type( $post_id );
		if ( ! $post_type ) {
			throw new Exception( 'Invalid post ID or post type not found.' );
		}
	
		$taxonomy_terms = [];
		
		if ( ! empty( $selected_options['taxonomy'] ) && ! empty( $selected_options['taxonomy_term'] ) ) {
			$this->assign_terms_to_post( $post_id, $selected_options );
		}
		
		$taxonomy_terms = $this->get_post_taxonomy_terms( $post_id );
	
		return [
			'last_response'  => $last_response,
			'taxonomy_terms' => $taxonomy_terms,
		];
	}

	/**
	 * Assign terms to post.
	 *
	 * @param int   $post_id post_id.
	 * @param array $selected_options selected_options.
	 * @return void
	 */
	private function assign_terms_to_post( $post_id, $selected_options ) {
		if ( is_array( $selected_options['taxonomy'] ) && is_array( $selected_options['taxonomy_term'] ) ) {
			foreach ( $selected_options['taxonomy_term'] as $term ) {
				$this->set_object_terms_by_id( $post_id, $term['value'] );
			}
		} elseif ( is_array( $selected_options['taxonomy'] ) && ! is_array( $selected_options['taxonomy_term'] ) ) {
			$this->set_object_terms_by_name( $post_id, $selected_options['taxonomy'], $selected_options['taxonomy_term'] );
		} elseif ( ! is_array( $selected_options['taxonomy'] ) && is_array( $selected_options['taxonomy_term'] ) ) {
			foreach ( $selected_options['taxonomy_term'] as $term ) {
				$this->set_object_terms_by_id( $post_id, $term['value'] );
			}
		} else {
			$this->set_object_terms_by_name(
				$post_id,
				explode( ',', $selected_options['taxonomy'] ),
				$selected_options['taxonomy_term']
			);
		}
	}

	/**
	 * Set object terms by id.
	 *
	 * @param int    $post_id post_id.
	 * @param string $term_value term_value.
	 * @return void
	 */
	private function set_object_terms_by_id( $post_id, $term_value ) {
		$term_parts = explode( '%-%', $term_value );
		if ( count( $term_parts ) === 2 ) {
			list($term_id, $taxonomy) = $term_parts;
			wp_set_object_terms( $post_id, [ (int) $term_id ], $taxonomy, true );
		}
	}

	/**
	 * Set object terms by name.
	 *
	 * @param int    $post_id post_id.
	 * @param array  $taxonomies taxonomies.
	 * @param string $taxonomy_term taxonomy_term.
	 * @return void
	 */
	private function set_object_terms_by_name( $post_id, $taxonomies, $taxonomy_term ) {
		$taxonomy_terms_map = [];
		$taxonomies         = array_map( 'trim', (array) $taxonomies );
		$terms_input        = array_map( 'trim', explode( ',', $taxonomy_term ) );
	
		$available_terms = [];
		foreach ( $taxonomies as $taxonomy ) {
			$available_terms[ $taxonomy ] = get_terms(
				[
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				] 
			);
		}

		foreach ( $terms_input as $input_term ) {
			if ( is_array( $available_terms ) ) {
				foreach ( $available_terms as $taxonomy => $terms ) {
					if ( is_array( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( (int) $term->term_id === (int) $input_term || strtolower( $term->slug ) === $input_term ) {
								$taxonomy_terms_map[ $taxonomy ][] = (int) $term->term_id;
								break 2;
							}
						}
					}               
				}           
			}       
		}
		foreach ( $taxonomy_terms_map as $taxonomy => $term_ids ) {
			wp_set_object_terms( $post_id, $term_ids, $taxonomy, true );
		}
	}

	/**
	 * Get post taxonomy terms.
	 *  
	 * @param int $post_id post_id.
	 * @return array
	 */
	private function get_post_taxonomy_terms( $post_id ) {
		$taxonomy_terms      = [];
		$post_type           = get_post_type( $post_id );
		$response_taxonomies = $post_type ? get_object_taxonomies( $post_type ) : [];

	
		foreach ( $response_taxonomies as $taxonomy_name ) {
			$terms = wp_get_post_terms( $post_id, $taxonomy_name );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$taxonomy_terms = array_merge( $taxonomy_terms, $terms );
			}
		}
		
		return $taxonomy_terms;
	}
	
}

AddTaxonomyToPost::get_instance();
