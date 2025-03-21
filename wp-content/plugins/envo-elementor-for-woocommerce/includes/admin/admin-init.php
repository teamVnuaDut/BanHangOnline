<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class ETWW_Admin_Setting{

    public function __construct(){
        add_action('admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        $this->etww_admin_settings_page();
    }

    /*
    *  Setting Page
    */
    public function etww_admin_settings_page() {
        
        require_once('include/template-library.php');
    }

    /*
    *  Enqueue admin scripts
    */
    public function enqueue_scripts( $hook ){

        if( $hook === 'woocommerce_page_etww_templates' ){

            // wp core styles
            wp_enqueue_style( 'wp-jquery-ui-dialog' );
            // wp core scripts
            wp_enqueue_script( 'jquery-ui-dialog' );

        }

    }

}

new ETWW_Admin_Setting();