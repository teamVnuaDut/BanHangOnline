<?php
/**
 * Uninstall plugin
 * Deletes all the plugin data
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;


global $wpdb;

if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! function_exists( 'aws_is_plugin_installed' ) ) {

    function aws_is_plugin_installed( $plugin ) {
        $plugins_array = get_plugins();
        if ( is_multisite() ) {
            $plugins_array = get_mu_plugins();
        }
        return isset( $plugins_array[$plugin] );
    }

}

delete_option( 'aws_settings' );
delete_option( 'aws_plugin_ver' );
delete_option( 'aws_reindex_version' );
delete_option( 'aws_table_version' );
delete_option( 'aws_activation_time' );
delete_option( 'aws_cron_job' );
delete_option( 'aws_hide_welcome_notice' );
delete_option( 'aws_hide_int_notices' );

if ( ! aws_is_plugin_installed( 'advanced-woo-search-pro/advanced-woo-search-pro.php' ) ) {
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "aws_index" );
    $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "aws_cache" );
}