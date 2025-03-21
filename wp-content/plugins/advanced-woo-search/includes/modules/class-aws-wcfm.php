<?php
/**
 * WCFM - WooCommerce Multivendor Marketplace  plugin support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_WCFM' ) ) :

    /**
     * Class
     */
    class AWS_WCFM {

        /**
         * Main AWS_WCFM Instance
         *
         * Ensures only one instance of AWS_WCFM is loaded or can be loaded.
         *
         * @static
         * @return AWS_WCFM - Main instance
         */
        protected static $_instance = null;

        private $data = array();

        /**
         * Main AWS_WCFM Instance
         *
         * Ensures only one instance of AWS_WCFM is loaded or can be loaded.
         *
         * @static
         * @return AWS_WCFM - Main instance
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
            add_filter( 'aws_excerpt_search_result', array( $this, 'wcfm_excerpt_search_result' ), 1, 3 );
            add_filter( 'aws_searchbox_markup', array( $this, 'wcfm_searchbox_markup' ), 1, 2 );
            add_filter( 'aws_front_data_parameters', array( $this, 'wcfm_front_data_parameters' ), 1 );
            add_filter( 'aws_search_query_array', array( $this, 'wcfm_search_query_array' ), 1 );
            add_filter( 'aws_terms_search_query', array( $this, 'wcfm_terms_search_query' ), 1, 2 );
            add_filter( 'aws_search_tax_results', array( $this, 'wcfm_search_tax_results' ), 1 );
            add_action( 'wp_head', array( $this, 'wp_head' ), 1 );

            // Stores list
            add_filter( 'aws_searchbox_markup', array( $this, 'aws_searchbox_markup' ), 1 );
            add_action( 'wcfmmp_store_lists_before_sidabar', array( $this, 'wcfmmp_store_lists_before_sidabar' ), 1 );
            add_action( 'wcfmmp_store_lists_after_sidebar', array( $this, 'wcfmmp_store_lists_after_sidebar' ), 999 );

        }

        /*
         * Add store name and logo inside search results
         */
        function wcfm_excerpt_search_result( $excerpt, $post_id, $product ) {

            $show_vendor_info = apply_filters( 'show_wcfm_badge', true, $product );

            if ( $show_vendor_info && function_exists( 'wcfm_get_vendor_id_by_post' ) ) {

                $vendor_id = wcfm_get_vendor_id_by_post( $post_id );

                if ( $vendor_id ) {
                    if ( apply_filters( 'wcfmmp_is_allow_sold_by', true, $vendor_id ) && wcfm_vendor_has_capability( $vendor_id, 'sold_by' ) ) {

                        global $WCFM, $WCFMmp;

                        $is_store_offline = get_user_meta( $vendor_id, '_wcfm_store_offline', true );

                        if ( ! $is_store_offline ) {

                            $store_name = wcfm_get_vendor_store_name( absint( $vendor_id ) );
                            $store_url = function_exists('wcfmmp_get_store_url') && $vendor_id ? wcfmmp_get_store_url( $vendor_id ) : '';

                            $logo = '';

                            if ( apply_filters( 'wcfmmp_is_allow_sold_by_logo', true ) ) {
                                $store_logo = wcfm_get_vendor_store_logo_by_vendor( $vendor_id );
                                if ( ! $store_logo ) {
                                    $store_logo = apply_filters( 'wcfmmp_store_default_logo', $WCFM->plugin_url . 'assets/images/wcfmmp-blue.png' );
                                }
                                $logo = '<img style="margin-right:4px;" width="24px" src="' . $store_logo . '" />';
                            }

                            $excerpt .= '<br><a style="margin-top:4px;display:block;" href="' . $store_url . '">' . $logo . $store_name . '</a>';

                        }

                    }
                }

            }

            return $excerpt;

        }

        /*
         * WCFM - WooCommerce Multivendor Marketplace update search page url for vendors shops
         */
        public function wcfm_searchbox_markup( $markup, $params ) {

            $store = $this->get_current_store();

            if ( $store ) {
                $markup = preg_replace( '/action="(.+?)"/i', 'action="' . $store->get_shop_url() . '"', $markup );
            }

            return $markup;

        }

        /*
         * WCFM - WooCommerce Multivendor Marketplace limit search inside vendors shop
         */
        public function wcfm_front_data_parameters( $params ) {

            $store = $this->get_current_store();

            if ( $store ) {
                $params['data-tax'] = 'store:' . $store->get_id();
            }

            return $params;

        }

        /*
         * WCFM - WooCommerce Multivendor Marketplace limit search inside vendoes shop
         */
        public function wcfm_search_query_array( $query ) {

            global $wpdb;

            $vendor_id = false;

            if ( isset( $_REQUEST['aws_tax'] ) && $_REQUEST['aws_tax'] && strpos( $_REQUEST['aws_tax'], 'store:' ) !== false ) {
                $vendor_id = intval( str_replace( 'store:', '', $_REQUEST['aws_tax'] ) );
            } else {
                $store = $this->get_current_store();
                if ( $store ) {
                    $vendor_id = $store->get_id();
                }
            }

            if ( $vendor_id ) {

                $query['search'] .= " AND ( id IN ( SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_author = {$vendor_id} ) )";

            }

            return $query;

        }

        /*
         * WCFM - WooCommerce Multivendor Marketplace limit search inside vendoes shop for taxonomies
         */
        public function wcfm_terms_search_query( $sql, $taxonomy ) {

            global $wpdb, $WCFMmp;

            $store = false;

            if ( isset( $_REQUEST['aws_tax'] ) && $_REQUEST['aws_tax'] && strpos( $_REQUEST['aws_tax'], 'store:' ) !== false ) {
                $vendor_id = intval( str_replace( 'store:', '', $_REQUEST['aws_tax'] ) );
                $store = function_exists( 'wcfmmp_get_store' ) ? wcfmmp_get_store( $vendor_id ) : false;
            } else {
                $store = $this->get_current_store();
            }

            if ( $store ) {
                $all_vendor_tax = array();
                foreach ( $taxonomy as $taxonomy_slug ) {
                    $vendor_tax = $WCFMmp->wcfmmp_vendor->wcfmmp_get_vendor_taxonomy( $vendor_id, $taxonomy_slug );
                    if ( ! empty( $vendor_tax) ) {
                        foreach ( $vendor_tax as $vendor_tax_key => $vendor_tax_i ) {
                            if ( is_array( $vendor_tax_i ) ) {
                                $vendor_tax[$vendor_tax_key] = implode('', array_values($vendor_tax_i) );
                            }
                        }
                        $all_vendor_tax = array_merge( $all_vendor_tax, $vendor_tax );
                    }
                }

                if ( ! empty( $all_vendor_tax ) ) {
                    $sql_terms = "AND $wpdb->term_taxonomy.term_id IN ( " . implode( ',', $all_vendor_tax ) . " )";
                    $sql = str_replace( 'WHERE 1 = 1', 'WHERE 1 = 1 ' . $sql_terms, $sql );
                } else {
                    $sql = '';
                }

            }

            return $sql;

        }

        /*
         * WCFM - Update links for taxonomies inside vendors store
         */
        public function wcfm_search_tax_results( $result_array ) {

            $store = false;
            if ( isset( $_REQUEST['aws_tax'] ) && $_REQUEST['aws_tax'] && strpos( $_REQUEST['aws_tax'], 'store:' ) !== false ) {
                $vendor_id = intval( str_replace( 'store:', '', $_REQUEST['aws_tax'] ) );
                $store = function_exists( 'wcfmmp_get_store' ) ? wcfmmp_get_store( $vendor_id ) : false;
            } else {
                $store = $this->get_current_store();
            }

            if ( $store && $result_array ) {
                foreach ( $result_array as $tax_name => $items ) {
                    $url_base = ( $tax_name === 'product_cat' ) ? 'category' : 'tax-' . $tax_name;
                    foreach ( $items as $item_key => $item ) {
                        $result_array[$tax_name][$item_key]['link'] = $store->get_shop_url() . $url_base . '/' . $item['slug'];
                        $result_array[$tax_name][$item_key]['count'] = '';
                    }
                }
            }

            return $result_array;

        }

        /*
         * Limit search inside vendor shop page
         */
        function wp_head() {

            $store = $this->get_current_store();
            if ( ! $store ) {
                return;
            }

            $form_action = AWS_Helpers::get_search_url();

            ?>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let $awsForms = jQuery(".aws-container");
                    if ( $awsForms.length > 0 ) {
                        $awsForms.each(function( index ) {
                            if ( ! jQuery(this).closest("#wcfmmp-store").length > 0 && ! jQuery(this).closest("#wcfmmp-store-content").length > 0 ) {
                                jQuery(this).find('form').attr('action', '<?php echo $form_action; ?>');
                                jQuery(this).data('tax', '');
                            }
                        });
                    }
                });
            </script>

        <?php }

        /*
         * Search form inside stores list page
         */
        public function aws_searchbox_markup( $markup ) {
            if ( isset( $this->data['is_stores_sidebar'] ) && $this->data['is_stores_sidebar'] ) {
                $markup = str_replace( '<form', '<div', $markup );
                $markup = str_replace( '</form>', '</div>', $markup );
            }
            return $markup;
        }
        public function wcfmmp_store_lists_before_sidabar() {
            $this->data['is_stores_sidebar'] = true;
        }
        public function wcfmmp_store_lists_after_sidebar() {
            $this->data['is_stores_sidebar'] = false;
        }

        /*
         * Get current store object
         */
        private function get_current_store() {

            $store = false;

            if ( function_exists('wcfmmp_is_store_page') && function_exists('wcfm_get_option') && wcfmmp_is_store_page() ) {

                $wcfm_store_url  = wcfm_get_option( 'wcfm_store_url', 'store' );
                $wcfm_store_name = apply_filters( 'wcfmmp_store_query_var', get_query_var( $wcfm_store_url ) );

                if ( $wcfm_store_name ) {
                    $seller_info = get_user_by( 'slug', $wcfm_store_name );
                    if ( $seller_info && function_exists( 'wcfmmp_get_store' ) ) {
                        $store_user = wcfmmp_get_store( $seller_info->ID );
                        if ( $store_user ) {
                            $store = $store_user;
                        }
                    }
                }

            }

            return $store;

        }

    }

endif;