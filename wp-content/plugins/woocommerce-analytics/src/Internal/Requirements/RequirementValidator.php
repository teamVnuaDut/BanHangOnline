<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\Requirements;

use RuntimeException;

defined( 'ABSPATH' ) || exit;

/**
 * Class RequirementValidator
 *
 * @package Automattic\WooCommerce\Analytics\Internal\Requirements
 */
abstract class RequirementValidator implements RequirementValidatorInterface {

	/**
	 * @var RequirementValidator[]
	 */
	private static $instances = array();

	/**
	 * Get the instance of the RequirementValidator object.
	 *
	 * @return RequirementValidator
	 */
	public static function instance(): RequirementValidator {
		$class = get_called_class();
		if ( ! isset( self::$instances[ $class ] ) ) {
			self::$instances[ $class ] = new $class();
		}
		return self::$instances[ $class ];
	}


	/**
	 * Add a standard requirement validation error notice.
	 *
	 * @param RuntimeException $e The Exception.
	 */
	protected function add_admin_notice( RuntimeException $e ): void {
		// Display notice error message.
		add_action(
			'admin_notices',
			function () use ( $e ) {
				echo '<div class="notice notice-error">' . PHP_EOL;
				echo '	<p>' . $e->getMessage() . '</p>' . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>' . PHP_EOL;
			}
		);
	}
}
