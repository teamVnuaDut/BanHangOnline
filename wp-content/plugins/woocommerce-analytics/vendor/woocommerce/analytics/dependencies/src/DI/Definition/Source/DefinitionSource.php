<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Source;

use Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Definition;
use Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Exception\InvalidDefinition;

/**
 * Source of definitions for entries of the container.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface DefinitionSource
{
    /**
     * Returns the Automattic\WooCommerce\Analytics\Dependencies\DI definition for the entry name.
     *
     * @throws InvalidDefinition An invalid definition was found.
     * @return Definition|null
     */
    public function getDefinition(string $name);

    /**
     * @return Definition[] Definitions indexed by their name.
     */
    public function getDefinitions() : array;
}
