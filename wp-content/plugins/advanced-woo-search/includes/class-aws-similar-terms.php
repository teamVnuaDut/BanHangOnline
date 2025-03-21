<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Similar_Terms' ) ) :

    /**
     * Class for misspellings fix
     */
    class AWS_Similar_Terms {

        /**
         * @var AWS_Similar_Terms Search data
         */
        private $data = array();

        /**
         * @var AWS_Similar_Terms Fuzzy search parameters
         */
        private $fuzzy_params = array();

        /**
         * @var AWS_Similar_Terms Search terms array
         */
        private $search_terms = array();

        /**
         * Constructor
         */
        public function __construct( $data ) {

            $this->data = $data;

            $this->search_terms = isset( $this->data['search_terms'] ) ? $this->data['search_terms'] : array();

            $fuzzy_params = array(
                'min_terms_length' => 3,
                'term_like_prefix' => 2,
                'max_similar_terms' => 100,
                'min_distance' => 2,
                'allow_numeric' => false,
            );

            /**
             * Filter fuzzy search related parameters
             * @since 3.05
             * @param array $fuzzy_params Array of fuzzy search parameters
             * @param array $this->data Array of search parameters
             */
            $this->fuzzy_params = apply_filters( 'aws_fuzzy_params', $fuzzy_params, $this->data );

        }

        /*
         * Get similat terms
         */
        public function get_similar_terms() {

            global $wpdb;

            $new_terms = array();
            $new_terms['all'] = array();
            $and_search_groupds = array();

            foreach ( $this->search_terms as $search_term ) {

                if ( strlen( $search_term ) > $this->fuzzy_params['min_terms_length'] ) {

                    if ( ! $this->fuzzy_params['allow_numeric'] && is_numeric( $search_term ) ) {
                        continue;
                    }

                    // find similar terms inside index table
                    $matches = $this->get_all_similar( $search_term );

                    $top_distance = 10;
                    $temp_matches = array();

                    if ( $matches ) {

                        $distances = array();
                        foreach ( $matches as $key => $match ) {

                            $distance = levenshtein( $match['term'], $search_term );

                            if ( $distance <= $this->fuzzy_params['min_distance'] ) {

                                if ( $distance < $top_distance ) {
                                    $top_distance = $distance;
                                    $temp_matches = array();
                                    $temp_matches[] = $match['term'];
                                } elseif ( $distance === $top_distance ) {
                                    $temp_matches[] = $match['term'];
                                }

                            }

                        }

                    }

                    if ( ! empty( $temp_matches ) ) {
                        $new_terms['pairs'][] = array(
                            'old' => $search_term,
                            'new' => $temp_matches
                        );
                    }

                    $new_terms['all'] = array_merge( $new_terms['all'], $temp_matches );

                }

            }

            return $new_terms;

        }

        /*
         * Perform sql query for index table to find all similar terms
         */
        public function get_all_similar( $search_term ) {

            global $wpdb;

            $query_sources = '';
            if ( isset( $this->data['search_in'] ) && $this->data['search_in'] ) {
                $search_in_arr_string = '';
                foreach ( $this->data['search_in'] as $s_source ) {
                    $search_in_arr_string .= "'" . $s_source . "',";
                }
                $search_in_arr_string = rtrim( $search_in_arr_string, "," );
                $query_sources = sprintf( ' AND term_source IN ( %s )', $search_in_arr_string );
            }

            $query_stock = isset( $this->data['query_params'] ) && isset( $this->data['query_params']['stock'] ) ? $this->data['query_params']['stock'] : '';
            $query_visibility = isset( $this->data['query_params'] ) && isset( $this->data['query_params']['visibility'] ) ? $this->data['query_params']['visibility'] : '';
            $query_exclude_products = isset( $this->data['query_params'] ) && isset( $this->data['query_params']['exclude_products'] ) ? $this->data['query_params']['exclude_products'] : '';
            $query_lang = isset( $this->data['query_params'] ) && isset( $this->data['query_params']['lang'] ) ? $this->data['query_params']['lang'] : '';

            $keyword_like = $wpdb->esc_like( substr( $search_term, 0, $this->fuzzy_params['term_like_prefix'] ) ) . "%";

            $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            $sql = "SELECT term, count
                    FROM
                        {$table_name}
                    WHERE
                        term LIKE '{$keyword_like}'
                        {$query_sources}
                        {$query_stock}
                        {$query_visibility}
                        {$query_exclude_products}
                        {$query_lang}
                    GROUP BY term
                    ORDER BY count DESC
                     LIMIT 0, {$this->fuzzy_params['max_similar_terms']}
                ";

            return $wpdb->get_results( $sql, ARRAY_A );

        }

    }

endif;
