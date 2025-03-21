<?php
/**
 * Avada theme integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Avada' ) ) :

    /**
     * Class
     */
    class AWS_Avada {

        /**
         * Main AWS_Avada Instance
         *
         * Ensures only one instance of AWS_Avada is loaded or can be loaded.
         *
         * @static
         * @return AWS_Avada - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Avada Instance
         *
         * Ensures only one instance of AWS_Avada is loaded or can be loaded.
         *
         * @static
         * @return AWS_Avada - Main instance
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

            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {
                add_action( 'wp_head', array( $this, 'avada_head_action' ) );
                add_action( 'wp_footer', array( $this, 'avada_wp_footer' ) );
            }

            add_filter( 'aws_posts_per_page', array( $this, 'avada_posts_per_page' ), 2 );
            add_filter( 'aws_products_order_by', array( $this, 'avada_aws_products_order_by' ), 1 );
            add_filter( 'post_class', array( $this, 'avada_post_class' ) );
            add_action( 'fusion_builder_before_init', array( $this, 'avada_builder_elements' ) );

        }

        /*
         * Avada wp theme
         */
        public function avada_head_action() { ?>

            <style>

                .fusion-flyout-search .aws-container {
                    margin: 0 auto;
                    padding: 0;
                    width: 100%;
                    width: calc(100% - 40px);
                    max-width: 600px;
                    position: absolute;
                    top: 40%;
                    left: 20px;
                    right: 20px;
                }

                .fusion-overlay-search .aws-container {
                    width: 100%;
                }

                .fusion-secondary-menu-search .aws-container {
                    margin-left: 10px;
                }

            </style>

        <?php }

        public function avada_wp_footer() { ?>

            <script>

                window.addEventListener('load', function() {
                    var awsSearch = document.querySelectorAll(".fusion-menu .fusion-main-menu-search a, .fusion-flyout-menu-icons .fusion-icon-search");
                    if ( awsSearch ) {
                        for (var i = 0; i < awsSearch.length; i++) {
                            awsSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".fusion-menu .fusion-main-menu-search .aws-search-field, .fusion-flyout-search .aws-search-field").focus();
                                }, 100);
                            }, false);
                        }
                    }

                }, false);

            </script>

        <?php }

        /*
         * Avada theme posts per page option
         */
        public function avada_posts_per_page( $posts_per_page ) {
            $num = 12;
            $search_page_res_per_page = AWS()->get_settings( 'search_page_res_per_page' );
            if ( $search_page_res_per_page ) {
                $num = intval( $search_page_res_per_page );
            }
            $posts_per_page = isset( $_GET['product_count'] ) && intval( sanitize_text_field( $_GET['product_count'] ) ) ? intval( sanitize_text_field( $_GET['product_count'] ) ) : $num;
            return $posts_per_page;
        }

        /*
         * Avada theme order by options
         */
        public function avada_aws_products_order_by( $order_by ) {

            $order_by_new = '';

            if ( isset( $_GET['product_orderby'] ) ) {
                switch( sanitize_text_field( $_GET['product_orderby'] ) ) {
                    case 'name':
                        $order_by_new = 'title';
                        break;
                    case 'price':
                        $order_by_new = 'price';
                        break;
                    case 'date':
                        $order_by_new = 'date';
                        break;
                    case 'popularity':
                        $order_by_new = 'popularity';
                        break;
                    case 'rating':
                        $order_by_new = 'rating';
                        break;
                }
            }

            if ( isset( $_GET['product_order'] ) && $order_by_new ) {
                $product_order = sanitize_text_field( $_GET['product_order'] );
                if ( in_array( $product_order, array( 'asc', 'desc' ) ) ) {
                    $order_by_new = $order_by_new . '-' . $product_order;
                }

            }

            if ( $order_by_new ) {
                $order_by = $order_by_new;
            }

            return $order_by;

        }

        /*
         * Avada theme fix for product variations inside list products view
         */
        public function avada_post_class( $classes ) {
            if ( 'product_variation' === get_post_type()  ) {
                if ( isset( $_SERVER['QUERY_STRING'] ) ) {
                    parse_str( sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ), $params );
                    if ( isset( $params['product_view'] ) && $params['product_view'] ) {
                        $classes[] = 'product-' . $params['product_view'] . '-view';
                    }
                }
            }
            return $classes;
        }

        /*
         * Register search element for Avada Builder
         */
        function avada_builder_elements() {

            fusion_builder_map(
                array(
                    'name'       => esc_attr__( 'AWS Search', 'advanced-woo-search' ),
                    'shortcode'  => 'aws_search_form',
                    'icon'       => 'fusiona-search',
                    'help_url'   => 'https://advanced-woo-search.com/',
                    'params'     => array(),
                )
            );

        }

    }

endif;

AWS_Avada::instance();