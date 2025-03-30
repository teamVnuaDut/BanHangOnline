<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Exception;

use Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Definition;
use Automattic\WooCommerce\Analytics\Dependencies\Psr\Container\ContainerExceptionInterface;

/**
 * Invalid Automattic\WooCommerce\Analytics\Dependencies\DI definitions.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InvalidDefinition extends \Exception implements ContainerExceptionInterface
{
    public static function create(Definition $definition, string $message, ?\Exception $previous = null) : self
    {
        return new self(sprintf(
            '%s' . \PHP_EOL . 'Full definition:' . \PHP_EOL . '%s',
            $message,
            (string) $definition
        ), 0, $previous);
    }
}
