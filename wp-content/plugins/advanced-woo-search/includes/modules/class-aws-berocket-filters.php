<?php

/**
 * AWS BeRocket WooCommerce AJAX Products Filter integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Berocket_Filters')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Berocket_Filters {

        /**
         * @var AWS_Berocket_Filters The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Berocket_Filters Instance
         *
         * Ensures only one instance of AWS_Berocket_Filters is loaded or can be loaded.
         *
         * @static
         * @return AWS_Berocket_Filters - Main instance
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

            add_filter( 'aws_search_page_filters', array( $this, 'berocket_search_page_filters' ) );

            add_filter( 'bapf_uparse_parse_filter_line_to_array_modify', array( $this, 'bapf_uparse_parse_filter_line_to_array_modify' ) );

        }

        /*
         * Fix filters
         */
        public function berocket_search_page_filters( $filters ) {

            $get_filters = isset( $_GET['filters'] ) ? explode( '|', $_GET['filters'] ) : ( isset( $this->data['filters'] ) ? $this->data['filters'] : array() );

            foreach( $get_filters as $get_filter ) {

                if ( $get_filter === '_stock_status[1]' || $get_filter === '_stock_status[instock]' ) {
                    $filters['in_status'] = true;
                } elseif ( $get_filter === '_stock_status[2]' || $get_filter === '_stock_status[outofstock]' ) {
                    $filters['in_status'] = false;
                } elseif ( $get_filter === '_sale[1]' || $get_filter === '_sale[sale]' ) {
                    $filters['on_sale'] = true;
                } elseif ( $get_filter === '_sale[2]' || $get_filter === '_sale[notsale]' ) {
                    $filters['on_sale'] = false;
                } elseif ( strpos( $get_filter, 'price[' ) === 0 ) {
                    if ( preg_match( '/([\w]+)\[(\d+)_(\d+)\]/', $get_filter, $matches ) ) {
                        $filters['price_min'] = intval( $matches[2] );
                        $filters['price_max'] = intval( $matches[3] );
                    }
                } elseif ( preg_match( '/(.+)\[(.+?)\]/', $get_filter, $matches ) ) {

                    $taxonomy = $matches[1];
                    $operator = strpos( $matches[2], '-' ) !== false ? 'OR' : 'AND';
                    $explode_char = strpos( $matches[2], '-' ) !== false ? '-' : '+';
                    $terms_arr = explode( $explode_char, $matches[2] );

                    if ( isset( $filters['tax'] ) && isset( $filters['tax'][$taxonomy] ) && isset( $filters['tax'][$taxonomy]['terms'] ) ) {

                        $terms_arr = $filters['tax'][$taxonomy]['terms'];

                    }

                    $filters['tax'][$taxonomy] = array(
                        'terms' => $terms_arr,
                        'operator' => $operator,
                        'include_parent' => true,
                    );

                }

            }

            return $filters;

        }

        /*
         * Get filters values
         */
        public function bapf_uparse_parse_filter_line_to_array_modify( $data ) {
            if ( isset( $data['filters'] ) ) {
                foreach ( $data['filters'] as $filter ) {
                    $this->data['filters'][] = $filter['attr'] . '[' . $filter['val'] . ']';
                }
            }
            return $data;
        }

    }

endif;

AWS_Berocket_Filters::instance();