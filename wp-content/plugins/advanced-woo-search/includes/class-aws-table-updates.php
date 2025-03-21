<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AWS_Table_Updates' ) ) :

    /**
     * Class for pro plugin updates
     */
    class AWS_Table_Updates {

        /**
         * Plugin index table version
         * @var string
         */
        public $index_version;

        /*
         * Initialize a new instance of the WordPress license class
         */
        public function __construct() {

            $this->index_version = AWS()->option_vars->get_index_table_version();

        }

        /*
         * Check if we are inside reindex process
         */
        private function is_reindexing() {
            return isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'aws-reindex';
        }

        /**
         * Get table rows format
         * @return string
         */
        public function get_table_rows() {

            $rows = "(%d, %s, %s, %s, %d, %d, %d, %d, %s, %s)";

            if ( ( $this->index_version && version_compare( $this->index_version, '3.21', '>=' ) ) || $this->is_reindexing() ) {
                $rows = "(%d, %s, %s, %d, %d, %d, %d, %d, %d, %s)";
            }

            return $rows;

        }

        /**
         * Get numeric valume of product type
         * @param string $type Product type
         * @return string|integer
         */
        public function get_product_type_code( $type ) {

            $type_code = $type;

            $codes = array(
                'product' => 0,
                'var'     => 1,
                'child'   => 2
            );

            if ( ( ( $this->index_version && version_compare( $this->index_version, '3.21', '>=' ) ) || $this->is_reindexing() ) && array_key_exists( $type, $codes ) ) {
                $type_code = $codes[$type];
            }

            return $type_code;

        }

        /**
         * Get numeric valume of product visibility
         * @param string $visibility Product visibility
         * @return string|integer
         */
         public function get_visibility_code( $visibility ) {

            $visibility_code = $visibility;

            $codes = array(
                'visible' => 1,
                'search'  => 2,
                'catalog' => 3,
                'hidden'  => 0
            );

            if ( ( ( $this->index_version && version_compare( $this->index_version, '3.21', '>=' ) ) || $this->is_reindexing() ) && array_key_exists( $visibility, $codes ) ) {
                $visibility_code = $codes[$visibility];
            }

            return $visibility_code;

        }

    }

endif;