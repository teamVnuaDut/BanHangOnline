<?php
/**
 * AWS plugin shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Shortcodes' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Shortcodes {

        /**
         * @var AWS_Shortcodes The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Shortcodes Instance
         *
         * Ensures only one instance of AWS_Shortcodes is loaded or can be loaded.
         *
         * @static
         * @return AWS_Shortcodes - Main instance
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

            // Search form
            add_shortcode( 'aws_search_form', array( $this, 'aws_search_form' ) );

            // Similar ( fixed ) terms
            add_shortcode( 'aws_search_did_you_mean', array( $this, 'aws_search_did_you_mean' ) );
            add_shortcode( 'aws_search_showing_results_for', array( $this, 'aws_search_showing_results_for' ) );

            // Search terms links
            add_shortcode( 'aws_search_terms', array( $this, 'aws_search_terms' ) );

        }

        /*
	     * Generate search box markup
	     */
        public function aws_search_form( $atts = array() ) {

            return AWS()->markup( $atts );

        }

        /*
         * Similar terms suggestions
         */
        public function aws_search_did_you_mean( $atts = array() ) {

            extract( shortcode_atts( array(
                'always_show' => false,
            ), $atts ) );

            $result = '';

            $s_data = apply_filters( 'aws_current_search_data', array() );

            if ( ( isset( $s_data['fuzzy'] ) && $s_data['fuzzy'] === 'false_text' ) || $always_show ) {

                $terms_suggestions = array();

                $product_ids = apply_filters( 'aws_current_search_product_ids', array() );
                if ( ! empty( $product_ids ) ) {
                    return $result;
                }

                if ( ! isset( $s_data['similar_terms'] ) ) {

                    $similar_terms_obj = new AWS_Similar_Terms( $s_data );
                    $similar_terms_res = $similar_terms_obj->get_similar_terms();

                    if ( ! empty( $similar_terms_res ) && ! empty( $similar_terms_res['all'] ) ) {

                        $s_data['similar_terms'] = $similar_terms_res;

                        $terms_suggestions = AWS_Helpers::get_fixed_terms_suggestions( $s_data );

                    }

                }

                if ( ! empty( $terms_suggestions ) ) {

                    $new_terms = array();
                    foreach ( $terms_suggestions as $terms_suggestion ) {
                        if ( $terms_suggestion === $s_data['s'] ) {
                            continue;
                        }
                        $search_url = AWS_Helpers::get_search_term_url( $terms_suggestion );
                        $new_terms[] = '<a href="' . $search_url . '" class="aws_term_suggestion">'. $terms_suggestion . '</a>';
                    }

                    if ( ! empty( $new_terms ) ) {
                        $result = '<div class="aws_terms_suggestions tagcloud">' . implode(', ', $new_terms ) . '</div>';
                    }

                }

            }

            /**
             * Filter shortcode results
             * @since 3.23
             * @param string $result Shortcode generated markup
             * @param array $atts Shortcode parameters
             */
            $result = apply_filters( 'aws_search_did_you_mean_shortcode', $result, $atts );

            return $result;

        }

        /*
         * Show current fixed search term
         */
        public function aws_search_showing_results_for( $atts = array() ) {

            extract( shortcode_atts( array(
                'always_show' => false,
            ), $atts ) );

            $result = '';

            $s_data = apply_filters( 'aws_current_search_data', array() );
            $product_ids = apply_filters( 'aws_current_search_product_ids', array() );

            if ( ! empty( $product_ids ) && isset( $s_data['fuzzy'] ) && ( $s_data['fuzzy'] === 'true_text' || ( $s_data['fuzzy'] === 'true' && $always_show ) ) && isset( $s_data['similar_terms'] )  ) {

                $terms_suggestions = AWS_Helpers::get_fixed_terms_suggestions( $s_data );

                if ( ! empty( $terms_suggestions ) ) {
                    $new_terms = array();
                    foreach ( $terms_suggestions as $terms_suggestion ) {
                        $new_terms[] = '<span class="aws_term_suggestion"><strong>'. $terms_suggestion . '</strong></span>';
                    }
                    $result = '<div class="aws_terms_suggestions">' . implode(', ', $new_terms ) . '</div>';
                }


            }

            /**
             * Filter shortcode results
             * @since 3.23
             * @param string $result Shortcode generated markup
             * @param array $atts Shortcode parameters
             */
            $result = apply_filters( 'aws_search_showing_results_for_shortcode', $result, $atts );

            return $result;

        }

        /*
         * Generate search terms links
         */
        public function aws_search_terms( $atts = array() ) {

            extract( shortcode_atts( array(
                'terms' => '',
            ), $atts ) );

            $result = '';

            if ( $terms ) {

                $terms = sanitize_text_field( $terms );

                $term_arr = array_map( 'trim', explode( ',', $terms ) );

                if ( ! empty( $term_arr ) ) {

                    $new_terms = array();
                    foreach ( $term_arr as $term ) {
                        $term = esc_html( $term );
                        $search_url = esc_url( AWS_Helpers::get_search_term_url( $term ) );
                        $new_terms[] = '<a href="' . $search_url . '" class="aws_term_suggestion">'. $term . '</a>';
                    }

                    if ( ! empty( $new_terms ) ) {
                        $result = '<div class="aws_search_terms tagcloud">' . implode(' ', $new_terms ) . '</div>';
                    }

                }

            }

            /**
             * Filter shortcode results
             * @since 3.23
             * @param string $result Shortcode generated markup
             * @param array $atts Shortcode parameters
             */
            $result = apply_filters( 'aws_search_terms_shortcode', $result, $atts );

            return $result;

        }
        
    }

endif;