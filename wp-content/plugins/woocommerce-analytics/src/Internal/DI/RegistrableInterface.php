<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\DI;

defined( 'ABSPATH' ) || exit;

/**
 * Interface RegistrableInterface
 *
 * @package Automattic\WooCommerce\Analytics\Internal
 */
interface RegistrableInterface {

	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public function register(): void;
}
