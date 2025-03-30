<?php
/**
 * WooCommerceAnalyticsException interface.
 *
 * @package Automattic\WooCommerce\Analytics\Exception
 */

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Exception;

use Throwable;

/**
 * This interface is used for all of our exceptions so that we can easily catch only our own exceptions.
 */
interface WooCommerceAnalyticsException extends Throwable {}
