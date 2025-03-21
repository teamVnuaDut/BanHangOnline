<?php
/**
 * MultiVendorX – WooCommerce multivendor marketplace plugin support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Multivendorx' ) ) :

    /**
     * Class
     */
    class AWS_Multivendorx {

        /**
         * Main AWS_Multivendorx Instance
         *
         * Ensures only one instance of AWS_Multivendorx is loaded or can be loaded.
         *
         * @static
         * @return AWS_Multivendorx - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Multivendorx Instance
         *
         * Ensures only one instance of AWS_Multivendorx is loaded or can be loaded.
         *
         * @static
         * @return AWS_Multivendorx - Main instance
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

            add_action( 'wp_after_insert_post', array( $this, 'wp_after_insert_post' ), 10, 4 );

            add_filter( 'aws_search_data_params', array( $this, 'wc_marketplace_filter' ), 10, 3 );
            add_filter( 'aws_search_pre_filter_products', array( $this, 'wc_marketplace_products_filter' ), 10, 2 );

        }

        /*
         * Index product after it was approved
         */
        public function wp_after_insert_post( $post_id, $post, $update, $post_before ) {

            if ( $update && $post->post_type === 'product' && $post->post_status === 'publish' && $post_before && $post_before->post_status === 'pending' ) {
                do_action( 'aws_reindex_product', $post_id );
            }

        }

        /*
         * WC Marketplace plugin support
         */
        public function wc_marketplace_filter( $data, $post_id, $product ) {

            $wcmp_spmv_map_id = get_post_meta( $post_id, '_wcmp_spmv_map_id', true );

            if ( $wcmp_spmv_map_id ) {

                if ( isset( $data['wcmp_price'] ) && isset( $data['wcmp_price'][$wcmp_spmv_map_id] )  ) {

                    if ( $product->get_price() < $data['wcmp_price'][$wcmp_spmv_map_id] ) {
                        $data['wcmp_price'][$wcmp_spmv_map_id] = $product->get_price();
                        $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] = $post_id;
                    }

                } else {
                    $data['wcmp_price'][$wcmp_spmv_map_id] = $product->get_price();
                }

                $data['wcmp_spmv_product_id'][$wcmp_spmv_map_id][] = $post_id;

            }

            return $data;

        }

        /*
         * WC Marketplace plugin products filter
         */
        public function wc_marketplace_products_filter( $products_array, $data ) {

            $wcmp_spmv_exclude_ids = array();

            if ( isset( $data['wcmp_spmv_product_id'] ) ) {

                foreach( $data['wcmp_spmv_product_id'] as $wcmp_spmv_map_id => $wcmp_spmv_product_id ) {

                    if ( count( $wcmp_spmv_product_id ) > 1 ) {

                        if ( isset( $data['wcmp_lowest_price_id'] ) && isset( $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] ) ) {

                            foreach ( $wcmp_spmv_product_id as $wcmp_spmv_product_id_n ) {

                                if ( $wcmp_spmv_product_id_n === $data['wcmp_lowest_price_id'][$wcmp_spmv_map_id] ) {
                                    continue;
                                }

                                $wcmp_spmv_exclude_ids[] = $wcmp_spmv_product_id_n;

                            }

                        } else {

                            foreach ( $wcmp_spmv_product_id as $key => $wcmp_spmv_product_id_n ) {

                                if ( $key === 0 ) {
                                    continue;
                                }

                                $wcmp_spmv_exclude_ids[] = $wcmp_spmv_product_id_n;

                            }

                        }

                    }

                }

            }

            $new_product_array = array();

            foreach( $products_array as $key => $pr_arr ) {

                if ( ! in_array( $pr_arr['id'], $wcmp_spmv_exclude_ids ) ) {
                    $new_product_array[] = $pr_arr;
                }

            }

            return $new_product_array;

        }

    }

endif;

AWS_Multivendorx::instance();