<?php

namespace FSVendor\WPDesk\Persistence;

use FSVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements NotFoundExceptionInterface
{
}
