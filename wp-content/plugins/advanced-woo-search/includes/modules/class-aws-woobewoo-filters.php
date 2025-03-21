<?php

/**
 * AWS plugin WooCommerce Product Filter by WooBeWoo integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Woobewoo_Filters')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Woobewoo_Filters {

        /**
         * @var AWS_Woobewoo_Filters The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Woobewoo_Filters Instance
         *
         * Ensures only one instance of AWS_Woobewoo_Filters is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woobewoo_Filters - Main instance
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

            add_filter( 'wpf_addHtmlBeforeFilter', array( $this, 'wpf_add_html_before_filter' ) );

            add_filter( 'aws_search_page_custom_data', array( $this, 'wpf_search_page_custom_data' ), 1 );

            add_filter( 'aws_search_page_filters', array( $this, 'wpf_search_page_filters' ) );

            add_filter( 'wpf_addFilterExistsItemsArgs', array( $this, 'addFilterExistsItemsArgs' ), 9999999 );

        }

        /*
         * WooCommerce Product Filter by WooBeWoo: check for active widget
         */
        public function wpf_add_html_before_filter( $html ) {
            $this->data['wpf_filter'] = true;
            if ( isset( $_GET['type_aws'] ) ) {
                $html = str_replace( '&quot;enable_ajax&quot;:&quot;1&quot;', '&quot;enable_ajax&quot;:&quot;0&quot;', $html );
                $html = str_replace( '"enable_ajax":"1"', '"enable_ajax":"0"', $html );
            }
            return $html;
        }

        /*
         * WooCommerce Product Filter by WooBeWoo: fix filters display
         */
        public function wpf_search_page_custom_data( $data ) {
            $this->data['ids'] = isset( $data['ids'] ) ? $data['ids'] : array();
            if ( isset( $this->data['wpf_filter'] ) ) {
                $data['force_ids'] = true;
            }
            return $data;
        }

        /*
         * WooCommerce Product Filter by WooBeWoo: filter products
         */
        public function wpf_search_page_filters( $filters ) {
            
            foreach ( $_GET as $key => $param ) {

                $isNot = ( substr($param, 0, 1) === '!' );

                if ( strpos($key, 'filter_cat') !== false ) {

                    $idsAnd = explode(',', $param);
                    $idsOr = explode('|', $param);
                    $isAnd = count($idsAnd) > count($idsOr);
                    $operator = $isAnd ? 'AND' : 'OR';
                    $filters['tax']['product_cat'] = array(
                        'terms' => $isAnd ? $idsAnd : $idsOr,
                        'operator' => $operator
                    );

                }
                elseif ( strpos($key, 'product_tag') !== false ) {

                    $idsAnd = explode(',', $param);
                    $idsOr = explode('|', $param);
                    $isAnd = count($idsAnd) > count($idsOr);
                    $operator = $isAnd ? 'AND' : 'OR';
                    $filters['tax']['product_tag'] = array(
                        'terms' => $isAnd ? $idsAnd : $idsOr,
                        'operator' => $operator
                    );

                }
                elseif ( strpos( $key, 'pr_onsale' ) !== false ) {
                    $filters['on_sale'] = true;
                }
                elseif ( strpos( $key, 'pr_stock' ) !== false ) {
                    $filters['in_status'] = $param;
                }
                elseif ( strpos( $key, 'pr_rating' ) !== false ) {
                    switch ( $param ) {
                        case '1-5':
                            $rating = array( 1, 2, 3, 4, 5 );
                            break;
                        case '2-5':
                            $rating = array( 2, 3, 4, 5 );
                            break;
                        case '3-5':
                            $rating = array( 3, 4, 5 );
                            break;
                        case '4-5':
                            $rating = array( 4, 5 );
                            break;
                        default:
                            $rating = array( 5 );
                    }
                    $filters['rating'] = $rating;
                }
                elseif ( strpos( $key, 'filter_' ) === 0 || strpos( $key, 'wpf_filter_' ) === 0 ) {

                    if ( strpos( $key, 'filter_pwb_' ) === 0 || strpos( $key, 'wpf_filter_pwb_' ) === 0 ) {
                        $taxonomy = 'pwb-brand';
                    } else {
                        $taxonomy = str_replace( 'wpf_filter_', '', $key );
                        $taxonomy = str_replace( 'filter_', '', $taxonomy );
                    }

                    if ( preg_match( '/([a-z]+?)_[\d]/', $taxonomy, $matches )  ) {
                        $taxonomy = $matches[1];
                    }

                    $idsAnd = explode(',', $param);
                    $idsOr = explode('|', $param);
                    $isAnd = count($idsAnd) > count($idsOr);
                    $operator = $isAnd ? 'AND' : 'OR';

                    $terms_arr = $isAnd ? $idsAnd : $idsOr;

                    if ( isset( $filters['tax'] ) && isset( $filters['tax'][$taxonomy] ) && isset( $filters['tax'][$taxonomy]['terms'] ) ) {

                        $terms_arr = $filters['tax'][$taxonomy]['terms'];

                    }

                    $filters['tax'][$taxonomy] = array(
                        'terms' => $terms_arr,
                        'operator' => $operator
                    );

                }

            }

            return $filters;

        }

        /*
         * Add posts Ids into filters arguments for WP_Query
         */
        public function addFilterExistsItemsArgs( $args ) {

            if ( isset( $this->data['wpf_filter'] ) && isset( $this->data['ids'] ) ) {

                $args['post__in'] = array_keys( $this->data['ids'] );

                if ( isset( $args['s'] ) ) {
                    unset( $args['s'] );
                }

            }

            return $args;

        }

    }

endif;

AWS_Woobewoo_Filters::instance();