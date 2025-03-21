<?php

/**
 * FacetWP integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_FacetWP')) :

    /**
     * Class for main plugin functions
     */
    class AWS_FacetWP {

        /**
         * @var AWS_FacetWP The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_FacetWP Instance
         *
         * Ensures only one instance of AWS_FacetWP is loaded or can be loaded.
         *
         * @static
         * @return AWS_FacetWP - Main instance
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

            if ( apply_filters( 'aws_disable_facetwp_integration', false ) ) {
                return;
            }

            add_filter( 'facetwp_pre_filtered_post_ids', array( $this, 'facetwp_pre_filtered_post_ids' ), 10, 2 );
            add_filter( 'facetwp_filtered_post_ids', array( $this, 'facetwp_filtered_post_ids' ), 1 );
            add_filter( 'aws_searchpage_enabled', array( $this, 'aws_searchpage_enabled' ), 1 );
            add_filter( 'aws_search_page_custom_data', array( $this, 'aws_search_page_custom_data' ), 1 );
            add_filter( 'posts_pre_query', array( $this, 'posts_pre_query' ), 9999, 2 );
            add_filter( 'facetwp_pager_args', array( $this, 'facetwp_pager_args' ), 1 );

        }

        /*
         * FacetWP add unfiltered products IDs
         */
        public function facetwp_pre_filtered_post_ids( $post_ids, $obj ) {
            if ( class_exists( 'AWS_Search_Page' ) && isset( $_GET['type_aws'] ) && isset( $_GET['s'] ) ) {

                global $wp_query;
                $posts_per_page = $obj && $obj->query_args && isset( $obj->query_args['posts_per_page'] ) ? $obj->query_args['posts_per_page'] : $wp_query->query_vars['posts_per_page'];

                $paged = false;
                if ( ! $paged && isset( $_REQUEST['_paged'] ) ) {
                    $paged = intval( $_REQUEST['_paged'] );
                }
                if ( ! $paged && $obj && $obj->ajax_params && isset( $obj->ajax_params['paged'] )  ) {
                    $paged = intval( $obj->ajax_params['paged'] );
                }
                if ( ! $paged ) {
                    $paged = $obj && $obj->query_args && isset( $obj->query_args['paged'] ) ? $obj->query_args['paged'] : $wp_query->query_vars['paged'];
                }
                if ( ! $paged ) {
                    $paged = 1;
                }

                $search_res = AWS_Search_Page::factory()->search( $obj->query, $posts_per_page, $paged );

                $this->data['posts_per_page'] = $posts_per_page;
                $this->data['paged'] = $paged;

                if ( $search_res ) {
                    $products_ids = array();
                    $all_products_ids = array();
                    foreach ( $search_res['products'] as $product ) {
                        $products_ids[] = $product['id'];
                    }
                    foreach ( $search_res['all'] as $product ) {
                        $all_products_ids[] = $product;
                    }
                    $post_ids = $all_products_ids;
                    $this->data['all_products_ids'] = $all_products_ids;
                    $this->data['products_ids'] = $products_ids;
                }

            }
            return $post_ids;
        }

        /*
         * FacetWP check for active filters
         */
        public function facetwp_filtered_post_ids( $post_ids ) {

            if ( isset( $_GET['type_aws'] ) && isset( $_GET['s'] ) && ! empty( $post_ids ) ) {

                $this->data['facetwp'] = true;
                $this->data['pager_count'] = count( $post_ids );

                if ( count( $this->data['all_products_ids'] ) === count( $post_ids ) ) {
                    $this->data['facetwp'] = false;
                    $post_ids = $this->data['products_ids'];
                    $this->data['filtered_post_ids'] = $this->data['products_ids'];
                    $this->data['pager_count'] = count( $this->data['all_products_ids'] );
                } else {
                    $this->data['products_ids'] = $post_ids;
                    $offset = ( $this->data['paged'] > 1 ) ? $this->data['paged'] * $this->data['posts_per_page'] - $this->data['posts_per_page'] : 0;
                    $post_ids = array_slice( $post_ids, $offset, $this->data['posts_per_page'] );
                    $this->data['filtered_post_ids'] = $post_ids;
                }

            }

            return $post_ids;

        }

        /*
         * Disable AWS search if FacetWP is active
         */
        public function aws_searchpage_enabled( $enabled ) {
            if ( isset( $this->data['facetwp'] ) && $this->data['facetwp'] ) {
                $enabled = false;
            }
            return $enabled;
        }

        /*
         * FacetWP - Update search page query
         */
        public function aws_search_page_custom_data( $data ) {
            if ( isset( $this->data['facetwp'] ) && $this->data['facetwp'] ) {
                $data['force_ids'] = true;
            }
            return $data;
        }

        /*
         * Update posts query
         */
        public function posts_pre_query( $posts, $query ) {
            if (  isset( $this->data['facetwp'] ) && $this->data['facetwp'] && ( $query->is_main_query() || $query->is_search() ) && isset( $this->data['filtered_post_ids'] ) && ! empty( $this->data['filtered_post_ids'] ) ) {
                if ( isset( $this->data['products_ids'] ) && $posts && count( $this->data['products_ids'] ) !== count( $posts ) ) {
                    $query->found_posts = count( $this->data['products_ids'] );
                    $query->max_num_pages = ceil( count( $this->data['products_ids'] ) / $query->get( 'posts_per_page' ) );
                }
                $posts = $this->data['filtered_post_ids'];
            }
            return $posts;
        }

        /*
         * Fix pagination facet
         */
        public function facetwp_pager_args( $pager_args ) {

            if ( isset( $this->data['pager_count'] ) ) {
                $pager_args['total_rows'] = $this->data['pager_count'];
                $pager_args['total_pages'] = ceil( $pager_args['total_rows'] / $pager_args['per_page'] );
            }
            return $pager_args;
        }

    }

endif;

AWS_FacetWP::instance();