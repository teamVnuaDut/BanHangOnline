<?php
/**
 * Generatepress theme integration
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Generatepress' ) ) :

    /**
     * Class
     */
    class AWS_Generatepress {

        /**
         * Main AWS_Generatepress Instance
         *
         * Ensures only one instance of AWS_Generatepress is loaded or can be loaded.
         *
         * @static
         * @return AWS_Generatepress - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_Generatepress Instance
         *
         * Ensures only one instance of AWS_Generatepress is loaded or can be loaded.
         *
         * @static
         * @return AWS_Generatepress - Main instance
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
                add_filter( 'generate_navigation_search_output', array( $this, 'generate_navigation_search_output' ) );
                remove_action( 'generate_inside_search_modal', 'generate_do_search_fields' );
                add_action( 'generate_inside_search_modal', array( $this, 'generate_inside_search_modal' ) );
            }

        }

        public function generate_navigation_search_output( $html ) {
            if ( function_exists( 'aws_get_search_form' ) ) {
                $html = '<style>.navigation-search .aws-container .aws-search-form{height: 60px;} .navigation-search .aws-container{margin-right: 60px;} .navigation-search .aws-container .search-field{border:none;} </style>';
                $html .= '<script>
                     window.addEventListener("awsShowingResults", function(e) {
                         var links = document.querySelectorAll(".aws_result_link");
                         if ( links ) {
                            for (var i = 0; i < links.length; i++) {
                                links[i].className += " search-item";
                            }
                        }
                     }, false);
                    </script>';
                $html .= '<div class="navigation-search">' . aws_get_search_form( false ) . '</div>';
                $html = str_replace( 'aws-search-field', 'aws-search-field search-field', $html );
            }
            return $html;
        }

        /*
         * Add search form inside modal window
         */
        public function generate_inside_search_modal() {

            $html = '<style>
                .aws-modal-container { width: 500px; max-width: 100%;} 
                .aws-modal-container .search-modal-form {height: 60px;} 
                .aws-modal-container .aws-search-field { font-size: 17px; padding: 10px 15px; } 
                .aws-modal-container .aws-search-form .aws-form-btn { background: transparent; border: none;}
            </style>
            
            <script>
            window.addEventListener("load", function() {
                 if ( typeof jQuery !== "undefined" ) {
                    jQuery(".gp-modal__overlay").on("click", function(e) {
                        if ( jQuery("#gp-search").hasClass("gp-modal--open") &&
                         ! jQuery(event.target).closest(".aws-container").length &&
                         ! jQuery(event.target).closest(".aws-search-result").length ) {
                            jQuery(".aws-search-result").hide();
                        }
                    });
                 }
            }, false);
            </script>';

            $form = aws_get_search_form( false );
            $form = str_replace( 'aws-container', 'aws-container aws-modal-container', $form );
            $form = str_replace( 'aws-search-form', 'aws-search-form search-modal-form', $form );
            $form = str_replace( 'aws-search-field', 'aws-search-field search-field', $form );

            echo $html . $form;

        }

    }

endif;

AWS_Generatepress::instance();