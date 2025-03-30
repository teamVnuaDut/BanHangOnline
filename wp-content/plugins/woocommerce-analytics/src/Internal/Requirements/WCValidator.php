<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\Requirements;

use Automattic\WooCommerce\Analytics\Exception\InvalidVersion;

defined( 'ABSPATH' ) || exit;

/**
 * Class WCValidator
 *
 * @package Automattic\WooCommerce\Analytics\Internal\Requirements
 */
class WCValidator extends RequirementValidator {

	/**
	 * Validate all requirements for the plugin to function properly.
	 *
	 * @return bool
	 */
	public function validate(): bool {
		try {
			$this->validate_wc_version();
			return true;
		} catch ( InvalidVersion $e ) {
			$this->add_admin_notice( $e );
			return false;
		}
	}

	/**
	 * Validate the minimum required WooCommerce version (after plugins are fully loaded).
	 *
	 * @throws InvalidVersion When the WooCommerce version does not meet the minimum version.
	 */
	protected function validate_wc_version(): void {
		if ( ! defined( 'WC_VERSION' ) ) {
			throw InvalidVersion::requirement_missing( esc_html__( 'WooCommerce', 'woocommerce-analytics' ), esc_html( WC_ANALYTICS_MIN_WC_VER ) );
		}

		if ( ! version_compare( WC_VERSION, WC_ANALYTICS_MIN_WC_VER, '>=' ) ) {
			throw InvalidVersion::from_requirement(
				esc_html__( 'WooCommerce', 'woocommerce-analytics' ),
				esc_html( WC_VERSION ),
				esc_html( WC_ANALYTICS_MIN_WC_VER ),
				'https://downloads.wordpress.org/plugin/woocommerce.' . WC_ANALYTICS_MIN_WC_VER . '.zip', // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			);
		}
	}
}
