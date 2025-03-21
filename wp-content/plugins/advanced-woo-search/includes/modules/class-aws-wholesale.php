<?php
/**
 * Wholesale plugin support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Wholesale' ) ) :

    /**
     * Class
     */
    class AWS_Wholesale {

        /**
         * Main AWS_Wholesale Instance
         *
         * Ensures only one instance of AWS_Wholesale is loaded or can be loaded.
         *
         * @static
         * @return AWS_Wholesale - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Wholesale Instance
         *
         * Ensures only one instance of AWS_Wholesale is loaded or can be loaded.
         *
         * @static
         * @return AWS_Wholesale - Main instance
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

            add_filter( 'aws_exclude_products', array( $this, 'exclude_products' ) );

            add_filter( 'aws_search_tax_exclude', array( $this, 'exclude_tax' ), 10, 2 );

        }

        /*
         * Restrict products
         */
        public function exclude_products( $products_ids ) {

            $excluded = $this->get_excluded_products();
            $excluded_by_cat = $this->get_excluded_products_by_cat();

            if ( ! empty( $excluded ) || ! empty( $excluded_by_cat ) ) {
                $products_ids = array_merge( $products_ids, $excluded, $excluded_by_cat );
            }

            return $products_ids;

        }

        /*
         * Restrict product_cat taxonomy
         */
        public function exclude_tax( $excludes_array, $taxonomy ) {

            if ( array_search( 'product_cat', $taxonomy ) !== false ) {

                $user_role = $this->get_current_user();
                $product_cat_wholesale_role_filter = get_option( 'wwpp_option_product_cat_wholesale_role_filter' );
                $categories_exclude_list = array();

                if ( is_array( $product_cat_wholesale_role_filter ) && ! empty( $product_cat_wholesale_role_filter ) && $user_role !== 'administrator' ) {
                    foreach( $product_cat_wholesale_role_filter as $term_id => $term_roles ) {
                        if ( array_search( $user_role, $term_roles ) === false ) {
                            $categories_exclude_list[] = $term_id;
                        }
                    }
                }

                $excludes_array = array_merge( $excludes_array, $categories_exclude_list );

            }

            return $excludes_array;

        }

        /*
         * Get current user role
         */
        private function get_current_user() {

            $user_role = 'all';
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                $user_role = $roles[0];
            }

            return $user_role;

        }

        /*
         * Get excluded products by category
         */
        private function get_excluded_products_by_cat() {

            $user_role = $this->get_current_user();

            $products_ids = array();
            $product_cat_wholesale_role_filter = get_option( 'wwpp_option_product_cat_wholesale_role_filter' );
            $categories_exclude_list = array();

            if ( is_array( $product_cat_wholesale_role_filter ) && ! empty( $product_cat_wholesale_role_filter ) && $user_role !== 'administrator' ) {
                foreach( $product_cat_wholesale_role_filter as $term_id => $term_roles ) {
                    if ( array_search( $user_role, $term_roles ) === false ) {
                        $categories_exclude_list[] = $term_id;
                    }
                }
            }

            if ( $categories_exclude_list && ! empty( $categories_exclude_list ) ) {

                $restricted_products = get_posts( array(
                    'posts_per_page'      => -1,
                    'fields'              => 'ids',
                    'post_type'           => array( 'product', 'product_variation' ),
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                    'suppress_filters'    => true,
                    'has_password'        => false,
                    'no_found_rows'       => 1,
                    'orderby'             => 'ID',
                    'order'               => 'DESC',
                    'lang'                => '',
                    'tax_query' => array(
                        'relation' => 'OR',
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'id',
                            'terms'    => $categories_exclude_list,
                        ),
                    ),
                ) );

                if ( $restricted_products ) {
                    $products_ids = $restricted_products;
                }

            }

            return $products_ids;

        }

        /*
         * Get excluded products
         */
        private function get_excluded_products() {

            $user_role = $this->get_current_user();

            $all_registered_wholesale_roles = unserialize( get_option( 'wwp_options_registered_custom_roles' ) );
            if ( ! is_array( $all_registered_wholesale_roles ) ) {
                $all_registered_wholesale_roles = array();
            }

            $products_ids = array();
            $meta_query = array();

            if ( is_user_logged_in() && !empty( $all_registered_wholesale_roles ) && isset( $all_registered_wholesale_roles[$user_role] )
                && get_option( 'wwpp_settings_only_show_wholesale_products_to_wholesale_users', false ) === 'yes' ) {

                $meta_query = array(
                    'relation' => 'OR',
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => 'wwpp_product_wholesale_visibility_filter',
                            'value'   => 'all',
                            'compare' => '!=',
                        ),
                        array(
                            'key' => 'wwpp_product_wholesale_visibility_filter',
                            'value'   => $user_role,
                            'compare' => '!=',
                        )
                    ),
                    array(
                        'key' => 'wholesale_customer_wholesale_price',
                        'compare' => 'NOT EXISTS'
                    ),
                );

            } else {

                $meta_query = array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wwpp_product_wholesale_visibility_filter',
                        'value'   => 'all',
                        'compare' => '!=',
                    ),
                    array(
                        'key' => 'wwpp_product_wholesale_visibility_filter',
                        'value'   => $user_role,
                        'compare' => '!=',
                    )
                );

            }

            $restricted_products = get_posts( array(
                'posts_per_page'      => -1,
                'fields'              => 'ids',
                'post_type'           => array( 'product', 'product_variation' ),
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'suppress_filters'    => true,
                'has_password'        => false,
                'no_found_rows'       => 1,
                'orderby'             => 'ID',
                'order'               => 'DESC',
                'lang'                => '',
                'meta_query'          => $meta_query
            ) );

            if ( $restricted_products ) {
                $products_ids = $restricted_products;
            }

            return $products_ids;

        }

    }

endif;

AWS_Wholesale::instance();