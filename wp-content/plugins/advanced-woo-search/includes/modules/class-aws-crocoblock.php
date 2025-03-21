<?php
/**
 * AWS Crocoblock plugins integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Crocoblock' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Crocoblock {

        /**
         * @var AWS_Crocoblock The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Crocoblock Instance
         *
         * Ensures only one instance of AWS_Crocoblock is loaded or can be loaded.
         *
         * @static
         * @return AWS_Crocoblock - Main instance
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

                add_filter( 'elementor/widget/render_content', array( $this, 'elementor_render_content' ), 10, 2 );
                
            }

        }

        /*
         * Elementor replace search form widget
         */
        public function elementor_render_content( $content, $widget ) {
            
            if ( method_exists( $widget, 'get_name' ) && $widget->get_name() === 'jet-search' ) {
                
                if ( method_exists( $widget, 'get_settings' )  ) {
                    
                    $settings = $widget->get_settings();

                    if ( is_array( $settings ) && isset( $settings['is_product_search'] ) && $settings['is_product_search'] === 'true' ) {

                        $aws_form = aws_get_search_form( false );

                        if ( isset( $settings['full_screen_popup'] ) && $settings['full_screen_popup'] === 'true' ) {
                            $aws_form .= '<style>
                                .jet-search .aws-container { width:100%; } 
                                .jet-search .aws-container .aws-show-clear .aws-search-field,
                                .jet-search .aws-container .aws-search-field:focus::-webkit-input-placeholder { color:#fff; }
                                .jet-search .aws-container .aws-search-form .aws-search-btn svg {  fill: #fff; }
                            </style>';
                        }

                        $content = preg_replace( '/<form[\S\s]*?<\/form>/i', $aws_form, $content );

                    }
                    
                }

            }

            return $content;

        }

    }

endif;

AWS_Crocoblock::instance();