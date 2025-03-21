<?php
/**
 * Advanced Woo Labels plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_AWL' ) ) :

    /**
     * Class
     */
    class AWS_AWL {

        /**
         * Main AWS_AWL Instance
         *
         * Ensures only one instance of AWS_AWL is loaded or can be loaded.
         *
         * @static
         * @return AWS_AWL - Main instance
         */
        protected static $_instance = null;

        public $old_title = true;

        /**
         * Main AWS_AWL Instance
         *
         * Ensures only one instance of AWS_AWL is loaded or can be loaded.
         *
         * @static
         * @return AWS_AWL - Main instance
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
            add_filter( 'aws_admin_page_options', array( $this, 'add_admin_options' ) );
            add_filter( 'aws_title_search_result', array( $this, 'aws_old_title_search_result' ), 9 );
            add_filter( 'aws_title_search_result', array( $this, 'aws_title_search_result' ), 11 );
        }

        /*
         * Add wishlist admin options
         */
        public function add_admin_options( $options ) {

            $new_options = array();

            if ( $options ) {
                foreach ( $options as $section_name => $section ) {
                    foreach ( $section as $values ) {

                        $new_options[$section_name][] = $values;

                        if ( isset( $values['id'] ) && $values['id'] === 'show_stock' ) {

                            $new_options[$section_name][] = array(
                                "name"  => __( "Show AWL labels?", "advanced-woo-search" ),
                                "desc"  => __( "Show or not AWL plugin labels for all products inside search results.", "advanced-woo-search" ),
                                "id"    => "show_awl_labels",
                                "value" => 'true',
                                "type"  => "radio",
                                'choices' => array(
                                    'true'  => __( 'On', 'advanced-woo-search' ),
                                    'false' => __( 'Off', 'advanced-woo-search' )
                                )
                            );

                        }

                    }
                }

                return $new_options;

            }

            return $options;

        }

        /*
         * Save product title before adding labels to it
         */
        public function aws_old_title_search_result( $title ) {
            $this->old_title = $title;
            return $title;
        }

        /*
         * Hide labels is such option is enabled
         */
        public function aws_title_search_result( $title ) {

            if ( $title && preg_match( '/aws_result_labels/i', $title ) ) {

                $show_labels = AWS()->get_settings( 'show_awl_labels' );
                if ( ! $show_labels ) {
                    $show_labels = 'true';
                }

                if ( $show_labels === 'false' ) {
                    $title = $this->old_title;
                }

            }

            return $title;

        }

    }

endif;

AWS_AWL::instance();