<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal;

use Automattic\WooCommerce\Analytics\HelperTraits\LoggerTrait;
use Automattic\WooCommerce\Analytics\Internal\DI\Configuration as DIConfiguration;
use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;
use Automattic\WooCommerce\Analytics\Internal\Requirements\PluginValidator;
use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;
use Exception;
use Automattic\WooCommerce\Analytics\Dependencies\Psr\Container\ContainerExceptionInterface;
use Automattic\WooCommerce\Analytics\Dependencies\Psr\Container\ContainerInterface;
use Automattic\WooCommerce\Analytics\Dependencies\DI\ContainerBuilder;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 */
final class Plugin implements RegistrableInterface {

	use LoggerTrait;

	/**
	 * The instance of the Plugin object.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * The DI container.
	 *
	 * @var ContainerInterface
	 */
	private ContainerInterface $container;

	/**
	 * Set the DI container.
	 *
	 * @param ContainerInterface $container The DI container.
	 * @param LoggerInterface    $logger The logger object.
	 */
	public function __construct( ContainerInterface $container, LoggerInterface $logger ) {
		$this->container = $container;
		$this->set_logger( $logger );
	}

	/**
	 * Get the instance of the Plugin object.
	 *
	 * @return Plugin
	 *
	 * @throws Exception If the DI container cannot be built.
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			$container_builder = new ContainerBuilder();
			$container_builder->addDefinitions( DIConfiguration::get_php_di_configuration() );
			$container      = $container_builder->build();
			self::$instance = $container->get( self::class );
		}

		return self::$instance;
	}

	/**
	 * Register our hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		// Ensure the plugin requirements are met.
		if ( ! PluginValidator::validate() ) {
			return;
		}

		try {
			$registrables = $this->container->get( RegistrableInterface::class );
			foreach ( $registrables as $service ) {
				$service->register();
			}
		} catch ( ContainerExceptionInterface $e ) {
			$this->get_logger()->log_error( 'Failed to register services.', __METHOD__ );
		}
	}

	/**
	 * Hooked to register_activation_hook() by an anonymous function in the plugin file.
	 *
	 * @return void
	 */
	public function activate(): void {
		// For firing one-off events immediately after activation.
		set_transient( 'activated_woocommerce_analytics', true, 15 * MINUTE_IN_SECONDS );
	}
}
