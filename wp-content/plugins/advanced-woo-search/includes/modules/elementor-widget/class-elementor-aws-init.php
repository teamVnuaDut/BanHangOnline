<?php
/**
 * AWS plugin elementor integrations init
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Elementor_Init' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Elementor_Init {

        /**
         * @var AWS_Elementor_Init The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Elementor_Init Instance
         *
         * Ensures only one instance of AWS_Elementor_Init is loaded or can be loaded.
         *
         * @static
         * @return AWS_Elementor_Init - Main instance
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

            if ( defined('ELEMENTOR_VERSION') && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
                add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
            } else {
                add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_elementor_widgets' ) );
            }

            add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'filter_editor_styles' ) );
            add_action( 'elementor/preview/enqueue_styles', array( $this, 'filter_editor_styles' ) );

            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {

                add_filter( 'elementor/widget/render_content', array( $this, 'elementor_render_content' ), 10, 2 );

                // Elementor pro
                if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
                    add_action( 'wp_footer', array( $this, 'elementor_pro_popup' ) );
                }

            }

        }

        /**
         * Register elementor widget
         */
        public function register_elementor_widgets() {
            include_once( 'class-elementor-aws-widget.php' );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_AWS_Widget() );
        }

        /**
         * Enqueue editor filter styles
         */
        public function filter_editor_styles() {

            wp_enqueue_style(
                'aws-icons',
                AWS_URL . 'includes/modules/elementor-widget/elementor.css', array(), AWS_VERSION
            );

        }

        /*
         * Elementor replace search form widget
         */
        public function elementor_render_content( $content, $widget ) {

            if ( method_exists( $widget, 'get_name' ) && $widget->get_name() === 'search' ) {
                $content = preg_replace( '/<search[\S\s]*?<\/search>/i', aws_get_search_form( false ), $content );
                return $content;
            }

            if ( method_exists( $widget, 'get_name' ) && $widget->get_name() === 'search-form' ) {
                if ( method_exists( $widget, 'get_settings' )  ) {
                    $settings = $widget->get_settings();
                    if ( is_array( $settings ) && isset( $settings['skin'] ) && $settings['skin'] === 'full_screen' ) {
                        $content = '<style>
                            .elementor-search-form--skin-full_screen .elementor-search-form__container {
                                overflow: hidden;
                                align-items: flex-start !important;
                                padding-top: 100px !important;
                            }
                            .elementor-search-form--full-screen .aws-container {
                                width: 100%;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-form {
                                height: auto !important;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-form .aws-search-btn.aws-form-btn {
                                display: none;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-field {
                                border-bottom: 1px solid #fff !important;
                                font-size: 50px !important;
                                text-align: center !important;
                                line-height: 1.5 !important;
                                color: #7a7a7a !important;
                            }
                            .elementor-search-form--full-screen .aws-container .aws-search-field:focus {
                                background-color: transparent !important;
                            }
                        </style>' . $content;
                        $content = str_replace( array( '<form', '</form>' ), array( '<div', '</div>' ), $content );
                        $content = preg_replace( '/(<input[\S\s]*?elementor-search-form__input[\S\s]*?\>)/i', aws_get_search_form( false ), $content );
                        return $content;
                    }
                }
                return aws_get_search_form( false );
            }

            // Elementskit plugin header search
            if ( method_exists( $widget, 'get_name' ) && $widget->get_name() === 'elementskit-header-search' ) {
                $content = '<style>
                .ekit-search-panel .aws-container .aws-search-form {
                    height: 50px;
                }
                .ekit-search-panel .aws-container .aws-search-field {
                    border-radius: 50px !important;
                }
                .ekit-search-panel .aws-container .aws-search-form .aws-search-btn.aws-form-btn,
                .ekit-search-panel .aws-container .aws-search-form .aws-form-btn:last-of-type {
                    width: 60px;
                }
                .ekit-search-panel .aws-container .aws-search-form,
                .ekit-search-panel .aws-container .aws-search-form .aws-form-btn,
                .ekit-search-panel .aws-container .aws-search-field {
                    background: transparent;
                }
                .ekit-search-panel .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current,
                .ekit-search-panel .aws-container .aws-search-form .aws-search-btn_icon,
                .ekit-search-panel .aws-container .aws-search-field,
                .ekit-search-panel .aws-container .aws-search-field::-webkit-input-placeholder {
                    color: #fff;
                }
                .ekit-search-panel .aws-container .aws-search-field,
                .ekit-search-panel .aws-container .aws-search-form .aws-form-btn{
                    border: 2px solid #fff;
                }          
                .ekit-search-panel .aws-container .aws-search-field {
                    padding-left: 20px;
                }
                .ekit-search-panel .aws-container[data-buttons-order="2"] .aws-search-field {
                    border-top-right-radius: 0 !important;
                    border-bottom-right-radius: 0 !important;
                }               
                .ekit-search-panel .aws-container[data-buttons-order="2"] .aws-search-form .aws-search-btn {
                    border-top-left-radius: 0 !important;
                    border-bottom-left-radius: 0 !important;
                    border-top-right-radius: 50px !important;
                    border-bottom-right-radius: 50px !important;
                }
                .ekit-search-panel .aws-container[data-buttons-order="3"] .aws-search-field {
                    border-top-left-radius: 0 !important;
                    border-bottom-left-radius: 0 !important;
                }               
                .ekit-search-panel .aws-container[data-buttons-order="3"] .aws-search-form .aws-search-btn {
                    border-top-left-radius: 50px !important;
                    border-bottom-left-radius: 50px !important;
                    border-top-right-radius: 0 !important;
                    border-bottom-right-radius: 0 !important;
                }
                </style>' . $content;
                $content = preg_replace( '/<form[\S\s]*?<\/form>/i', aws_get_search_form( false ), $content );
            }

            return $content;

        }

        /*
         * Elementor popup search form init
         */
        public function elementor_pro_popup() { ?>

            <script>
                window.addEventListener('load', function() {
                    if (window.jQuery) {
                        jQuery( document ).on( 'elementor/popup/show', function() {
                            window.setTimeout(function(){
                                jQuery('.elementor-container .aws-container, .elementor-popup-modal .aws-container').each( function() {
                                    jQuery(this).aws_search();
                                });
                            }, 1000);
                        } );
                    }
                }, false);
            </script>

        <?php }
        
    }

endif;

AWS_Elementor_Init::instance();