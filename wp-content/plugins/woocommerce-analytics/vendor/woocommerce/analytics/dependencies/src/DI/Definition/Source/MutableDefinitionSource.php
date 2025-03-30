<?php

declare(strict_types=1);

namespace Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Source;

use Automattic\WooCommerce\Analytics\Dependencies\DI\Definition\Definition;

/**
 * Describes a definition source to which we can add new definitions.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface MutableDefinitionSource extends DefinitionSource
{
    public function addDefinition(Definition $definition);
}
