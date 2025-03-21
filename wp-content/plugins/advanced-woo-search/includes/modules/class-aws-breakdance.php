<?php

/**
 * AWS Breakdance plugin support
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Breakdance')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Breakdance {

        /**
         * @var AWS_Breakdance Custom data
         */
        public $data = array();

        /**
         * @var AWS_Breakdance The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Breakdance Instance
         *
         * Ensures only one instance of AWS_Breakdance is loaded or can be loaded.
         *
         * @static
         * @return AWS_Breakdance - Main instance
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

            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {
                add_filter( 'breakdance_render_element_html', array( $this, 'breakdance_render_element_html' ), 10, 2 );
            }

        }
        
        /*
         * Show labels for product title on single page
         */
        function breakdance_render_element_html( $elementHtml, $node ) {
            if ( $node && isset( $node['data'] ) && isset( $node['data']['type']  ) && $node['data']['type'] === 'EssentialElements\SearchForm' ) {
                $is_full_screen = strpos( $elementHtml, 'full-screen' ) !== false;
                if ( preg_match( "/form id=\"search-form-(.*?)\"/i", $elementHtml, $matches ) ) {
                    $form_id = $matches[1];
                }
                if ( strpos( $elementHtml, 'aws-container' ) === false ) {

                    if ( $is_full_screen ) {

                        $pattern = '/(<div class="search-form__lightbox-container"[\S\s]*?<\/div>)/i';
                        $search_from = aws_get_search_form( false );
                        $search_from = str_replace( '<form', '<div', $search_from );
                        $search_from = str_replace( '</form>', '</div>', $search_from );

                        $search_from = '<div class="search-form__lightbox-container">'.$search_from.'</div>';

                        $elementHtml = preg_replace( $pattern, $search_from, $elementHtml );

                        $elementHtml = '<style>
                            .search-form__lightbox-container .aws-container { 
                                width:100%; 
                            }
                            .search-form__lightbox-container .aws-search-form {
                                background: transparent;
                            }
                            .search-form__lightbox-container .aws-search-field {
                                border: none;
                                color:#fff;
                                background: transparent;
                            }
                            .search-form__lightbox-container .aws-search-field:focus {
                                background: transparent;
                            }
                            .search-form__lightbox-container .aws-search-form .aws-form-btn {
                                background: transparent;
                                border: none;
                            }
                            .search-form__lightbox-container .aws-search-form .aws-main-filter .aws-main-filter__current {
                                color:#fff;
                            }
                        </style>' . $elementHtml;

                    } else {

                        $pattern = '/(<form[\S\s]*?<\/form>)/i';
                        $elementHtml = preg_replace( $pattern, aws_get_search_form( false ), $elementHtml );

                    }
                }
            }
            return $elementHtml;
        }

    }

endif;

AWS_Breakdance::instance();