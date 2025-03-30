<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Dependencies\DI;

use Automattic\WooCommerce\Analytics\Dependencies\Psr\Container\ContainerExceptionInterface;

/**
 * Exception for the Container.
 */
class DependencyException extends \Exception implements ContainerExceptionInterface
{
}
