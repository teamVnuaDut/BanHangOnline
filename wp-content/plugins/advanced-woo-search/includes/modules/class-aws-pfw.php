<?php

/**
 * Product Filters for WooCommerce plugin integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_PFW')) :

    /**
     * Class for main plugin functions
     */
    class AWS_PFW {

        /**
         * @var AWS_PFW The single instance of the class
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_PFW Instance
         *
         * Ensures only one instance of AWS_PFW is loaded or can be loaded.
         *
         * @static
         * @return AWS_PFW - Main instance
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
            
            add_filter( 'aws_search_page_custom_data', array( $this, 'aws_search_page_custom_data' ) );

            add_filter( 'wcpf_product_counts_search_sql', array( $this, 'wcpf_product_counts_search_sql' ), 999 );

            add_filter( 'wcpf_product_counts_clauses', array( $this, 'wcpf_product_counts_clauses' ), 999, 2 );

            add_filter( 'aws_search_page_filters', array( $this, 'aws_search_page_filters' ), 9999 );

        }

        /*
         * Set search query custom data
         */
        public function aws_search_page_custom_data( $data ) {
            $this->data = $data;
            return $data;
        }

        /*
         * Disable filters search query
         */
        public function wcpf_product_counts_search_sql( $sql ) {
            if ( isset( $_GET['type_aws'] ) ) {
                $sql = '';
            }
            return $sql;
        }

        /*
         * Add product IDs into sql query
         */
        public function wcpf_product_counts_clauses( $query, $args ) {

            if ( isset( $_GET['type_aws'] ) && isset( $this->data['ids'] ) && ! empty( $this->data['ids'] ) ) {
                global $wpdb;
                $post__in = implode( ',', array_map( 'absint', $this->data['ids'] ) );
                $query['where'] .= " AND {$wpdb->posts}.ID IN ($post__in)";
            }

            return $query;

        }

        /*
         * Filter products
         */
        public function aws_search_page_filters( $filters ) {

            if ( isset( $_GET['type_aws'] ) && function_exists( 'wcpf_component' ) ) {

                $component_storage = wcpf_component( 'Project/Filter_Component_Storage' );
                $projects = $component_storage ? $component_storage->get_projects() : false;

                if ( $projects ) {

                    foreach ($projects as $project_id => $project ) {

                        $project_components = $project->get_filter_components();

                        $child_filter_components = $project->get_child_filter_components();

                        if ( $child_filter_components ) {
                            foreach ( $child_filter_components as $child_component ) {

                                $url_key = $child_component->get_option('optionKey');
                                $operator = $child_component->get_option('queryType') ? strtoupper( $child_component->get_option('queryType') ) : 'OR';
                                $taxonomy = $this->get_taxonomy( $child_component ) ;
                                $items_source = $child_component->get_option( 'itemsSource' );

                                if ( isset( $_GET[$url_key] ) && $_GET[$url_key] ) {

                                    $param = $_GET[$url_key];
                                    $terms_arr = explode(',', $param );

                                    if ( $taxonomy ) {

                                        if ( isset( $filters['tax'] ) && isset( $filters['tax'][$taxonomy] ) && isset( $filters['tax'][$taxonomy]['terms'] ) ) {

                                            $terms_arr = $filters['tax'][$taxonomy]['terms'];

                                        }

                                        $filters['tax'][$taxonomy] = array(
                                            'terms' => $terms_arr,
                                            'operator' => $operator
                                        );

                                    } elseif ( $items_source === 'stock-status' ) {

                                        $filters['in_status'] = $param === 'in-stock';

                                    }

                                }

                            }
                        }

                    }

                }

            }

            return $filters;

        }

        /*
         * Get taxonomy for current filter component
         */
        private function get_taxonomy( $component ) {
            $items_source = $component->get_option( 'itemsSource' );

            $taxonomy = false;

            if ( 'attribute' === $items_source ) {
                $taxonomy = wc_attribute_taxonomy_name( $component->get_option( 'itemsSourceAttribute' ) );
            } elseif ( 'tag' === $items_source ) {
                $taxonomy = 'product_tag';
            } elseif ( 'category' === $items_source ) {
                $taxonomy = 'product_cat';
            } elseif ( 'taxonomy' === $items_source ) {
                $taxonomy = $component->get_option( 'itemsSourceTaxonomy' );
            }

            return $taxonomy;
        }

    }


endif;

AWS_PFW::instance();