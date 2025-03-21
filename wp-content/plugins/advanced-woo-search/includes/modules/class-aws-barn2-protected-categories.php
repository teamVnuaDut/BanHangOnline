<?php
/**
 *  WooCommerce Protected Categories
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


if ( ! class_exists( 'AWS_Barn2PC' ) ) :

    /**
     * Class
     */
    class AWS_Barn2PC {

        /**
         * Main AWS_Barn2PC Instance
         *
         * Ensures only one instance of AWS_Barn2PC is loaded or can be loaded.
         *
         * @static
         * @return AWS_Barn2PC - Main instance
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Barn2PC Instance
         *
         * Ensures only one instance of AWS_Barn2PC is loaded or can be loaded.
         *
         * @static
         * @return AWS_Barn2PC - Main instance
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

            // Show/hide products
            add_filter( 'aws_search_query_array', array( $this, 'search_query_array' ), 1 );

            // Show/hide categories
            add_filter( 'aws_terms_search_query', array( $this, 'terms_search_query' ), 1, 2 );

        }


        /*
         * Filter products search results
         */
        public function search_query_array( $query ) {

            $filter = $this->get_filtered_terms();

            if ( isset( $filter['terms'] ) && ! empty( $filter['terms'] ) ) {

                $query['search'] .= sprintf( ' AND ( term_id NOT IN ( %s ) )', implode( ',', $filter['terms'] ) );

            }

            return $query;

        }

        /*
         * Filter categories search results
         */
        public function terms_search_query( $sql, $taxonomy ) {

            global $wpdb;

            $filter = $this->get_filtered_terms();

            if ( isset( $filter['terms'] ) && ! empty( $filter['terms'] ) ) {
                $sql_terms = "AND $wpdb->term_taxonomy.term_id NOT IN ( " . implode( ',', $filter['terms'] ) . " )";
                $sql = str_replace( 'WHERE 1 = 1', 'WHERE 1 = 1 ' . $sql_terms, $sql );
            }

            return $sql;

        }

        /*
         * Get filtered products/terms
         */
        private function get_filtered_terms() {

            if ( isset( $this->data['filtered'] ) && is_array( $this->data['filtered'] ) ) {
                return $this->data['filtered'];
            }

            $filtered = array();
            $hidden_categories = array();

            if ( class_exists( '\Barn2\Plugin\WC_Protected_Categories\Util' ) ) {
                $show_protected = \Barn2\Plugin\WC_Protected_Categories\Util::showing_protected_categories();
                foreach ( \Barn2\Plugin\WC_Protected_Categories\Util::to_category_visibilities( \Barn2\Plugin\WC_Protected_Categories\Util::get_product_categories() ) as $category ) {
                    if ( $category->is_private() || ( ! $show_protected && $category->is_protected() ) ) {
                        $hidden_categories[] = $category->get_term_id();
                    }
                }
            } elseif ( class_exists('WC_PPC_Util') ) {
                $show_protected = WC_PPC_Util::showing_protected_categories();
                foreach (WC_PPC_Util::to_category_visibilities(WC_PPC_Util::get_product_categories()) as $category) {
                    if ($category->is_private() || (!$show_protected && $category->is_protected())) {
                        $hidden_categories[] = $category->term_id;
                    }
                }
            }

            $filtered['terms'] = $hidden_categories;

            $this->data['filtered'] = $filtered;

            return $filtered;

        }

    }

endif;

AWS_Barn2PC::instance();