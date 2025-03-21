<?php

/**
 * AWS plugin WOOF ( HUSKY ) - WooCommerce Products Filter integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Woof_Filter_Init')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Woof_Filter_Init {

        /**
         * @var AWS_Woof_Filter_Init The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Woof_Filter_Init Instance
         *
         * Ensures only one instance of AWS_Woof_Filter_Init is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woof_Filter_Init - Main instance
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

            add_filter( 'aws_search_page_filters', array( $this, 'woof_search_page_filters' ) );

            add_filter( 'aws_searchpage_enabled', array( $this, 'woof_searchpage_enabled' ), 1, 2 );

            add_filter( 'aws_search_page_query', array( $this, 'woof_aws_searchpage_query' ) );

            add_filter( 'woof_text_search_like_option', array( $this, 'woof_text_search_like_option' ) );

            add_filter( 'woof_get_request_data', array( $this, 'woof_get_request_data' ), 999 );

            add_filter( 'posts_where_request', array( $this, 'posts_where_request' ), 1 );

            add_filter( 'woof_get_filtered_price_query', array( $this, 'get_filtered_price_query' ) );

            add_filter( 'aws_search_page_custom_data', array( $this, 'aws_search_page_custom_data' ) );

        }

        /*
         * Filter products
         */
        public function woof_search_page_filters( $filters ) {

            if ( isset( $_GET['swoof'] ) || isset( $_GET['woof_text'] ) ) {

                $taxonomy_objects = get_object_taxonomies( 'product', 'objects' );
                $taxonomies_names = array();

                if ( $taxonomy_objects ) {
                    foreach( $taxonomy_objects as $taxonomy_object ) {
                        $taxonomies_names[] = $taxonomy_object->name;
                    }
                }

                foreach ( $_GET as $key => $param ) {

                    if ( array_search( $key, $taxonomies_names ) !== false || strpos($key, 'pa_') !== false ) {

                        $slugs_arr = explode(',', $param);
                        $term_ids = array();

                        if ( $slugs_arr ) {
                            foreach( $slugs_arr as $slug ) {
                                $term = get_term_by('slug', $slug, $key );
                                if ( $term ) {
                                    $term_ids[] = $term->term_id;
                                }
                            }
                        }

                        if ( ! empty( $term_ids ) ) {
                            $operator = 'OR';
                            $filters['tax'][$key] = array(
                                'terms' => $term_ids,
                                'operator' => $operator
                            );
                        }

                    }

                }
            }

            return $filters;

        }

        /*
         * Enable aws search
         */
        public function woof_searchpage_enabled( $enabled, $query ) {
            if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' && isset( $_GET['type_aws'] ) && isset( $_GET['woof_text'] ) && ! isset( $_REQUEST['woof_dyn_recount_going'] ) && ( $query->get( 'post_type' ) && is_string( $query->get( 'post_type' ) ) && $query->get( 'post_type' ) === 'product' ) ) {
                return true;
            }
            return $enabled;
        }

        /*
         * WOOF - WooCommerce Products Filter: set search query string
         */
        public function woof_aws_searchpage_query( $search_query ) {
            if ( ! $search_query && isset( $_GET['woof_text'] ) ) {
                return $_GET['woof_text'];
            }
            return $search_query;
        }

        /*
         * Enable text search feature
         */
        public function woof_text_search_like_option( $enable ) {
            if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' && isset( $_GET['type_aws'] ) ) {
                return true;
            }
            return $enable;
        }

        /*
         * Add woof_text query if it is not exists
         */
        public function woof_get_request_data( $request ) {
            if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' && isset( $_GET['type_aws'] ) && isset( $_GET['s'] ) && ! isset( $_GET['woof_text'] ) ) {
                $request['woof_text'] = $request['s'];
            }
            if ( isset( $_REQUEST['woof_dyn_recount_going'] ) ) {
                return '';
            }
            return $request;
        }

        /*
         * Set WHERE request for filters counter
         */
        public function posts_where_request( $where ) {
            if ( isset( $_REQUEST['woof_dyn_recount_going'] ) && $this->data && isset( $this->data['ids'] ) ) {
                global $wpdb;
                $where .= " AND {$wpdb->posts}.ID IN ( " . implode( ',', $this->data['ids'] ) . " ) ";
            }
            return $where;
        }

        /*
         * Fix price range filters
         */
        public function get_filtered_price_query( $sql ) {
            if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' && isset( $_GET['type_aws'] ) && $this->data && isset( $this->data['ids'] ) ) {
                global $wpdb;
                $sql .= " AND $wpdb->posts.ID IN(" . implode( ',', $this->data['ids'] ) . ")";
            }
            return $sql;
        }

        /*
         * Set search query custom data
         */
        public function aws_search_page_custom_data( $data ) {
            $this->data = $data;
            return $data;
        }


    }


endif;

AWS_Woof_Filter_Init::instance();