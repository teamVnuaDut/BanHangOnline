<?php
/**
 * Bricks Builder support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Bricks' ) ) :

    /**
     * Class
     */
    class AWS_Bricks {

        /**
         * Main AWS_Bricks Instance
         *
         * Ensures only one instance of AWS_Bricks is loaded or can be loaded.
         *
         * @static
         * @return AWS_Bricks - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Bricks Instance
         *
         * Ensures only one instance of AWS_Bricks is loaded or can be loaded.
         *
         * @static
         * @return AWS_Bricks - Main instance
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {

                add_filter( 'aws_js_seamless_selectors', array( $this, 'js_seamless_selectors' ) );

                add_action( 'wp_head', array( $this, 'wp_head' ) );

            }

        }

        /*
         * Selector filter of js seamless
         */
        public function js_seamless_selectors( $selectors ) {
            $selectors[] = '.bricks-search-inner form, .brxe-search form';
            return $selectors;
        }

        /*
         * Add additional styles
         */
        public function wp_head() { ?>
            <style>
                .bricks-search-inner .aws-container {
                    align-items: stretch;
                    display: flex !important;
                    justify-content: space-between;
                    position: relative;
                    width: 100%;
                    max-width: 600px;
                }
            </style>
        <?php }

    }

endif;

AWS_Bricks::instance();

class Prefix_Element_Test extends \Bricks\Element {

    public $category     = 'woocommerce'; // Use predefined element category 'general'
    public $name         = 'aws-search-form'; // Make sure to prefix your elements
    public $icon         = 'ti-search'; // Themify icon font class
    public $css_selector = '.aws-search-form-wrapper'; // Default CSS selector

    public function get_label() {
        return esc_html__( 'Advanced Woo Search', 'advanced-woo-search' );
    }

    public function set_control_groups() {

        $this->control_groups['settings'] = [
            'title' => esc_html__( 'Settings', 'advanced-woo-search' ),
            'tab' => 'content',
        ];
    }

    // Set builder controls
    public function set_controls() {

        $this->controls['placeholder'] = [
            'tab' => 'content',
            'group' => 'settings',
            'label' => esc_html__( 'Placeholder', 'advanced-woo-search' ),
            'type' => 'text',
            'default' => '',
        ];

    }

    public function enqueue_scripts() {
    }

    // Render element HTML
    public function render() {

        $root_classes[] = 'aws-search-form-wrapper';

        $this->set_attribute( '_root', 'class', $root_classes );

        echo "<div {$this->render_attributes( '_root' )}>";

        if ( function_exists( 'aws_get_search_form' ) ) {
            $args = isset( $this->settings['placeholder'] ) && $this->settings['placeholder'] ? array( 'placeholder' => $this->settings['placeholder'] ) : array();

            $search_form = aws_get_search_form( false, $args );
            echo $search_form;
        }

        echo '</div>';

    }
}