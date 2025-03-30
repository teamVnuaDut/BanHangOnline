<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\DI;

use Automattic\Jetpack\Connection\Manager as JetpackManager;
use Automattic\WooCommerce\Analytics\Admin\DebugTools\WooCommerceStatusTools;
use Automattic\WooCommerce\Analytics\Admin\Admin;
use Automattic\WooCommerce\Analytics\API\ApiProxy;
use Automattic\WooCommerce\Analytics\API\SyncStatus;
use Automattic\WooCommerce\Analytics\Internal\Jetpack\Sync\Configuration as JetpackConfiguration;
use Automattic\WooCommerce\Analytics\Logging\DebugLogger;
use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;
use Automattic\WooCommerce\Analytics\Utilities\OrderStatsFixer;
use function Automattic\WooCommerce\Analytics\Dependencies\DI\get;
use function Automattic\WooCommerce\Analytics\Dependencies\DI\factory;
defined( 'ABSPATH' ) || exit;

/**
 * Class Configuration
 *
 * @package Automattic\WooCommerce\Analytics\Internal
 */
class Configuration {

	/**
	 * Configuration for dependency injection container.
	 *
	 * @return array The PHP-DI configuration.
	 */
	public static function get_php_di_configuration(): array {
		return array(
			LoggerInterface::class      => function () {
				return new DebugLogger( wc_get_logger() );
			},

			JetpackManager::class       => factory(
				function ( $container ) {
					/** @var Admin $admin */
					$admin = $container->get( Admin::class );

					return new JetpackManager( $admin->get_plugin_slug() );
				}
			),

			RegistrableInterface::class => array(
				get( Admin::class ),
				get( JetpackConfiguration::class ),
				get( SyncStatus::class ),
				get( ApiProxy::class ),
				get( WooCommerceStatusTools::class ),
				get( OrderStatsFixer::class ),
			),
		);
	}
}
