<?php

/**
 * Filter Everything plugin integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_FEverything')) :

    /**
     * Class for main plugin functions
     */
    class AWS_FEverything {

        /**
         * @var AWS_FEverything The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_FEverything Instance
         *
         * Ensures only one instance of AWS_FEverything is loaded or can be loaded.
         *
         * @static
         * @return AWS_FEverything - Main instance
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

            add_action( 'wpc_related_set_ids', array( $this, 'wpc_has_widgets' ) );
            add_action( 'wpc_filtered_query_end', array( $this, 'wpc_has_widgets' ) );

            // Change default search page query
            add_filter( 'aws_search_page_custom_data', array( $this, 'aws_search_page_custom_data' ), 5 );

        }

        /*
         * Check if current page has filters widgets
         */
        public function wpc_has_widgets() {
            $this->data['has_widgets'] = true;
        }

        /*
         * Change default search page query
         */
        public function aws_search_page_custom_data( $data ) {
            if ( isset( $this->data['has_widgets'] ) && $this->data['has_widgets'] ) {
                $data['force_ids'] = true;
            }
            return $data;
        }

    }

endif;

AWS_FEverything::instance();