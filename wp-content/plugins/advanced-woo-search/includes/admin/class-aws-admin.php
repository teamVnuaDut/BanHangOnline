<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Admin' ) ) :

/**
 * Class for plugin admin panel
 */
class AWS_Admin {

    /*
     * Name of the plugin settings page
     */
    var $page_name = 'aws-options';

    /**
     * @var AWS_Admin The single instance of the class
     */
    protected static $_instance = null;


    /**
     * Main AWS_Admin Instance
     *
     * Ensures only one instance of AWS_Admin is loaded or can be loaded.
     *
     * @static
     * @return AWS_Admin - Main instance
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

        add_action( 'admin_menu', array( &$this, 'add_admin_page' ) );
        add_action( 'admin_init', array( &$this, 'register_settings' ) );

        if ( ! AWS_Admin_Options::get_settings() ) {
            $default_settings = AWS_Admin_Options::get_default_settings();
            update_option( 'aws_settings', $default_settings );
        }

        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );

        add_filter( 'submenu_file', array( $this, 'submenu_file' ), 10, 2 );

        add_filter( 'aws_admin_page_options_current', array( $this, 'check_sources_in_index' ), 1 );

        add_action( 'aws_admin_change_state', array( $this, 'disable_not_indexed_sources' ), 1, 3 );


    }

    /**
     * Add options page
     */
    public function add_admin_page() {
        add_menu_page( esc_html__( 'Adv. Woo Search', 'advanced-woo-search' ), esc_html__( 'Adv. Woo Search', 'advanced-woo-search' ), AWS_Helpers::user_admin_capability(), 'aws-options', array( &$this, 'display_admin_page' ), 'dashicons-search', 70 );
        add_submenu_page( 'aws-options', __( 'Settings', 'advanced-woo-search' ), __( 'Settings', 'advanced-woo-search'), AWS_Helpers::user_admin_capability(), 'aws-options', array( $this, 'display_admin_page' ) );
        add_submenu_page( 'aws-options', __( 'Premium', 'advanced-woo-search' ),  '<span style="color:rgba(255, 255, 91, 0.8);">' . __( 'Premium', 'advanced-woo-search' ) . '</span>', AWS_Helpers::user_admin_capability(), admin_url( 'admin.php?page=aws-options&tab=premium' ) );
    }

    /**
     * Generate and display options page
     */
    public function display_admin_page() {

        $nonce = wp_create_nonce( 'plugin-settings' );

        $tabs = array(
            'general'     => esc_html__( 'General', 'advanced-woo-search' ),
            'performance' => esc_html__( 'Performance', 'advanced-woo-search' ),
            'form'        => esc_html__( 'Search Form', 'advanced-woo-search' ),
            'results'     => esc_html__( 'Search Results', 'advanced-woo-search' ),
            'premium'     => esc_html__( 'Get Premium', 'advanced-woo-search' )
        );

        $current_tab = empty( $_GET['tab'] ) ? 'general' : sanitize_text_field( $_GET['tab'] );

        $tabs_html = '';

        foreach ( $tabs as $name => $label ) {
            $tabs_html .= '<a href="' . admin_url( 'admin.php?page=aws-options&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';

        }

        $tabs_html = '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">'.$tabs_html.'</h2>';

        if ( isset( $_POST["Submit"] ) && current_user_can( AWS_Helpers::user_admin_capability() ) && isset( $_POST["_wpnonce"] ) && wp_verify_nonce( $_POST["_wpnonce"], 'plugin-settings' ) ) {
            AWS_Admin_Options::update_settings();
        }


        echo '<div id="aws-admin-header">';
            echo '<div class="inner">';
                echo '<div class="logo">';
                    echo '<img src="' . AWS_URL . '/assets/img/logo.png' . '" alt="' . esc_html( 'logo', 'advanced-woo-search' ) . '">';
                    echo '<span class="title">';
                        echo esc_html( 'Advanced Woo Search', 'advanced-woo-search' );
                    echo '</span>';
                    echo '<span class="version">';
                        echo 'v' . AWS_VERSION;
                    echo '</span>';
                echo '</div>';
                echo '<div class="btns">';
                    echo '<a class="button button-docs" href="https://advanced-woo-search.com/guide/?utm_source=wp-plugin&utm_medium=header&utm_campaign=guide" target="_blank">' . esc_html( 'Documentation', 'advanced-woo-search' ) . '</a>';
                    echo '<a class="button button-support" href="https://advanced-woo-search.com/contact/?utm_source=wp-plugin&utm_medium=header&utm_campaign=support" target="_blank">' . esc_html( 'Support', 'advanced-woo-search' ) . '</a>';
                echo '</div>';
            echo '</div>';
        echo '</div>';


        echo '<div class="wrap">';

        echo '<h1></h1>';

        echo $tabs_html;

        echo '<form action="" name="aws_form" id="aws_form" method="post">';

        switch ($current_tab) {
            case('performance'):
                new AWS_Admin_Fields( 'performance' );
                break;
            case('form'):
                new AWS_Admin_Fields( 'form' );
                break;
            case('results'):
                new AWS_Admin_Fields( 'results' );
                break;
            case('premium'):
                new AWS_Admin_Page_Premium();
                break;
            default:
                echo AWS_Admin_Meta_Boxes::get_general_tab_content();
                new AWS_Admin_Fields( 'general' );
        }

        echo '<input type="hidden" name="_wpnonce" value="' . esc_attr( $nonce ) . '">';

        echo '</form>';

        echo '</div>';

    }

    /*
	 * Register plugin settings
	 */
    public function register_settings() {
        register_setting( 'aws_settings', 'aws_settings' );
    }

    /*
	 * Get plugin settings
	 */
    public function get_settings() {
        $plugin_options = get_option( 'aws_settings' );
        return $plugin_options;
    }

    /*
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts() {

        if ( isset( $_GET['page'] ) && $_GET['page'] == 'aws-options' ) {

            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

            wp_enqueue_style( 'plugin-admin-style', AWS_URL . 'assets/css/admin' . $suffix . '.css', array(), AWS_VERSION );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'plugin-admin-scripts', AWS_URL . 'assets/js/admin' . $suffix . '.js', array('jquery', 'jquery-ui-sortable'), AWS_VERSION );
            wp_localize_script( 'plugin-admin-scripts', 'aws_vars', array(
                'ajaxurl' => admin_url( 'admin-ajax.php', 'relative' ),
                'ajax_nonce' => wp_create_nonce( 'aws_admin_ajax_nonce' ),
            ) );

        }

    }

    /*
     * Change current class for premium tab
     */
    public function submenu_file( $submenu_file, $parent_file ) {
        if ( $parent_file === 'aws-options' && isset( $_GET['tab'] ) && $_GET['tab'] === 'premium' ) {
            $submenu_file = admin_url( 'admin.php?page=aws-options&tab=premium' );
        }
        return $submenu_file;
    }

    /*
     * Check if some sources for disabled from index
     */
    public function check_sources_in_index( $options ) {

        if ( $options ) {

            $index_options = AWS_Helpers::get_index_options();

            foreach( $options as $options_key => $options_tab ) {
                foreach( $options_tab as $key => $option ) {
                    if ( isset( $option['id'] ) && $option['id'] === 'search_in' && isset( $option['choices'] ) ) {
                        foreach( $option['choices'] as $choice_key => $choice_label ) {
                            if ( isset( $index_options['index'][$choice_key] ) && ! $index_options['index'][$choice_key] ) {
                                $text = '<span style="color:#dc3232;">' . __( '(index disabled)', 'advanced-woo-search' ) . '</span>' . ' <a href="'.esc_url( admin_url('admin.php?page=aws-options&tab=performance#index_sources') ).'">' . __( '(enable)', 'advanced-woo-search' ) . '</a>';
                                $options[$options_key][$key]['choices'][$choice_key] = $choice_label . ' ' . $text;
                            }
                        }
                    }
                }
            }

        }

        return $options;

    }

    /*
     * Disable sources that was excluded from index
     */
    public function disable_not_indexed_sources( $setting, $option, $state ) {

        if ( $setting === 'index_sources' && $state ) {
            $settings = AWS_Admin_Options::get_settings();
            if ( isset( $settings['search_in'][$option] ) ) {
                $settings['search_in'][$option] = 0;
                update_option( 'aws_settings', $settings );
            }
        }

    }

}

endif;


add_action( 'init', 'AWS_Admin::instance' );