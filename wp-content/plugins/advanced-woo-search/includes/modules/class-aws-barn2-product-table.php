<?php
/**
 *  WooCommerce Product Table plugin by Barn2
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


if ( ! class_exists( 'AWS_Barn2PT' ) ) :

    /**
     * Class
     */
    class AWS_Barn2PT {

        /**
         * Main AWS_Barn2PT Instance
         *
         * Ensures only one instance of AWS_Barn2PT is loaded or can be loaded.
         *
         * @static
         * @return AWS_Barn2PT - Main instance
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Barn2PT Instance
         *
         * Ensures only one instance of AWS_Barn2PT is loaded or can be loaded.
         *
         * @static
         * @return AWS_Barn2PT - Main instance
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

            add_filter( 'wc_product_table_query_args', array( $this, 'wc_product_table_query_args' ) );

            add_filter( 'wc_product_table_data_config', array( $this, 'wc_product_table_data_config' ) );

            add_filter( 'aws_search_page_custom_data', array( $this, 'aws_search_page_custom_data' ), 2 );

        }

        /*
         * Set default orderby
         */
        public function wc_product_table_query_args( $query_args ) {
            if ( isset( $_GET['type_aws'] ) ) {
                $query_args['orderby'] = 'none';
            }
            return $query_args;
        }

        /*
         * Set stateSave = false for table inside search page
         */
        public function wc_product_table_data_config( $config ) {
            if ( isset( $_GET['type_aws'] ) ) {
                $config['stateSave'] = 0;
            }
            return $config;
        }

        /*
         * Fix search query for product tables
         */
        public function aws_search_page_custom_data( $data ) {

            if ( class_exists('Barn2\Plugin\WC_Product_Table\Util\Util') ) {
                $shop_templates_tables = Barn2\Plugin\WC_Product_Table\Util\Util::get_shop_templates_tables();
                if ( isset( $_GET['type_aws'] ) && isset( $shop_templates_tables['search_override'] ) ) {
                    $data['force_ids'] = true;
                }
            }

            return $data;

        }

    }

endif;

AWS_Barn2PT::instance();