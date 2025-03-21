<?php
/**
 *  Products Visibility by User Roles plugin integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_AFPVU' ) ) :

    /**
     * Class
     */
    class AWS_AFPVU {

        /**
         * Main AWS_AFPVU Instance
         *
         * Ensures only one instance of AWS_AFPVU is loaded or can be loaded.
         *
         * @static
         * @return AWS_AFPVU - Main instance
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_AFPVU Instance
         *
         * Ensures only one instance of AWS_AFPVU is loaded or can be loaded.
         *
         * @static
         * @return AWS_AFPVU - Main instance
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

            if ( isset( $filter['ids'] ) && ! empty( $filter['ids'] ) ) {

                $relation = isset( $filter['relation'] ) && $filter['relation'] === 'show' ? 'IN' : 'NOT IN';

                $query['search'] .= sprintf( ' AND ( id %s ( %s ) )', $relation, implode( ',', $filter['ids'] ) );

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

                $relation = isset( $filter['relation'] ) && $filter['relation'] === 'show' ? 'IN' : 'NOT IN';

                $sql_terms = "AND $wpdb->term_taxonomy.term_id {$relation} ( " . implode( ',', $filter['terms'] ) . " )";
                $sql = str_replace( 'WHERE 1 = 1', 'WHERE 1 = 1 ' . $sql_terms, $sql );

            }

            return $sql;
        }

        /*
         * Get filtered products
         */
        private function get_filtered_terms() {

            if ( isset( $this->data['products'] ) && is_array( $this->data['products'] ) ) {
                return $this->data['products'];
            }

            $products = array();

            $afpvu_enable_global = get_option('afpvu_enable_global');
            $curr_role           = is_user_logged_in() ? current( wp_get_current_user()->roles ) : 'guest';
            $role_selected_data  = (array) get_option('afpvu_user_role_visibility');

            if ( empty( $role_selected_data ) && 'yes' !== $afpvu_enable_global ) {
                return $products;
            }

            $role_data = isset( $role_selected_data[$curr_role]['afpvu_enable_role'] ) ? $role_selected_data[$curr_role]['afpvu_enable_role'] : 'no';

            if ( 'yes' === $afpvu_enable_global ) {

                $afpvu_show_hide          = get_option('afpvu_show_hide');
                $afpvu_applied_products   = (array) get_option('afpvu_applied_products');
                $afpvu_applied_categories = (array) get_option('afpvu_applied_categories');
            }

            if ( 'yes' === $role_data ) {

                $_data                    = $role_selected_data[$curr_role];
                $afpvu_show_hide          = isset( $_data['afpvu_show_hide_role'] ) ? $_data['afpvu_show_hide_role'] : 'hide' ;
                $afpvu_applied_products   = isset( $_data['afpvu_applied_products_role'] ) ? (array) $_data['afpvu_applied_products_role'] : array() ;
                $afpvu_applied_categories = isset( $_data['afpvu_applied_categories_role'] ) ? (array) $_data['afpvu_applied_categories_role'] : array();
            }

            if ( empty( $afpvu_applied_products ) && empty( $afpvu_applied_categories ) ) {
                return $products;
            }

            $products_ids = array();

            if ( !empty($afpvu_applied_categories) ) {

                $product_args = array(
                    'numberposts' => -1,
                    'post_status' => array('publish'),
                    'post_type'   => array('product'), //skip types
                    'fields'      => 'ids'
                );

                $product_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $afpvu_applied_categories,
                        'operator' => 'IN',
                    ));

                $products_ids = (array) get_posts($product_args);
            }

            $afpvu_applied_products = array_merge( (array) $afpvu_applied_products, (array) $products_ids );

            $products['ids'] = $afpvu_applied_products;
            $products['terms'] = $afpvu_applied_categories;

            if ( ! empty( $afpvu_show_hide ) && 'hide' == $afpvu_show_hide) {
                $products['relation'] = 'hide';
            } elseif ( ! empty( $afpvu_show_hide ) && 'show' == $afpvu_show_hide ) {
                $products['relation'] = 'show';
            }

            $this->data['products'] = $products;

            return $products;

        }

    }

endif;

AWS_AFPVU::instance();