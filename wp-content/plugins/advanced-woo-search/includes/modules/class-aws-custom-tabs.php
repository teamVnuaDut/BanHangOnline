<?php

/**
 * Custom Product Tabs for WooCommerce integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Custom_Tabs')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Custom_Tabs {

        /**
         * @var AWS_Custom_Tabs The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Custom_Tabs Instance
         *
         * Ensures only one instance of AWS_Custom_Tabs is loaded or can be loaded.
         *
         * @static
         * @return AWS_Custom_Tabs - Main instance
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            add_filter( 'aws_indexed_content', array( $this, 'aws_indexed_content'), 10, 3 );

            add_action( 'updated_postmeta', array( $this, 'updated_custom_tabs' ), 10, 4 );

        }

        /*
         * Index custom tabs content
         */
        public function aws_indexed_content( $content, $id, $product ) {

            // Get content from Custom Product Tabs
            if ( $custom_tabs = get_post_meta( $id, 'yikes_woo_products_tabs' ) ) {
                if ( $custom_tabs && ! empty( $custom_tabs ) ) {
                    foreach( $custom_tabs as $custom_tab_array ) {
                        if ( is_array( $custom_tab_array ) && ! empty( $custom_tab_array ) ) {
                            foreach( $custom_tab_array as $custom_tab ) {
                                if ( isset( $custom_tab['content'] ) && $custom_tab['content'] ) {
                                    $tab_content = $custom_tab['content'];
                                    if ( function_exists( 'wp_encode_emoji' ) ) {
                                        $tab_content = wp_encode_emoji( $tab_content );
                                    }
                                    $tab_content = AWS_Helpers::strip_shortcodes( $tab_content );
                                    $content = $content . ' ' . $tab_content;
                                }
                            }
                        }
                    }
                }
            }

            return $content;

        }

        /*
         * Custom Tabs was updated
         */
        public function updated_custom_tabs( $meta_id, $object_id, $meta_key, $meta_value ) {
            if ( $meta_key === 'yikes_woo_products_tabs' && apply_filters( 'aws_filter_yikes_woo_products_tabs_sync', true ) ) {
                do_action( 'aws_reindex_product', $object_id );
            }
        }

    }

endif;

AWS_Custom_Tabs::instance();