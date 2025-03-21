<?php
/**
 * AWS plugin integrations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'AWS_Integrations' ) ) :

    /**
     * Class for main plugin functions
     */
    class AWS_Integrations {

        private $data = array();

        /**
         * @var AWS_Integrations Current theme name
         */
        private $current_theme = '';

        /**
         * @var AWS_Integrations Child theme name
         */
        private $child_theme = '';

        /**
         * @var AWS_Integrations Active plugins arrray
         */
        public $active_plugins = array();

        /**
         * @var AWS_Integrations The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Integrations Instance
         *
         * Ensures only one instance of AWS_Integrations is loaded or can be loaded.
         *
         * @static
         * @return AWS_Integrations - Main instance
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

            $theme = function_exists( 'wp_get_theme' ) ? wp_get_theme() : false;

            if ( $theme ) {
                $this->current_theme = $theme->get( 'Name' );
                $this->child_theme = $theme->get( 'Name' );
                if ( $theme->parent() ) {
                    $this->current_theme = $theme->parent()->get( 'Name' );
                }
            }

            $active_plugins = get_option( 'active_plugins', array() );

            if ( is_multisite() ) {
                $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                $active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
            }

            $this->active_plugins = $active_plugins;

            $this->includes();

            //add_action('woocommerce_product_query', array( $this, 'woocommerce_product_query' ) );

            if ( class_exists( 'BM' ) ) {
                add_action( 'aws_search_start', array( $this, 'b2b_set_filter' ) );
            }

            if ( function_exists( 'dfrapi_currency_code_to_sign' ) ) {
                add_filter( 'woocommerce_currency_symbol', array( $this, 'dfrapi_set_currency_symbol_filter' ), 10, 2 );
            }

            // Maya shop theme
            if ( defined( 'YIW_THEME_PATH' ) ) {
                add_action( 'wp_head', array( $this, 'myashop_head_action' ) );
            }

            add_filter( 'aws_terms_exclude_product_cat', array( $this, 'filter_protected_cats_term_exclude' ) );
            add_filter( 'aws_exclude_products', array( $this, 'filter_products_exclude' ) );

            // Seamless integration
            if ( AWS()->get_settings( 'seamless' ) === 'true' ) {

                add_filter( 'aws_js_seamless_selectors', array( $this, 'js_seamless_selectors' ) );

                // Ocean wp theme
                if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
                    add_action( 'wp_head', array( $this, 'oceanwp_head_action' ) );
                }

                // TwentyTwenty theme
                if (  function_exists( 'twentytwenty_theme_support' ) ) {
                    add_action( 'wp_head', array( $this, 'twenty_twenty_head_action' ) );
                }

                if ( 'Jupiter' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'jupiter_head_action' ) );
                }

                if ( 'Storefront' === $this->current_theme ) {
                    add_action( 'wp_footer', array( $this, 'storefront_footer_action' ) );
                }

                if ( 'Elessi Theme' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'elessi_head_action' ) );
                }

                if ( 'Walker' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'walker_head_action' ) );
                }

                if ( 'Porto' === $this->current_theme ) {
                    add_filter( 'porto_search_form_content', array( $this, 'porto_search_form_content_filter' ) );
                    add_action( 'wp_head', array( $this, 'porto_head_action' ) );
                }

                if ( 'BoxShop' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'boxshop_head_action' ) );
                }

                if ( 'Aurum' === $this->current_theme ) {
                    add_filter( 'aurum_show_search_field_on_mobile', '__return_false' );
                    add_filter( 'wp_nav_menu', array( $this, 'aurum_mobile_menu' ), 10, 2 );
                    add_filter( 'aws_js_seamless_searchbox_markup', array( $this, 'aurum_seamless_searchbox_markup' ), 1 );
                    add_action( 'wp_head',  array( $this, 'aurum_wp_head' ) );
                }

                if ( 'Basel' === $this->current_theme ) {
                    add_filter( 'aws_js_seamless_searchbox_markup', array( $this, 'basel_seamless_searchbox_markup' ), 1 );
                    add_action( 'wp_head',  array( $this, 'basel_wp_head' ) );
                }

                if ( 'Woostify' === $this->current_theme ) {
                    add_filter( 'aws_searchbox_markup', array( $this, 'woostify_aws_searchbox_markup' ), 1 );
                }

                if ( 'Fury' === $this->current_theme ) {
                    add_filter( 'aws_searchbox_markup', array( $this, 'fury_searchbox_markup' ) );
                    add_action( 'wp_head',  array( $this, 'fury_wp_head' ) );
                }

                if ( 'Bazar' === $this->current_theme ) {
                    add_action( 'yit_header-cart-search_after', array( $this, 'bazar_add_header_form' ) );
                    add_action( 'wp_head', array( $this, 'bazar_wp_head' ) );
                }

                if ( 'Claue' === $this->current_theme ) {
                    add_filter( 'jas_claue_header', array( $this, 'claue_header' ) );
                    add_action( 'wp_head', array( $this, 'claue_wp_head' ) );
                }

                if ( 'Salient' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'salient_wp_head' ) );
                }

                if ( 'Royal' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'royal_wp_head' ) );
                }

                if ( 'WR Nitro' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'wr_nitro_wp_head' ) );
                }

                if ( 'Botiga' === $this->current_theme ) {
                    add_filter( 'aws_searchbox_markup', array( $this, 'botiga_aws_searchbox_markup' ) );
                    add_action( 'wp_head', array( $this, 'botiga_wp_head' ) );
                }

                if ( 'Rey' === $this->current_theme ) {
                    add_action( 'reycore/search_panel/after_search_form', array( $this, 'rey_search_panel' ) );
                    add_action( 'wp_head', array( $this, 'rey_wp_head' ) );
                }

                if ( 'Orchid Store' === $this->current_theme || function_exists('orchid_store_setup')  ) {
                    add_action( 'wp_head', array( $this, 'orchid_store_wp_head' ) );
                }

                if ( 'Betheme' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'betheme_wp_head' ) );
                }

                if ( 'Gecko' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'gecko_wp_head' ) );
                    add_filter( 'aws_js_seamless_searchbox_markup', array( $this, 'gecko_seamless_searchbox_markup' ), 1 );
                }

                if ( 'Savoy' === $this->current_theme ) {
                    add_filter( 'aws_js_seamless_searchbox_markup', array( $this, 'savoy_seamless_searchbox_markup' ), 1 );
                }

                if ( 'Vandana Lite' === $this->current_theme ) {
                    add_filter( 'aws_searchbox_markup', array( $this, 'vandana_searchbox_markup' ), 1 );
                    add_action( 'wp_head', array( $this, 'vandana_wp_head' ) );
                }

                if ( 'Pustaka' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'pustaka_wp_head' ) );
                }

                if ( 'XStore' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'xstore_wp_head' ) );
                }

                if ( 'Blocksy' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'blocksy_wp_head' ) );
                }

                if ( 'Kapee' === $this->current_theme ) {
                    add_action( 'kapee_get_template_before', array( $this, 'kapee_get_template_before' ), 10, 3 );
                }

                if ( 'Hestia' === $this->current_theme ) {
                    add_filter( 'hestia_after_primary_navigation_addons', array( $this, 'hestia_after_primary_navigation_addons' ), 1 );
                    add_action( 'wp_enqueue_scripts', array( $this, 'hestia_wp_enqueue_scripts' ) );
                }

                if ( 'Open Shop' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'open_shop_wp_head' ) );
                }

                if ( 'Zephyr' === $this->current_theme ) {
                    add_action( 'wp_head', array( $this, 'zephyr_wp_head' ) );
                }

                if ( 'Shoptimizer' === $this->current_theme ) {
                    add_action( 'wp_enqueue_scripts', array( $this, 'shoptimizer_wp_enqueue_scripts' ) );
                }

                if ( 'Hitek' === $this->current_theme ) {
                    add_action( 'xts_after_search_wrapper', array( $this, 'xts_after_search_wrapper' ) );
                }

                if ( 'Oxygen' === $this->current_theme ) {
                    add_action( 'wp_before_load_template', array( $this, 'oxygen_wp_before_load_template' ) );
                    add_action( 'wp_after_load_template', array( $this, 'oxygen_wp_after_load_template' ) );
                }

                // WP Bottom Menu
                if ( defined( 'WP_BOTTOM_MENU_VERSION' ) ) {
                    add_action( 'wp_head', array( $this, 'wp_bottom_menu_wp_head' ) );
                }

                // Advance Product Search by themehunk
                if ( class_exists( 'TH_Advance_Product_Search' ) ) {
                    add_filter( 'thaps_form_html', array( $this, 'aps_thaps_form_html' ) );
                }

            }

            add_action( 'wp_head', array( $this, 'head_js_integration' ) );

            // Search Exclude plugin
            if ( class_exists( 'SearchExclude' ) ) {
                add_filter( 'aws_index_product_ids', array( $this, 'search_exclude_filter' ) );
            }

            // WooCommerce Product Table plugin
            if ( class_exists( 'WC_Product_Table_Plugin' ) ) {
                add_filter( 'wc_product_table_data_config', array( $this, 'wc_product_table_data_config' ) );
                add_filter( 'aws_posts_per_page', array( $this, 'wc_product_table_posts_per_page' ) );
            }

            // WP all import finish
            //add_action( 'pmxi_after_xml_import', array( $this, 'pmxi_after_xml_import' ) );

            // Product Sort and Display for WooCommerce plugin
            if ( defined( 'WC_PSAD_NAME' ) ) {
                add_filter( "option_psad_shop_page_enable", array( $this, 'psad_filter' ) );
            }

            if ( 'Electro' === $this->current_theme ) {
                add_filter( 'aws_searchbox_markup', array( $this, 'electro_searchbox_markup' ), 1, 2 );
            }

            if ( 'Elessi Theme' === $this->current_theme ) {
                add_action( 'wp_enqueue_scripts', array( $this,  'elessi_wp_enqueue_scripts' ), 9999999 );
                add_action( 'aws_search_page_filters', array( $this,  'elessi_aws_search_page_filters' ), 1 );
                add_filter( 'woocommerce_get_filtered_term_product_counts_query', array( $this, 'elessi_woocommerce_get_filtered_term_product_counts_query' ), 9999999 );
            }

            // Product Visibility by User Role for WooCommerce plugin
            if ( class_exists( 'Alg_WC_PVBUR' ) ) {
                add_filter( 'aws_search_results_products', array( $this, 'pvbur_aws_search_results_products' ), 1 );
            }

            // ATUM Inventory Management for WooCommerce plugin ( Product level addon )
            if ( class_exists( 'AtumProductLevelsAddon' ) ) {
                add_filter( 'aws_indexed_data', array( $this, 'atum_index_data' ), 10, 2 );
            }

            // Popups for Divi plugin
            if ( defined( 'DIVI_POPUP_PLUGIN' ) ) {
                add_action( 'wp_enqueue_scripts', array( $this, 'divi_popups_enqueue_scripts' ), 999 );
            }

            // WooCommerce Catalog Visibility Options plugin
            if ( class_exists( 'WC_Catalog_Restrictions_Query' ) ) {
                add_filter( 'aws_exclude_products', array( $this, 'wcvo_exclude_products' ), 1 );
            }

            // Dynamic Content for Elementor plugin
            if ( function_exists( 'dce_load' ) ) {
                add_action( 'wp_footer', array( $this, 'dce_scripts' ) );
            }

            // WOOCS - WooCommerce Currency Switcher: fix ajax pricing loading
            if ( class_exists( 'WOOCS_STARTER' ) ) {
                add_filter( 'aws_search_pre_filter_products', array( $this, 'woocs_pricing_ajax_fix' ) );
            }

            //  Deals for WooCommerce
            if ( function_exists( 'DFW' ) ) {
                add_filter( 'aws_search_pre_filter_products', array( $this, 'dfm_search_pre_filter_products' ) );
            }

            // WooCommerce Product Search
            if ( defined('WOO_PS_PLUGIN_VERSION') ) {
                add_action( 'aws_search_page_filters', array( $this,  'wps_aws_search_page_filters' ), 1 );
            }

            // Yoast Premium
            if ( in_array( 'wordpress-seo-premium/wp-seo-premium.php', $this->active_plugins ) ) {
                add_filter( 'Yoast\WP\SEO\allowlist_permalink_vars', array( $this, 'yoast_allowlist_permalink_vars' ) );
            }

        }
        
        /**
         * Include files
         */
        public function includes() {

            // WP-CLI
            if ( defined( 'WP_CLI' ) && WP_CLI ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-wp-cli.php' );
            }

            // Getenberg block
            if ( function_exists( 'register_block_type' ) ) {
                include_once( AWS_DIR . '/includes/modules/gutenberg/class-aws-gutenberg-init.php' );
            }

            // Elementor plugin widget
            if ( defined( 'ELEMENTOR_VERSION' ) || defined( 'ELEMENTOR_PRO_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/elementor-widget/class-elementor-aws-init.php' );
            }

            // Divi module
            if ( defined( 'ET_BUILDER_PLUGIN_DIR' ) || function_exists( 'et_setup_theme' ) ) {
                include_once( AWS_DIR . '/includes/modules/divi/class-aws-divi.php' );
                include_once( AWS_DIR . '/includes/modules/divi/class-divi-aws-module.php' );
            }

            // Beaver builder module
            if ( class_exists( 'FLBuilder' ) ) {
                include_once( AWS_DIR . '/includes/modules/bb-aws-search/class-aws-bb-module.php' );
            }

            // WCFM - WooCommerce Multivendor Marketplace
            if ( class_exists( 'WCFMmp' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-wcfm.php' );
                AWS_WCFM::instance();
            }

            // WOOF - WooCommerce Products Filter
            if ( defined( 'WOOF_PLUGIN_NAME' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-woof-filter.php' );
            }

            // Ultimate Member plugin hide certain products
            if ( class_exists( 'UM_Functions' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-um.php' );
            }

            // Wholesale plugin
            if ( class_exists( 'WooCommerceWholeSalePrices' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-wholesale.php' );
            }

            // B2BKing plugin
            if ( class_exists( 'B2bking' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-b2bking.php' );
            }

            // Advanced Woo Labels plugin
            if ( function_exists( 'AWL' ) || function_exists( 'AWL_PRO' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-awl.php' );
            }

            // Products Visibility by User Roles plugin
            if ( class_exists( 'Addify_Products_Visibility' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-products-visibility.php' );
            }

            // WooCommerce Products Visibility
            if ( class_exists('WooCommerce_Products_Visibility') ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-wcpv.php' );
            }

            // WPBakery plugin
            if ( defined( 'WPB_VC_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-wpbakery.php' );
            }

            // WPML plugin
            if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-wpml.php' );
            }

            // WooCommerce Product Filter by WooBeWoo
            if ( defined( 'WPF_PLUG_NAME' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-woobewoo-filters.php' );
            }

            // FacetWP plugin
            if ( class_exists( 'FacetWP' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-facetwp.php' );
            }

            if ( class_exists( 'WC_PPC_Util' ) || class_exists( '\Barn2\Plugin\WC_Protected_Categories\Util' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-barn2-protected-categories.php' );
            }

            // WooCommerce Product Table plugin by Barn2
            if ( class_exists( 'Barn2\Plugin\WC_Product_Table\Product_Table' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-barn2-product-table.php' );
            }

            // WC Marketplace - https://wc-marketplace.com/
            if ( defined( 'WCMp_PLUGIN_VERSION' ) || defined( 'MVX_PLUGIN_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-multivendorx.php' );
            }

            // Custom Product Tabs for WooCommerce plugin
            if ( class_exists('YIKES_Custom_Product_Tabs') || class_exists( 'YIKES_Custom_Product_Tabs_Pro' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-custom-tabs.php' );
            }

            // Perfect Brands for WooCommerce
            if ( defined( 'PWB_PLUGIN_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-pwb.php' );
            }

            // Astra theme
            if ( 'Astra' === $this->current_theme ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-astra.php' );
            }

            // Bricks Builder theme
            if ( class_exists( '\Bricks\Elements' ) ) {
                add_action( 'init', function() {
                    \Bricks\Elements::register_element( AWS_DIR . '/includes/modules/class-aws-bricks-builder.php' );
                }, 11 );
            }

            // Avada theme
            if ( class_exists( 'Avada' ) || 'Avada' === $this->current_theme ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-avada.php' );
            }

            // Flatsome theme
            if ( 'Flatsome' === $this->current_theme ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-flatsome.php' );
            }

            // GeneratePress theme
            if ( 'GeneratePress' === $this->current_theme ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-generatepress.php' );
            }

            // The7 theme
            if ( 'The7' === $this->current_theme ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-the7.php' );
            }

            // Woodmart theme
            if ( 'Woodmart' === $this->current_theme ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-woodmart.php' );
            }

            // Product Filters for WooCommerce
            if ( defined( 'WC_PRODUCT_FILTER_VERSION' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-pfw.php' );
            }

            // WooCommerce Product Bundles
            if ( class_exists( 'WC_Bundles' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-product-bundles.php' );
            }

            // Shopengine plugin
            if ( class_exists( 'ShopEngine' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-shopengine.php' );
            }

            // BeRocket WooCommerce AJAX Products Filter
            if ( defined( 'BeRocket_AJAX_filters_version' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-berocket-filters.php' );
            }

            // WooCommerce Memberships
            if ( class_exists( 'WC_Memberships' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-woo-memberships.php' );
            }

            // WooCommerce Show Single Variations by Iconic
            if ( class_exists('Iconic_WSSV') || class_exists('JCK_WSSV') ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-single-variations.php' );
            }

            // YITH WooCommerce Ajax Product Filter
            if ( defined( 'YITH_WCAN' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-yith-wcan.php' );
            }

            // Filter Everything
            if ( class_exists( 'FlrtFilter' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-filter-everything.php' );
            }

            //  EAN for WooCommerce by WPFactory
            if ( class_exists( 'Alg_WC_EAN' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-alg-wc-ean.php' );
            }

            // Breakdance builder
            if ( defined( 'BREAKDANCE_PLUGIN_URL' ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-breakdance.php' );
            }

            // Crocoblock plugins
            if ( in_array( 'jet-blocks/jet-blocks.php', $this->active_plugins ) || in_array( 'jet-elements/jet-elements.php', $this->active_plugins ) || in_array( 'jet-woo-builder/jet-woo-builder.php', $this->active_plugins ) ) {
                include_once( AWS_DIR . '/includes/modules/class-aws-crocoblock.php' );
            }

        }

        /*
         * B2B market plugin
         */
        public function b2b_set_filter() {

            $args = array(
                'posts_per_page' => - 1,
                'post_type'      => 'customer_groups',
                'post_status'    => 'publish',
            );

            $posts           = get_posts( $args );
            $customer_groups = array();
            $user_role       = '';

            foreach ( $posts as $customer_group ) {
                $customer_groups[$customer_group->post_name] = $customer_group->ID;
            }

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            } else {
                $guest_slugs = array( 'Gast', 'Gäste', 'Guest', 'Guests', 'gast', 'gäste', 'guest', 'guests' );
                foreach( $customer_groups as $customer_group_key => $customer_group_id ) {
                    if ( in_array( $customer_group_key, $guest_slugs ) ) {
                        $user_role = $customer_group_key;
                    }
                }
            }

            if ( $user_role ) {

                if ( isset( $customer_groups[$user_role] ) ) {
                    $curret_customer_group_id = $customer_groups[$user_role];

                    $whitelist = get_post_meta( $curret_customer_group_id, 'bm_conditional_all_products', true );

                    if ( $whitelist && $whitelist === 'off' ) {

                        $products_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_products', false );
                        $cats_to_exclude = get_post_meta( $curret_customer_group_id, 'bm_conditional_categories', false );

                        if ( $products_to_exclude && ! empty( $products_to_exclude ) ) {

                            foreach( $products_to_exclude as $product_to_exclude ) {
                                $this->data['exclude_products'][] = trim( $product_to_exclude, ',' );
                            }

                        }

                        if ( $cats_to_exclude && ! empty( $cats_to_exclude ) ) {

                            foreach( $cats_to_exclude as $cat_to_exclude ) {
                                $this->data['exclude_categories'][] = trim( $cat_to_exclude, ',' );
                            }

                        }

                    }

                }

            }

        }

        /*
         * Datafeedr WooCommerce Importer plugin
         */
        public function dfrapi_set_currency_symbol_filter( $currency_symbol, $currency ) {

            global $product;
            if ( ! is_object( $product ) || ! isset( $product ) ) {
                return $currency_symbol;
            }
            $fields = get_post_meta( $product->get_id(), '_dfrps_product', true );
            if ( empty( $fields ) ) {
                return $currency_symbol;
            }
            if ( ! isset( $fields['currency'] ) ) {
                return $currency_symbol;
            }
            $currency_symbol = dfrapi_currency_code_to_sign( $fields['currency'] );
            return $currency_symbol;

        }

        /*
         * Maya shop theme support
         */
        public function myashop_head_action() { ?>

            <style>
                #header .aws-container {
                    margin: 0;
                    position: absolute;
                    right: 0;
                    bottom: 85px;
                }

                @media only screen and (max-width: 960px) {
                    #header .aws-container {
                        bottom: 118px !important;
                        right: 10px !important;
                    }
                }

                @media only screen and (max-width: 600px) {
                    #header .aws-container {
                        position: relative !important;
                        bottom: auto !important;
                        right: auto !important;
                        display: inline-block !important;
                        margin-top: 20px !important;
                        margin-bottom: 20px !important;
                    }
                }
            </style>

        <?php }

        /*
         * Ocean wp theme
         */
        public function oceanwp_head_action() { ?>

            <style>
                .oceanwp-theme #searchform-header-replace .aws-container {
                    padding-right: 45px;
                    padding-top: 0;
                }
                .oceanwp-theme #searchform-header-replace .aws-container .aws-search-form .aws-form-btn {
                    background: transparent;
                    border: none;
                }
                .oceanwp-theme #searchform-overlay .aws-container,
                .oceanwp-theme #icon-searchform-overlay .aws-container {
                    position: absolute;
                    top: 50%;
                    left: 0;
                    margin-top: -33px;
                    width: 100%;
                    text-align: center;
                }
                .oceanwp-theme #searchform-overlay .aws-container form,
                .oceanwp-theme #icon-searchform-overlay .aws-container form {
                    position: static;
                }
                .oceanwp-theme #searchform-overlay a.search-overlay-close,
                .oceanwp-theme #icon-searchform-overlay a.search-overlay-close {
                    top: -100px;
                }
                .oceanwp-theme #searchform-overlay .aws-container .aws-search-form,
                .oceanwp-theme #icon-searchform-overlay .aws-container .aws-search-form,
                .oceanwp-theme #searchform-overlay .aws-container .aws-search-form .aws-form-btn,
                .oceanwp-theme #icon-searchform-overlay .aws-container .aws-search-form .aws-form-btn {
                    background: transparent;
                }
                .oceanwp-theme #searchform-overlay .aws-container .aws-search-form .aws-form-btn,
                .oceanwp-theme #icon-searchform-overlay .aws-container .aws-search-form .aws-form-btn {
                    border: none;
                }
                #sidr .aws-container {
                    margin: 30px 20px 0;
                }
                #medium-searchform .aws-container .aws-search-form,
                #vertical-searchform .aws-container .aws-search-form {
                    background: #f5f5f5;
                }
                #medium-searchform .aws-container .aws-search-form .aws-search-field {
                    max-width: 100%;
                }
                #medium-searchform .aws-container .aws-search-form .aws-form-btn,
                #vertical-searchform .aws-container .aws-search-form .aws-form-btn{
                    background: #f5f5f5;
                    border: none;
                }
            </style>

            <script>

                window.addEventListener('load', function() {

                    window.setTimeout(function(){
                        var formOverlay = document.querySelectorAll("#searchform-overlay form, #icon-searchform-overlay form");
                        if ( formOverlay ) {
                            for (var i = 0; i < formOverlay.length; i++) {
                                formOverlay[i].innerHTML += '<a href="#" class="search-overlay-close"><span></span></a>';
                            }
                        }
                    }, 300);

                    jQuery(document).on( 'click', 'a.search-overlay-close', function (e) {

                        jQuery( '#searchform-overlay, #icon-searchform-overlay' ).removeClass( 'active' );
                        jQuery( '#searchform-overlay, #icon-searchform-overlay' ).fadeOut( 200 );

                        setTimeout( function() {
                            jQuery( 'html' ).css( 'overflow', 'visible' );
                        }, 400);

                        jQuery( '.aws-search-result' ).hide();

                    } );

                }, false);

            </script>

        <?php }

        /*
         * Twenty Twenty theme
         */
        public function twenty_twenty_head_action() { ?>

            <style>

                .search-modal .aws-container {
                    width: 100%;
                    margin: 20px 0;
                }

            </style>

            <script>

                window.addEventListener('load', function() {

                    var awsSearch = document.querySelectorAll("#site-header .search-toggle");
                    if ( awsSearch ) {
                        for (var i = 0; i < awsSearch.length; i++) {
                            awsSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".aws-container .aws-search-field").focus();
                                    jQuery( '.aws-search-result' ).hide();
                                }, 100);
                            }, false);
                        }
                    }

                    var searchToggler = document.querySelectorAll('[data-modal-target-string=".search-modal"]');
                    if ( searchToggler ) {
                        for (var i = 0; i < searchToggler.length; i++) {
                            searchToggler[i].addEventListener('toggled', function() {
                                jQuery( '.aws-search-result' ).hide();
                            }, false);
                        }
                    }

                }, false);

            </script>

        <?php }

        /*
         * Jupiter theme
         */
        public function jupiter_head_action() { ?>

            <style>

                .mk-fullscreen-search-overlay .aws-container .aws-search-form {
                    height: 60px;
                }

                .mk-fullscreen-search-overlay .aws-container .aws-search-field {
                    width: 800px;
                    background-color: transparent;
                    box-shadow: 0 3px 0 0 rgba(255,255,255,.1);
                    border: none;
                    font-size: 35px;
                    color: #fff;
                    padding-bottom: 20px;
                    text-align: center;
                }

                .mk-fullscreen-search-overlay .aws-container .aws-search-form .aws-form-btn {
                    background-color: transparent;
                    border: none;
                    box-shadow: 0 3px 0 0 rgba(255,255,255,.1);
                }

                .mk-fullscreen-search-overlay .aws-container .aws-search-form .aws-search-btn_icon {
                    height: 30px;
                    line-height: 30px;
                }

                .mk-header .aws-container {
                    margin: 10px;
                }

                .mk-header .mk-responsive-wrap {
                    padding-bottom: 1px;
                }

            </style>

            <script>

                window.addEventListener('load', function() {

                    var iconSearch = document.querySelectorAll(".mk-fullscreen-trigger");
                    if ( iconSearch ) {
                        for (var i = 0; i < iconSearch.length; i++) {
                            iconSearch[i].addEventListener('click', function() {
                                window.setTimeout(function(){
                                    document.querySelector(".mk-fullscreen-search-overlay .aws-container .aws-search-field").focus();
                                    jQuery( '.aws-search-result' ).hide();
                                }, 100);
                            }, false);
                        }
                    }


                }, false);

            </script>

        <?php }

        /*
         * Elessi theme
         */
        public function elessi_head_action() { ?>

            <style>
                .warpper-mobile-search .aws-container .aws-search-field {
                    border-radius: 30px !important;
                    border: 1px solid #ccc !important;;
                    padding-left: 20px !important;;
                }
                .warpper-mobile-search .aws-container .aws-search-form .aws-form-btn,
                .nasa-header-search-wrap .aws-container .aws-search-form .aws-form-btn {
                    background: transparent !important;
                    border: none !important;
                }
            </style>

        <?php }

        /*
         * Walker theme
         */
        public function walker_head_action()  {  ?>
            <style>
                .edgtf-fullscreen-search-inner .aws-container {
                    position: relative;
                    width: 50%;
                    margin: auto;
                }
            </style>
            <script>
                window.addEventListener('load', function() {
                    if ( typeof jQuery !== 'undefined' ) {
                        jQuery(document).on( 'click focus', '.edgtf-fullscreen-search-inner input', function(e) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                            return false;
                        } );
                    }
                }, false);
            </script>
        <?php }

        /*
         * Storefront theme search form layout
         */
        public function storefront_footer_action() {

            $mobile_screen = AWS()->get_settings( 'mobile_overlay' );

            ?>

            <?php if ( $mobile_screen && $mobile_screen === 'true' ): ?>

                <script>
                    window.addEventListener('load', function() {
                        if ( typeof jQuery !== 'undefined' ) {
                            var search = jQuery('.storefront-handheld-footer-bar .search a');
                            search.on( 'click', function() {
                                var searchForm = jQuery('.storefront-handheld-footer-bar .aws-container');
                                searchForm.after('<div class="aws-placement-container"></div>');
                                searchForm.addClass('aws-mobile-fixed').prepend('<div class="aws-mobile-fixed-close"><svg width="17" height="17" viewBox="1.5 1.5 21 21"><path d="M22.182 3.856c.522-.554.306-1.394-.234-1.938-.54-.543-1.433-.523-1.826-.135C19.73 2.17 11.955 10 11.955 10S4.225 2.154 3.79 1.783c-.438-.371-1.277-.4-1.81.135-.533.537-.628 1.513-.25 1.938.377.424 8.166 8.218 8.166 8.218s-7.85 7.864-8.166 8.219c-.317.354-.34 1.335.25 1.805.59.47 1.24.455 1.81 0 .568-.456 8.166-7.951 8.166-7.951l8.167 7.86c.747.72 1.504.563 1.96.09.456-.471.609-1.268.1-1.804-.508-.537-8.167-8.219-8.167-8.219s7.645-7.665 8.167-8.218z"></path></svg></div>');
                                jQuery('body').addClass('aws-overlay').append('<div class="aws-overlay-mask"></div>').append( searchForm );
                                searchForm.find('.aws-search-field').focus();
                            } );
                        }
                    }, false);
                </script>

                <style>
                    .storefront-handheld-footer-bar ul li.search.active .site-search {
                        display: none !important;
                    }
                </style>

            <?php else: ?>

                <script>
                    window.addEventListener('load', function() {
                        function aws_results_layout( styles, options  ) {
                            if ( typeof jQuery !== 'undefined' ) {
                                var $storefrontHandheld = options.form.closest('.storefront-handheld-footer-bar');
                                if ( $storefrontHandheld.length ) {
                                    if ( ! $storefrontHandheld.find('.aws-search-result').length ) {
                                        $storefrontHandheld.append( options.resultsBlock );
                                    }
                                    styles.top = 'auto';
                                    styles.bottom = 130;
                                }
                            }
                            return styles;
                        }
                        if ( typeof AwsHooks === 'object' && typeof AwsHooks.add_filter === 'function' ) {
                            AwsHooks.add_filter( 'aws_results_layout', aws_results_layout );
                        }
                    }, false);
                </script>

                <style>
                    .storefront-handheld-footer-bar .aws-search-result ul li {
                        float: none !important;
                        display: block !important;
                        text-align: left !important;
                    }
                    .storefront-handheld-footer-bar .aws-search-result ul li a {
                        text-indent: 0 !important;
                        text-decoration: none;
                    }
                </style>

            <?php endif; ?>

        <?php }

        /*
         * Porto theme seamless integration
         */
        public function porto_search_form_content_filter( $markup ) {
            $pattern = '/(<form[\S\s]*?<\/form>)/i';
            if ( strpos( $markup, 'aws-container' ) === false ) {
                $markup = preg_replace( $pattern, aws_get_search_form( false ), $markup );
            }
            $markup = str_replace( 'aws-container', 'aws-container searchform', $markup );
            return $markup;
        }

        /*
         * Porto theme styles
         */
        public function porto_head_action() { ?>

            <style>
                #header .aws-container.searchform {
                    border: 0 !important;
                    border-radius: 0 !important;
                }
                #header .aws-container .aws-search-field {
                    border: 1px solid #eeeeee !important;
                    height: 100%;
                }
                #header .aws-container .aws-search-form {
                    height: 36px;
                }
                #header .aws-container .aws-search-form .aws-form-btn {
                    background: #fff;
                    border-color: #eeeeee;
                }
            </style>

        <?php }

        /*
         * BoxShop theme styles
         */
        public function boxshop_head_action() { ?>

            <style>
                .ts-header .aws-container .aws-search-form .aws-search-btn.aws-form-btn {
                    background-color: #e72304;
                }
                .ts-header .aws-container .aws-search-form .aws-search-btn.aws-form-btn:hover {
                    background-color: #000000;
                }
                .aws-container .aws-search-form .aws-search-btn_icon {
                    color: #fff;
                }
            </style>

        <?php }

        /*
         * Add search form to Aurum theme mobile menu
         */
        public function aurum_mobile_menu( $nav_menu, $args ) {
            if ( $args->theme_location === 'main-menu' && $args->menu_class && $args->menu_class === 'mobile-menu' ) {
                $form = aws_get_search_form( false );
                $nav_menu = $form . $nav_menu;
            }
            return $nav_menu;
        }

        /*
         * Aurum theme markup for seamless js integration
         */
        public function aurum_seamless_searchbox_markup( $markup ) {

            if ( function_exists( 'lab_get_svg' ) ) {
                $button = '<a href="#" class="search-btn">' . lab_get_svg( "images/search.svg" ) . '<span class="sr-only">' . __( "Search", "aurum" ) .'</span></a>';
                $markup = preg_replace( '/(<form[\S\s]*?>)/i', '${1}<div class="search-input-env">', $markup );
                $markup = str_replace( '</form>', '</div></form>', $markup );
                $markup = str_replace( '</form>', $button . '</form>', $markup );
                $markup = str_replace( 'aws-search-form', 'aws-search-form search-form', $markup );
                $markup = str_replace( 'aws-search-field', 'aws-search-field search-input', $markup );
                $markup = preg_replace( '/(<div class="aws-search-btn aws-form-btn">[\S\s]*?<\/div>)/i', '', $markup );
            }

            return $markup;

        }

        /*
         * Basel theme markup for seamless js integration
         */
        public function basel_seamless_searchbox_markup( $markup ) {
            $markup = str_replace( '</form>', '<div class="searchform basel-ajax-search" style="opacity:0;visibility:hidden;"></div></form>', $markup );
            return $markup;
        }

        /*
         * Basel theme styles
         */
        public function basel_wp_head() { ?>

            <style>
                .basel-search-full-screen .basel-search-wrapper .aws-container {
                    margin: 20px 0 0;
                }
                .basel-search-full-screen .basel-search-inner .basel-close-search {
                    height: 60px;
                    top: 90px;
                    z-index: 9999;
                }
                .secondary-header #searchform {
                    display: none;
                }
                .secondary-header .aws-container,
                .secondary-header .aws-container .aws-search-form,
                .secondary-header .aws-container {
                    background: transparent;
                }
            </style>

        <?php }

        /*
         * Woostify theme markup for seamless integration
         */
        public function woostify_aws_searchbox_markup( $markup ) {
            $markup = str_replace( 'aws-search-field', 'aws-search-field search-field', $markup );
            return $markup;
        }

        /*
         * Aurum theme scripts
         */
        public function aurum_wp_head() { ?>

            <script>
                window.addEventListener("load", function() {
                    window.setTimeout(function(){
                        var forms = document.querySelectorAll(".search-btn");
                        if ( forms ) {
                            for (var i = 0; i < forms.length; i++) {
                                forms[i].addEventListener("click", function() {
                                    var links = document.querySelectorAll(".header-links .search-form");
                                    if ( links ) {
                                        for (var i = 0; i < links.length; i++) {
                                            links[i].className += " input-visible";
                                        }
                                    }
                                }, false);
                            }
                        }
                    }, 1000);
                }, false);
            </script>

        <?php }

        /*
         * Fury theme markup change
         */
        public function fury_searchbox_markup( $markup ) {
            global $wp_current_filter;
            if ( in_array( 'wp_head', $wp_current_filter ) ) {
                $search_tools = '<div class="search-tools">
                    <button type="button" class="clear-search">' . esc_html( "Clear", "fury" ) . '</button>
                    <button type="button" class="close-search" aria-label="' . esc_attr( "Close search", "fury" ) . '"><i class="icon-cross"></i></button>
                </div>';
                $markup = str_replace( 'aws-container', 'aws-container aws-fury-navbar', $markup );
                $markup = str_replace( 'aws-search-form', 'aws-search-form site-search', $markup );
                $markup = str_replace( '<div class="aws-search-clear">', $search_tools . '<div class="aws-search-clear">', $markup );
            }
            return $markup;
        }

        /*
         * Fury theme styles and scripts
         */
        public function fury_wp_head() { ?>
            <style>
                .aws-fury-navbar.aws-container,
                .aws-fury-navbar.aws-container form {
                    height: 100%;
                    position: absolute;
                    width: 100%;
                }
                .aws-fury-navbar.aws-container .aws-search-form.aws-show-clear.aws-form-active .aws-search-clear {
                    display: none !important;
                }
            </style>
            <script>
                window.addEventListener('load', function() {
                    if ( typeof jQuery !== 'undefined' ) {

                        jQuery(document).on( 'click', '.aws-fury-navbar .clear-search', function () {
                            jQuery('.aws-fury-navbar input').val('');
                            jQuery('.aws-search-result').hide();
                            jQuery('.aws-fury-navbar .aws-search-form').removeClass('aws-form-active');
                        } );

                        jQuery(document).on( 'click', '.aws-fury-navbar .close-search', function () {
                            jQuery('.aws-fury-navbar .aws-search-form').removeClass('search-visible');
                            jQuery('.aws-fury-navbar input').val('');
                            jQuery('.aws-search-result').hide();
                            jQuery('.aws-fury-navbar .aws-search-form').removeClass('aws-form-active');
                        } );

                    }
                }, false);
            </script>
        <?php }

        /*
         * Bazar theme: add search form
         */
        public function bazar_add_header_form() {
            $output = aws_get_search_form( false );
            $output = str_replace( 'aws-container', 'aws-container widget widget_search_mini', $output );
            echo $output;
        }

        /*
         * Bazar theme: add styles
         */
        public function bazar_wp_head() { ?>
            <style>
                #header-cart-search .widget_search_mini:not(.aws-container){
                    display: none !important;
                }
                #header-cart-search .aws-container,
                #header-cart-search .aws-container .aws-search-form {
                    height: 50px;
                }
                #header-cart-search .aws-container .aws-search-field {
                    font-size: 18px;
                    font-family: 'Oswald', sans-serif;
                    color: #747373;
                    font-style: normal;
                    font-weight: 400;
                    text-transform: uppercase;
                    padding-left: 14px;
                }
            </style>
        <?php }

        /*
         * Claue theme header markup
         */
        public function claue_header( $markup ) {
            $pattern = '/(<form[\S\s]*?<\/form>)/i';
            if ( strpos( $markup, 'aws-container' ) === false ) {
                $form = '<div class="header__search w__100 dn pf">' . aws_get_search_form( false ) . '<a id="sf-close" class="pa" href="#"><i class="pe-7s-close"></i></a></div>';
                $markup = preg_replace( $pattern, $form, $markup );
            }
            return $markup;
        }

        /*
         * Claue theme styles
         */
        public function claue_wp_head() { ?>
            <style>
                #jas-header .aws-container {
                    position: absolute;
                }
                #jas-header .aws-container .aws-search-field {
                    height: 100% !important;
                    font-size: 20px;
                }
                #jas-header .aws-container .aws-search-field,
                #jas-header .aws-container .aws-search-form .aws-form-btn {
                    background: transparent;
                    border-color: rgba(255, 255, 255, .2);
                }
            </style>
        <?php }

        /*
         * Salient theme styles
         */
        public function salient_wp_head() { ?>
            <style>
                #search-outer #search #close {
                    top: -5px;
                }
                #search-box .aws-container {
                    margin-right: 70px;
                }
                #search-box .aws-container .aws-search-form .aws-search-btn_icon {
                    margin: 0 !important;
                    color: rgba(0,0,0,0.7) !important;
                }
                #search-box .aws-container .aws-search-field {
                    font-size: 26px;
                    font-weight: bold;
                    padding: 6px 15px 8px 0;
                    background: transparent;
                }
                #search-box .aws-container .aws-search-field:focus {
                    box-shadow: none;
                }
                #search-box .aws-container .aws-search-field,
                #search-box .aws-container .aws-search-form .aws-form-btn {
                    border: none;
                    border-bottom: 3px solid #3452ff !important;
                }
            </style>
        <?php }

        /*
         * Royal theme styles
         */
        public function royal_wp_head() { ?>
            <style>
                .et-search-trigger.search-dropdown .aws-container {
                    position: absolute;
                    width: 325px;
                    top: 55px;
                    z-index: 1001;
                    right: -12px;
                }
                .et-search-trigger.search-dropdown .aws-container .aws-search-form,
                .et-search-trigger.search-dropdown:hover .aws-container .aws-search-form{
                    top: 0;
                    right: 0;
                    height: auto;
                }
                #searchModal .aws-container {
                    margin: 30px 30px 20px;
                }
            </style>
        <?php }

        /*
         * WR Nitro theme styles
         */
        public function wr_nitro_wp_head() { ?>

            <style>

                .hb-search-fs .aws-container {
                    width: 500px;
                    max-width: 500px;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    -webkit-transform: translate(-50%, -50%);
                    transform: translate(-50%, -50%);
                }

                .wrls-header-outer .aws-container {
                    width: 650px!important;
                    margin: 0;
                    padding: 0 35px 0 10px;
                    height: 38px;
                }

            </style>

        <?php }

        /*
         * Botiga theme fix back to top button
         */
        public function botiga_aws_searchbox_markup( $markup ) {
            $markup = str_replace( '</form>', '<span class="search-submit"></span></form>', $markup );
            return $markup;
        }

        /*
         * Botiga theme fix back to top button
         */
        public function botiga_wp_head()  { ?>
            <style>
                .header-search-form .aws-container {
                    max-width: 720px;
                    margin-left: auto;
                    margin-right: auto;
                }
            </style>
        <?php }

        /*
         * Rey theme add search form
         */
        public function rey_search_panel() {
            $output = aws_get_search_form( false );
            echo $output;
        }

        /*
         * Rey theme search form styles
         */
        public function rey_wp_head() { ?>
            <style>
                .rey-searchPanel-inner > form,
                .rey-searchPanel-inner .rey-searchResults,
                .rey-searchPanel-inner .rey-lineLoader,
                .rey-inlineSearch-wrapper > form,
                .rey-searchPanel-inner .aws-container .aws-search-form .aws-form-btn {
                    display: none !important;
                }
                .rey-searchPanel-inner .aws-container .aws-search-field {
                    width: 100%;
                }
                .rey-searchPanel-inner .aws-container .aws-search-field:focus {
                    background: transparent;
                }
            </style>


            <script>

                window.addEventListener('load', function() {

                    function aws_results_layout( styles, options ) {
                        var form = options.form;
                        if ( options.form.closest('.rey-searchPanel-inner').length > 0 ) {
                            var containerWidth = form.find('.aws-search-form').outerWidth();
                            var offset = form.find('.aws-search-form').offset();
                            var bodyPosition = jQuery('body').css('position');
                            var bodyOffset = jQuery('body').offset();
                            if ( bodyPosition === 'relative' || bodyPosition === 'absolute' || bodyPosition === 'fixed' ) {
                                styles.left = offset.left - bodyOffset.left;
                            } else {
                                styles.left = offset.left;
                            }
                            if ( containerWidth ) {
                                styles.width = containerWidth;
                            }
                        }
                        return styles;
                    }
                    AwsHooks.add_filter( "aws_results_layout", aws_results_layout );
                }, false);

            </script>

        <?php }

         /*
          * Orchid store theme search form styles
          */
        public function orchid_store_wp_head() { ?>
            <style>
                .masterheader .aws-container .aws-search-field {
                    border: 2px solid #0286e7;
                    border-radius: 50px !important;
                }
                .masterheader .aws-container .aws-search-form {
                    height: 45px;
                }
                .masterheader .aws-container .aws-search-form .aws-form-btn {
                    background: #0286e7;
                    border-radius: 50px !important;
                    margin: 3px 0;
                }
                .masterheader .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current,
                .masterheader .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current:after,
                .masterheader .aws-container .aws-search-form .aws-search-btn_icon {
                    color: #fff;
                }
            </style>
        <?php }

        /*
         * Be theme search form styles
         */
        public function betheme_wp_head() { ?>
            <style>
                #Header_wrapper #searchform {
                    opacity: 0;
                }
                #Side_slide .aws-container .aws-search-field,
                #Side_slide .aws-container .aws-search-field:focus {
                    background-color: #191919 !important;
                    border-color: rgba(255,255,255,.05);
                }
            </style>
        <?php }

        /*
         * Gecko theme search form
         */
        public function gecko_wp_head() { ?>
            <style>
                #jas-wrapper .aws-container.header__search {
                    height: 100%;
                    width: 100%;
                    top: 0;
                    left: 0;
                    background: rgba(0, 0, 0, .95);
                    z-index: 9999;
                }
                #jas-wrapper .aws-container.header__search .aws-search-form {
                    max-width: 550px;
                    top: calc(50% - 125px);
                    left: 50%;
                    width: 100%;
                    -webkit-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                }
                #jas-wrapper .aws-container.header__search .aws-search-form .aws-search-field {
                    background: transparent;
                    border: none;
                    border-bottom: 1px solid rgba(255, 255, 255, .1);
                    text-align: center;
                    font-size: 18px;
                    color: #fff;
                }
            </style>

            <script>
                window.addEventListener('load', function() {
                    if ( typeof jQuery !== 'undefined' ) {
                        jQuery(document).on( 'click', '#jas-header .sf-open, #sf-close', function () {
                            jQuery('.aws-container').toggleClass('dn');
                            jQuery('.aws-search-result').hide();
                            jQuery('.aws-container.header__search .aws-search-field:visible').focus();
                        } );
                    }
                    function aws_results_layout( styles, options ) {
                        var form = options.form;
                        var realForm = form.closest('#jas-wrapper').find('.aws-container.header__search .aws-search-form');
                        if ( options.form.closest('#jas-wrapper').length > 0 && jQuery('.aws-container.header__search').is(':visible') ) {
                            var offset = parseInt( realForm.css('top') );
                            var offsetLeft = parseInt( realForm.css('left') );
                            var bodyOffset = jQuery('body').offset();
                            styles.top = offset + form.find('.aws-search-field').innerHeight();
                            styles.width = realForm.outerWidth();
                            styles.left = offsetLeft - realForm.outerWidth() / 2;
                        }
                        return styles;
                    }
                    AwsHooks.add_filter( "aws_results_layout", aws_results_layout );
                }, false);
            </script>

        <?php }

        /*
         * Gecko theme markup for seamless js integration
         */
        public function gecko_seamless_searchbox_markup( $markup ) {
            $markup = str_replace( 'aws-container', 'aws-container header__search w__100 dn pf', $markup );
            $markup = str_replace( '</form>', '</form><a id="sf-close" class="pa" href="#"><i class="pe-7s-close"></i></a>', $markup );
            return $markup;
        }

        /*
         * Savoy theme markup for seamless js integration
         */
        public function savoy_seamless_searchbox_markup( $markup ) {
            $markup = str_replace( 'name="s"', 'name="s" id="nm-header-search-input"', $markup );
            return $markup;
        }

        /*
         * Vandana theme markup changes
         */
        public function vandana_searchbox_markup( $markup ) {
            $markup = str_replace( 'aws-container', 'aws-container search-form', $markup );
            return $markup;
        }

        /*
         * Vandana theme custom styles
         */
        public function vandana_wp_head() { ?>
            <style>
                .header-search .header-search-wrap .aws-container .aws-show-clear .aws-search-field,
                .header-search .header-search-wrap .aws-container .aws-search-form .aws-form-btn{
                    background: transparent;
                    border: none;
                    color: #fff;
                    font-size: 2em;
                }
                .header-search .header-search-wrap .aws-container .aws-search-form .aws-search-btn_icon {
                    color: #fff;
                }
            </style>
        <?php }

        /*
         * Pustaka theme custom styles
         */
        public function pustaka_wp_head() { ?>
            <style>
                .site-header .searchform {
                    opacity: 0;
                }
                .site-header .aws-container {
                    width: 100%;
                }
                .site-header .aws-container .aws-search-form .aws-search-btn svg {
                    fill: #CD40E6;
                }
                .site-header .aws-container .aws-search-field {
                    border: none;
                    padding: 0;
                }
                .site-header .aws-container .aws-search-form .aws-form-btn {
                    border: none;
                    background: transparent;
                }
            </style>
        <?php }

        /*
         * XStore theme custom styles
         */
        public function xstore_wp_head() { ?>
            <style>
                #header form[role="search"]:not(.aws-search-form) {
                    opacity: 0;
                }
                #header .aws-container .aws-search-form .aws-form-btn {
                    background: #fff;
                }
                #header .aws-container .aws-search-form .aws-search-btn.aws-form-btn {
                    background-color: #000000;
                }
                #header .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current {
                    color: #888;
                }
                #header .aws-container .aws-search-form .aws-search-btn svg {
                    fill: #fff;
                }
                #header .et-mini-content .aws-container {
                    width: 100%;
                    max-width: 750px;
                    margin-top: 50px;
                }
                #header [class*=et-content-]:not(.et-popup_toggle) .et_b_search-icon + .aws-container {
                    opacity: 0;
                    visibility: hidden;
                    top: 100%;
                    position: absolute;
                    z-index: 9999;
                    right: 0;
                    width: 100%;
                    min-width: 310px;
                }
                #header [class*=et-content-]:not(.et-popup_toggle):hover .et_b_search-icon + .aws-container {
                    opacity: 1;
                    visibility: visible;
                }
            </style>
        <?php }

        /*
         * Blocksy theme custom styles
         */
        public function blocksy_wp_head() { ?>
            <style>
                #search-modal .aws-container {
                    width: 100%;
                    margin: 0 auto;
                    max-width: 800px;
                }
                #search-modal .aws-container .aws-search-form {
                    background-color: transparent;
                    height: 60px;
                }
                #search-modal .aws-container .aws-search-form .aws-search-field {
                    border: none;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                    font-size: 26px;
                    background-color: transparent;
                }
                #search-modal .aws-container .aws-search-form .aws-form-btn {
                    border: none;
                    background-color: #585a5c;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                }
                #search-modal .aws-container .aws-search-form .aws-form-btn:hover {
                    background-color: #ccc;
                }
                #search-modal .aws-container .aws-search-form .aws-main-filter .aws-main-filter__current {
                    color: #000;
                }
                #search-modal .aws-container .aws-search-form .aws-search-btn svg {
                    fill: #222;
                }
            </style>
        <?php }

        /*
         * Kapee theme header search form replace
         */
        public function kapee_get_template_before( $located, $templates, $args ) {
            if ( $templates === 'template-parts/header/elements/ajax-search.php' && ! isset( $this->data['kapee_form'] ) ) {
                aws_get_search_form();
                $this->data['kapee_form'] = true;
                echo '<style>.kapee-ajax-search { display:none; } .header-main .aws-container, .header-col .aws-container { width:100%; }</style>';
            }

        }

        /*
         * Hestia them fix header search form
         */
        public function hestia_after_primary_navigation_addons( $form ) {
            $form = str_replace('class="aws-wrapper', ' style="height:35px;" class="aws-wrapper', $form );
            $output  = '';
            $output .= '<li class="hestia-search-in-menu">';
                $output .= '<div class="hestia-nav-search">';
                    $output .= $form;
                $output .= '</div>';
                $output .= '<a class="hestia-toggle-search"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg></a>';
            $output .= '</li>';
            return $output;
        }
        public function hestia_wp_enqueue_scripts() {
            if ( wp_is_mobile() ) {
                return;
            }
            $script = '
                function aws_results_layout( styles, options ) {
                    styles.width = 200;
                    styles.left = styles.left - 200;
                    styles.top = styles.top + 40;
                    return styles;
                }
                AwsHooks.add_filter( "aws_results_layout", aws_results_layout );
            ';
            wp_add_inline_script( 'aws-script', $script);
            wp_add_inline_script( 'aws-pro-script', $script);
        }

        /*
         * Open Shop theme header styles
         */
        public function open_shop_wp_head() { ?>
            <style>
                .below-header-bar .aws-container {
                    max-width: 550px;
                    margin: 0 auto;
                }
            </style>
        <?php }

        /*
         * Zephyr theme search form styles
         */
        public function zephyr_wp_head() { ?>
            <style>
                .header_hor .aws-container {
                    width: 100%;
                }
                .header_hor .aws-container .aws-search-field {
                    color: rgb(53, 65, 91);
                    font-size: 16px;
                }
                .header_hor .aws-container .aws-search-form .aws-form-btn {
                    background: transparent;
                    border: none;
                }
            </style>
        <?php }

        /*
         * Shoptimizer fix search form inside dialog window
         */
        public function shoptimizer_wp_enqueue_scripts() {

            $script = "
                function aws_results_append_to( container, options  ) {
                    if ( options.form.closest('.shoptimizer-modal').length > 0 ) {
                        return '.shoptimizer-modal .site-search';
                    }
                    return container;
                }
                function aws_results_layout( styles, options  ) {
                    if ( options.form.closest('.shoptimizer-modal').length > 0 ) {
                        var offset = options.form.offset();
                        var dialogOffset = options.form.closest('.site-search').offset();
                        styles.left = offset.left - dialogOffset.left;
                        styles.top = offset.top - dialogOffset.top + options.form.innerHeight();
                    }
                    return styles;
                }
                AwsHooks.add_filter( 'aws_results_append_to', aws_results_append_to );
                AwsHooks.add_filter( 'aws_results_layout', aws_results_layout );
            ";

            wp_add_inline_script( 'aws-script', $script);
            wp_add_inline_script( 'aws-pro-script', $script);

        }

        /*
         * Add search form inside Hitek theme header
         */
        public function xts_after_search_wrapper() {

            echo '<style>
                .xts-header form.searchform, 
                .xts-search-wrapper form.searchform {
                    display: none;
                }
                .xts-search-wrapper.xts-search-full-screen .aws-container {
                    margin: 0 35px;
                    padding-top: 30px;
                }
                .xts-search-full-screen .xts-search-close {
                    top: -110px;
                }
            </style>';

            aws_get_search_form();

        }

        /*
         * Oxygen theme header search form
         */
        public function oxygen_wp_before_load_template( $_template_file ) {
            if ( strpos( $_template_file, 'oxygen/sidebar-menu-top.php' ) !== false ) {
                ob_start();
            }
        }
        public function oxygen_wp_after_load_template( $_template_file ) {
            if ( strpos( $_template_file, 'oxygen/sidebar-menu-top.php' ) !== false ) {
                $deal_html = ob_get_contents();
                ob_end_clean();
                $form = aws_get_search_form( false );
                $form = str_replace( 'aws-container', 'aws-container search-form',$form );
                echo preg_replace('/\<form[\s\S]*?search-form[\s\S]*?<\/form>/', $form, $deal_html);
            }
        }

        /*
         * WP Bottom Menu
         */
        public function wp_bottom_menu_wp_head() { ?>

            <script>
                window.addEventListener('load', function() {
                    function wpbottom_aws_results_force_position( forcePosition, options ) {
                        if ( options.form.closest('#wp-bottom-menu-search-form-wrapper').length > 0 ) {
                            forcePosition = 'top';
                        }
                        return forcePosition;
                    }
                    AwsHooks.add_filter( "aws_results_force_position", wpbottom_aws_results_force_position );
                }, false);
            </script>

        <?php }

        /*
         * Advance Product Search by themehunk
         */
        public function aps_thaps_form_html( $html ) {
            $output = aws_get_search_form( false );
            return $output;
        }

        /*
         * Exclude product categories
         */
        public function filter_protected_cats_term_exclude( $exclude ) {
            if ( isset( $this->data['exclude_categories'] ) ) {
                foreach( $this->data['exclude_categories'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        /*
         * Exclude products
         */
        public function filter_products_exclude( $exclude ) {
            if ( isset( $this->data['exclude_products'] ) ) {
                foreach( $this->data['exclude_products'] as $to_exclude ) {
                    $exclude[] = $to_exclude;
                }
            }
            return $exclude;
        }

        public function woocommerce_product_query( $query ) {

            $query_args = array(
                's'                => 'a',
                'post_type'        => 'product',
                'suppress_filters' => true,
                'fields'           => 'ids',
                'posts_per_page'   => 1
            );

            $query = new WP_Query( $query_args );
            $query_vars = $query->query_vars;

            $query_args_options = get_option( 'aws_search_query_args' );

            if ( ! $query_args_options ) {
                $query_args_options = array();
            }

            $user_role = 'non_login';

            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $role = ( array ) $user->roles;
                $user_role = $role[0];
            }

            $query_args_options[$user_role] = array(
                'post__not_in' => $query_vars['post__not_in'],
                'category__not_in' => $query_vars['category__not_in'],
            );

            update_option( 'aws_search_query_args', $query_args_options );

        }

        /*
         * Selector filter of js seamless
         */
        public function js_seamless_selectors( $selectors ) {

            // shopkeeper theme
            if ( function_exists( 'shopkeeper_theme_setup' ) ) {
                $selectors[] = '.site-search .woocommerce-product-search';
            }

            // ocean wp theme
            if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
                $selectors[] = '#searchform-header-replace form';
                $selectors[] = '#searchform-overlay form';
                $selectors[] = '#sidr .sidr-class-mobile-searchform';
                $selectors[] = '#mobile-menu-search form';
                $selectors[] = '#site-header form';
            }

            if ( 'Jupiter' === $this->current_theme ) {
                $selectors[] = '#mk-fullscreen-searchform';
                $selectors[] = '.responsive-searchform';
            }

            if ( 'Venedor' === $this->current_theme ) {
                $selectors[] = '#search-form form';
            }

            if ( 'Elessi Theme' === $this->current_theme ) {
                $selectors[] = '.warpper-mobile-search form';
            }

            if ( 'Walker' === $this->current_theme ) {
                $selectors[] = '.edgtf-page-header form, .edgtf-mobile-header form, .edgtf-fullscreen-search-form';
            }

            if ( 'Martfury' === $this->current_theme ) {
                $selectors[] = '#site-header .products-search';
            }

            if ( 'BoxShop' === $this->current_theme ) {
                $selectors[] = '.ts-header .search-wrapper form';
            }

            if ( 'Fury' === $this->current_theme ) {
                $selectors[] = 'header .site-search';
            }

            if ( 'Urna' === $this->current_theme ) {
                $selectors[] = '#tbay-header .searchform';
            }

            if ( 'Salient' === $this->current_theme ) {
                $selectors[] = '#search-box form';
            }

            if ( 'Aurum' === $this->current_theme ) {
                $selectors[] = '.header-links .search-form';
            }

            if ( 'Royal' === $this->current_theme ) {
                $selectors[] = '.header-search form';
                $selectors[] = '#searchModal form';
            }

            if ( 'Modern Store' === $this->current_theme ) {
                $selectors[] = '#search-form-container form';
            }

            if ( 'WR Nitro' === $this->current_theme ) {
                $selectors[] = '.search-form-inner form';
            }

            if ( 'Basel' === $this->current_theme ) {
                $selectors[] = '.basel-search-wrapper #searchform, .secondary-header #searchform, .mobile-nav #searchform';
            }

            if ( 'Betheme' === $this->current_theme ) {
                $selectors[] = '#Header_wrapper #searchform, #side-form';
            }

            if ( 'Gecko' === $this->current_theme ) {
                $selectors[] = '#jas-wrapper .header__search';
            }

            if ( 'Savoy' === $this->current_theme ) {
                $selectors[] = '#nm-header-search-form';
            }

            if ( 'Pustaka' === $this->current_theme ) {
                $selectors[] = '.site-header .searchform';
            }

            if ( 'XStore' === $this->current_theme ) {
                $selectors[] = "#header form[role='search']";
            }

            if ( 'Kapee' === $this->current_theme ) {
                $selectors[] = ".kapee-ajax-search";
            }

            if ( 'Sinatra' === $this->current_theme ) {
                $selectors[] = '.si-header-widgets .si-search-form';
            }

            if ( 'Shopical' === $this->current_theme ) {
                $selectors[] = '.search .search-form-wrapper';
            }

            // WCFM - WooCommerce Multivendor Marketplace
            if ( class_exists( 'WCFMmp' ) ) {
                $selectors[] = '#wcfmmp-store .woocommerce-product-search';
            }

            // WP Bottom Menu
            if ( defined( 'WP_BOTTOM_MENU_VERSION' ) ) {
                $selectors[] = '#wp-bottom-menu-search-form-wrapper form';
            }

            return $selectors;

        }

        /*
         * Js seamless integration method
         */
        public function head_js_integration() {

            /**
             * Filter seamless integrations js selectors for forms
             * @since 1.85
             * @param array $forms Array of css selectors
             */
            $forms = apply_filters( 'aws_js_seamless_selectors', array() );

            if ( ! is_array( $forms ) || empty( $forms ) ) {
                return;
            }

            $forms_selector = implode( ',', $forms );

            $form_html = str_replace( 'aws-container', 'aws-container aws-js-seamless', aws_get_search_form( false ) );

            /**
             * Filter seamless integrations default form markup
             * @since 2.25
             * @param string $form_html Form html output
             * @param array $forms Array of css selectors
             */
            $form_html = apply_filters( 'aws_js_seamless_searchbox_markup', $form_html, $forms );

            ?>

            <script>

                window.addEventListener('load', function() {
                    var forms = document.querySelectorAll("<?php echo $forms_selector; ?>");

                    var awsFormHtml = <?php echo json_encode( $form_html ); ?>;

                    if ( forms ) {

                        for ( var i = 0; i < forms.length; i++ ) {
                            if ( forms[i].parentNode.outerHTML.indexOf('aws-container') === -1 ) {
                                forms[i].outerHTML = awsFormHtml;
                            }
                        }

                        window.setTimeout(function(){
                            jQuery('.aws-js-seamless').each( function() {
                                try {
                                    jQuery(this).aws_search();
                                } catch (error) {
                                    window.setTimeout(function(){
                                        try {
                                            jQuery(this).aws_search();
                                        } catch (error) {}
                                    }, 2000);
                                }
                            });
                        }, 1000);

                    }
                }, false);
            </script>

        <?php }

        /*
         * Remove products that was excluded with Search Exclude plugin ( https://wordpress.org/plugins/search-exclude/ )
         */
        public function search_exclude_filter( $products ) {

            $excluded = get_option('sep_exclude');

            if ( $excluded && is_array( $excluded ) && ! empty( $excluded ) && $products && is_array( $products ) ) {
                foreach( $products as $key => $product_id ) {
                    if ( false !== array_search( $product_id, $excluded ) ) {
                        unset( $products[$key] );
                    }
                }
            }

            return $products;

        }

        /*
         * Fix WooCommerce Product Table for search page
         */
        public function wc_product_table_data_config( $config ) {
            if ( isset( $_GET['type_aws'] ) && isset( $config['search'] ) ) {
                $config['search']['search'] = '';
            }
            return $config;
        }

        /*
         * WooCommerce Product Table plugin change number of products on page
         */
        public function wc_product_table_posts_per_page( $num ) {
            return 9999;
        }

        /*
         * WP all import cron job
         */
        public function pmxi_after_xml_import() {
            $sunc = AWS()->get_settings( 'autoupdates' );
            if ( $sunc === 'true' ) {
                wp_schedule_single_event( time() + 1, 'aws_reindex_table' );
            }
        }


        /*
         * Product Sort and Display for WooCommerce plugin disable on search page
         */
        function psad_filter( $value ) {
            if ( isset( $_GET['type_aws'] ) ) {
                return 'no';
            }
            return $value;
        }

        /*
         * Electro them update search form markup
         */
        public function electro_searchbox_markup( $markup, $params ) {
            $pattern = '/<div class="aws-search-btn aws-form-btn">[\S\s]*?<\/div>/i';
            $markup = preg_replace( $pattern, '', $markup );
            return $markup;
        }

        /*
         * Elessi Theme fix for shop filters
         */
        public function elessi_wp_enqueue_scripts() {
            $script = "
               jQuery('body').on( 'nasa_store_filter_ajax', function(e) { 
                    window.setTimeout(function(){
                        jQuery('.aws-container').aws_search();     
                    }, 3000);          
               });
            ";
            wp_add_inline_script( 'aws-script', $script);
        }

        /*
         * Elessi Theme fix for shop filters
         */
        public function elessi_aws_search_page_filters( $filters ) {

            if ( isset( $_GET['on-sale'] ) && $_GET['on-sale'] === '1' ) {
                $filters['on_sale'] = true;
            }

            if ( isset( $_GET['in-stock'] ) && $_GET['in-stock'] === '1' ) {
                $filters['in_status'] = true;
            }

            if ( isset( $_GET['min_price'] ) ) {
                $filters['price_min'] = intval( $_GET['min_price'] );
            }

            if ( isset( $_GET['max_price'] ) ) {
                $filters['price_max'] = intval( $_GET['max_price'] );
            }

            return $filters;

        }

        /*
         * Elessi Theme fix for shop filters products count
         */
        public function elessi_woocommerce_get_filtered_term_product_counts_query( $query ) {
            if ( isset ($_GET['s'] ) && $_GET['s'] && isset( $_GET['type_aws'] ) ) {
                global $wpdb;
                $regex = "/AND \({$wpdb->posts}\.post_title.*?\)/i";
                $query['where'] = preg_replace( $regex, '', $query['where'] );
            }
            return $query;
        }

        /*
         * Product Visibility by User Role for WooCommerce plugin hide products for certain users
         */
        public function pvbur_aws_search_results_products( $products ) {

            $user_role = 'guest';
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                $user_role = $roles[0];
            }

            $remove_products = array();

            foreach( $products as $key => $product ) {

                $visible_roles = get_post_meta( $product['parent_id'], '_alg_wc_pvbur_visible', true );
                $invisible_roles = get_post_meta( $product['parent_id'], '_alg_wc_pvbur_invisible', true );

                if ( is_array( $invisible_roles ) && ! empty( $invisible_roles ) ) {
                    foreach( $invisible_roles as $invisible_role ) {
                        if ( $user_role == $invisible_role ) {
                            $remove_products[] = $key;
                            continue 2;
                        }
                    }
                }

                if ( is_array( $visible_roles ) && ! empty( $visible_roles ) ) {
                    $show = false;
                    foreach( $visible_roles as $visible_role ) {
                        if ( $user_role == $visible_role ) {
                            $show = true;
                            break;
                        }
                    }
                    if ( ! $show ) {
                        $remove_products[] = $key;
                        continue;
                    }
                }

            }

            if ( ! empty( $remove_products ) ) {
                $new_products = array();
                foreach( $products as $key => $product ) {
                    if ( array_search( $key, $remove_products ) === false ) {
                        $new_products[] = $product;
                    }
                }
                $products = $new_products;
            }

            return $products;

        }

        /*
         * ATUM Inventory Management for WooCommerce plugin ( Product level addon )
         */
        public function atum_index_data( $data, $id ) {
            $is_purchasable = AtumLevels\Inc\Helpers::is_purchase_allowed( $id );
            if ( ! $is_purchasable ) {
                $data = array();
            }
            return $data;
        }

        /*
         * Popups for Divi plugin fix scrolling for search results
         */
        function divi_popups_enqueue_scripts() {

            $script = "
                if ( typeof DiviArea === 'object' ) {
                    DiviArea.addAction('disabled_scrolling', function() {
                        var aws_form = jQuery('[data-da-area] .aws-search-form');
                        if ( aws_form.length > 0 ) {
                            DiviArea.Core.enableBodyScroll();
                            jQuery('body').addClass('da-overlay-visible');
                        }
                    });      
                    DiviArea.addAction('close_area', function() {
                        var aws_form = jQuery('[data-da-area] .aws-search-form');
                        if ( aws_form.length > 0 ) {
                            jQuery('body').removeClass('da-overlay-visible');
                            jQuery('.aws-search-result').hide();
                        }
                    });
                }
            ";

            wp_add_inline_script( 'aws-script', $script );

        }

        /*
         * WooCommerce Catalog Visibility Options plugin: exclude restricted products
         */
        public function wcvo_exclude_products( $exclude_products ) {
            $catalog_query = WC_Catalog_Restrictions_Query::instance();
            if ( is_object( $catalog_query ) && method_exists( $catalog_query, 'get_disallowed_products' ) ) {
                $disallowed_products = $catalog_query->get_disallowed_products();
                if ( is_array( $disallowed_products ) && ! empty( $disallowed_products ) ) {
                    foreach( $disallowed_products as $disallowed_product ) {
                        $exclude_products[] = $disallowed_product;
                    }
                }
            }
            return $exclude_products;
        }

        /*
         * Load  Dynamic Content for Elementor plugin scripts for search page
         */
        public function dce_scripts() {

            if ( ! isset( $_GET['s'] ) || ! isset( $_GET['post_type'] ) || $_GET['post_type'] !== 'product' || ! isset( $_GET['type_aws'] ) ) {
                return;
            }

            ?>

            <script>
                window.addEventListener('load', function() {
                    if ( typeof jQuery !== 'undefined' ) {
                        var $navItems = jQuery('.dce-page-numbers a.page-numbers');
                        if ( $navItems.length > 0 ) {
                            $navItems.each(function(){
                                var s = encodeURIComponent( '<?php echo esc_attr( $_GET['s'] ); ?>' );
                                var href = jQuery(this).attr( 'href' ) + '&post_type=product&type_aws=true&s=' + s;
                                jQuery(this).attr( 'href', href );
                            });
                        }
                    }
                }, false);
            </script>

        <?php }

        /*
         * WOOCS - WooCommerce Currency Switcher: fix ajax pricing loading
         */
        public function woocs_pricing_ajax_fix( $products_array ) {
            if ( $products_array ) {
                foreach ( $products_array as $key => $product ) {
                    if ( $product['price'] && strpos( $product['price'], 'woocs_preloader_ajax' ) !== false ) {
                        $products_array[$key]['price'] = str_replace( 'woocs_preloader_ajax', '', $product['price'] );
                    }
                }
            }
            return $products_array;
        }

        /*
         * Deals for WooCommerce: Add deals prices
         */
        public function dfm_search_pre_filter_products( $products_array ) {

            foreach( $products_array as $key => $pr_arr ) {

                if ( isset( $pr_arr['price'] ) && $pr_arr['price'] && function_exists( 'dfw_get_deal_ids' ) ) {

                    $product = wc_get_product( $pr_arr['id'] );
                    $deal_html = '';

                    if ( is_a( $product, 'WC_Product' ) && $product->get_type() == 'simple' ) {
                        $args = array(
                            'meta_key' => 'dfw_deal_type_id',
                            'meta_value' => $product->get_id(),
                        );

                        $deal_ids = dfw_get_deal_ids($args);

                        if ( function_exists( 'dfw_check_is_array' ) && dfw_check_is_array( $deal_ids ) ) {

                            $deal_id = reset($deal_ids);
                            $deal = function_exists('dfw_get_deal') ? dfw_get_deal($deal_id) : false;

                            if ( is_object( $deal ) ) {

                                if ( 'product' == $deal->get_type() ) {
                                    $product_id = $deal->get_deal_type_id() ;

                                    if ( 'yes' == get_post_meta( $product_id , 'dfw_enable_deal' , true ) && class_exists('DFW_Product_Handler') ) {
                                        $matched_data = DFW_Product_Handler::get_matched_rules( $product , $product_id , $deal_id , $deal->get_type() ) ;

                                        if ( function_exists('dfw_check_is_array') && dfw_check_is_array( $matched_data ) ) {
                                            if ( isset( $matched_data[ 'price' ] ) && isset( $matched_data[ 'rule_id' ] ) ) {

                                                ob_start();
                                                dfw_get_template( 'product-deals/deal-price.php' , array( 'product' => $product , 'deal_price' => $matched_data[ 'price' ] , 'class_name' => '' ) ) ;
                                                $deal_html = ob_get_contents();
                                                ob_end_clean();

                                                $deal_html = str_replace('class="price "', 'class=""', $deal_html );
                                                $deal_html = str_replace('<p ', '<span ', $deal_html );
                                                $deal_html = str_replace('</p>', '</span>', $deal_html );

                                            }
                                        }
                                    }
                                }

                            }

                        }

                    }

                    if ( $deal_html ) {
                        $products_array[$key]['price'] .= $deal_html;
                    }

                }

            }

            return $products_array;

        }

        /*
         * WooCommerce Product Search plugin - fix filters for s page
         */
        public function wps_aws_search_page_filters( $filters ) {

            if ( isset( $_GET['ixwpst'] ) && $_GET['ixwpst'] && is_array( $_GET['ixwpst'] ) ) {
                foreach( $_GET['ixwpst'] as $tax => $terms ) {
                    $filters['tax'][$tax] = array(
                        'terms' => $terms,
                        'operator' => 'AND',
                        'include_parent' => true,
                    );
                }
            }

            return $filters;

        }

        /*
         * Yoast: Allow some permalink vars
         */
        public function yoast_allowlist_permalink_vars( $allowed_extravars ) {
            $allowed_extravars[] = 'post_type';
            $allowed_extravars[] = 'type_aws';
            $allowed_extravars[] = 'aws_id';
            $allowed_extravars[] = 'aws_filter';
            return $allowed_extravars;
        }

    }

endif;