<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Exception;

use RuntimeException;

defined( 'ABSPATH' ) || exit;

/**
 * Class InvalidVersion
 *
 * Error messages generated in this class should be translated, as they are intended to be displayed
 * to end users.
 *
 * @package Automattic\WooCommerce\Analytics\Exception
 */
class InvalidVersion extends RuntimeException implements WooCommerceAnalyticsException {

	/**
	 * Create a new instance of the exception when an invalid version is detected.
	 *
	 * @param string $requirement The name of the requirement.
	 * @param string $found_version The version in use on the site.
	 * @param string $minimum_version The minimum required version.
	 * @param string $download_url The URL to download the minimum required version.
	 *
	 * @return static
	 */
	public static function from_requirement( string $requirement, string $found_version, string $minimum_version, $download_url ): InvalidVersion {
		return new static(
			sprintf(
			/* translators: 1 is the required component, 2 is the minimum required version, 3 is the version in use on the site, 4 is the opening anchor tag for the update link, 5 is the closing anchor tag, 6 is the opening anchor tag for the download link, 7 is the closing anchor tag */
				esc_html__( 'WooCommerce Analytics requires %1$s version %2$s or higher. You are using version %3$s. Please %4$supdate to the latest version%5$s or %6$sdownload the minimum required version%7$s.', 'woocommerce-analytics' ),
				esc_html( $requirement ),
				esc_html( $minimum_version ),
				esc_html( $found_version ),
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '#update-plugins-table">',
				'</a>',
				'<a href="' . esc_url( $download_url ) . '" target="_blank">',
				'</a>'
			)
		);
	}

	/**
	 * Create a new instance of the exception when a requirement is missing.
	 *
	 * @param string $requirement The name of the requirement.
	 * @param string $minimum_version The minimum required version.
	 *
	 * @return InvalidVersion
	 */
	public static function requirement_missing( string $requirement, string $minimum_version ): InvalidVersion {
		return new static(
			sprintf(
				/* translators: 1 is the required component, 2 is the minimum required version */
				__( 'WooCommerce Analytics requires %1$s version %2$s or higher.', 'woocommerce-analytics' ),
				$requirement,
				$minimum_version
			)
		);
	}
}
