<?php

namespace ETWW;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Base
*/
final class Base {

    const MINIMUM_PHP_VERSION = '5.4';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [__construct] Class construcotr
     */
    private function __construct() {

        if ( ! function_exists('is_plugin_active') ){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

        // Register Plugin Active Hook
        register_activation_hook( ETWW_ROOT, [ $this, 'plugin_activate_hook' ] );

        // Register Plugin Deactive Hook
        register_deactivation_hook( ETWW_ROOT, [ $this, 'plugin_deactivation_hook'] );


    }

    /**
     * [i18n] Load Text Domain
     * @return [void]
     */
    public function i18n() {
        load_plugin_textdomain( 'etww', false, dirname( plugin_basename( ETWW_ROOT ) ) . '/languages/' );
    }

    /**
     * [init] Plugins Loaded Init Hook
     * @return [void]
     */
    public function init() {

        // Check for required PHP version
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Check WooCommerce
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action('admin_notices', [ $this, 'admin_notic_missing_woocommerce' ] );
            return;
        }

        // Plugins Setting Page
        add_filter('plugin_action_links_'.ETWW_PLUGIN_BASE, [ $this, 'plugins_setting_links' ] );

        // Include File
        $this->include_files();

        // After Active Plugin then redirect to setting page
        $this->plugin_redirect_option_page();

        // Promo Banner
        if( is_admin() && !is_plugin_active('envo-elementor-for-woocommerce-pro/elementor-templates-woocommerce-pro.php') ){
            if( isset( \ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['status'] ) && null !== \ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['status'] ){
                if( !is_plugin_active('envo-elementor-for-woocommerce-pro/elementor-templates-woocommerce-pro.php') && ( \ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['status'] == 1 ) ){
                    add_action( 'wp_ajax_etww_pro_notice', [ $this, 'ajax_dismiss' ] );
                    add_action( 'admin_notices', [ $this, 'admin_promo_notice' ] );
                    return;
                }
            }
        }
		
		if( is_plugin_active('envo-elementor-for-woocommerce-pro/elementor-templates-woocommerce-pro.php') && version_compare(ETW_PRO_VERSION, '1.3.6', '<') ){
            add_action('admin_notices', [ $this, 'admin_notice_update_pro' ] );
            return;
        }

    }

    /**
     * [admin_notice_missing_main_plugin] Admin Notice For missing elementor.
     * @return [void]
     */
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $elementor = 'elementor/elementor.php';
        if( $this->is_plugins_active( $elementor ) ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );
            $message = sprintf( __( '%1$sElementor templates & widgets for WooCommerce%2$s requires %1$s"Elementor"%2$s plugin to be active. Please activate Elementor to continue.', 'etww' ), '<strong>', '</strong>' );
            $button_text = esc_html__( 'Activate Elementor', 'etww' );
        } else {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
            $message = sprintf( __( '%1$sElementor templates & widgets for WooCommerce%2$s requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', 'etww' ), '<strong>', '</strong>' );
            $button_text = esc_html__( 'Install Elementor', 'etww' );
        }
        $button = '<p><a href="' . esc_url($activation_url) . '" class="button-primary">' . esc_html($button_text) . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', $message, $button );
    }
	
	public function admin_notice_update_pro(){

        $message = sprintf( __( '%1$sElementor Templates for WooCommerce PRO%2$s requires an %1$supdate%2$s. Please update the plugin to ensure full compatibility with WordPress & WooCommerce.', 'etww' ), '<strong>', '</strong>' );
        $button_text = __( 'Update', 'etww' );
		$changelogurl = 'https://envothemes.com/elementor-templates-for-woocommerce-pro-changelog/';
		$updateurl = 'https://envothemes.com/how-to-update-plugin/';
        
		$button = '<p><a href="' . esc_url(admin_url( 'update-core.php?force-check=1')) . '" class="button-secondary">' . esc_html($button_text) . '</a><a href="' . esc_url($changelogurl) . '" target="_blank" class="envo-link-changelog" style="margin-left:10px;margin-top: 4px;display: inline-block;">' . esc_html('Changelog') . '</a><a href="' . esc_url($updateurl) . '" target="_blank" class="envo-link" style="margin-left:10px;margin-top: 4px;display: inline-block;">' . esc_html('How to update?') . '</a></p>';
		printf( '<div class="error"><p>%1$s</p>%2$s</div>', $message, $button );
    }

    /**
     * [admin_notic_missing_woocommerce] Admin Notice For missing WooCommerce
     * @return [void]
     */
    public function admin_notic_missing_woocommerce(){
        $woocommerce = 'woocommerce/woocommerce.php';
        if( $this->is_plugins_active( $woocommerce ) ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce );
            $message = sprintf( __( '%1$sElementor templates & widgets for WooCommerce%2$s requires %1$s"WooCommerce"%2$s plugin to be active. Please activate WooCommerce to continue.', 'etww' ), '<strong>', '</strong>');
            $button_text = __( 'Activate WooCommerce', 'etww' );
        } else {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
            $message = sprintf( __( '%1$sElementor templates & widgets for WooCommerce%2$s requires %1$s"WooCommerce"%2$s plugin to be installed and activated. Please install WooCommerce to continue.', 'etww' ), '<strong>', '</strong>' );
            $button_text = __( 'Install WooCommerce', 'etww' );
        }
        $button = '<p><a href="' . esc_url($activation_url) . '" class="button-primary">' . esc_html($button_text) . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', $message, $button );
    }

    /**
     * [admin_notice_minimum_php_version] Admin Notice For Required PHP Version
     * @return [void]
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'etww' ),
            '<strong>' . esc_html__( 'Elementor templates & widgets for WooCommerce', 'etww' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'etww' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * [ajax_dismiss] Ajax Call back funtion for update user meta
     * @return [void]
     */
    public function ajax_dismiss() {
        update_user_meta( get_current_user_id(), 'etww_pro_promo_message', 1 );
        wp_die();
    }

    /**
     * [admin_promo_notice]
     * @return [void] Promo banner admin notice
     */
    public function admin_promo_notice(){

        if( get_user_meta( get_current_user_id(), 'etww_pro_promo_message', true ) ){
            return;
        }

        if( \ETWW_Template_Library::instance()->get_templates_info()['notices'] ){
            ?>
            <style type="text/css">
                .etww-admin-notice.notice {
                  position: relative;
                  padding-top: 20px !important;
                  padding-right: 40px;
                }
                .etww-admin-notice.notice img{
                  width: 100%;
                }
                .etww-admin-notice.notice-warning {
                  border-left-color: #22b9ff;
                }
            </style>
            <script>
                ;jQuery( function( $ ) {
                    $( 'div.notice.etww-admin-notice' ).on( 'click', 'button.notice-dismiss', function( event ) {
                        event.preventDefault();
                        $.ajax({
                            url: ajaxurl,
                            data: {
                                'action': 'etww_pro_notice',
                            }
                        });
                    } );
                });
            </script>
            <?php
            printf( '<div class="etww-admin-notice is-dismissible notice notice-warning"><a href="%1$s" target="_blank"><img src="%2$s" alt="%3$s"></a><p>%4$s</p></div>', esc_url(\ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['bannerlink']), esc_url(ETWW_URL . 'includes/admin/img/' . \ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['bannerimage']), esc_attr(\ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['title']), wp_kses_post(\ETWW_Template_Library::instance()->get_templates_info()['notices'][0]['description'] ));
           
        }
    }

   /**
    * [is_plugins_active] Check Plugin is Installed or not
    * @param  [string]  $pl_file_path plugin file path
    * @return boolean  true|false
    */
    public function is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }

   /**
    * [plugins_setting_links]
    * @param  [array] $links default plugin action link
    * @return [array] plugin action link
    */
    public function plugins_setting_links( $links ) {
        $settings_link = '<a href="'.admin_url('admin.php?page=etww_templates').'">'.esc_html__( 'Templates Library', 'etww' ).'</a>'; 
        array_unshift( $links, $settings_link );
        if( !is_plugin_active('envo-elementor-for-woocommerce-pro/elementor-templates-woocommerce-pro.php') ){
            $links['etwwgo_pro'] = sprintf('<a href="https://envothemes.com/products/elementor-templates-woocommerce-pro/" target="_blank" style="color: #39b54a; font-weight: bold;">' . esc_html__('Go Pro','etww') . '</a>');
        }
        return $links; 
    }

   /**
    * [plugin_activate_hook] Plugin Activation hook callable
    * @return [void]
    */
    public function plugin_activate_hook() {
        add_option( 'etww_do_activation_redirect', TRUE );
        add_site_option('etww_active_time', time());
        delete_transient( 'etww_template_info' );
    }

    /**
     * [plugin_deactivation_hook] Plugin Deactivation hook callable
     * @return [void]
     */
    public function plugin_deactivation_hook() {
        delete_metadata( 'user', null, 'etww_pro_promo_message', null, true );
        delete_option('etww_active_time');
        delete_transient( 'etww_template_info' );
    }

    /**
     * [plugin_redirect_option_page] After Active the plugin then redirect to option page
     * @return [void]
     */
    public function plugin_redirect_option_page() {
        if ( get_option( 'etww_do_activation_redirect', FALSE ) && is_plugin_active( 'woocommerce/woocommerce.php') ) {
            delete_option('etww_do_activation_redirect');
            if( !isset( $_GET['activate-multi'] ) ){
                wp_redirect( admin_url("admin.php?page=etww_templates") );
            }
        }
    }


  

    /**
     * [include_files] Required File
     * @return [void]
     */
    public function include_files(){

        require( ETWW_PATH.'classes/class.assest_management.php' );


        // Admin Setting file
        if( is_admin() ){
            
            require( ETWW_PATH.'includes/admin/admin-init.php' );
            
        }

    }
    
}

/**
 * Initializes the main plugin
 *
 * @return \Base
 */
function etww() {
    return Base::instance();
}