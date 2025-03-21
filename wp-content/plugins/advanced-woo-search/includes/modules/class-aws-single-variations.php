<?php
/**
 * WooCommerce Show Single Variations by Iconic plugin integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Single_Variations' ) ) :

    /**
     * Class
     */
    class AWS_Single_Variations {

        /**
         * Main AWS_Single_Variations Instance
         *
         * Ensures only one instance of AWS_Single_Variations is loaded or can be loaded.
         *
         * @static
         * @return AWS_Single_Variations - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Single_Variations Instance
         *
         * Ensures only one instance of AWS_Single_Variations is loaded or can be loaded.
         *
         * @static
         * @return AWS_Single_Variations - Main instance
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

            add_action( 'woocommerce_product_set_visibility', array( $this, 'woocommerce_product_set_visibility' ), 99, 2 );

        }

        /*
         * Update index table on bulk visibility change
         */
        public function woocommerce_product_set_visibility( $id, $terms ) {

            if ( is_ajax() && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'iconic_wssv_process_product_visibility' ) {

                $sync = AWS()->get_settings( 'autoupdates' );

                if ( $terms && $sync !== 'false' ) {

                    $new_visibility = $terms;

                    if ( $new_visibility && ! AWS()->option_vars->is_index_table_not_exists() ) {

                        global $wpdb;

                        $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

                        $new_visibility = AWS()->table_updates->get_visibility_code( $new_visibility );

                        $wpdb->update( $table_name, array( 'visibility' => $new_visibility ), array( 'id' => $id ) );

                        do_action('aws_cache_clear');

                    }

                }

            }

        }

    }

endif;

AWS_Single_Variations::instance();