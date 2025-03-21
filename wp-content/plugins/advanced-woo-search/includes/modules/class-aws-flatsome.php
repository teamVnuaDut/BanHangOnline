<?php
/**
 * Flatsome Theme and Flatsome UX Builder support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Flatsome' ) ) :

    /**
     * Class
     */
    class AWS_Flatsome {

        /**
         * Main AWS_Flatsome Instance
         *
         * Ensures only one instance of AWS_Flatsome is loaded or can be loaded.
         *
         * @static
         * @return AWS_Flatsome - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Flatsome Instance
         *
         * Ensures only one instance of AWS_Flatsome is loaded or can be loaded.
         *
         * @static
         * @return AWS_Flatsome - Main instance
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

            // Remove search page block
            if ( isset( $_GET['type_aws'] ) && function_exists( 'flatsome_pages_in_search_results' ) ) {
                remove_action('woocommerce_after_main_content','flatsome_pages_in_search_results', 10);
            }

            add_action( 'wp_loaded', array( $this, 'add_ux_builder_shortcodes' ) );

        }

        /*
         * Add shortcodes to UX Builder
         */
        public function add_ux_builder_shortcodes() {

            if ( function_exists('add_ux_builder_shortcode' ) && function_exists('flatsome_ux_builder_thumbnail' ) ) {

                add_ux_builder_shortcode( 'aws_search_form', array(
                    'name'      => __( 'Advanced Woo Search' ),
                    'thumbnail' => flatsome_ux_builder_thumbnail( 'search' ),
                    'wrap' => false,
                    'allow_in' => array( 'text_box' ),
                    'category'  => __( 'Shop' ),
                ) );

            }

        }

    }

endif;

AWS_Flatsome::instance();