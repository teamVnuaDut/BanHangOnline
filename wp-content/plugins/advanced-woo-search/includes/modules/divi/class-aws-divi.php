<?php
/**
 * Divi builder integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Divi' ) ) :

    /**
     * Class
     */
    class AWS_Divi {

        /**
         * Main AWS_Divi Instance
         *
         * Ensures only one instance of AWS_Divi is loaded or can be loaded.
         *
         * @static
         * @return AWS_Divi - Main instance
         */
        protected static $_instance = null;
        
        /**
         * Main AWS_Divi Instance
         *
         * Ensures only one instance of AWS_Divi is loaded or can be loaded.
         *
         * @static
         * @return AWS_Divi - Main instance
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

            add_filter( 'aws_before_strip_shortcodes', array( $this, 'divi_builder_strip_shortcodes' ) );
            add_filter( 'aws_index_do_shortcodes', array( $this, 'divi_builder_index_do_shortcodes' ) );
            add_filter( 'aws_indexed_content', array( $this, 'aws_indexed_content' ), 10, 3 );

            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {

                add_action( 'wp_head', array( $this, 'wp_head' ) );

                add_filter( 'et_html_main_header', array( $this, 'et_html_main_header' ) );
                add_filter( 'et_html_slide_header', array( $this, 'et_html_main_header' ) );

                add_filter( 'et_pb_search_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_filter( 'et_pb_menu_shortcode_output', array( $this, 'divi_builder_search_module' ) );
                add_filter( 'et_pb_fullwidth_menu_shortcode_output', array( $this, 'divi_builder_search_module' ) );

            }

        }

        /*
         * Divi builder remove dynamic text shortcodes
         */
        public function divi_builder_strip_shortcodes( $str ) {
            $str = preg_replace( '#\[et_pb_text.[^\]]*?_dynamic_attributes.*?\]@ET-.*?\[\/et_pb_text\]#', '', $str );
            return $str;
        }

        /*
         * Disable shortcodes exucution inside product content when runing Divi visual builder
         */
        public function divi_builder_index_do_shortcodes( $do_shortcodes ) {
            if ( isset( $_POST['action'] ) && $_POST['action'] === 'et_fb_ajax_save' ) {
                return false;
            }
            return $do_shortcodes;
        }

        /*
         * Add to index content from 'long description' field
         */
        public function aws_indexed_content( $content, $post_id, $product ) {

            if ( function_exists('et_pb_is_pagebuilder_used') && defined('ET_BUILDER_WC_PRODUCT_LONG_DESC_META_KEY') && et_pb_is_pagebuilder_used( $post_id ) ) {
                $description = get_post_meta( $post_id, ET_BUILDER_WC_PRODUCT_LONG_DESC_META_KEY, true );
                if ( $description ) {
                    $description = AWS_Helpers::strip_shortcodes( $description );
                    $content .= ' ' . $description;
                }
            }

            return $content;

        }

        /*
         * Focus search field on icon click
         */
        public function wp_head() {

            $html = '
                <script>
                
                    window.addEventListener("load", function() {
                        
                        var awsDiviSearch = document.querySelectorAll("header .et_pb_menu__search-button");
                        if ( awsDiviSearch ) {
                            for (var i = 0; i < awsDiviSearch.length; i++) {
                                awsDiviSearch[i].addEventListener("click", function() {
                                    window.setTimeout(function(){
                                        document.querySelector(".et_pb_menu__search-container .aws-container .aws-search-field").focus();
                                        jQuery( ".aws-search-result" ).hide();
                                    }, 100);
                                }, false);
                            }
                        }

                    }, false);

                </script>';

            echo $html;

        }

        /*
         * Seamless integration for header
         */
        public function et_html_main_header( $html ) {
            if ( function_exists( 'aws_get_search_form' ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $html, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $html = '<style>.et_search_outer .aws-container { position: absolute;right: 40px;top: 20px; top: calc( 100% - 60px ); }</style>' . $html;
                $html = trim(preg_replace('/\s\s+/', ' ', $html));
                $html = preg_replace( $pattern, $form, $html );

            }
            return $html;
        }

        /*
         * Divi builder replace search module
         */
        public function divi_builder_search_module( $output ) {
            if ( function_exists( 'aws_get_search_form' ) && is_string( $output ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $output, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $output = trim(preg_replace('/\s\s+/', ' ', $output));
                $output = preg_replace( $pattern, $form, $output );

            }
            return $output;
        }

    }

endif;

AWS_Divi::instance();