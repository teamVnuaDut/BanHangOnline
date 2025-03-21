<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Search' ) ) :

    /**
     * Class for plugin search
     */
    class AWS_Search {

        /**
         * @var AWS_Search Array of all plugin data $data
         */
        private $data = array();

        /**
         * @var AWS_Search Current language $lang
         */
        private $lang = 0;

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
         * Constructor
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            $this->data['settings'] = get_option( 'aws_settings' );

            if ( isset( $_REQUEST['wc-ajax'] ) ) {
                add_action( 'wc_ajax_aws_action', array( $this, 'action_callback' ) );
            } else {
                add_action( 'wp_ajax_aws_action', array( $this, 'action_callback' ) );
                add_action( 'wp_ajax_nopriv_aws_action', array( $this, 'action_callback' ) );
            }

        }
        
        /*
         * AJAX call action callback
         */
        public function action_callback() {

            if ( ! defined( 'DOING_AJAX' ) ) {
                define( 'DOING_AJAX', true );
            }

            if ( ! headers_sent() && isset( $_REQUEST['typedata'] ) ) {
                header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
            }

            ob_start();

            $search_results = $this->search();

            ob_end_clean();

            echo json_encode( $search_results );

            die;

        }
        
        /*
         * Search
         */
        public function search( $keyword = '', $output = 'all' ) {

            global $wpdb;

            $this->lang = isset( $_REQUEST['lang'] ) ? sanitize_text_field( $_REQUEST['lang'] ) : '';

            if ( $this->lang ) {
                do_action( 'wpml_switch_language', $this->lang );
            }

            if ( $this->lang ) {
                $current_lang = $this->lang;
            } else {
                $current_lang = AWS_Helpers::get_lang();
            }

            /**
             * Filter current language code
             * @since 1.59
             * @param string $current_lang Lang code
             */
            $current_lang = apply_filters( 'aws_search_current_lang', $current_lang );

            $cache = AWS()->get_settings( 'cache' );

            $s = $keyword ? esc_attr( $keyword ) : ( isset( $_POST['keyword'] ) ? esc_attr( $_POST['keyword'] ) : '' );
            $s = htmlspecialchars_decode( $s );

            $this->data['s_nonormalize'] = $s;

            $s = AWS_Helpers::normalize_string( $s );


            /**
             * Fires each time when performing the search
             * @since 1.59
             * @param string $s Search query
             */
            do_action( 'aws_search_start', $s );


            $cache_option_name = '';
            
            if ( $cache === 'true' && ! $keyword && $output === 'all'  ) {
                $cache_option_name = AWS()->cache->get_cache_name( $s );
                $res = AWS()->cache->get_from_cache_table( $cache_option_name );
                if ( $res ) {
                    $cached_value = json_decode( $res );
                    if ( $cached_value && ! empty( $cached_value ) ) {
                        return $cached_value;
                    }
                }
            }

            $search_archives   = AWS()->get_settings( 'search_archives' );
            $show_cats         = ( isset( $search_archives['archive_category'] ) && $search_archives['archive_category'] ) ? 'true' : 'false';
            $show_tags         = ( isset( $search_archives['archive_tag'] ) && $search_archives['archive_tag'] ) ? 'true' : 'false';
            $results_num       = $keyword ? apply_filters( 'aws_page_results', 100 ) : AWS()->get_settings( 'results_num' );
            $pages_results_num = AWS()->get_settings( 'pages_results_num' );
            $search_in         = AWS()->get_settings( 'search_in' );
            $outofstock        = AWS()->get_settings( 'outofstock' );
            $search_rule       = AWS()->get_settings( 'search_rule' );
            $search_words_num  = AWS()->get_settings( 'search_words_num' );
            $fuzzy             = AWS()->get_settings( 'fuzzy' );

            $search_in_arr = array();

            if ( is_array( $search_in ) && ! empty( $search_in ) ) {
                foreach( $search_in as $search_in_source => $search_in_active ) {
                    if ( $search_in_active ) {
                        $search_in_arr[] = $search_in_source;
                    }
                }
            } elseif ( is_string( $search_in ) && $search_in ) {
                $search_in_arr = explode( ',',  $search_in );
            }

            $products_array = array();
            $tax_to_display = array();
            $custom_tax_array = array();

            $this->data['s'] = $s;
            $this->data['results_num']  = $results_num;
            $this->data['pages_results_num']  = $pages_results_num;
            $this->data['search_terms'] = array();
            $this->data['search_in']    = $search_in_arr;
            $this->data['outofstock']   = $outofstock;
            $this->data['search_rule']   = $search_rule;
            $this->data['search_words_num'] = $search_words_num;
            $this->data['fuzzy'] = $fuzzy;
            $this->data['is_search_page'] = !! $keyword;
            $this->data['current_lang'] = $current_lang;

            $search_array = array_unique( explode( ' ', $s ) );

            $search_array = AWS_Helpers::filter_stopwords( $search_array );

            if ( is_array( $search_array ) && ! empty( $search_array ) ) {
                foreach ( $search_array as $search_term ) {
                    $search_term = trim( $search_term );
                    if ( $search_term ) {
                        $this->data['search_terms'][] = $search_term;
                    }
                }
            }

            if ( $this->data['search_words_num'] && count( $this->data['search_terms'] ) > intval( $this->data['search_words_num'] ) ) {
                $this->data['search_terms'] = array_slice( $this->data['search_terms'], 0, intval( $this->data['search_words_num'] ) );
            }

//            if ( empty( $this->data['search_terms'] ) ) {
//                $this->data['search_terms'][] = '';
//            }

            /**
             * Filter search data parameters
             * @since 2.50
             * @param array $this->data Array of data parameters
             */
            $this->data = apply_filters( 'aws_search_data_parameters', $this->data );

            $posts_ids = array();

            if ( ! empty( $this->data['search_terms'] ) ) {

                if ( ! empty( $this->data['search_in'] ) && $this->data['results_num'] ) {

                    $posts_ids = $this->query_index_table();

                    // try to fix misspellings
                    if ( empty( $posts_ids ) && ( $fuzzy === 'true' || $fuzzy === 'true_text' ) ) {

                        $similar_terms_obj = new AWS_Similar_Terms( $this->data );
                        $similar_terms_res = $similar_terms_obj->get_similar_terms();

                        if ( ! empty( $similar_terms_res ) && ! empty( $similar_terms_res['all'] ) ) {

                            $this->data['similar_terms'] = $similar_terms_res;

                            $similar_terms = $similar_terms_res['all'];

                            $this->data['search_terms'] = $similar_terms;
                            $posts_ids = $this->query_index_table();

                        }

                    }

                }

                if ( $output === 'all' ) {

                    if ( $show_cats === 'true' ) {
                        $tax_to_display[] = 'product_cat';
                    }

                    if ( $show_tags === 'true' ) {
                        $tax_to_display[] = 'product_tag';
                    }

                    /**
                     * Filters array of custom taxonomies that must be displayed in search results
                     *
                     * @since 1.68
                     *
                     * @param array $taxonomies_archives Array of custom taxonomies
                     * @param string $s Search query
                     */
                    $taxonomies_archives = apply_filters( 'aws_search_results_tax_archives', $tax_to_display, $s );

                    if ( $taxonomies_archives && is_array( $taxonomies_archives ) && ! empty( $taxonomies_archives ) ) {

                        $tax_search = new AWS_Tax_Search( $taxonomies_archives, $this->data );
                        $custom_tax_array = $tax_search->get_results();

                    }

                }

            }

            /**
             * Filters array of products ids
             * @since 1.53
             * @param array $posts_ids Array of products ids
             * @param string $s Search query
             * @param array $this->data Array of search data ( since 3.09 )
             */
            $posts_ids = apply_filters( 'aws_search_results_products_ids', $posts_ids, $s, $this->data );

            if ( empty( $posts_ids ) && empty( $custom_tax_array ) ) {

                /**
                 * If no search results - apply filter to add custom ones
                 * @since 3.09
                 * @param array $posts_ids Array of products ids
                 * @param string $s Search query
                 * @param array $this->data Array of search data
                 */
                $posts_ids = apply_filters( 'aws_search_no_results', $posts_ids, $s, $this->data );

            }

            // Return array of its to short-circuit search return
            if ( $output === 'ids' ) {
                return $posts_ids;
            }

            $products_array = $this->get_products( $posts_ids );

            $result_array = array(
                'tax'      => $custom_tax_array,
                'products' => $products_array,
            );

            /**
             * Filters array of all results data before they displayed in search results
             *
             * @since 1.43
             *
             * @param array $brands_array Array of products data
             * @param string $s Search query
             */
            $result_array = apply_filters( 'aws_search_results_all', $result_array, $s );

            $result_array['data'] = AWS_Helpers::get_custom_results_data( array( 'products' => $products_array, 'tax' => $custom_tax_array ), $this->data );

            if ( $cache === 'true' && ! $keyword && $output === 'all' ) {
                AWS()->cache->insert_into_cache_table( $cache_option_name, $result_array );
            }

            return $result_array;

        }

        /*
         * Query in index table
         */
        private function query_index_table() {

            global $wpdb;

            $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            $search_in_arr    = $this->data['search_in'];
            $results_num      = $this->data['results_num'];
            $outofstock       = $this->data['outofstock'];
            $search_rule      = $this->data['search_rule'];
            $current_lang     = $this->data['current_lang'];

            $reindex_version = AWS()->option_vars->get_reindex_version();
            $index_table_version = AWS()->option_vars->get_index_table_version();

            $query = array();

            $query['select'] = '';
            $query['search'] = '';
            $query['relevance'] = '';
            $query['stock'] = '';
            $query['visibility'] = '';
            $query['exclude_products'] = '';
            $query['lang'] = '';

            $search_array = array();
            $relevance_array = array();
            $new_relevance_array = array();


            /**
             * Filters array of search terms before generating SQL query
             *
             * @since 1.49
             *
             * @param array $this->data['search_terms'] Array of search terms
             */
            $this->data['search_terms'] = apply_filters( 'aws_search_terms', $this->data['search_terms'] );


            /**
             * Multiplier for relevance score depending on number of terms repeats
             * @since 3.06
             * @param array $this->data Search parameters
             */
            $count_multiplier = apply_filters( 'aws_relevance_count_multiplier', '1 + (count-1)/5', $this->data );


            $relevance_scores = AWS_Helpers::get_relevance_scores( $this->data );

            foreach ( $this->data['search_terms'] as $search_term ) {

                $search_term_len = strlen( $search_term );
                $is_normal_term = $search_term_len > 1;

                $relevance_params = array(
                    'title' => array(
                        'full' => $relevance_scores['title'] + 20 * $search_term_len,
                        'like' => $relevance_scores['title'] / 5 + 2 * $search_term_len,
                    ),
                    'content' => array(
                        'full' => $relevance_scores['content'] + 4 * $search_term_len,
                        'like' => $relevance_scores['content'] + 1 * $search_term_len,
                    ),
                    'excerpt' => array(
                        'full' => $relevance_scores['content'] + 4 * $search_term_len,
                        'like' => $relevance_scores['content'] + 1 * $search_term_len,
                    ),
                    'category' => array(
                        'full' => $relevance_scores['other'],
                        'like' => $relevance_scores['other'] / 5,
                    ),
                    'tag' => array(
                        'full' => $relevance_scores['other'],
                        'like' => $relevance_scores['other'] / 5,
                    ),
                    'sku' => array(
                        'full' => $relevance_scores['sku'],
                        'like' => $relevance_scores['sku'] / 5,
                    ),
                    'id' => array(
                        'full' => $relevance_scores['id'],
                        'like' => $relevance_scores['id'] / 10,
                    ),
                );

                /**
                 * Array of relevance parameters
                 * @since 2.88
                 * @param array $relevance_params Array of relevance parameters
                 * @param array $relevance_scores Array of relevance scores
                 * @param string $search_term Search term
                 * @param array $data Array of search query related data
                 */
                $relevance_params = apply_filters( 'aws_relevance_parameters', $relevance_params, $relevance_scores, $search_term, $this->data );

                $search_term_norm = AWS_Helpers::singularize( $search_term );

                if ( $search_term_norm && $search_term_len > 3 && strlen( $search_term_norm ) > 2 ) {
                    $search_term = $search_term_norm;
                }

                if ( $search_rule === 'begins' ) {
                    $like = $wpdb->esc_like( $search_term ) . '%';
                } else {
                    $like = '%' . $wpdb->esc_like( $search_term ) . '%';
                }

                if ( $is_normal_term ) {
                    $search_array[] = $wpdb->prepare( '( term LIKE %s )', $like );
                } else {
                    $search_array[] = $wpdb->prepare( '( term = "%s" )', $search_term );
                }

                foreach ( $search_in_arr as $search_in_term ) {

                    if ( isset( $relevance_params[$search_in_term] ) ) {

                        $relevance = $relevance_params[$search_in_term]['full'];
                        $relevance_like = $relevance_params[$search_in_term]['like'];

                        $relevance_array[$search_in_term][] = $wpdb->prepare( "( case when ( term_source = '%s' AND term = '%s' ) then {$relevance} * ( {$count_multiplier} ) else 0 end )", $search_in_term, $search_term );

                        if ( $is_normal_term ) {
                            $relevance_array[$search_in_term][] = $wpdb->prepare( "( case when ( term_source = '%s' AND term LIKE %s ) then {$relevance_like} * ( {$count_multiplier} ) else 0 end )", $search_in_term, $like );
                        }

                    }

                }

            }

            // Sort 'relevance' queries in the array by search priority
            foreach ( $search_in_arr as $search_in_item ) {
                if ( isset( $relevance_array[$search_in_item] ) ) {
                    $new_relevance_array[$search_in_item] = implode( ' + ', $relevance_array[$search_in_item] );
                }
            }

            $query['select'] = ' distinct ID';
            $query['relevance'] = sprintf( ' (SUM( %s )) ', implode( ' + ', $new_relevance_array ) );
            $query['search'] = sprintf( ' AND ( %s )', implode( ' OR ', $search_array ) );


            if ( $reindex_version && version_compare( $reindex_version, '1.16', '>=' ) ) {

                if ( $outofstock !== 'true' ) {
                    $query['stock'] = " AND in_stock = 1";
                }

                $query['visibility'] = " AND visibility NOT IN ( 'hidden', 'catalog' )";

            }

            if ( $index_table_version && version_compare( $index_table_version, '3.21', '>=' ) ) {
                $query['visibility'] = " AND visibility NOT IN ( 0, 3 )";
            }


            /**
             * Exclude certain products from search
             *
             * @since 1.58
             *
             * @param array
             */
            $exclude_products_filter = apply_filters( 'aws_exclude_products', array() );

            if ( $exclude_products_filter && is_array( $exclude_products_filter ) && ! empty( $exclude_products_filter ) ) {
                $query['exclude_products'] = sprintf( ' AND ( id NOT IN ( %s ) )', implode( ',', $exclude_products_filter ) );
            }

            if ( $current_lang && $reindex_version && version_compare( $reindex_version, '1.20', '>=' ) ) {
                $query['lang'] = $wpdb->prepare( " AND ( lang LIKE %s OR lang = '' )", '%' . $wpdb->esc_like( $current_lang ) . '%' );
            }

            /**
             * Filter search query parameters
             * @since 1.67
             * @param array $query Query parameters
             */
            $query = apply_filters( 'aws_search_query_array', $query );

            $sql = "SELECT
                    {$query['select']},
                    {$query['relevance']} as relevance
                FROM
                    {$table_name}
                WHERE
                    1=1
                {$query['search']}
                {$query['stock']}
                {$query['visibility']}
                {$query['exclude_products']}
                {$query['lang']}
                GROUP BY ID
                    having relevance > 0
                ORDER BY
                    relevance DESC, id DESC
				LIMIT 0, {$results_num}
		    ";

            /**
             * Filter search query string
             * @since 2.06
             * @param array $query Query string
             */
            $sql = apply_filters( 'aws_search_query_string', $sql );

            $this->data['query_params'] = $query;

            $this->data['sql'] = $sql;

            $posts_ids = $this->get_posts_ids( $sql );

            return $posts_ids;

        }

        /*
         * Get array of included to search result posts ids
         */
        private function get_posts_ids( $sql ) {

            global $wpdb;

            $posts_ids = array();

            $search_results = $wpdb->get_results( $sql );


            if ( !empty( $search_results ) && !is_wp_error( $search_results ) && is_array( $search_results ) ) {
                foreach ( $search_results as $search_result ) {
                    $post_id = intval( $search_result->ID );
                    if ( ! in_array( $post_id, $posts_ids ) ) {
                        $posts_ids[] = $post_id;
                    }
                }
            }

            unset( $search_results );

            return $posts_ids;

        }

        /*
         * Get products info
         */
        public function get_products( $posts_ids ) {

            $products_array = array();

            if ( count( $posts_ids ) > 0 ) {

                $show_excerpt         = AWS()->get_settings( 'show_excerpt' );
                $excerpt_source       = AWS()->get_settings( 'desc_source' );
                $excerpt_length       = AWS()->get_settings( 'excerpt_length' );
                $desc_scrap_words     = AWS()->get_settings( 'mark_words' );
                $highlight_words      = AWS()->get_settings( 'highlight' );
                $show_price           = AWS()->get_settings( 'show_price' );
                $show_outofstockprice = AWS()->get_settings( 'show_outofstock_price' );
                $show_sale            = AWS()->get_settings( 'show_sale' );
                $show_image           = AWS()->get_settings( 'show_image' );
                $show_sku             = AWS()->get_settings( 'show_sku' );
                $show_stock_status    = AWS()->get_settings( 'show_stock' );
                $show_featured        = AWS()->get_settings( 'show_featured' );

                $posts_items = $posts_ids;

                foreach ( $posts_items as $post_item ) {

                    if ( ! is_object( $post_item ) ) {
                        $product = wc_get_product( $post_item );
                    } else {
                        $product = $post_item;
                    }

                    if ( ! is_a( $product, 'WC_Product' ) ) {
                        continue;
                    }

                    setup_postdata( $post_item );

                    $post_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $post_item;
                    $parent_id = $product->is_type( 'variation' ) && method_exists( $product, 'get_parent_id' ) ? $product->get_parent_id() : $post_id;

                    /**
                     * Filter additional product data
                     * @since 1.60
                     * @param array $this->data Additional data
                     * @param int $post_id Product id
                     * @param object $product Product
                     */
                    $this->data = apply_filters( 'aws_search_data_params', $this->data, $post_id, $product );

                    $post_data = get_post( $post_id );

                    $title = $product->get_title();
                    $title = AWS_Helpers::html2txt( $title );

                    $excerpt      = '';
                    $price        = '';
                    $on_sale      = '';
                    $image        = '';
                    $sku          = '';
                    $stock_status = '';
                    $featured     = '';


                    if ( $show_excerpt === 'true' ) {

                        $excerpt = ( $excerpt_source === 'excerpt' && $post_data->post_excerpt ) ? $post_data->post_excerpt : $post_data->post_content;
                        $excerpt = AWS_Helpers::html2txt( $excerpt );
                        $excerpt = str_replace('"', "'", $excerpt);
                        $excerpt = strip_shortcodes( $excerpt );
                        $excerpt = AWS_Helpers::strip_shortcodes( $excerpt );

                        if ( $desc_scrap_words === 'true'  ) {

                            $marked_content = $this->scrap_content( $excerpt );

                            if ( $marked_content ) {
                                $excerpt = $marked_content;
                            }

                        }

                        $excerpt = $excerpt_length ? wp_trim_words( $excerpt, $excerpt_length, '...' ) : '';

                    }

                    if ( $show_price === 'true' && ( $product->is_in_stock() || ( ! $product->is_in_stock() && $show_outofstockprice === 'true' ) ) ) {
                        $price = $product->get_price_html();
                        $price = preg_replace("/<a\s(.+?)>(.+?)<\/a>/is", "<span>$2</span>", $price);
                    }

                    if ( $show_sale === 'true' && ( $product->is_in_stock() || ( ! $product->is_in_stock() && $show_outofstockprice === 'true' ) ) ) {
                        $on_sale = $product->is_on_sale();
                    }

                    if ( $show_image === 'true' ) {

                        $image_id = $product->get_image_id();
                        $image_size = 'thumbnail';

                        /**
                         * Filter products images size
                         * @since 2.06
                         * @param string $image_size Image size
                         */
                        $image_size = apply_filters( 'aws_image_size', $image_size );

                        if ( $image_id ) {
                            $image_attributes = wp_get_attachment_image_src( $image_id, $image_size );
                            $image = $image_attributes ? $image_attributes[0] : '';
                        }

                    }

                    if ( $show_sku === 'true' ) {
                        $sku = $product->get_sku();
                    }

                    if ( $show_featured === 'true' ) {
                        $featured = $product->is_featured();
                    }

                    if ( method_exists( $product, 'get_stock_status' ) ) {
                        $product_stock_status = $product->get_stock_status();
                    } else {
                        $product_stock_status = false;
                    }

                    if ( $show_stock_status === 'true' ) {
                        if ( $product->is_in_stock() && $product_stock_status !== 'onbackorder' ) {
                            $stock_status = array(
                                'status' => true,
                                'text'   => esc_html__( 'In stock', 'woocommerce' )
                            );
                        } else {
                            $stock_status = array(
                                'status' => false,
                                'text'   => $product_stock_status === 'onbackorder' ? esc_html__( 'On backorder', 'woocommerce' ) : esc_html__( 'Out of stock', 'woocommerce' )
                            );
                        }
                    }

                    if ( method_exists( $product, 'get_price' ) ) {
                        $f_price = $product->get_price();
                    }

                    if ( method_exists( $product, 'get_average_rating' ) ) {
                        $f_rating  = $product->get_average_rating();
                    }

                    if ( method_exists( $product,'get_review_count' ) ) {
                        $f_reviews = $product->get_review_count();
                    }

                    $f_stock = $product->is_in_stock();
                    $f_sale  = $product->is_on_sale();

//                    $categories = $product->get_categories( ',' );
//                    $tags = $product->get_tags( ',' );

                    if ( $highlight_words === 'true'  ) {
                        $title   = $this->highlight_words( $title );
                        $excerpt = $this->highlight_words( $excerpt );
                        $sku     = $this->highlight_words( $sku );
                    }

                    $title   = apply_filters( 'aws_title_search_result', $title, $post_id, $product );
                    $excerpt = apply_filters( 'aws_excerpt_search_result', $excerpt, $post_id, $product );

                    if ( ! isset( $this->data['is_search_page'] ) || ! $this->data['is_search_page'] ) {
                        $post_data->post_content = '';
                        $post_data->post_excerpt = '';
                    }

                    $new_result = array(
                        'id'           => $post_id,
                        'parent_id'    => $parent_id,
                        'title'        => $title,
                        'excerpt'      => $excerpt,
                        'link'         => get_permalink( $post_id ),
                        'image'        => $image,
                        'price'        => $price,
                        'on_sale'      => $on_sale,
                        'sku'          => $sku,
                        'stock_status' => $stock_status,
                        'featured'     => $featured,
                        'f_price'      => $f_price,
                        'f_rating'     => $f_rating,
                        'f_reviews'    => $f_reviews,
                        'f_stock'      => $f_stock,
                        'f_sale'       => $f_sale,
                        'post_data'    => $post_data
                    );

                    /**
                     * Filter single product search result
                     * @since 2.49
                     * @param array $new_result Product data array
                     * @param int $post_id Product id
                     * @param object $product Product
                     */
                    $new_result = apply_filters( 'aws_search_pre_filter_single_product', $new_result, $post_id, $product );

                    $products_array[] = $new_result;

                    wp_reset_postdata();

                }

            }

            /**
             * Filter products array before output
             * @since 1.60
             * @param array $products_array Products array
             * @param array $this->data Additional data
             */
            $products_array = apply_filters( 'aws_search_pre_filter_products', $products_array, $this->data );

            $s = isset( $this->data['s'] ) ? $this->data['s'] : '';

            /**
             * Filters array of products before they displayed in search results
             *
             * @since 1.42
             *
             * @param array $products_array Array of products results
             * @param string $s Search query
             */
            $products_array = apply_filters( 'aws_search_results_products', $products_array, $s );

            return $products_array;

        }

        /*
         * Scrap content excerpt
         */
        private function scrap_content( $content ) {

            $exact_words = array();
            $words = array();
            $excerpt_length = AWS()->get_settings( 'excerpt_length' );

            $search_terms = array();

            if ( ! empty( $this->data['search_terms'] ) ) {
                $search_terms = array_fill_keys( $this->data['search_terms'], 1);
                $search_terms = AWS_Helpers::get_synonyms( $search_terms );
                $search_terms = array_keys( $search_terms );
            }

            foreach( $search_terms as $search_in ) {

                $search_in = preg_quote( $search_in, '/' );
                $exact_words[] = '\b' . $search_in . '\b';

                if ( strlen( $search_in ) > 1 ) {
                    $words[] = $search_in;
                } else {
                    $words[] = '\b' . $search_in . '\b';
                }

            }

            usort( $exact_words, array( $this, 'sort_by_length' ) );
            $exact_words = implode( '|', $exact_words );

            usort( $words, array( $this, 'sort_by_length' ) );
            $words = implode( '|', $words );

            preg_match( '/([^.?!]*?)(' . $exact_words . '){1}(.*?[.!?])/i', $content, $matches );

            if ( ! isset( $matches[0] ) ) {
                preg_match( '/([^.?!]*?)(' . $words . '){1}(.*?[.!?])/i', $content, $matches );
            }

            if ( isset( $matches[0] ) ) {

                $content = $matches[0];

                // Trim to long content
                if (str_word_count(strip_tags($content)) > 34) {

                    if (str_word_count(strip_tags($matches[3])) > 34) {
                        $matches[3] = wp_trim_words($matches[3], 30, '...');
                    }

                    $content = '...' . $matches[2] . $matches[3];

                }

            } else {

                // Get first N sentences
                if ( str_word_count( strip_tags( $content ) ) > $excerpt_length ) {

                    $sentences_array = preg_split( "/(?<=[.!?])/", $content, 10, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
                    $sentences_string = '';
                    $str_word_count = 0;

                    if ( ! empty( $sentences_array ) ) {
                        foreach ( $sentences_array as $sentence ) {
                            $str_word_count = $str_word_count + str_word_count( strip_tags( $sentence ) );
                            if ( $str_word_count <= $excerpt_length ) {
                                $sentences_string .= $sentence;
                            } else {
                                break;
                            }
                        }
                    }

                    if ( $sentences_string ) {
                        $content = $sentences_string;
                    }

                }

            }

            return $content;

        }

        /*
         * Highlight search words
         */
        private function highlight_words( $text ) {

            if ( ! $text ) {
                 return $text;
            }

            $pattern = array();

            $search_terms = array();

            if ( ! empty( $this->data['search_terms'] ) ) {
                $search_terms = array_fill_keys( $this->data['search_terms'], 1);
                $search_terms = AWS_Helpers::get_synonyms( $search_terms );
                $search_terms = array_keys( $search_terms );
            }

            foreach( $search_terms as $search_in ) {

                $search_in = preg_quote( $search_in, '/' );

                if ( strlen( $search_in ) > 1 ) {
                    $pattern[] = '(' . $search_in . ')+';
                } else {
                    $pattern[] = '\b[' . $search_in . ']{1}\b';
                }

            }

            usort( $pattern, array( $this, 'sort_by_length' ) );
            $pattern = implode( '|', $pattern );
            $pattern = sprintf( '/%s/i', $pattern );

            /**
             * Tag to use for highlighting search words inside content
             * @since 1.88
             * @param string Tag for highlighting
             */
            $highlight_tag = apply_filters( 'aws_highlight_tag', 'strong' );

            $highlight_tag_pattern = '<' . $highlight_tag . '>${0}</' . $highlight_tag . '>';

            $text = preg_replace($pattern, $highlight_tag_pattern, $text );

            return $text;

        }

        /*
         * Sort array by its values length
         */
        private function sort_by_length( $a, $b ) {
            return strlen( $b ) - strlen( $a );
        }

    }


endif;

AWS_Search::factory();

function aws_search( $keyword = '', $output = 'all' ) {

    ob_start();

    $search_results = AWS_Search::factory()->search( $keyword, $output );

    ob_end_clean();

    return $search_results;

}