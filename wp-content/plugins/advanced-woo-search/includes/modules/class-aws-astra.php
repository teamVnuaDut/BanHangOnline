<?php
/**
 * Astra theme integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Astra' ) ) :

    /**
     * Class
     */
    class AWS_Astra {

        /**
         * Main AWS_Astra Instance
         *
         * Ensures only one instance of AWS_Astra is loaded or can be loaded.
         *
         * @static
         * @return AWS_Astra - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Astra Instance
         *
         * Ensures only one instance of AWS_Astra is loaded or can be loaded.
         *
         * @static
         * @return AWS_Astra - Main instance
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
                add_filter( 'aws_js_seamless_selectors', array( $this, 'js_seamless_selectors' ), 1 );
                add_filter( 'aws_js_seamless_searchbox_markup', array( $this, 'seamless_searchbox_markup' ), 1 );
                add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 999 );
                add_filter( 'astra_get_search_form', array( $this, 'astra_markup' ), 999999 );
                add_filter( 'aws_searchbox_markup', array( $this, 'astra_aws_searchbox_markup' ), 1 );
                add_action( 'wp_head', array( $this, 'astra_head_action' ) );
            }

        }

        /*
         * Selector filter of js seamless
         */
        public function js_seamless_selectors( $selectors ) {
            $selectors[] = '.ast-search-box.header-cover form';
            $selectors[] = '.ast-search-box.full-screen form';
            return $selectors;
        }

        /*
         * Markup for seamless js integration
         */
        public function seamless_searchbox_markup( $markup ) {
            $markup = str_replace( 'aws-search-field', 'aws-search-field search-field', $markup );
            return $markup;
        }

        /*
         * Astra theme form markup
         */
        public function astra_markup( $output ) {
            if ( function_exists( 'aws_get_search_form' ) && is_string( $output ) ) {

                $pattern = '/(<form[\s\S]*?<\/form>)/i';
                $form = aws_get_search_form(false);

                if ( strpos( $output, 'aws-container' ) !== false ) {
                    $pattern = '/(<div class="aws-container"[\s\S]*?<form.*?<\/form><\/div>)/i';
                }

                $output = trim(preg_replace('/\s\s+/', ' ', $output));
                $output = preg_replace( $pattern, $form, $output );
                $output = str_replace( 'aws-container', 'aws-container search-form', $output );
                $output = str_replace( 'aws-search-field', 'aws-search-field search-field', $output );

            }
            return $output;
        }

        /*
         * Astra theme form markup
         */
        public function astra_aws_searchbox_markup( $markup ) {
            $markup = str_replace( 'aws-container', 'aws-container search-form', $markup );
            return $markup;
        }

        /*
         * Add custom js scripts
         */
        public function wp_enqueue_scripts() {

            $script = ' 
              document.addEventListener("awsLoaded", function() {
                jQuery(document).on("click", ".ast-search-box .close", function(e) {
                    jQuery(this).closest(".ast-search-box.header-cover").attr("style", "");
                });
              });
            ';

            if ( function_exists('astra_get_option') && astra_get_option( 'header-search-box-type' ) === 'header-cover' && class_exists('Astra_Icons') ) {

                $close_btn = '<span id="close" class="close">' . str_replace(array("\r", "\n"), '', Astra_Icons::get_icons( 'close', false )) . '</span>';

                $script .= '
                document.addEventListener("awsLoaded", function() {
                      if ( ! jQuery(".ast-search-box.header-cover .close").length > 0 ) {
                          jQuery(".ast-search-box.header-cover form").append(\'' . $close_btn . '\');
                      }
                  });
                ';

            }

            wp_add_inline_script( 'aws-script', $script);
            wp_add_inline_script( 'aws-pro-script', $script);

        }

        /*
         * Astra theme
         */
        public function astra_head_action() { ?>

            <style>
                .ast-search-menu-icon.slide-search .search-form {
                    width: auto;
                }
                .ast-search-menu-icon .search-form {
                    padding: 0 !important;
                }
                .ast-search-menu-icon.ast-dropdown-active.slide-search .ast-search-icon {
                    opacity: 0;
                }
                .ast-search-menu-icon.slide-search .aws-container .aws-search-field {
                    width: 0;
                    background: #fff;
                    border: none;
                }
                .ast-search-menu-icon.ast-dropdown-active.slide-search .aws-search-field {
                    width: 235px;
                }
                .ast-search-menu-icon.slide-search .aws-container .aws-search-form .aws-form-btn {
                    background: #fff;
                    border: none;
                }
                .ast-search-menu-icon.ast-dropdown-active.slide-search .ast-search-icon {
                    opacity: 1;
                }
                .ast-search-menu-icon.ast-dropdown-active.slide-search .ast-search-icon .slide-search.astra-search-icon {
                    opacity: 0;
                }
                .ast-search-box.header-cover .aws-container .aws-search-form {
                    background: transparent;
                }
                .ast-search-box.header-cover .aws-container .aws-search-form .aws-search-field,
                .ast-search-box.full-screen .aws-container .aws-search-form .aws-search-field {
                    outline: none;
                }
                .ast-search-box.header-cover .aws-container .aws-search-form .aws-form-btn,
                .ast-search-box.full-screen .aws-container .aws-search-form .aws-form-btn {
                    background: transparent;
                    border: none;
                }
                .ast-search-box.header-cover .aws-container .aws-search-form .aws-search-btn_icon,
                .ast-search-box.full-screen .aws-container .aws-search-form .aws-search-btn_icon,
                .ast-search-box.header-cover .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current,
                .ast-search-box.full-screen .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current {
                    color: #fff;
                }
                .ast-search-box.full-screen .aws-container {
                    margin: 40px auto !important;
                }
                .ast-search-box.full-screen .aws-container #close {
                    display: none;
                }
                .ast-search-box.full-screen .aws-container .aws-search-form {
                    background: transparent;
                    border-bottom: 2px solid #9E9E9E;
                    height: 50px;
                }
                .ast-search-box.full-screen .aws-container .aws-search-form .aws-search-field {
                    padding-bottom: 10px;
                }
            </style>

        <?php }
        
    }

endif;

AWS_Astra::instance();