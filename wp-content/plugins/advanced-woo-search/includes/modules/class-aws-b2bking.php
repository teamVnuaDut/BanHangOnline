<?php
/**
 * B2BKing plugin support
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_B2BKing' ) ) :

    /**
     * Class
     */
    class AWS_B2BKing {

        /**
         * Main AWS_B2BKing Instance
         *
         * Ensures only one instance of AWS_B2BKing is loaded or can be loaded.
         *
         * @static
         * @return AWS_B2BKing - Main instance
         */
        protected static $_instance = null;

        /**
         * Main AWS_B2BKing Instance
         *
         * Ensures only one instance of AWS_B2BKing is loaded or can be loaded.
         *
         * @static
         * @return AWS_B2BKing - Main instance
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

            // Hide all products for guests
            if ( ! is_user_logged_in() && get_option('b2bking_guest_access_restriction_setting', 'hide_prices') === 'hide_website') {
                add_filter( 'aws_search_query_string', array( $this, 'sql_hide_results' ), 1 );
                add_filter( 'aws_terms_search_query', array( $this, 'sql_hide_results' ), 1 );
            }

            // Products visibility
            if ( ( intval( get_option( 'b2bking_all_products_visible_all_users_setting', 1 ) ) !== 1 ) ) {
                add_filter( 'aws_search_query_array', array( $this, 'products_filter' ), 1 );
                add_filter( 'aws_terms_search_query', array( $this, 'terms_filter' ), 1, 2 );
            }

        }

        /*
         * Hide all products
         */
        public function sql_hide_results( $sql ) {

            global $wpdb;

            $table_name = $wpdb->prefix . AWS_INDEX_TABLE_NAME;

            $sql = "SELECT * FROM {$table_name} WHERE 1=2";

            return $sql;

        }

        /*
         * Include only visible products
         */
        public function products_filter( $query ) {

            $products_ids = $this->b2bking_get_all_visible_products();

            if ( ! empty( $products_ids ) ) {
                $query['exclude_products'] .= sprintf( ' AND ( id IN ( %s ) )', implode( ',', $products_ids ) );
            }

            return $query;

        }

        public function terms_filter(  $sql, $taxonomy ) {

            if ( array_search( 'product_cat', $taxonomy ) !== false ) {

                $categories_ids = $this->b2bking_get_all_visible_categories();

                if ( ! empty( $categories_ids ) ) {
                    global $wpdb;
                    $sql_terms = sprintf( "  AND $wpdb->term_taxonomy.term_id IN  ( %s )", implode( ',', $categories_ids ) );
                    $sql = str_replace( 'WHERE 1 = 1', 'WHERE 1 = 1 ' . $sql_terms, $sql );
                }

            }

            return $sql;

        }

        /*
         * Get all visible products IDs
         */
        private function b2bking_get_all_visible_products() {

            $allTheIDs = array();

            if (intval(get_option( 'b2bking_all_products_visible_all_users_setting', 1 )) !== 1){

                if ( get_option( 'b2bking_plugin_status_setting', 'b2b' ) !== 'disabled' ){

                    $user_is_b2b = get_user_meta( get_current_user_id(), 'b2bking_b2buser', true );

                    // if user logged in and is b2b
                    if (is_user_logged_in() && ($user_is_b2b === 'yes')){
                        // Get current user's data: group, id, login, etc
                        $currentuserid = get_current_user_id();
                        $account_type = get_user_meta($currentuserid,'b2bking_account_type', true);
                        if ($account_type === 'subaccount'){
                            // for all intents and purposes set current user as the subaccount parent
                            $parent_user_id = get_user_meta($currentuserid, 'b2bking_account_parent', true);
                            $currentuserid = $parent_user_id;
                        }
                        $currentuser = get_user_by('id', $currentuserid);
                        $currentuserlogin = $currentuser -> user_login;
                        $currentusergroupidnr = get_user_meta( $currentuserid, 'b2bking_customergroup', true );
                        // if user is b2c
                    } else if (is_user_logged_in() && ($user_is_b2b !== 'yes')){
                        $currentuserlogin = 'b2c';
                        $currentusergroupidnr = 'b2c';
                    } else {
                        $currentuserlogin = 0;
                        $currentusergroupidnr = 0;
                    }
                    /*
                    *
                    *	There are 2 separate queries that need to be made:
                    * 	1. Query of all Categories visible to the USER AND all Categories visible to the USER'S GROUP
                    *	2. Query of all Products set to Manual visibility mode, visible to the user or the user's group
                    *
                    */

                    // Build Visible Categories for the 1st Query
                    $visiblecategories = array();
                    $hiddencategories = array();

                    $terms = get_terms( array(
                        'taxonomy' => 'product_cat',
                        'fields' => 'ids',
                        'hide_empty' => false
                    ) );

                    foreach ($terms as $term){

                        /*
                        * If category is visible to GROUP OR category is visible to USER
                        * Push category into visible categories array
                        */

                        // first check group
                        $group_meta = get_term_meta( $term, 'b2bking_group_'.$currentusergroupidnr, true );
                        if (intval($group_meta) === 1){
                            array_push($visiblecategories, $term);
                            // else check user
                        } else {
                            $userlistcommas = get_term_meta( $term, 'b2bking_category_users_textarea', true );
                            $userarray = explode(',', $userlistcommas);
                            $visible = 'no';
                            foreach ($userarray as $user){
                                if (trim($user) === $currentuserlogin){
                                    array_push($visiblecategories, $term);
                                    $visible = 'yes';
                                    break;
                                }
                            }
                            if ($visible === 'no'){
                                array_push($hiddencategories, $term);
                            }
                        }
                    }


                    $product_category_visibility_array = array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $visiblecategories,
                        'operator' => 'IN'
                    );

                    // if user has enabled "hidden has priority", override setting
                    if (intval(get_option( 'b2bking_hidden_has_priority_setting', 0 )) === 1){
                        $product_category_visibility_array = array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $hiddencategories,
                            'operator' => 'NOT IN'
                        );
                    }

                    /* Get all items that do not have manual visibility set up */
                    // get all products ids
                    if (intval(get_option( 'b2bking_product_visibility_cache_setting', 0 )) === 1){
                        $items_not_manual_visibility_array = get_transient('b2bking_not_manual_visibility_array');
                    } else {
                        $items_not_manual_visibility_array = false;
                    }

                    if (!$items_not_manual_visibility_array){
                        $all_prods = new WP_Query(array(
                            'posts_per_page' => -1,
                            'post_type' => 'product',
                            'fields' => 'ids'));
                        $all_prod_ids = $all_prods->posts;

                        // get all products with manual visibility ids
                        $all_prods_manual = new WP_Query(array(
                            'posts_per_page' => -1,
                            'post_type' => 'product',
                            'fields' => 'ids',
                            'meta_query'=> array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'b2bking_product_visibility_override',
                                    'value' => 'manual',
                                )
                            )));
                        $all_prod_manual_ids = $all_prods_manual->posts;
                        // get the difference
                        $items_not_manual_visibility_array = array_diff($all_prod_ids,$all_prod_manual_ids);
                        set_transient('b2bking_not_manual_visibility_array', $items_not_manual_visibility_array);
                    }

                    // Build first query
                    $queryAparams = array(
                        'posts_per_page' => -1,
                        'post_type' => 'product',
                        'fields' => 'ids',
                        'tax_query' => array(
                            $product_category_visibility_array
                        ),
                        'post__in' => $items_not_manual_visibility_array,
                    );

                    // Build 2nd query: all manual visibility products with USER OR USER GROUP visibility
                    $queryBparams = array(
                        'posts_per_page' => -1,
                        'post_type' => 'product',
                        'fields' => 'ids',
                        'meta_query'=> array(
                            'relation' => 'AND',
                            array(
                                'relation' => 'OR',
                                array(
                                    'key' => 'b2bking_group_'.$currentusergroupidnr,
                                    'value' => '1'
                                ),
                                array(
                                    'key' => 'b2bking_user_'.$currentuserlogin,
                                    'value' => '1'
                                )
                            ),
                            array(
                                'key' => 'b2bking_product_visibility_override',
                                'value' => 'manual',
                            )
                        ));


                    // if caching is enabled
                    if (intval(get_option( 'b2bking_product_visibility_cache_setting', 0 )) === 1){

                        // cache query results
                        if (!get_transient('b2bking_user_'.get_current_user_id().'_visibility')){
                            $queryA = new WP_Query($queryAparams);
                            $queryB = new WP_Query($queryBparams);
                            // Merge the 2 queries in an IDs array
                            $allTheIDs = array_merge($queryA->posts,$queryB->posts);
                            set_transient('b2bking_user_'.get_current_user_id().'_visibility', $allTheIDs);
                        } else {
                            $allTheIDs = get_transient('b2bking_user_'.get_current_user_id().'_visibility');
                        }

                    } else {
                        $queryA = new WP_Query($queryAparams);
                        $queryB = new WP_Query($queryBparams);
                        // Merge the 2 queries in an IDs array
                        $allTheIDs = array_merge($queryA->posts,$queryB->posts);
                    }


                }
            }

            return $allTheIDs;

        }

        /*
         * Get all visible categories terms IDs
         */
        private function b2bking_get_all_visible_categories() {

            $visiblecategories = array();
            $hiddencategories = array();

            if (intval(get_option( 'b2bking_all_products_visible_all_users_setting', 1 )) !== 1){

                if ( get_option( 'b2bking_plugin_status_setting', 'b2b' ) !== 'disabled' ){

                    if ( $visiblecategories_transient = get_transient('b2bking_user_'.get_current_user_id().'_cat_visibility' ) ) {
                        return $visiblecategories_transient;
                    }

                    $user_is_b2b = get_user_meta( get_current_user_id(), 'b2bking_b2buser', true );

                    // if user logged in and is b2b
                    if (is_user_logged_in() && ($user_is_b2b === 'yes')){
                        // Get current user's data: group, id, login, etc
                        $currentuserid = get_current_user_id();
                        $account_type = get_user_meta($currentuserid,'b2bking_account_type', true);
                        if ($account_type === 'subaccount'){
                            // for all intents and purposes set current user as the subaccount parent
                            $parent_user_id = get_user_meta($currentuserid, 'b2bking_account_parent', true);
                            $currentuserid = $parent_user_id;
                        }
                        $currentuser = get_user_by('id', $currentuserid);
                        $currentuserlogin = $currentuser -> user_login;
                        $currentusergroupidnr = get_user_meta( $currentuserid, 'b2bking_customergroup', true );
                        // if user is b2c
                    } else if (is_user_logged_in() && ($user_is_b2b !== 'yes')){
                        $currentuserlogin = 'b2c';
                        $currentusergroupidnr = 'b2c';
                    } else {
                        $currentuserlogin = 0;
                        $currentusergroupidnr = 0;
                    }

                    $terms = get_terms( array(
                        'taxonomy' => 'product_cat',
                        'fields' => 'ids',
                        'hide_empty' => false
                    ) );

                    foreach ($terms as $term){

                        /*
                        * If category is visible to GROUP OR category is visible to USER
                        * Push category into visible categories array
                        */

                        // first check group
                        $group_meta = get_term_meta( $term, 'b2bking_group_'.$currentusergroupidnr, true );
                        if (intval($group_meta) === 1){
                            array_push($visiblecategories, $term);
                            // else check user
                        } else {
                            $userlistcommas = get_term_meta( $term, 'b2bking_category_users_textarea', true );
                            $userarray = explode(',', $userlistcommas);
                            $visible = 'no';
                            foreach ($userarray as $user){
                                if (trim($user) === $currentuserlogin){
                                    array_push($visiblecategories, $term);
                                    $visible = 'yes';
                                    break;
                                }
                            }
                            if ($visible === 'no'){
                                array_push($hiddencategories, $term);
                            }
                        }
                    }

                    set_transient('b2bking_user_'.get_current_user_id().'_cat_visibility', $visiblecategories, 600 );

                }
            }

            return $visiblecategories;

        }

    }

endif;

AWS_B2BKing::instance();