<?php

declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Exception;

use RuntimeException;

defined( 'ABSPATH' ) || exit;

/**
 * Exception thrown when a required feature is not available.
 *
 * @package Automattic\WooCommerce\Analytics\Exception
 */
class RequiredFeatureDisabled extends RuntimeException implements WooCommerceAnalyticsException {

	/**
	 * Create a new instance of the exception when a required feature is not available.
	 *
	 * @param string $feature The name of the feature that is required.
	 * @param string $feature_url The URL to enable the feature.
	 * @param string $documentation_url The URL to the documentation.
	 *
	 * @return RequiredFeatureDisabled
	 */
	public static function feature_disabled( string $feature, string $feature_url, string $documentation_url ): RequiredFeatureDisabled {
		return new static(
			sprintf(
			/* translators: 1 is the name of the feature, 2 is the opening anchor tag, 3 is the closing anchor tag linking to the page to enable the feature, 4 is the opening anchor tag linking to our documentation, 5 is the closing anchor tag */
				esc_html__( 'WooCommerce Analytics requires %1$s to be enabled. Please %2$sclick here%3$s to enable the feature. For more details, visit the %4$sdocumentation%5$s.', 'woocommerce-analytics' ),
				$feature,
				'<a href="' . esc_html( $feature_url ) . '">',
				'</a>',
				'<a href="' . esc_url( $documentation_url ) . '" target="_blank">',
				'</a>'
			)
		);
	}
}
