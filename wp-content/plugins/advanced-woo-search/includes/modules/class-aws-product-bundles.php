<?php
/**
 * WooCommerce Product Bundles plugin integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Product_Bundles')) :

    /**
     * Class
     */
    class AWS_Product_Bundles {

        /**
         * Main AWS_Product_Bundles Instance
         *
         * Ensures only one instance of AWS_Product_Bundles is loaded or can be loaded.
         *
         * @static
         * @return AWS_Product_Bundles - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Product_Bundles Instance
         *
         * Ensures only one instance of AWS_Product_Bundles is loaded or can be loaded.
         *
         * @static
         * @return AWS_Product_Bundles - Main instance
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            add_action( 'woocommerce_after_product_object_save', array( $this, 'woocommerce_after_product_object_save' ), 20 );

            add_action( 'added_post_meta', array( $this, 'meta_changed' ), 20, 4 );
            add_action( 'updated_postmeta', array( $this, 'meta_changed' ), 20, 4 );

        }

        /*
         * Update index table for bundle products
         */
        public function woocommerce_after_product_object_save( $product ) {

            $post_id = $product->get_id();

            if ( $product->get_type() !== 'bundle' ) {
                return;
            }

            $status = get_post_meta( $post_id, '_wc_pb_bundled_items_stock_status', true );

            if ( $status && $status !== $product->get_stock_status() ) {

                $in_stock_field = 1;
                if ( $status === 'outofstock' ) {
                    $in_stock_field = 0;
                }

                $this->update_stock_status( $in_stock_field, $post_id );

            }

        }

        /*
         * On meta stock status field change
         */
        public function meta_changed( $meta_id, $object_id, $meta_key, $meta_value ) {

            if ( $meta_key !== '_wc_pb_bundled_items_stock_status' ) {
                return;
            }

            $post_type = get_post_type( $object_id );

            if ( ! $post_type || $post_type !== 'product' ) {
                return;
            }

            $product = wc_get_product( $object_id );

            if ( ! is_a( $product, 'WC_Product' ) ) {
                return;
            }

            if ( $product->get_type() !== 'bundle' ) {
                return;
            }

            $in_stock_field = 1;
            if ( $meta_value === 'outofstock' ) {
                $in_stock_field = 0;
            }

            $this->update_stock_status( $in_stock_field, $object_id );

        }

        /*
         * Update stock status field for index table
         */
        private function update_stock_status( $status, $post_id ) {

            if ( ! AWS()->option_vars->is_index_table_not_exists() ) {

                global $wpdb;

                $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

                $wpdb->update( $table_name, array( 'in_stock' => $status ), array( 'id' => $post_id ) );
                do_action('aws_cache_clear');

            }

        }

    }

endif;

AWS_Product_Bundles::instance();