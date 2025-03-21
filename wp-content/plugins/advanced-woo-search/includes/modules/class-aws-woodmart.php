<?php
/**
 * Woodmart theme support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Woodmart' ) ) :

    /**
     * Class
     */
    class AWS_Woodmart {

        /**
         * Main AWS_Woodmart Instance
         *
         * Ensures only one instance of AWS_Woodmart is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woodmart - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Woodmart Instance
         *
         * Ensures only one instance of AWS_Woodmart is loaded or can be loaded.
         *
         * @static
         * @return AWS_Woodmart - Main instance
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
                add_filter( 'aws_js_seamless_selectors', array( $this, 'js_seamless_selectors' ) );
                add_action( 'wp_head', array( $this, 'woodmart_head_action' ) );
                add_filter( 'aws_seamless_search_form_filter', array( $this, 'woodmart_seamless_search_form_filter' ), 10, 2 );
            }

            add_filter( 'woodmart_shop_page_link', array( $this, 'woodmart_shop_page_link' ), 9999 );

        }

        /*
         * New js selectors for seamless integration
         */
        public function js_seamless_selectors( $selectors ) {
            $selectors[] = '.woodmart-search-form form, form.woodmart-ajax-search';
            return $selectors;
        }

        /*
         * Custom styles
         */
        public function woodmart_head_action() { ?>

            <style>

                .woodmart-search-full-screen .aws-container .aws-search-form,
                .wd-search-full-screen .aws-container .aws-search-form {
                    padding-top: 0;
                    padding-right: 0;
                    padding-bottom: 0;
                    padding-left: 0;
                    height: 110px;
                    border: none;
                    background-color: transparent;
                    box-shadow: none;
                }

                .woodmart-search-full-screen .aws-container .aws-search-field,
                .wd-search-full-screen .aws-container .aws-search-field {
                    color: #333;
                    text-align: center;
                    font-weight: 600;
                    font-size: 48px;
                }

                .woodmart-search-full-screen .aws-container .aws-search-form .aws-form-btn,
                .wd-search-full-screen .aws-container .aws-search-form .aws-form-btn,
                .woodmart-search-full-screen .aws-container .aws-search-form.aws-show-clear.aws-form-active .aws-search-clear,
                .wd-search-full-screen .aws-container .aws-search-form.aws-show-clear.aws-form-active .aws-search-clear {
                    display: none !important;
                }

                .wd-search-full-screen-2.wd-fill.wd-opened {
                    display: block;
                    top: 30px;
                }

            </style>

        <?php }

        /*
         * Filter default search form markup
         */
        public function woodmart_seamless_search_form_filter( $markup, $search_form ) {
            if ( strpos( $search_form, 'wd-search-full-screen' ) !== false ) {
                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $markup = preg_replace( $pattern, $markup, $search_form );
            }
            elseif ( strpos( $search_form, 'wd-display-full-screen-2' ) !== false ) {
                $markup = str_replace( 'aws-container', 'aws-container wd-search-form wd-header-search-form wd-display-full-screen-2', $markup );
            }
            elseif ( strpos( $search_form, 'wd-search-dropdown' ) !== false ) {
                $markup = str_replace( 'aws-container', 'aws-container wd-search-dropdown wd-dropdown', $markup );
            }
            return $markup;
        }

        /*
         * Update search page pagination links
         */
        public function woodmart_shop_page_link( $link ) {
            if ( isset( $_GET['type_aws'] ) && strpos( $link, 'type_aws' ) === false ) {
                $link = add_query_arg( array(
                    'type_aws' => 'true',
                ), $link );
            }
            return $link;
        }

    }

endif;

AWS_Woodmart::instance();
