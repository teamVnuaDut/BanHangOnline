<?php
/**
 * VoxelPostFollowed.
 * php version 5.6
 *
 * @category VoxelPostFollowed
 * @package  SureTriggers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */

namespace SureTriggers\Integrations\Voxel\Triggers;

use SureTriggers\Controllers\AutomationController;
use SureTriggers\Traits\SingletonLoader;

if ( ! class_exists( 'VoxelPostFollowed' ) ) :

	/**
	 * VoxelPostFollowed
	 *
	 * @category VoxelPostFollowed
	 * @package  SureTriggers
	 * @author   BSF <username@example.com>
	 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
	 * @link     https://www.brainstormforce.com/
	 * @since    1.0.0
	 *
	 * @psalm-suppress UndefinedTrait
	 */
	class VoxelPostFollowed {


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
		public $trigger = 'voxel_post_followed';

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
				'label'         => __( 'Post Followed', 'suretriggers' ),
				'action'        => $this->trigger,
				'common_action' => 'st_voxel_post_followed',
				'function'      => [ $this, 'trigger_listener' ],
				'priority'      => 10,
				'accepted_args' => 1,
			];
			return $triggers;
		}

		/**
		 * Trigger listener
		 *
		 * @param array $follow_data Follow data.
		 * @return void
		 */
		public function trigger_listener( $follow_data ) {
			if ( empty( $follow_data ) ) {
				return;
			}

			global $wpdb;
			$sql                            = "SELECT COUNT(*) FROM {$wpdb->prefix}voxel_followers WHERE object_id= %d";
			$followers      = $wpdb->get_var( $wpdb->prepare( $sql, $follow_data['ID'] ));// @phpcs:ignore
			$follow_data['total_followers'] = $followers + 1;
			$context                        = $follow_data;
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
	VoxelPostFollowed::get_instance();

endif;
