<?php

namespace Automattic\WooCommerce\Analytics\Utilities;

/**
 * Class Features
 * Handles feature flag functionality for WooCommerce Analytics
 */
class Features {
	/**
	 * @var array
	 */
	private static $features = null;

	/**
	 * @var array
	 */
	private static $features_defaults = array(
		'orderAttribution' => true,
		'addDevMenu'       => false,
	);

	/**
	 * The meta key used to store user preferences in the database
	 *
	 * @var string
	 */
	private static $analytics_meta_key = 'woocommerce/analytics';

	/**
	 * Initialize the features
	 */
	public static function init() {
		if ( null === self::$features ) {
			$feature_config_path = WC_ANALYTICS_ABSPATH . 'features/feature-config.php';

			if ( file_exists( $feature_config_path ) ) {
				require_once $feature_config_path;
			}

			if ( function_exists( 'wc_analytics_get_feature_config' ) ) {
				self::$features = wc_analytics_get_feature_config();
			} else {
				self::$features = self::$features_defaults;
			}

			/*
			 * Load user preferences from the dB
			 * and merge them with the default features.
			 */
			$current_user = wp_get_current_user();
			if ( $current_user && 0 !== $current_user->ID ) {
				$preferences = get_user_meta( $current_user->ID, 'wp_persisted_preferences', true );
				if ( ! is_array( $preferences ) ) {
					$preferences = array();
				}

				$woocommerce_analytics = isset( $preferences[ self::$analytics_meta_key ] ) && is_array( $preferences[ self::$analytics_meta_key ] )
					? $preferences[ self::$analytics_meta_key ]
					: array();

				self::$features = array_merge( self::$features, $woocommerce_analytics );
			}

			add_action( 'admin_enqueue_scripts', array( self::class, 'expose_features_to_client' ), 20 );
		}
	}

	/**
	 * Check if a feature is enabled
	 *
	 * @param string $feature_name The name of the feature to check.
	 * @return bool Whether the feature is enabled
	 */
	public static function is_enabled( $feature_name ) {
		if ( null === self::$features ) {
			self::init();
		}

		return isset( self::$features[ $feature_name ] ) && true === self::$features[ $feature_name ];
	}

	/**
	 * Get all enabled features
	 *
	 * @return array List of enabled features
	 */
	public static function get_enabled_features() {
		if ( null === self::$features ) {
			self::init();
		}

		return array_filter(
			self::$features,
			function ( $enabled ) {
				return true === $enabled;
			}
		);
	}

	/**
	 * Expose features to client-side code
	 */
	public static function expose_features_to_client() {
		wp_add_inline_script(
			'analytics-main-app',
			'window.wcAnalyticsFeatures = ' . wp_json_encode( self::$features ),
			'before'
		);
	}
}
