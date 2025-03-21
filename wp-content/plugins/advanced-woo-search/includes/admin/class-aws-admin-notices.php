<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Admin_Notices' ) ) :

    /**
     * Class for plugin admin panel
     */
    class AWS_Admin_Notices {

        /**
         * @var AWS_Admin_Notices Active plugins arrray
         */
        public $active_plugins = array();

        /**
         * @var AWS_Admin_Notices The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Admin_Notices Instance
         *
         * Ensures only one instance of AWS_Admin_Notices is loaded or can be loaded.
         *
         * @static
         * @return AWS_Admin_Notices - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /*
         * Constructor
         */
        public function __construct() {

            $active_plugins = get_option( 'active_plugins', array() );

            if ( is_multisite() ) {
                $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                $active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
            }

            $this->active_plugins = $active_plugins;

            // Welcome notice
            add_action( 'admin_notices', array( $this, 'display_welcome_header' ), 1 );

            // Reindex notice
            add_action( 'admin_notices', array( $this, 'display_reindex_message' ), 1 );

            // Plugins integration notice
            add_action( 'admin_notices', array( $this, 'plugins_integration_notice' ), 1 );

            // Hide notices
            add_action( 'admin_init', array( $this, 'hide_notices' ) );
            
        }

        /*
         * Show notices about PRO plugin integrations
         */
        public function plugins_integration_notice() {

            if ( ! current_user_can( AWS_Helpers::user_admin_capability() ) ) {
                return;
            }

            if ( ! class_exists( 'WCFMmp' ) && ! class_exists('ACF') && ! class_exists('YITH_WCWL') && ! class_exists( 'WooCommerceWholeSalePrices' ) && ! class_exists( 'UM_Functions' ) && ! defined( 'PWB_PLUGIN_VERSION' )
                && ! defined( 'TINVWL_FVERSION' ) && ! class_exists( 'WeDevs_Dokan' )
                && ! ( defined( 'WCMp_PLUGIN_VERSION' ) || defined( 'MVX_PLUGIN_VERSION' ) )
                && ! class_exists( 'WC_Memberships' )
                && ! ( class_exists('Iconic_WSSV') || class_exists('JCK_WSSV') )
                && ! in_array( 'wc-vendors/class-wc-vendors.php', $this->active_plugins )
            ) {
                return;
            }

            $hide_option = get_option( 'aws_hide_int_notices' );
            $notice_top_message = sprintf( __( 'Hi! Looks like you are using some plugins that have the advanced integration with %s. Please find more details below.', 'advanced-woo-search' ), '<b>Advanced Woo Search PRO</b>' );
            $notice_message = '';
            $notice_id = '';

            if ( class_exists( 'WCFMmp' ) && ( ! $hide_option || array_search( 'wcfm', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'WCFM Multivendor Marketplace plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/features/wcfm-plugin-support/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=wcfm">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'wcfm|';
            }

            if ( class_exists('ACF') && ( ! $hide_option || array_search( 'acf', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'Advanced Custom Fields ( ACF ) plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/features/acf-plugin-support/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=acf">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'acf|';
            }

            if ( class_exists( 'YITH_WCWL' ) && ( ! $hide_option || array_search( 'yithwish', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'YITH WooCommerce Wishlist plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/yith-woocommerce-wishlist/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=yithwish">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'yithwish|';
            }

            if ( class_exists( 'WooCommerceWholeSalePrices' ) && ( ! $hide_option || array_search( 'wholesaleprices', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'Wholesale Prices plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/wholesale-prices/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=wholesaleprices">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'wholesaleprices|';
            }

            if ( class_exists( 'UM_Functions' ) && ( ! $hide_option || array_search( 'um', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'Ultimate Member plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/ultimate-member/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=um">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'um|';
            }

            if ( defined( 'PWB_PLUGIN_VERSION' ) && ( ! $hide_option || array_search( 'pwb', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'Perfect Brands for WooCommerce plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/perfect-brands-for-woocommerce/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=pwb">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'pwb|';
            }

            if ( defined( 'TINVWL_FVERSION' ) && ( ! $hide_option || array_search( 'tinvwl', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'TI WooCommerce Wishlist plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/ti-woocommerce-wishlist/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=tinvwl">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'tinvwl|';
            }

            if ( class_exists( 'WeDevs_Dokan' ) && ( ! $hide_option || array_search( 'dokan', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'Dokan – WooCommerce Multivendor Marketplace Solution plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/dokan-woocommerce-multivendor-marketplace/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=dokan">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'dokan|';
            }

            if ( ( defined( 'WCMp_PLUGIN_VERSION' ) || defined( 'MVX_PLUGIN_VERSION' ) ) && ( ! $hide_option || array_search( 'multivendorx', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'MultiVendorX – WooCommerce Multivendor Marketplace plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/multivendorx/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=multivendorx">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'multivendorx|';
            }

            if ( class_exists( 'WC_Memberships' ) && ( ! $hide_option || array_search( 'wcmember', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'WooCommerce Memberships plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/woocommerce-memberships/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=wcmember">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'wcmember|';
            }

            if ( ( class_exists('Iconic_WSSV') || class_exists('JCK_WSSV') ) && ( ! $hide_option || array_search( 'singlevar', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'WooCommerce Show Single Variations by Iconic plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/guide/woocommerce-show-single-variations-by-iconic/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=singlevar">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'singlevar|';
            }

            if ( in_array( 'wc-vendors/class-wc-vendors.php', $this->active_plugins ) && ( ! $hide_option || array_search( 'wcvendors', $hide_option ) === false ) ) {
                $notice_message .= '<li>' . __( 'WC Vendors plugin.', 'advanced-woo-search' ) . ' <a target="_blank" href="https://advanced-woo-search.com/features/wc-vendors-plugin-support/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=wcvendors">' . __( 'Learn more', 'advanced-woo-search' ) . '</a></li>';
                $notice_id .= 'wcvendors|';
            }

            $notice_id = 'aws_hide_int_notices=' . urlencode( trim( $notice_id, '|' ) );

            if ( $notice_message ) {

                $check_timing = $this->check_activation_time();
                if ( ! $check_timing ) {
                    return;
                }

                $current_page_url = function_exists('wc_get_current_admin_url') ? wc_get_current_admin_url() : esc_url( admin_url('admin.php?page=aws-options'));
                $dismiss_link = strpos( $current_page_url, '?' ) === false ? $current_page_url . '?' : $current_page_url . '&';

                $html = '';

                $html .= '<div class="aws-integration-notice notice notice-success" style="position:relative;display:flex;">';
                    $html .= '<div style="margin: 20px 20px 0 0;" class="aws-integration-notice--logo">';
                        $html .= '<img style="max-width:70px;border-radius:3px;" src="' . AWS_URL . 'assets/img/logo.jpeg' . '">';
                    $html .= '</div>';
                    $html .= '<div class="aws-integration-notice--content">';
                        $html .= '<h2>Advanced Woo Search: ' . __( 'Integrations for your plugins', 'advanced-woo-search' ) . '</h2>';
                        $html .= '<p>' . $notice_top_message. '</p>';
                        $html .= '<ul style="list-style:disc;padding-left:20px;margin:15px 0 18px;">' . $notice_message. '</ul>';
                        $html .= '<a href="https://advanced-woo-search.com/features/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=all_pro" target="_blank" class="button button-primary">' . __( 'All PRO Features', 'advanced-woo-search' ) . '</a>&nbsp;&nbsp;<a href="https://advanced-woo-search.com/pricing/?utm_source=wp-plugin&utm_medium=integration_notice&utm_campaign=pricing" target="_blank" class="button button-primary">' . __( 'View Pricing', 'advanced-woo-search' ) . '</a>';
                        $html .= '<div style="margin-bottom:15px;"></div>';
                        $html .= '<a href="' . $dismiss_link . $notice_id . '" title="' . __( 'Dismiss', 'advanced-woo-search'  ) . '" style="color:#787c82;text-decoration:none;font-size:16px;position:absolute;top:0;right:1px;border:none;margin:0;padding:9px;background:0 0;cursor:pointer;"><span style="font-size:16px;" class="dashicons dashicons-dismiss"></span></a>';
                    $html .= '</div>';
                $html .= '</div>';

                echo $html;

            }

        }
        
        /*
         * Add welcome notice
         */
        public function display_welcome_header() {

            if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'aws-options' ) {
                return;
            }

            if ( ! current_user_can( AWS_Helpers::user_admin_capability() ) ) {
                return;
            }

            $hide_notice = get_option( 'aws_hide_welcome_notice' );

            if ( ! $hide_notice || $hide_notice === 'true' ) {
                return;
            }

            echo AWS_Admin_Meta_Boxes::get_welcome_notice();

        }

        /*
         * Add reindex notice after index options change
         */
        public function display_reindex_message() {

            if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'aws-options' ) {
                return;
            }

            if ( ! isset( $_POST["Submit"] ) || ! current_user_can( AWS_Helpers::user_admin_capability() ) ) {
                return;
            }

            if ( isset( $_POST["index_variations"] ) || isset( $_POST["search_rule"] ) ) {
                echo AWS_Admin_Meta_Boxes::get_reindex_notice();
            }

        }

        /*
         * Hide admin notices
         */
        public function hide_notices() {

            if ( isset( $_GET['aws_hide_int_notices'] ) && $_GET['aws_hide_int_notices'] ) {
                $option = strpos( $_GET['aws_hide_int_notices'], '|' ) !== false ? explode('|', $_GET['aws_hide_int_notices'] ) : array( $_GET['aws_hide_int_notices'] );
                $option_current = get_option( 'aws_hide_int_notices' );
                $option = $option_current ? array_merge( $option_current, $option ) : $option;
                update_option( 'aws_hide_int_notices', $option, false );
            }

        }

        /*
         * Check plugin activation time
         */
        public function check_activation_time() {

            $activation_time = get_option( 'aws_activation_time' );
            $show_notices = false;

            if ( ! $activation_time ) {
                update_option( 'aws_activation_time', time(), 'no' );
            } else {
                $time_pass = time() - $activation_time;
                $days_pass = (int) round((($time_pass/24)/60)/60);
                if ( $days_pass && $days_pass > 7 ) {
                    $show_notices = true;
                }
            }

            return $show_notices;

        }

    }

endif;


add_action( 'init', 'AWS_Admin_Notices::instance' );