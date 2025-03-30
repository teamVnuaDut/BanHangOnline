<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Source;

use Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Exception\InvalidDefinition;
use Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\ObjectDefinition;

/**
 * Source of definitions for entries of the container.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Autowiring
{
    /**
     * Autowire the given definition.
     *
     * @throws InvalidDefinition An invalid definition was found.
     * @return ObjectDefinition|null
     */
    public function autowire(string $name, ?ObjectDefinition $definition = null);
}
