<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\HelperTraits;

use Automattic\WooCommerce\Analytics\Logging\LoggerInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Trait LoggerTrait
 */
trait LoggerTrait {

	/** @var LoggerInterface */
	private LoggerInterface $logger;

	/**
	 * Set the logger object.
	 *
	 * @param LoggerInterface $logger The logger object.
	 *
	 * @return void
	 */
	public function set_logger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Get the logger object.
	 *
	 * @return LoggerInterface
	 */
	public function get_logger(): LoggerInterface {
		return $this->logger;
	}
}
