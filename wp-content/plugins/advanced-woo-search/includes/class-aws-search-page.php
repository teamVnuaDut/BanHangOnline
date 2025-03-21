<?php
/**
 * Integrate with WP_Query
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Search_Page' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Search_Page {

        /**
         * Is set only when we are within a multisite loop
         *
         * @var bool|WP_Query
         */
        private $query_stack = array();

        private $posts_by_query = array();

        private $data = array();

        /**
         * Return a singleton instance of the current class
         *
         * @return object
         */
        public static function factory() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
                $instance->setup();
            }

            return $instance;
        }

        /**
         * Placeholder
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            // Make sure we return nothing for MySQL posts query
            add_filter( 'posts_request', array( $this, 'filter_posts_request' ), 999, 2 );

            // Query and filter to WP_Query
            add_filter( 'the_posts', array( $this, 'filter_the_posts' ), 999, 2 );

            // Add header
            add_action( 'pre_get_posts', array( $this, 'action_pre_get_posts' ), 5 );

            // Overwrite query
            add_action( 'pre_get_posts', array( $this, 'pre_get_posts_overwrite' ), 999 );

            // Nukes the FOUND_ROWS() database query
            add_filter( 'found_posts_query', array( $this, 'filter_found_posts_query' ), 5, 2 );

            // Update found post query param
            add_filter( 'found_posts', array( $this, 'filter_found_posts' ), 999, 2 );

            // WooCommerce default widget filters
            add_filter( 'woocommerce_layered_nav_link', array( $this, 'woocommerce_layered_nav_link' ) );
            add_filter( 'woocommerce_get_filtered_term_product_counts_query', array( $this, 'woocommerce_get_filtered_term_product_counts_query' ), 999 );
            add_filter( 'woocommerce_price_filter_sql', array( $this, 'woocommerce_price_filter_sql' ), 999 );

            add_filter( 'posts_pre_query', array( $this, 'posts_pre_query' ), 999, 2 );

            // Overwrite WooCommerce global products count if it is set to zero
            add_filter( 'woocommerce_product_loop_start', array( $this, 'woocommerce_product_loop_start' ), 99999 );

            add_filter( 'body_class', array( $this, 'body_class' ), 999 );

            // Divi builder support
            add_action( 'et_pb_shop_before_print_shop', array( $this, 'et_pb_shop_before_print_shop' ) );
            add_action( 'et_pb_shop_after_print_shop', array( $this, 'et_pb_shop_after_print_shop' ) );

            // Total number of search results
            add_filter( 'aws_page_results', array( $this, 'aws_page_results' ), 1 );

            // Number of search results per page
            add_filter( 'aws_posts_per_page', array( $this, 'aws_posts_per_page' ), 1 );
            add_filter( 'woocommerce_product_query', array( $this, 'woocommerce_product_query' ), 1 );

            // Change default search page query
            add_filter( 'aws_search_page_custom_data', array( $this, 'aws_search_page_custom_data' ), 1 );

        }

        /**
         * Filter query string used for get_posts(). Query for posts and save for later.
         * Return a query that will return nothing.
         *
         * @param string $request
         * @param object $query
         * @return string
         */
        public function filter_posts_request( $request, $query ) {
            if ( ! AWS_Helpers::aws_searchpage_enabled( $query ) ) {
                return $request;
            }

            $new_posts = array();

            $posts_per_page = apply_filters( 'aws_posts_per_page', $query->get( 'posts_per_page' ) );
            $paged = $query->query_vars['paged'] ? $query->query_vars['paged'] : 1;
            $search_res = $this->search( $query, $posts_per_page, $paged );

            if ( $search_res ) {

                $query->found_posts = count( $search_res['all'] );
                $query->max_num_pages = ceil( count( $search_res['all'] ) / $posts_per_page );
                $query->set( 'posts_per_page', $posts_per_page );

                $new_posts = $this->set_posts_objects( $search_res, $query );

                $this->posts_by_query[spl_object_hash( $query )] = $new_posts;

            }

            global $wpdb;

            return "SELECT * FROM $wpdb->posts WHERE 1=0";

        }

        /**
         * Filter the posts array.
         *
         * @param array $posts
         * @param object $query
         * @return array|null
         */
        public function posts_pre_query( $posts, $query ) {

            $post_type_product = $query->get( 'post_type' ) && ( ( is_string( $query->get( 'post_type' ) ) && ( $query->get( 'post_type' ) === 'product' ) ) || ( is_array( $query->get( 'post_type' ) ) && in_array( 'product', $query->get( 'post_type' ) ) ) );

            if ( ( $query->is_main_query() || $query->is_search() || isset( $query->query_vars['s'] ) ) && $post_type_product && isset( $_GET['type_aws'] ) && $query->query ) {

                /**
                 * Filter search results custom data array
                 * @since 2.19
                 * @param array $this->data Search results data array
                 * @param object $query Query
                 * @param array $posts Posts
                 */
                $this->data = apply_filters( 'aws_search_page_custom_data', $this->data, $query, $posts );

                if ( ( isset( $this->data['force_ids'] ) && $this->data['force_ids'] ) || ( isset( $this->data['is_elementor'] ) && $this->data['is_elementor'] ) || ( isset( $this->data['is_divi_s_page'] ) && $this->data['is_divi_s_page'] ) ) {

                    $products_ids = array();
                    $posts_per_page = apply_filters( 'aws_posts_per_page', $query->get( 'posts_per_page' ) );
                    $paged = $query->query_vars['paged'] ? $query->query_vars['paged'] : 1;

                    $search_res = $this->search( $query, $posts_per_page, $paged );

                    if ( $search_res ) {

                        $query->found_posts = count( $search_res['all'] );
                        $query->max_num_pages = ceil( count( $search_res['all'] ) / $posts_per_page );

                        foreach ( $search_res['products'] as $product ) {
                            $products_ids[] = $product['id'];
                        }

                        $posts = $products_ids;

                    }

                }

            }

            return $posts;

        }

        /**
         * Filter the posts array to contain search query result. Pull previously queried posts.
         *
         * @param array $posts
         * @param object $query
         * @return array
         */
        public function filter_the_posts( $posts, $query ) {
            if ( ! AWS_Helpers::aws_searchpage_enabled( $query ) || ! isset( $this->posts_by_query[spl_object_hash( $query )] ) ) {
                return $posts;
            }

            $new_posts = $this->posts_by_query[spl_object_hash( $query )];

            return $new_posts;

        }

        /**
         * Disables cache_results, adds header.
         *
         * @param $query
         */
        public function action_pre_get_posts( $query ) {
            if ( ! AWS_Helpers::aws_searchpage_enabled( $query ) ) {
                return;
            }

            /**
             * `cache_results` defaults to false but can be enabled
             */
            $query->set( 'cache_results', false );
            if ( ! empty( $query->query['cache_results'] ) ) {
                $query->set( 'cache_results', true );
            }

            if ( ! headers_sent() ) {
                /**
                 * Manually setting a header as $wp_query isn't yet initialized
                 * when we call: add_filter('wp_headers', 'filter_wp_headers');
                 */
                header( 'X-AWS-Search: true' );
            }
        }

        /**
         * Make necessary changes in main query.
         *
         * @param $query
         */
        public function pre_get_posts_overwrite( $query ) {
            if ( ! AWS_Helpers::aws_searchpage_enabled( $query ) ) {
                return;
            }

            // Divi builder fix
            if ( defined( 'ET_CORE' ) && $GLOBALS && isset( $GLOBALS['et_builder_used_in_wc_shop'] ) && $GLOBALS['et_builder_used_in_wc_shop'] ) {

                $GLOBALS['et_builder_used_in_wc_shop'] = false;

                $query->set( 'page_id', 0 );
                $query->set( 'post_type', 'product' );
                $query->set( 'posts_per_page', apply_filters( 'aws_posts_per_page', get_option( 'posts_per_page' ) ) );
                $query->set( 'wc_query', 'product_query' );
                $query->set( 'meta_query', array() );

                $query->is_singular          = false;
                $query->is_page              = false;
                $query->is_post_type_archive = true;
                $query->is_archive           = true;

            }

            $query->set( 'aws_query', true );

        }

        /**
         * Remove the found_rows from the SQL Query
         *
         * @param string $sql
         * @param object $query
         * @return string
         */
        public function filter_found_posts_query( $sql, $query ) {
            if ( ! AWS_Helpers::aws_searchpage_enabled( $query ) ) {
                return $sql;
            }
            return '';
        }

        /**
         * Filters the number of found posts for the query.
         *
         * @param int $found_posts The number of posts found
         * @param object $query
         * @return string
         */
        public function filter_found_posts( $found_posts, $query ) {

            $post_type_product = $query->get( 'post_type' ) && ( ( is_string( $query->get( 'post_type' ) ) && ( $query->get( 'post_type' ) === 'product' ) ) || ( is_array( $query->get( 'post_type' ) ) && in_array( 'product', $query->get( 'post_type' ) ) ) );

            if ( ( $query->is_main_query() || $query->is_search() || isset( $query->query_vars['s'] ) ) && $post_type_product && isset( $_GET['type_aws'] ) && isset( $this->data['all_products'] ) && $this->data['all_products'] && isset( $query->query_vars['nopaging'] ) && ! $query->query_vars['nopaging'] &&
                ( ( isset( $this->data['force_ids'] ) && $this->data['force_ids'] ) || ( isset( $this->data['is_elementor'] ) && $this->data['is_elementor'] ) || ( isset( $this->data['is_divi_s_page'] ) && $this->data['is_divi_s_page'] ) )
            ) {
                $found_posts = count( $this->data['all_products'] );
            }

            return $found_posts;

        }

        /**
         * Perform the search.
         *
         * @param object $query
         * @param int $posts_per_page
         * @param int $paged
         * @return array | bool
         */
        public function search( $query, $posts_per_page, $paged = 1 ) {

            if ( ! did_action( 'woocommerce_init' ) || ! did_action( 'woocommerce_after_register_taxonomy' ) || ! did_action( 'woocommerce_after_register_post_type' ) ) {
                return false;
            }

            $s = $this->get_search_query( $query );

            $hash = hash( 'md2', $s );

            if ( isset( $this->data['search_res'][$hash] ) ) {
                $post_array_products = $this->data['search_res'][$hash];
            } else {
                $post_array_products = (array) aws_search( $s, 'ids' );
                $this->data['search_res'][$hash] = $post_array_products;
            }

            // Filter and order output
            if ( $post_array_products && is_array( $post_array_products ) && ! empty( $post_array_products ) && is_object( $query ) ) {
                $post_array_products = AWS()->order( $post_array_products, $query );
            }

            if ( is_numeric( $posts_per_page ) && (int) $posts_per_page < 0 ) {
                $posts_per_page = 999999;
            }

            $offset = ( $paged > 1 ) ? $paged * $posts_per_page - $posts_per_page : 0;

            $products = array_slice( $post_array_products, $offset, $posts_per_page );

            $this->data['all_products'] = $post_array_products;

            if ( $this->data['all_products'] ) {
                foreach( $this->data['all_products'] as $sproduct ) {
                    if ( ! is_array( $sproduct ) && ! is_object( $sproduct ) ) {
                        $this->data['ids'][$sproduct] = $sproduct;
                    }
                }
            }

            /**
             * Return only current page products IDs id needed
             * @since 3.10
             * @param bool $return_only_ids
             * @param object|bool $query Search query object
             * @param array $this->data Search data array
             */
            $return_only_ids = apply_filters( 'aws_search_page_posts_objects_ids', false, $query, $this->data );

            if ( ! $return_only_ids ) {
                $products = AWS_Search::factory()->get_products( $products );
            }

            return array(
                'all'      => $post_array_products,
                'products' => $products,
            );

        }

        /*
         * Overwrite WooCommerce global products count if it is set to zero
         */
        public function woocommerce_product_loop_start( $loop_start ) {
            if ( isset( $_GET['type_aws'] ) && isset( $this->data['all_products'] ) && ! empty( $this->data['all_products'] ) ) {
                if ( isset( $GLOBALS['woocommerce_loop'] ) && isset( $GLOBALS['woocommerce_loop']['total'] ) && $GLOBALS['woocommerce_loop']['total'] === 0 ) {
                    $GLOBALS['woocommerce_loop']['total'] = count( $this->data['all_products'] );
                }
            }
            return $loop_start;
        }

        /*
         * Update links for WooCommerce filter widgets
         */
        public function woocommerce_layered_nav_link( $link ) {
            if ( ! isset( $_GET['type_aws'] ) ) {
                return $link;
            }

            $first_char = '&';

            if ( strpos( $link, '?' ) === false ) {
                $first_char = '?';
            }

            if ( isset( $_GET['type_aws'] ) && strpos( $link, 'type_aws' ) === false ) {
                $link = $link . $first_char . 'type_aws=true';
            }

            return $link;

        }

        /*
         * Change WooCommerce attributes filter widget query
         */
        public function woocommerce_get_filtered_term_product_counts_query( $query ) {
            if ( ! isset( $_GET['type_aws'] ) ) {
                return $query;
            }

            $search = ' AND ' . WC_Query::get_main_search_query_sql();

            $query['where'] = str_replace( $search, '', $query['where'] );

            if ( isset( $this->data['ids'] ) && $this->data['ids'] ) {
                global $wpdb;

                $new_select_query = "SELECT DISTINCT {$wpdb->posts}.ID as parent_post_id, COUNT( DISTINCT {$wpdb->posts}.ID ) + ( SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} WHERE parent_post_id = {$wpdb->posts}.post_parent AND {$wpdb->posts}.ID IN (".implode( ',', array_map( 'absint', $this->data['ids'] ) ).") ) as term_count";

                $query['select'] = str_replace( "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count", $new_select_query, $query['select'] );
                $query['where'] .= " AND {$wpdb->posts}.ID IN (". implode( ',', array_map( 'absint', $this->data['ids'] ) ) .")";

            }

            return $query;
        }

        /*
         * Change WooCommerce price filter widget query
         */
        public function woocommerce_price_filter_sql( $sql ) {

            if ( isset( $_GET['type_aws'] ) && isset( $this->data['ids'] ) && $this->data['ids'] ) {
                global $wpdb;

                $sql = "SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
				FROM {$wpdb->wc_product_meta_lookup}
				WHERE product_id IN (". implode( ',', array_map( 'absint', $this->data['ids'] ) ) .")";

            }

            return $sql;

        }

        /*
         * Check some strings inside body classes
         */
        function body_class( $classes ) {
            foreach( $classes as $class ) {
                if ( $class && strpos( $class, 'elementor-page-' ) !== false ) {
                    $this->data['is_elementor'] = true;
                    break;
                }
            }
            return $classes;
        }

        /*
         * Is it Divi builder search page template with Shop module?
         */
        public function et_pb_shop_before_print_shop() {
            $this->data['is_divi_s_page'] = true;
        }
        public function et_pb_shop_after_print_shop() {
            $this->data['is_divi_s_page'] = false;
        }

        /**
         * Get current page search query
         *
         * @param object|bool $query
         * @return string
         */
        private function get_search_query( $query = false ) {

            $search_query = isset( $_GET['s'] ) ? $_GET['s'] : ( ( is_object( $query ) && $query->query_vars['s'] ) ? $query->query_vars['s'] : '' );

            /**
             * Filter search query string for search results page
             * @since 2.22
             * @param string $search_query Search query string
             * @param object|bool $query Search query object
             */

            return apply_filters( 'aws_search_page_query', $search_query, $query );

        }

        /*
         * Set posts objects with data
         * @param $search_res Search results array
         * @param $query $query
         * @return array
         */
        private function set_posts_objects( $search_res, $query ) {

            $new_posts = array();

            if ( ! empty( $search_res['products'] ) && is_array( $search_res['products'][0] ) ) {

                foreach ( $search_res['products'] as $post_array ) {
                    $post = new stdClass();

                    $post_array = (array) $post_array;
                    $post_data = $post_array['post_data'];

                    $post->ID = ( isset( $post_array['parent_id'] ) && $post_array['parent_id'] ) ? $post_array['parent_id'] : $post_data->ID;
                    $post->site_id = get_current_blog_id();

                    if ( ! empty( $post_data->site_id ) ) {
                        $post->site_id = $post_data->site_id;
                    }

                    $post_return_args = array(
                        'post_type',
                        'post_author',
                        'post_name',
                        'post_status',
                        'post_title',
                        'post_parent',
                        'post_content',
                        'post_excerpt',
                        'post_date',
                        'post_date_gmt',
                        'post_modified',
                        'post_modified_gmt',
                        'post_mime_type',
                        'comment_count',
                        'comment_status',
                        'ping_status',
                        'menu_order',
                        'permalink',
                        'terms',
                        'post_meta'
                    );

                    foreach ( $post_return_args as $key ) {
                        if ( isset( $post_data->$key ) ) {
                            $post->$key = $post_data->$key;
                        }
                    }

                    $post->awssearch = true; // Super useful for debugging

                    if ( $post ) {
                        $new_posts[] = $post;
                    }
                }

            } else {

                // return only products IDs
                $new_posts = $search_res['products'];

            }

            /**
             * Filter search page results
             * @since 2.01
             * @param array $new_posts Posts array
             * @param object $query Query
             * @param array $this->data Search results data array
             */
            $new_posts = apply_filters( 'aws_search_page_results', $new_posts, $query, $this->data );

            return $new_posts;

        }

        /*
        * Total maximal number of search results for results pages
        */
        public function aws_page_results( $num ) {
            $search_page_res_num = AWS()->get_settings( 'search_page_res_num' );
            if ( $search_page_res_num ) {
                $num = intval( $search_page_res_num );
            }
            return $num;
        }

        /*
         * Number of search results per page
         */
        public function aws_posts_per_page( $num ) {
            $search_page_res_per_page = AWS()->get_settings( 'search_page_res_per_page' );
            if ( $search_page_res_per_page ) {
                $num = intval( $search_page_res_per_page );
            }
            return $num;
        }

        /*
         * Number of search results per page
         */
        public function woocommerce_product_query( $query ) {
            if ( AWS_Helpers::aws_searchpage_enabled( $query ) && $query->get( 'posts_per_page' ) ) {
                $query->set( 'posts_per_page', $this->aws_posts_per_page( $query->get( 'posts_per_page' ) ) );
            }
        }

        /*
         * Change default search page query
         */
        public function aws_search_page_custom_data( $data ) {
            $search_page_query = AWS()->get_settings( 'search_page_query' );
            if ( $search_page_query && $search_page_query === 'posts_pre_query' ) {
                $data['force_ids'] = true;
            }
            return $data;
        }

    }


endif;

AWS_Search_Page::factory();