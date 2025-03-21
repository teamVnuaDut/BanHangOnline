<?php

namespace ETWWElementor;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register elementor widget.
 *
 * @since 1.0.0
 */
class ETWWElementorPlugin {

    /**
     * @var Manager
     */
    public $modules_manager;

    /**
     * @var WPML
     */
    public $wpml_compatibility;

    /**
     * @var Plugin
     */
    private static $_instance;

    /**
     * @var Module_Base[]
     */
    private $modules = [];

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        spl_autoload_register([$this, 'autoload']);

        add_action('elementor/init', [$this, 'init'], 0);
        add_action('elementor/init', [$this, 'init_panel_section'], 0);
        add_action('elementor/elements/categories_registered', [$this, 'init_panel_section']);

        // Modules to enqueue styles
        $this->modules = [
            'animated-heading',
            'blog-grid',
            'off-canvas',
            'flip-box',
            'pricing',
            'tabs',
            'search',
            'woo-addtocart',
            'woo-slider',
        ];
    }

    /**
     * Autoload Classes
     *
     * @since 1.0.0
     */
    public function autoload($class) {
        if (0 !== strpos($class, __NAMESPACE__)) {
            return;
        }

        $class_to_load = $class;

        if (!class_exists($class_to_load)) {
            $filename = strtolower(
                    preg_replace(
                            ['/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/'],
                            ['', '$1-$2', '-', DIRECTORY_SEPARATOR],
                            $class_to_load
                    )
            );
            $filename = ETWW_ELEMENTOR_PATH . $filename . '.php';

            if (is_readable($filename)) {
                include($filename);
            }
        }
    }

    /**
     * Init
     *
     * @since 1.0.0
     *
     * @access private
     */
    public function init() {

        // Elementor hooks
        $this->add_actions();

        // Include extensions
        $this->includes();

        // Components
        $this->init_components();

        do_action('wvn_elementor/init');
    }

    /**
     * Plugin instance
     * 
     * @since 1.0.0
     * @return Plugin
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Add Actions
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function add_actions() {

        // Front-end Scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_styles']);

        // Preview Styles
        add_action('elementor/preview/enqueue_styles', [$this, 'preview_styles']);

        // Editor Style
        add_action('elementor/editor/after_enqueue_styles', [$this, 'editor_style']);
    }

    /**
     * Register scripts
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_scripts() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_register_script('isotope',
                plugins_url('/assets/js/isotope' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );


        wp_register_script('etww-blog-grid',
                plugins_url('/assets/js/blog-grid' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );


        wp_register_script('morphext',
                plugins_url('/assets/js/morphext' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );


        wp_register_script('etww-off-canvas',
                plugins_url('/assets/js/off-canvas' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );


        wp_register_script('etww-search',
                plugins_url('/assets/js/search' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );


        wp_register_script('etww-tabs',
                plugins_url('/assets/js/tabs' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );

        wp_register_script('typed',
                plugins_url('/assets/js/typed' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );


        wp_register_script('etww-woo-slider',
                plugins_url('/assets/js/woo-slider' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );
        
        wp_register_script('slick-q',
                plugins_url('/assets/js/slick' . $suffix . '.js', ETWW_ELEMENTOR__FILE__),
                ['jquery'],
                false,
                true
        );
    }

    /**
     * Register styles
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function register_styles() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        foreach ($this->modules as $module_name) {
            wp_register_style('etww-' . $module_name . '', plugins_url('/assets/css/' . $module_name . '/style' . $suffix . '.css', ETWW_ELEMENTOR__FILE__));
        }
    }

    /**
     * Enqueue styles in the editor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function preview_styles() {

        foreach ($this->modules as $module_name) {
            wp_enqueue_style('etww-' . $module_name . '');
        }

        // Fix the Woo Slider issue in the preview
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_enqueue_style('etww-elementor-preview', plugins_url('/assets/css/elementor/preview' . $suffix . '.css', ETWW_ELEMENTOR__FILE__));
    }

    /**
     * Enqueue style in the editor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function editor_style() {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_enqueue_style('etww-elementor-editor', plugins_url('/assets/css/elementor/editor' . $suffix . '.css', ETWW_ELEMENTOR__FILE__));
    }

    /**
     * Include components
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function includes() {



        // Modules
        include_once(ETWW_ELEMENTOR_PATH . 'includes/managers/modules.php');
    }

    /**
     * Sections init
     *
     * @since 1.0.0
     *
     * @access private
     */
    public function init_panel_section() {
        // Theme branding
        if (function_exists('etww_theme_branding')) {
            $brand = etww_theme_branding();
        } else {
            $brand = 'ETWW';
        }

        // Add element category in panel
        \Elementor\Plugin::instance()->elements_manager->add_category(
                'etww-elements',
                array('title' => $brand . ' ' . esc_html__('Elements', 'etww'),),
                1
        );
    }

    /**
     * Components init
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function init_components() {
        $this->modules_manager = new Modules_Manager();
    }

}

if (!defined('ETWW_ELEMENTOR_TESTS')) {
    // In tests we run the instance manually.
    ETWWElementorPlugin::instance();
}