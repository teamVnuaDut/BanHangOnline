<?php
/**
 * VoxelPostUnFollowed.
 * php version 5.6
 *
 * @category VoxelPostUnFollowed
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'VoxelPostUnFollowed' ) ) :

	/**
	 * VoxelPostUnFollowed
	 *
	 * @category VoxelPostUnFollowed
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class VoxelPostUnFollowed {


		/**
		 * Integration type.
		 *
		 * @var string
		 */
		public $integration = 'Voxel';


		/**
		 * Trigger name.
		 *
		 * @var string
		 */
		public $trigger = 'voxel_post_unfollowed';

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
		 * @param array $triggers trigger data.
		 * @return array
		 */
		public function register( $triggers ) {
			$triggers[ $this->integration ][ $this->trigger ] = [
				'label'         => __( 'Post UnFollowed', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'st_voxel_post_unfollowed',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param array $unfollow_data UnFollow data.
		 * @return void
		 */
		public function trigger_listener( $unfollow_data ) {
			if ( empty( $unfollow_data ) ) {
				return;
			}

			global $wpdb;
			$sql                              = "SELECT COUNT(*) FROM {$wpdb->prefix}voxel_followers WHERE object_id= %d";
			$followers      = $wpdb->get_var( $wpdb->prepare( $sql, $unfollow_data['ID'] ));// @phpcs:ignore
			$unfollow_data['total_followers'] = $followers;
			$context                          = $unfollow_data;
			AutomationController::sure_trigger_handle_trigger(
				[
					'trigger' => $this->trigger,
					'context' => $context,
				]
			);
		}
	}

	/**
	 * Ignore false positive
	 *
	 * @psalm-suppress UndefinedMethod
	 */
	VoxelPostUnFollowed::get_instance();

endif;
