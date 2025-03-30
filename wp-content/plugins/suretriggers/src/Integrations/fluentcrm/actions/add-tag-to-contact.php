<?php
/**
 * AddTagToContact.
 * php version 5.6
 *
 * @category AddTagToContact
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\FluentCRM\Actions;

use Exception;
use SureTriggers\Integrations\AutomateAction;
use SureTriggers\Traits\SingletonLoader;
use FluentCrm\App\Models\Tag;

/**
 * AddTagToContact
 *
 * @category AddTagToContact
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddTagToContact extends AutomateAction {


	/**
	 * Integration type.
	 *
	 * @var string
	 */
	public $integration = 'FluentCRM';

	/**
	 * Action name.
	 *
	 * @var string
	 */
	public $action = 'fluentcrm_add_tag_to_contact';

	use SingletonLoader;

	/**
	 * Register a action.
	 *
	 * @param array $actions actions.
	 * @return array
	 */
	public function register( $actions ) {

		$actions[ $this->integration ][ $this->action ] = [
			'label'    => __( 'Add Tag', 'suretriggers' ),
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
	 * @param array $selected_options selectedOptions.
	 * @return array
	 * @throws Exception Exception.
	 */
	public function _action_listener( $user_id, $automation_id, $fields, $selected_options ) {
		$contact_api = FluentCrmApi( 'contacts' );

		$contact = $contact_api->getContact( trim( $selected_options['contact_email'] ) );

		if ( is_null( $contact ) ) {
			throw new Exception( 'Invalid contact.' );
		}

		$tag_ids      = [];
		$tag_names    = [];
		$selected_tag = $selected_options['tag_id'];
		if ( ! empty( $selected_tag ) ) {
			if ( is_array( $selected_tag ) ) {
				foreach ( $selected_tag as $tag ) {
					$tag_ids[]   = $tag['value'];
					$tag_names[] = esc_html( $tag['label'] );
				}
			} elseif ( is_string( $selected_tag ) ) {
				$tags_arr = array_filter( explode( ',', $selected_tag ) );
				if ( ! class_exists( 'FluentCrm\App\Models\Tag' ) ) {
					throw new Exception( 'Tag model not found.' );
				}
				if ( ! empty( $tags_arr ) ) {
					foreach ( $tags_arr as $tag ) {
						$exist = Tag::where( 'title', $tag )
						->orWhere( 'slug', $tag )
						->first();
						if ( is_null( $exist ) ) {
							$new_tag     = Tag::create(
								[
									'title' => $tag,
								]
							);
							$tag_ids[]   = $new_tag->id;
							$tag_names[] = esc_html( $new_tag->title );
						} else {
							$tag_ids[]   = $exist->id;
							$tag_names[] = esc_html( $exist->title );
						}
					}
				}
			}
		}

		$contact->attachTags( $tag_ids );

		$context                   = [];
		$context['tag_name']       = implode( ',', $tag_names );
		$context['full_name']      = $contact->full_name;
		$context['first_name']     = $contact->first_name;
		$context['last_name']      = $contact->last_name;
		$context['contact_owner']  = $contact->contact_owner;
		$context['company_id']     = $contact->company_id;
		$context['email']          = $contact->email;
		$context['address_line_1'] = $contact->address_line_1;
		$context['address_line_2'] = $contact->address_line_2;
		$context['postal_code']    = $contact->postal_code;
		$context['city']           = $contact->city;
		$context['state']          = $contact->state;
		$context['country']        = $contact->country;
		$context['phone']          = $contact->phone;
		$context['status']         = $contact->status;
		$context['contact_type']   = $contact->contact_type;
		$context['source']         = $contact->source;
		$context['date_of_birth']  = $contact->date_of_birth;
		return $context;
	}

}

AddTagToContact::get_instance();
