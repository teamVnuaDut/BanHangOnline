<?php
/**
 * UserJoinsPrivateGroup.
 * php version 5.6
 *
 * @category UserJoinsPrivateGroup
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\BuddyBoss\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Integrations\WordPress\WordPress;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'UserJoinsPrivateGroup' ) ) :
	/**
	 * UserJoinsPrivateGroup
	 *
	 * @category UserJoinsPrivateGroup
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class UserJoinsPrivateGroup {

		use SingletonLoader;

		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'BuddyBoss';

		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'bb_user_joins_private_group';

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
		 * @param array $triggers triggers.
		 *
		 * @return array
		 */
		public function register( $triggers ) {
			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'User Joins Private Group.', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => [
					'groups_membership_accepted',
					'groups_accept_invite',
				],
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 60,
				'accepted_args' => 2,
			];

			return $triggers;
		}

		/**
		 *  Trigger listener
		 *
		 * @param int $user_id user id.
		 * @param int $group_id group id.
		 *
		 * @return void
		 */
		public function trigger_listener( $user_id, $group_id ) {
			$context = WordPress::get_user_context( $user_id );
			$avatar  = get_avatar_url( $user_id );
			$group   = groups_get_group( $group_id );

			if ( 'private' === $group->status ) {
				$context['avatar_url']        = ( $avatar ) ? $avatar : '';
				$context['group_id']          = ( property_exists( $group, 'id' ) ) ? (int) $group->id : '';
				$context['group_name']        = ( property_exists( $group, 'name' ) ) ? $group->name : '';
				$context['group_description'] = ( property_exists( $group, 'description' ) ) ? $group->description : '';

				AutomationController::sure_trigger_handle_trigger(
					[
						'trigger'    => $this->trigger,
						'wp_user_id' => $user_id,
						'context'    => $context,
					]
				);
			}
		}
	}

	UserJoinsPrivateGroup::get_instance();
endif;
