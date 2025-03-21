<?php
/**
 *  WooCommerce Products Visibility
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_WCPV' ) ) :

    /**
     * Class
     */
    class AWS_WCPV {

        /**
         * Main AWS_WCPV Instance
         *
         * Ensures only one instance of AWS_WCPV is loaded or can be loaded.
         *
         * @static
         * @return AWS_WCPV - Main instance
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_WCPV Instance
         *
         * Ensures only one instance of AWS_WCPV is loaded or can be loaded.
         *
         * @static
         * @return AWS_WCPV - Main instance
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

            add_filter( 'aws_search_query_array', array( $this, 'aws_search_query_array' ), 1 );

            add_filter( 'aws_terms_search_query', array( $this, 'aws_terms_search_query' ), 1 );

        }

        /*
         * Filter products
         */
        public function aws_search_query_array( $query ) {
            if ( isset( $query['exclude_products'] ) && class_exists( 'WCPV_FRONTEND' ) ) {
                $wc_front = WCPV_FRONTEND::get_instance();
                if ( method_exists( $wc_front, 'get_products_total_subquery' ) ) {
                    $filter_sql = str_replace("post_type='product' AND", '', $wc_front->get_products_total_subquery() );
                    $query['exclude_products'] .= ' ' . $filter_sql;
                }
            }
            return $query;
        }

        /*
         * Filter taxonomies
         */
        public function aws_terms_search_query( $sql ) {

            if ( class_exists( 'WCPV_FRONTEND' ) ) {

                global $wpdb;

                $string = '';
                $category_ids = array();
                $tag_ids = array();

                $wc_front = WCPV_FRONTEND::get_instance();

                if ( property_exists( $wc_front, 'categoryids' ) && property_exists( $wc_front, 'categories_visibility' ) ) {

                    $category_ids = $wc_front->categoryids;
                    $relation = $wc_front->categories_visibility === 'include' ? "IN" : "NOT IN";

                    if ( ! empty( $category_ids ) ) {
                        $string .= sprintf( " ( " . $wpdb->terms . ".term_id %s ( %s ) ) ", $relation, implode( ',', $category_ids ) );
                    }

                }

                if ( property_exists( $wc_front, 'tagids' ) && property_exists( $wc_front, 'tags_visibility' ) ) {

                    $tag_ids = $wc_front->tagids;
                    $relation = $wc_front->tags_visibility === 'include' ? "IN" : "NOT IN";

                    $subpart = '';
                    if ( ! empty( $category_ids ) ) {
                        $subpart = $wc_front->tags_visibility == 'include' ? " OR " : " AND ";
                    }

                    if ( ! empty( $tag_ids ) ) {
                        $string .= sprintf( " %s ( " . $wpdb->terms . ".term_id %s ( %s ) ) ", $subpart, $relation, implode( ',', $tag_ids ) );
                    }

                }

                if ( $string ) {
                    $sql = str_replace( 'WHERE 1 = 1', 'WHERE 1 = 1 AND ( ' . $string . ' )', $sql );
                }

            }

            return $sql;

        }

    }

endif;

AWS_WCPV::instance();