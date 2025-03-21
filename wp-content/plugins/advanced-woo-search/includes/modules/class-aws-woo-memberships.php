<?php
/**
 *  WooCommerce Memberships plugin integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Woo_Memberships' ) ) :

    /**
     * Class
     */
    class AWS_Woo_Memberships {

        /**
         * Main AWS_Woo_Memberships Instance
         *
         * Ensures only one instance of AWS_Woo_Memberships is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woo_Memberships - Main instance
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Woo_Memberships Instance
         *
         * Ensures only one instance of AWS_Woo_Memberships is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woo_Memberships - Main instance
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

            // Restrict products
            add_filter( 'aws_search_query_array', array( $this, 'search_query_array' ), 1 );

            // Restrict product categories
            add_filter( 'aws_terms_search_query', array( $this, 'terms_search_query' ), 1, 2 );

            // Restrict products content
            add_filter( 'aws_search_pre_filter_products', array( $this, 'aws_search_pre_filter_products' ), 1 );

        }

        /*
         * Hide restricted products
         */
        public function search_query_array( $query ) {

            global $wp_query;

            if ( ! current_user_can( 'wc_memberships_access_all_restricted_content' ) && function_exists( 'wc_memberships' ) ) {
                $feed_is_restricted = $wp_query instanceof \WP_Query && $wp_query->is_feed() && ! wc_memberships()->get_restrictions_instance()->is_restriction_mode( 'hide_content' );
                if ( $feed_is_restricted || wc_memberships()->get_restrictions_instance()->is_restriction_mode('hide') ) {
                    $restricted_posts = wc_memberships()->get_restrictions_instance()->get_user_restricted_posts();
                    if ( ! empty( $restricted_posts ) ) {
                        $query['search'] .= sprintf( ' AND ( id NOT IN ( %s ) )', implode( ',', $restricted_posts ) );
                    }
                }
            }

            return $query;

        }

        /*
         * Hide restricted categories
         */
        public function terms_search_query( $sql, $taxonomy ) {

            global $wpdb, $wp_query;

            if ( ! current_user_can( 'wc_memberships_access_all_restricted_content' ) && function_exists( 'wc_memberships' ) ) {
                $feed_is_restricted = $wp_query instanceof \WP_Query && $wp_query->is_feed() && !wc_memberships()->get_restrictions_instance()->is_restriction_mode('hide_content');
                if ( $feed_is_restricted || wc_memberships()->get_restrictions_instance()->is_restriction_mode('hide') ) {

                    $conditions = wc_memberships()->get_restrictions_instance()->get_user_content_access_conditions();
                    $conditions = isset( $conditions['restricted']['terms'] ) && is_array( $conditions['restricted']['terms'] ) ? $conditions['restricted']['terms'] : array();

                    if ( ! empty( $conditions ) && isset( $conditions['product_cat'] ) && ! empty( $conditions['product_cat'] ) ) {
                        $sql_terms = "AND $wpdb->term_taxonomy.term_id NOT IN ( " . implode( ',', $conditions['product_cat'] ) . " )";
                        $sql = str_replace( 'WHERE 1 = 1', 'WHERE 1 = 1 ' . $sql_terms, $sql );
                    }

                }
            }

            return $sql;

        }

        /*
         * Filter restricted products content
         */
        public function aws_search_pre_filter_products( $products_array ) {
            if ( ! current_user_can( 'wc_memberships_access_all_restricted_content' ) && function_exists( 'wc_memberships' ) ) {
                if ( wc_memberships()->get_restrictions_instance()->is_restriction_mode( 'hide_content' ) ) {
                    $restricted_posts = wc_memberships()->get_restrictions_instance()->get_user_restricted_posts();
                    if ( ! empty( $restricted_posts ) ) {

                        $show_excerpts = 'yes' === get_option( 'wc_memberships_show_excerpts' );

                        foreach ( $products_array as $key => $product_item ) {
                            if ( array_search( $product_item['parent_id'], $restricted_posts ) !== false ) {
                                $products_array[$key]['image'] = wc_placeholder_img_src();
                                $products_array[$key]['excerpt'] = $show_excerpts ? $product_item['excerpt'] : '';
                                $products_array[$key]['price'] = '';
                                $products_array[$key]['categories'] = '';
                                $products_array[$key]['tags'] = '';
                                $products_array[$key]['brands'] = '';
                                $products_array[$key]['on_sale'] = '';
                                $products_array[$key]['sku'] = '';
                                $products_array[$key]['stock_status'] = '';
                                $products_array[$key]['rating'] = '';
                                $products_array[$key]['reviews'] = '';
                                $products_array[$key]['variations'] = '';
                                $products_array[$key]['add_to_cart'] = '';
                            }
                        }

                    }
                }
            }

            return $products_array;

        }

    }

endif;

AWS_Woo_Memberships::instance();