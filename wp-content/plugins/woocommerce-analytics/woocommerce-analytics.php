<?php
/**
 * Plugin Name:          WooCommerce Analytics
 * Plugin URI:           https://woocommerce.com
 * Description:          Unlock actionable insights to boost sales and maximize your marketing ROI with WooCommerce Analytics.
 * Version:              0.9.10
 * Author:               WooCommerce
 * Author URI:           https://woocommerce.com/
 * Text Domain:          woocommerce-analytics
 *
 * Requires Plugins:     woocommerce
 * Requires PHP:         7.4
 * Tested up to:         6.7
 * Requires at least:    6.5
 * WC tested up to: 9.8
 * WC requires at least: 9.5
 *
 * License:              GNU General Public License v3.0
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.html
 */

use Automattic\WooCommerce\Analytics\Autoloader;
use Automattic\WooCommerce\Analytics\Internal\Plugin;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

define( 'WC_ANALYTICS_VERSION', '0.9.10' ); // WRCS: DEFINED_VERSION.
define( 'WC_ANALYTICS_MIN_PHP_VER', '7.4' );
define( 'WC_ANALYTICS_MIN_WC_VER', '9.5.0' );
define( 'WC_ANALYTICS_FILE', __FILE__ );
define( 'WC_ANALYTICS_ABSPATH', plugin_dir_path( __FILE__ ) );

// Load and initialize the autoloader.
require_once __DIR__ . '/src/Autoloader.php';
if ( ! Autoloader::init() ) {
	return;
}

// Declare compatibility with HPOS.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
		}
	}
);

/**
 * Global function to get the plugin instance.
 *
 * @return Plugin
 */
function woocommerce_analytics(): Plugin {
	return Plugin::get_instance();
}

// Hook much of our plugin after WooCommerce is loaded.
add_action(
	'woocommerce_loaded',
	function () {
		woocommerce_analytics()->register();
	}
);

/**
 * Check if WC_Site_Tracking exits and if not, create an alias for it.
 * This is needed to avoid fatals when sending or opening email notes.
 * See https://github.com/woocommerce/woocommerce/pull/51525 that is not yet released.
 */
add_action(
	'plugins_loaded',
	function () {
		if ( class_exists( 'WC_Site_Tracking' ) && ! class_exists( 'Automattic\WooCommerce\Admin\Notes\WC_Site_Tracking' ) ) {
			class_alias( 'WC_Site_Tracking', 'Automattic\WooCommerce\Admin\Notes\WC_Site_Tracking' );
		}
	},
	5
);


// Allow WooCommerce core to ask about translations updates for our plugin.
add_filter( 'woocommerce_translations_updates_for_woocommerce-analytics', '__return_true' );

register_activation_hook(
	__FILE__,
	function () {
		woocommerce_analytics()->activate();
	}
);
