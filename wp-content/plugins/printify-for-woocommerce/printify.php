<?php

/*
 * Plugin Name: Printify Shipping Method
 * Plugin URI: https://wordpress.org/plugins/printify-for-woocommerce/
 * Description: Calculate shipping rates for products managed by Printify.
 * Version: 3.0
 * Author: Printify
 * Author URI: https://www.printify.com
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {

    function printify_shipping_method_init() {
        if(!class_exists('Printify_Shipping_Method')) {
            require_once 'includes/printify-shipping-method.php';

            new Printify_Shipping_Method();
        }
    }

    add_action('woocommerce_shipping_init', 'printify_shipping_method_init');
}
