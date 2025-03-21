<?php

/**
 * Plugin Name: Envo's Elementor Templates & Widgets for WooCommerce
 * Description: Elementor templates library and widgets for WooCommerce.
 * Plugin URI: 	https://envothemes.com/elementor-templates-for-woocommerce/
 * Version: 	1.4.22
 * Author: 	EnvoThemes
 * Author URI: 	https://envothemes.com/
 * License:  	GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: etww
 * Domain Path: /languages
 * WC tested up to: 9.5
 * Elementor tested up to: 3.27.0
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

define('ETWW_VERSION', '1.4.22');
define('ETWW_ROOT', __FILE__);
define('ETWW_URL', plugins_url('/', ETWW_ROOT));
define('ETWW_PATH', plugin_dir_path(ETWW_ROOT));
define('ETWW_DIR_URL', plugin_dir_url(ETWW_ROOT));
define('ETWW_PLUGIN_BASE', plugin_basename(ETWW_ROOT));
define('ETWW_ENVO', 'https://envothemes.com/');

// Required File
require_once ( ETWW_PATH . 'includes/base.php' );
require_once ( ETWW_PATH . 'widgets.php' );
\ETWW\etww();

/**
 * WooCommerce HPOS support
 *
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );