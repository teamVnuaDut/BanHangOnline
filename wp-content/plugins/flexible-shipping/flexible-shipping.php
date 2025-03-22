<?php
/**
 * Plugin Name: Flexible Shipping
 * Plugin URI: https://wordpress.org/plugins/flexible-shipping/
 * Description: Create additional shipment methods in WooCommerce and enable pricing based on cart weight or total.
 * Version: 5.3.5
 * Author: Octolize
 * Author URI: https://octol.io/fs-author
 * Text Domain: flexible-shipping
 * Domain Path: /lang/
 * Requires at least: 6.4
 * Tested up to: 6.7
 * WC requires at least: 9.4
 * WC tested up to: 9.8
 * Requires PHP: 7.4
 * ​
 * Copyright 2017 WP Desk Ltd.
 * ​
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * ​
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * ​
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

defined( 'ABSPATH' ) || exit;

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '5.3.5';

$plugin_name        = 'Flexible Shipping';
$plugin_class_name  = Flexible_Shipping_Plugin::class;
$plugin_text_domain = 'flexible-shipping';
$product_id         = 'Flexible Shipping';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;
$plugin_shops       = [
	'default' => 'https://octolize.com/',
];

define( 'FLEXIBLE_SHIPPING_VERSION', $plugin_version );
define( $plugin_class_name, $plugin_version );

$requirements = [
	'php'          => '7.4',
	'wp'           => '5.8',
	'repo_plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '6.6',
		],
	],
];

add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}
);

add_action( 'plugins_loaded', function() {
	$dependent_plugins_versions = [
		'FLEXIBLE_SHIPPING_LOCATIONS_VERSION' => '3.0.0',
		'FLEXIBLE_SHIPPING_PRO_VERSION' =>'3.0.0',
		'FLEXIBLE_SHIPPING_VENDORS_VERSION' => '2.0.0',
		'OCTOLIZE_BOX_PACKING_VERSION'=> '2.0.0',
		'OCTOLIZE_OCTOLIZE_DISTANCE_BASED_SHIPPING_RATES_VERSION' => '2.0.0',
		'OCTOLIZE_DELIVERY_DATE_PICKER_VERSION' => '2.0.0',
	];
	$psr_not_prefixed = false;
	foreach ( $dependent_plugins_versions as $constant_name => $plugins_version ) {
		$psr_not_prefixed = $psr_not_prefixed || ( defined( $constant_name ) && version_compare( constant( $constant_name ), $plugins_version, '<' ) );
	}
	define( 'FLEXIBLE_SHIPPING_PSR_NOT_PREFIXED', $psr_not_prefixed );
} );

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
