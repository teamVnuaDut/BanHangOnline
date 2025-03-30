<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\Requirements;

use Automattic\WooCommerce\Analytics\Exception\RequiredFeatureDisabled;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;

defined( 'ABSPATH' ) || exit;

/**
 * Class OrderAttributionConfigValidator
 *
 * @package Automattic\WooCommerce\Analytics\Internal\Requirements
 */
class OrderAttributionConfigValidator extends RequirementValidator {
	use Utilities;

	/**
	 * Validate requirements for plugin to function properly.
	 *
	 * @return bool True if the requirements are met.
	 */
	public function validate(): bool {
		try {
			$this->validate_order_attribution_enabled();
			return true;
		} catch ( RequiredFeatureDisabled $e ) {
			$this->add_admin_notice( $e );
			return false;
		}
	}

	/**
	 * Validate the configuration requirements for the plugin to function properly.
	 *
	 * @throws RequiredFeatureDisabled When the Order Attribution feature is disabled.
	 */
	protected function validate_order_attribution_enabled(): void {
		if ( ! $this->is_order_attribution_enabled() ) {
			throw RequiredFeatureDisabled::feature_disabled(
				'Order Attribution',
				admin_url( 'admin.php?page=wc-settings&tab=advanced&section=features' ), // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				'https://woocommerce.com/document/order-attribution-tracking/'
			);
		}
	}
}
