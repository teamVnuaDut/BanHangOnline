<?php

/**
 * EAN for WooCommerce by WPFactory plugin integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Alg_Wc_Ean')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Alg_Wc_Ean {

        /**
         * @var AWS_Alg_Wc_Ean The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_Alg_Wc_Ean Instance
         *
         * Ensures only one instance of AWS_Alg_Wc_Ean is loaded or can be loaded.
         *
         * @static
         * @return AWS_Alg_Wc_Ean - Main instance
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

            add_filter( 'aws_indexed_data', array( $this, 'aws_indexed_data' ), 10, 2 );

            add_filter( 'aws_search_data_parameters', array( $this, 'aws_search_data_parameters' ) );

            add_filter( 'aws_relevance_parameters', array( $this, 'aws_relevance_parameters' ), 10, 4 );

            add_filter( 'aws_search_pre_filter_single_product', array( $this, 'aws_search_pre_filter_single_product' ), 10, 3 );

        }

        /*
         * Add EAN to index
         */
        public function aws_indexed_data( $data, $id ) {

            $ean_key = get_option( 'alg_wc_ean_meta_key', '_alg_ean' );

            $ean = get_post_meta( $id, $ean_key, true );

            if ( $ean ) {

                $data['terms']['alg_wc_ean'][$ean] = 1;

            }

            return $data;

        }

        /*
         * Add relevance for alg_wc_ean field
         */
        public function aws_relevance_parameters( $relevance_params, $relevance_scores, $search_term, $data ) {
            if ( isset( $relevance_scores['sku'] ) ) {
                $relevance_params['alg_wc_ean'] = array(
                    'full' => $relevance_scores['sku'],
                    'like' => $relevance_scores['sku'] / 5,
                );
            }
            return $relevance_params;
        }

        /*
         * Add ETN search source
         */
        public function aws_search_data_parameters( $data ) {
            if ( isset( $data['search_in'] ) && get_option( 'alg_wc_ean_plugin_enabled', 'yes' ) === 'yes' && get_option( 'alg_wc_ean_frontend_search', 'yes' ) === 'yes' ) {
                $data['search_in'][] = 'alg_wc_ean';
            }
            return $data;
        }

        /*
         * Display EAN number in search results
         */
        public function aws_search_pre_filter_single_product( $result, $post_id, $product ) {

            $show_ean = apply_filters( 'aws_show_alg_ean', true, $product );

            if ( $show_ean ) {

                $ean_key = get_option( 'alg_wc_ean_meta_key', '_alg_ean' );
                $ean = get_post_meta( $post_id, $ean_key, true );

                if ( $ean ) {

                    $title = get_option( 'alg_wc_ean_title', __( 'EAN', 'ean-for-woocommerce' ) );
                    $br = isset($result['excerpt']) && $result['excerpt'] ? '<br>' : '';

                    $result['excerpt'] = '<span class="aws_result_alg_ean">' . $title . ': ' . $ean . '</span>' . $br . $result['excerpt'];

                }

            }

            return $result;

        }

    }

endif;

AWS_Alg_Wc_Ean::instance();