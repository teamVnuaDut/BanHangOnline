<?php
/**
 * Ultimate Member plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_UM' ) ) :

    /**
     * Class
     */
    class AWS_UM {

        /**
         * Main AWS_UM Instance
         *
         * Ensures only one instance of AWS_UM is loaded or can be loaded.
         *
         * @static
         * @return AWS_UM - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_UM Instance
         *
         * Ensures only one instance of AWS_UM is loaded or can be loaded.
         *
         * @static
         * @return AWS_UM - Main instance
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

            add_filter( 'aws_search_tax_results', array( $this, 'exclude_tax' ), 10, 2 );

        }

        /*
         * Restrict products
         */
        public function exclude_products( $products_ids ) {

            $restricted_products_by_terms = $this->get_restricted_products_by_terms();

            $restricted_products = get_posts( array(
                'posts_per_page'      => -1,
                'fields'              => 'ids',
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'suppress_filters'    => true,
                'has_password'        => false,
                'no_found_rows'       => 1,
                'orderby'             => 'ID',
                'order'               => 'DESC',
                'lang'                => '',
                'meta_query' => array(
                    array(
                        'key' => 'um_content_restriction',
                        'compare' => 'EXISTS',
                    )
                )
            ) );

            if ( $restricted_products ) {
                foreach( $restricted_products as $restricted_product_id ) {

                    $um_content_restriction = get_post_meta( $restricted_product_id, 'um_content_restriction', true );

                    $is_restricted = $this->is_restricted( $um_content_restriction );

                    if ( $is_restricted ) {
                        $products_ids[] = $restricted_product_id;
                    }

                }
            }

            if ( ! empty( $restricted_products_by_terms ) ) {
                $products_ids = array_merge($products_ids, $restricted_products_by_terms);

            }

            return $products_ids;

        }

        /*
         * Restrict taxonomies
         */
        public function exclude_tax( $result_array, $taxonomy ) {

            $new_result_array = array();

            foreach ( $result_array as $result_tax_name => $result_tax ) {
                foreach ( $result_tax as $tax_key => $tax_item ) {
                    $um_content_restriction = get_term_meta( $tax_item['id'], 'um_content_restriction', true );
                    $is_restricted = $this->is_restricted( $um_content_restriction );
                    if ( $is_restricted ) {
                        continue;
                    }
                    $new_result_array[$result_tax_name][] = $tax_item;
                }
            }

            return $new_result_array;

        }

        /*
         * Check is product/term is restricted for current user
         */
        private function is_restricted( $um_content_restriction ) {

            if ( $um_content_restriction && is_array( $um_content_restriction ) && ! empty( $um_content_restriction ) ) {

                $um_custom_access_settings = isset( $um_content_restriction['_um_custom_access_settings'] ) ? $um_content_restriction['_um_custom_access_settings'] : false;
                $um_access_hide_from_queries = isset( $um_content_restriction['_um_access_hide_from_queries'] ) ? $um_content_restriction['_um_access_hide_from_queries'] : false;

                if ( $um_custom_access_settings && $um_custom_access_settings === '1' && $um_access_hide_from_queries && $um_access_hide_from_queries === '1' ) {

                    $um_accessible = isset( $um_content_restriction['_um_accessible'] ) ? $um_content_restriction['_um_accessible'] : false;

                    if ( $um_accessible ) {

                        if ( $um_accessible === '1' && is_user_logged_in() ) {
                            return true;
                        }
                        elseif ( $um_accessible === '2' && ! is_user_logged_in() ) {
                            return true;
                        }
                        elseif ( $um_accessible === '2' && is_user_logged_in() ) {

                            $um_access_roles = isset( $um_content_restriction['_um_access_roles'] ) ? $um_content_restriction['_um_access_roles'] : false;

                            if ( $um_access_roles && is_array( $um_access_roles ) && ! empty( $um_access_roles ) ) {
                                $user = wp_get_current_user();
                                $role = ( array ) $user->roles;
                                $user_role = $role[0];
                                if ( $user_role && $user_role !== 'administrator' && ! isset( $um_access_roles[$user_role] ) ) {
                                    return true;
                                }
                            }

                        }

                    }

                }

            }

            return false;

        }

        /*
         * Get restricted products by restricted terms
         */
        private function get_restricted_products_by_terms() {

            $restricted_products = array();
            $restricted_terms = $this->get_restricted_terms();

            if ( $restricted_terms && ! empty( $restricted_terms ) ) {

                $args = array(
                    'posts_per_page'      => -1,
                    'fields'              => 'ids',
                    'post_type'           => 'product',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                    'suppress_filters'    => true,
                    'has_password'        => false,
                    'no_found_rows'       => 1,
                    'orderby'             => 'ID',
                    'order'               => 'DESC',
                    'lang'                => '',
                ) ;

                $args['tax_query']['relation'] = 'OR';

                foreach ( $restricted_terms as $restricted_tax_name => $restricted_tax ) {
                    $args['tax_query'][] =  array(
                        'taxonomy' => $restricted_tax_name,
                        'field'    => 'id',
                        'terms'    => $restricted_tax,
                    );
                }

                $restricted_products = get_posts( $args );

            }

            return $restricted_products;

        }

        /*
         * Get restricted terms
         */
        private function get_restricted_terms() {

            $restricted_terms_arr = array();

            if ( function_exists( 'UM' ) ) {

                $restricted_taxonomies_option = UM()->options()->get( 'restricted_access_taxonomy_metabox' );
                $restricted_taxonomies = array();
                if ( $restricted_taxonomies_option && is_array( $restricted_taxonomies_option ) ) {
                    foreach( $restricted_taxonomies_option as $restricted_taxonomy => $restricted_taxonomy_val ) {
                        if ( $restricted_taxonomy_val ) {
                            $restricted_taxonomies[] = $restricted_taxonomy;
                        }
                    }
                }

                if ( ! empty( $restricted_taxonomies ) ) {

                    $restricted_terms = get_terms( array(
                        'taxonomy'   => $restricted_taxonomies,
                        'hide_empty' => false,
                        'lang'       => '',
                        'meta_query' => array(
                            array(
                                'key' => 'um_content_restriction',
                                'compare' => 'EXISTS',
                            )
                        )
                    ) );

                    if ( $restricted_terms ) {
                        foreach( $restricted_terms as $restricted_term ) {
                            if ( is_object( $restricted_term ) ) {
                                $um_content_restriction = get_term_meta( $restricted_term->term_id, 'um_content_restriction', true );
                                $is_restricted = $this->is_restricted( $um_content_restriction );
                                if ( $is_restricted ) {
                                    $restricted_terms_arr[$restricted_term->taxonomy][] = $restricted_term->term_id;
                                }
                            }
                        }
                    }

                }
            }

            return $restricted_terms_arr;

        }

    }

endif;

AWS_UM::instance();