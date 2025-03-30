<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\Requirements;

defined( 'ABSPATH' ) || exit;

/**
 * Class PluginValidator
 *
 * Display admin notices for required and incompatible plugins.
 */
class PluginValidator {

	const PLUGINS = array(
		WCValidator::class,
		OrderAttributionConfigValidator::class,
	);

	/**
	 * @var bool $is_validated
	 * Holds the validation status of the plugin.
	 */
	protected static $is_validated = null;

	/**
	 * Validate all required and incompatible plugins.
	 *
	 * @return bool
	 */
	public static function validate(): bool {
		if ( null !== self::$is_validated ) {
			return self::$is_validated;
		}

		self::$is_validated = true;

		/** @var RequirementValidator $plugin */
		foreach ( self::PLUGINS as $plugin ) {
			if ( ! $plugin::instance()->validate() ) {
				self::$is_validated = false;
			}
		}
		return self::$is_validated;
	}
}
