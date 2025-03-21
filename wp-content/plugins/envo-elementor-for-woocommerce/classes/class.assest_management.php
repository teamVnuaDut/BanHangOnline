<?php

namespace ETWW;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Assest Management
*/
class Assets_Management{
    
    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){
        $this->init();
    }

    /**
     * [init] Init
     * @return [void]
     */
    public function init() {

        // Register Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {

        $style_list = [
            'etww-selectric' => [
                'src'     => ETWW_URL . 'includes/admin/assets/lib/css/selectric.css',
                'version' => ETWW_VERSION
            ],
            'etww-temlibray-style' => [
                'src'     => ETWW_URL . 'includes/admin/assets/css/tmp-style.css',
                'version' => ETWW_VERSION
            ],
            

        ];
        return $style_list;

    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [

            'jquery-selectric' => [
                'src'     => ETWW_URL . 'includes/admin/assets/lib/js/jquery.selectric.min.js',
                'version' => ETWW_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'jquery-ScrollMagic' => [
                'src'     => ETWW_URL . 'includes/admin/assets/lib/js/ScrollMagic.min.js',
                'version' => ETWW_VERSION,
                'deps'    => [ 'jquery' ]
            ],

            'etww-templates' => [
                'src'     => ETWW_URL . 'includes/admin/assets/js/template_library_manager.js',
                'version' => ETWW_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'etww-install-manager' => [
                'src'     => ETWW_URL . 'includes/admin/assets/js/install_manager.js',
                'version' => ETWW_VERSION,
                'deps'    => [ 'etww-templates', 'wp-util', 'updates' ]
            ],
            
        ];

        return $script_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        // Register Scripts
        foreach ( $scripts as $handle => $script ) {
            $deps = ( isset( $script['deps'] ) ? $script['deps'] : false );
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        // Register Styles
        foreach ( $styles as $handle => $style ) {
            $deps = ( isset( $style['deps'] ) ? $style['deps'] : false );
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        //Localize Scripts
        $localizeargs = array(
            'etwwajaxurl' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'       => wp_create_nonce( 'etww_psa_nonce' ),
        );
        wp_localize_script( 'etww-widgets-scripts', 'etww_addons', $localizeargs );

        // For Admin
        if( is_admin() ){

  

            //Localize Scripts For template Library
            $current_user  = wp_get_current_user();
            $localize_data = [
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'adminURL'         => admin_url(),
                'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
				'etww_data_nonce'  => wp_create_nonce('etww_data_nonce'),
                'version'          => ETWW_VERSION,
                'pluginURL'        => plugin_dir_url( __FILE__ ),
                'alldata'          => ( !empty( \ETWW_Template_Library::instance()->get_templates_info()['templates'] ) ? \ETWW_Template_Library::instance()->get_templates_info()['templates']:array() ),
                'prolink'          => ( !empty( \ETWW_Template_Library::instance()->get_pro_link() ) ? \ETWW_Template_Library::instance()->get_pro_link() : '#' ),
                'prolabel'         => esc_html__( 'Pro', 'etww' ),
                'loadingimg'       => ETWW_URL . 'includes/admin/assets/images/loading.gif',
				'img_url'		   => ETWW_URL . 'includes/admin/img/',
                'message'          =>[
                    'packagedesc'=> esc_html__( 'in this package', 'etww' ),
                    'allload'    => esc_html__( 'All Items have been Loaded', 'etww' ),
                    'notfound'   => esc_html__( 'Nothing Found', 'etww' ),
                ],
                'buttontxt'      =>[
                    'tmplibrary' => esc_html__( 'Import to Library', 'etww' ),
                    'tmppage'    => esc_html__( 'Import to Page', 'etww' ),
                    'import'     => esc_html__( 'Import', 'etww' ),
                    'buynow'     => esc_html__( 'Buy Now', 'etww' ),
                    'preview'    => esc_html__( 'Preview', 'etww' ),
                    'installing' => esc_html__( 'Installing..', 'etww' ),
                    'activating' => esc_html__( 'Activating..', 'etww' ),
                    'active'     => esc_html__( 'Active', 'etww' ),
                ],
                'user'           => [
                    'email' => $current_user->user_email,
                ],
            ];
            wp_localize_script( 'etww-templates', 'WLTM', $localize_data );
        }
        
    }

}

Assets_Management::instance();