<?php
/**
 *  AWS Shopengine plugin support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Shopengine' ) ) :

    /**
     * Class
     */
    class AWS_Shopengine {

        /**
         * @var AWS_Shopengine Custom data
         */
        public $data = array();

        /**
         * @var AWS_Shopengine Blocks names array
         */
        public $block_names = array( 'shopengine-product-list', 'shopengine-filterable-product-list' );

        /**
         * Main AWS_Shopengine Instance
         *
         * Ensures only one instance of AWS_Shopengine is loaded or can be loaded.
         *
         * @static
         * @return AWS_Shopengine - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Shopengine Instance
         *
         * Ensures only one instance of AWS_Shopengine is loaded or can be loaded.
         *
         * @static
         * @return AWS_Shopengine - Main instance
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

            add_action( 'elementor/widget/before_render_content', array( $this, 'before_render_content' ) );
            add_filter( 'elementor/widget/render_content', array( $this, 'render_content' ), 10, 2 );

            add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

        }

        public function before_render_content( $block ) {
            if ( array_search( $block->get_name(), $this->block_names ) !== false ) {
                $this->data['is_shopengine'] = true;
            }
        }

        public function render_content( $widget_content, $block ) {
            $this->data['is_shopengine'] = false;
            return $widget_content;
        }

        /*
         * Fix query loop for products inside search page
         */
        public function pre_get_posts( $query ) {
            if ( isset( $_GET['type_aws'] ) && isset( $this->data['is_shopengine'] ) && $this->data['is_shopengine'] ) {
                $query->query['orderby'] = '';
            }
        }

    }

endif;

AWS_Shopengine::instance();