<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AWS_Admin_Options' ) ) :

    /**
     * Class for plugin admin options methods
     */
    class AWS_Admin_Options {

        /*
         * Get default settings values
         * @param string $tab Tab name
		 * @return array
         */
        static public function get_default_settings( $tab = false ) {

            $options = self::options_array( $tab );
            $default_settings = array();

            foreach ( $options as $section_name => $section ) {

                foreach ( $section as $values ) {

                    if ( isset( $values['type'] ) && $values['type'] === 'heading' ) {
                        continue;
                    }

                    if ( isset( $values['type'] ) && $values['type'] === 'html' ) {
                        continue;
                    }

                    if ( isset( $values['type'] ) && $values['type'] === 'table' && empty( $values['value'] ) ) {
                        continue;
                    }

                    if ( isset( $values['type'] ) && ( $values['type'] === 'checkbox' || $values['type'] === 'table' ) ) {
                        foreach ( $values['choices'] as $key => $val ) {
                            $default_settings[ $values['id'] ][$key] = sanitize_text_field( $values['value'][$key] );
                        }
                        continue;
                    }

                    if ( $values['type'] === 'textarea' && isset( $values['allow_tags'] ) ) {
                        $default_settings[$values['id']] = (string) wp_kses( stripslashes( html_entity_decode( $values['value'] ) ), AWS_Helpers::get_kses( $values['allow_tags'] ) );
                        continue;
                    }

                    if ( $values['type'] === 'textarea' ) {
                        if ( function_exists('sanitize_textarea_field') ) {
                            $default_settings[ $values['id'] ] = (string) sanitize_textarea_field( $values['value'] );
                        } else {
                            $default_settings[ $values['id'] ] = (string) str_replace( "<\n", "&lt;\n", wp_strip_all_tags( $values['value'] ) );
                        }
                        continue;
                    }

                    $default_settings[$values['id']] = (string) sanitize_text_field( $values['value'] );

                    if ( isset( $values['sub_option'] ) ) {
                        $default_settings[$values['sub_option']['id']] = (string) sanitize_text_field( $values['sub_option']['value'] );
                    }

                }

            }

            return $default_settings;

        }

        /*
         * Update plugin settings
         */
        static public function update_settings() {

            $options = self::options_array();
            $update_settings = self::get_settings();
            $current_tab = empty( $_GET['tab'] ) ? 'general' : sanitize_text_field( $_GET['tab'] );

            foreach ( $options[$current_tab] as $values ) {

                if ( $values['type'] === 'heading' || $values['type'] === 'table' || $values['type'] === 'html' ) {
                    continue;
                }

                if ( $values['type'] === 'checkbox' ) {

                    $checkbox_array = array();

                    foreach ( $values['choices'] as $key => $value ) {
                        $new_value = isset( $_POST[ $values['id'] ][$key] ) ? '1' : '0';
                        $checkbox_array[$key] = (string) sanitize_text_field( $new_value );
                    }

                    $update_settings[ $values['id'] ] = $checkbox_array;

                    continue;
                }

                $new_value = isset( $_POST[ $values['id'] ] ) ? $_POST[ $values['id'] ] : '';

                if ( $values['type'] === 'textarea' && isset( $values['allow_tags'] ) ) {
                    $update_settings[ $values['id'] ] = (string) wp_kses( stripslashes( html_entity_decode( $new_value ) ), AWS_Helpers::get_kses( $values['allow_tags'] ) );
                    continue;
                }

                if ( $values['type'] === 'textarea' ) {
                    if ( function_exists('sanitize_textarea_field') ) {
                        $update_settings[ $values['id'] ] = (string) sanitize_textarea_field( $new_value );
                    } else {
                        $update_settings[ $values['id'] ] = (string) str_replace( "<\n", "&lt;\n", wp_strip_all_tags( $new_value ) );
                    }
                    continue;
                }

                $update_settings[ $values['id'] ] = (string) sanitize_text_field( $new_value );

                if ( isset( $values['sub_option'] ) ) {
                    $new_value = isset( $_POST[ $values['sub_option']['id'] ] ) ? $_POST[ $values['sub_option']['id'] ] : '';
                    $update_settings[ $values['sub_option']['id'] ] = (string) sanitize_text_field( $new_value );
                }
            }

            update_option( 'aws_settings', $update_settings );

            AWS_Helpers::register_wpml_translations( $update_settings );

            do_action( 'aws_settings_saved' );

            do_action( 'aws_cache_clear' );

        }

        /*
         * Get plugin settings
         * @return array
         */
        static public function get_settings() {
            $plugin_options = get_option( 'aws_settings' );
            return $plugin_options;
        }

        /*
         * Options array that generate settings page
         *
         * @param string $tab Tab name
         * @return array
         */
        static public function options_array( $tab = false ) {

            $options = self::include_options();
            $options_arr = array();

            foreach ( $options as $tab_name => $tab_options ) {

                if ( $tab && $tab !== $tab_name ) {
                    continue;
                }

                $options_arr[$tab_name] = $tab_options;

            }

            /**
             * Filter admin page options for current page
             * @since 2.23
             * @param array $options_arr Array of options
             * @param bool|string $tab Current settings page tab
             */
            $options_arr = apply_filters( 'aws_admin_page_options_current', $options_arr, $tab );

            return $options_arr;

        }

        /*
         * Include options array
         * @return array
         */
        static public function include_options() {

            $show_out_of_stock = 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ? 'false' : 'true';

            $options = array();

            $options['general'][] = array(
                "name" => __( "Main Settings", "advanced-woo-search" ),
                "id"   => "main",
                "type" => "heading"
            );

            $options['general'][] = array(
                "name"  => __( "Seamless integration", "advanced-woo-search" ),
                "desc"  => __( "Replace all the standard search forms on your website ( may not work with some themes ).", "advanced-woo-search" ),
                "id"    => "seamless",
                "value" => 'false',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['general'][] = array(
                "name"       => __( "Search in", "advanced-woo-search" ),
                "desc"       => __( "Click on the status icon to enable or disable the search source for products search.", "advanced-woo-search" ),
                "table_head" => __( 'Search Source', 'advanced-woo-search' ),
                "id"         => "search_in",
                "value"      => array(
                    'title'    => 1,
                    'content'  => 1,
                    'sku'      => 1,
                    'excerpt'  => 1,
                    'category' => 0,
                    'tag'      => 0,
                    'id'       => 0,
                ),
                "choices" => array(
                    "title"    => __( "Title", "advanced-woo-search" ),
                    "content"  => __( "Content", "advanced-woo-search" ),
                    "sku"      => __( "SKU", "advanced-woo-search" ),
                    "excerpt"  => __( "Short description", "advanced-woo-search" ),
                    "category" => __( "Category", "advanced-woo-search" ),
                    "tag"      => __( "Tag", "advanced-woo-search" ),
                    "id"       => __( "ID", "advanced-woo-search" ),
                ),
                "type"    => "table"
            );

            $options['general'][] = array(
                "name"       => __( "Archive pages", "advanced-woo-search" ),
                "desc"       => __( "Search for taxonomies and displayed their archive pages in search results.", "advanced-woo-search" ),
                'table_head' => __( 'Archive Pages', 'advanced-woo-search' ),
                "id"         => "search_archives",
                "value" => array(
                    'archive_category' => 0,
                    'archive_tag'      => 0,
                ),
                "choices" => array(
                    "archive_category" => __( "Category", "advanced-woo-search" ),
                    "archive_tag"      => __( "Tag", "advanced-woo-search" ),
                ),
                "type"    => "table"
            );

            $options['general'][] = array(
                "name"  => __( "Stop words list", "advanced-woo-search" ),
                "desc"  => __( "Comma separated list of words that will be excluded from search.", "advanced-woo-search" ) . '<br>' . __( "Re-index required on change.", "advanced-woo-search" ),
                "id"    => "stopwords",
                "value" => "a, also, am, an, and, are, as, at, be, but, by, call, can, co, con, de, do, due, eg, eight, etc, even, ever, every, for, from, full, go, had, has, hasnt, have, he, hence, her, here, his, how, ie, if, in, inc, into, is, it, its, ltd, me, my, no, none, nor, not, now, of, off, on, once, one, only, onto, or, our, ours, out, over, own, part, per, put, re, see, so, some, ten, than, that, the, their, there, these, they, this, three, thru, thus, to, too, top, un, up, us, very, via, was, we, well, were, what, when, where, who, why, will",
                "cols"  => "85",
                "rows"  => "3",
                "type"  => "textarea"
            );

            $options['general'][] = array(
                "name"  => __( "Synonyms", "advanced-woo-search" ),
                "desc"  => __( "Comma separated list of synonym words. Each group of synonyms must be on separated text line.", "advanced-woo-search" ) . '<br>' . __( "Re-index required on change.", "advanced-woo-search" ),
                "id"    => "synonyms",
                "value" => "buy, pay, purchase, acquire&#13;&#10;box, housing, unit, package",
                "cols"  => "85",
                "rows"  => "3",
                "type"  => "textarea"
            );

            $options['general'][] = array(
                "name"  => __( "Misspelling fix", "advanced-woo-search" ),
                "desc"  => sprintf( __( "Fix typos inside search words %s.", "advanced-woo-search" ), '( lapto<b>t</b> -> lapto<b>p</b> )' ) . '<br>' . __( "Applied if the current search query returns no results.", "advanced-woo-search" ),
                "id"    => "fuzzy",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On. Automatically search for fixed terms.', 'advanced-woo-search' ),
                    'true_text'  => __( "On. Additionally show text \"Showing results for ...\" with a list of fixed terms at the top of search results.", 'advanced-woo-search' ),
                    'false'  => __( 'Off. Totally disable misspelling fix.', 'advanced-woo-search' ),
                    'false_text'  => __( "Off. Instead show text \"Did you mean ...\" with a clickable list of fixed terms at the top of search results.", 'advanced-woo-search' ),
                )
            );

            $options['general'][] = array(
                "name"  => __( "Use Google Analytics", "advanced-woo-search" ),
                "desc"  => __( "Use google analytics to track searches. You need google analytics to be installed on your site.", "advanced-woo-search" ) .
                    '<br>' . sprintf( __( "Data will be visible inside Google Analytics 'Site Search' report. Need to activate 'Site Search' feature inside GA. %s", "advanced-woo-search" ), '<a href="https://advanced-woo-search.com/guide/google-analytics/" target="_blank">' . __( 'More info', 'advanced-woo-search' ) . '</a>' ) .
                    '<br>' . __( "Also will send event with category - 'AWS search', action - 'AWS Search Term' and label of value of search term.", "advanced-woo-search" ),
                "id"    => "use_analytics",
                "value" => 'false',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['general'][] = array(
                "name"    => __( "Search results page", "advanced-woo-search" ),
                "type"    => "heading"
            );

            $options['general'][] = array(
                "name"  => __( "Enable results page", "advanced-woo-search" ),
                "desc"  => __( "Show plugin search results on a separated search results page. Will use your current theme products search results page template.", "advanced-woo-search" ),
                "id"    => "search_page",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['general'][] = array(
                "name"  => __( "Max number of results", "advanced-woo-search" ),
                "desc"  => __( "Maximal total number of search results. Larger values can lead to slower search speed.", "advanced-woo-search" ),
                "id"    => "search_page_res_num",
                "value" => 100,
                "min" => 0,
                "type"  => "number"
            );

            $options['general'][] = array(
                "name"  => __( "Results per page", "advanced-woo-search" ),
                "desc"  => __( "Number of search results per page. Empty or 0 - use theme default value.", "advanced-woo-search" ),
                "id"    => "search_page_res_per_page",
                "value" => '',
                "min" => 0,
                "type"  => "number"
            );

            $options['general'][] = array(
                "name"  => __( "Change query hook", "advanced-woo-search" ),
                "desc"  => __( "If you have any problems with correct products results on the search results page - try to change this option.", "advanced-woo-search" ),
                "id"    => "search_page_query",
                "value" => 'default',
                "type"  => "radio",
                'choices' => array(
                    'default' => __( 'Default', 'advanced-woo-search' ),
                    'posts_pre_query' => __( 'posts_pre_query', 'advanced-woo-search' ),
                )
            );

            $options['performance'][] = array(
                "name"    => __( "Search options", "advanced-woo-search" ),
                "type"    => "heading"
            );

            $options['performance'][] = array(
                "name"  => __( "Search rule", "advanced-woo-search" ),
                "desc"  => __( "Search rule that will be used for terms search.", "advanced-woo-search" ),
                "id"    => "search_rule",
                "value" => 'contains',
                "type"  => "radio",
                'choices' => array(
                    'contains' => '%s% ' . __( "( contains ). Search query can be inside any part of the product words ( beginning, end, middle ). Slow.", "advanced-woo-search" ),
                    'begins'   => 's% ' . __( "( begins ). Search query can be only at the beginning of the product words. Fast.", "advanced-woo-search" ),
                )
            );

            $options['performance'][] = array(
                "name"  => __( "AJAX timeout", "advanced-woo-search" ),
                "desc"  => __( "Time after user input that script is waiting before sending a search event to the server, ms.", "advanced-woo-search" ),
                "id"    => "search_timeout",
                "value" => 300,
                'min'   => 100,
                "type"  => "number"
            );

            $options['performance'][] = array(
                "name"  => __( "Search words number", "advanced-woo-search" ),
                "desc"  => __( "The maximum number of words allowed for the search. All extra words will be removed from the search query.", "advanced-woo-search" ),
                "id"    => "search_words_num",
                "value" => 6,
                'min'   => 1,
                "type"  => "number"
            );

            $options['performance'][] = array(
                "name"    => __( "Cache options", "advanced-woo-search" ),
                "type"    => "heading"
            );

            $options['performance'][] = array(
                "name"  => __( "Cache results", "advanced-woo-search" ),
                "desc"  => __( "Cache search results to increase search speed.", "advanced-woo-search" ) . '<br>' .
                           __( "Turn off if you have old data in the search results after the content of products was changed.", "advanced-woo-search" ),
                "id"    => "cache",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['performance'][] = array(
                "name"    => __( "Clear cache", "advanced-woo-search" ),
                "type"    => "html",
                "desc"    =>__( "Clear cache for all search results.", "advanced-woo-search" ),
                "html"    => '<div id="aws-clear-cache"><input class="button" type="button" value="' . esc_attr__( 'Clear cache', 'advanced-woo-search' ) . '"><span class="loader"></span></div><br>',
            );

            $options['performance'][] = array(
                "name"    => __( "Index table options", "advanced-woo-search" ),
                "id"      => "index_sources",
                "type"    => "heading"
            );

            $options['performance'][] = array(
                "name"         => __( "Overview", "advanced-woo-search" ),
                'heading_type' => 'text',
                'desc'         => __( 'To perform the search plugin use a special index table. This table contains normalized words of all your products from all available sources.', "advanced-woo-search" ) . '<br>' .
                    __( 'Sometimes when there are too many products in your store index table can be very large and that can reflect on search speed.', "advanced-woo-search" ) . '<br>' .
                    __( 'In this section you can use several options to change the table size by disabling some unused product data.', "advanced-woo-search" ) . '<br>' .
                    '<b>' . __( "Note:", "advanced-woo-search" ) . '</b> ' . __( "Reindex is required after options changes.", "advanced-woo-search" ),
                "type"         => "heading"
            );

            $options['performance'][] = array(
                "name"       => __( "Data to index", "advanced-woo-search" ),
                "desc"       => __( "Choose what products data to add inside the plugin index table.", "advanced-woo-search" ),
                "table_head" => __( 'What to index', 'advanced-woo-search' ),
                "id"         => "index_sources",
                "value" => array(
                    'title'    => 1,
                    'content'  => 1,
                    'sku'      => 1,
                    'excerpt'  => 1,
                    'category' => 1,
                    'tag'      => 1,
                    'id'       => 1,
                ),
                "choices" => array(
                    "title"    => __( "Title", "advanced-woo-search" ),
                    "content"  => __( "Content", "advanced-woo-search" ),
                    "sku"      => __( "SKU", "advanced-woo-search" ),
                    "excerpt"  => __( "Short description", "advanced-woo-search" ),
                    "category" => __( "Category", "advanced-woo-search" ),
                    "tag"      => __( "Tag", "advanced-woo-search" ),
                    "id"       => __( "ID", "advanced-woo-search" ),
                ),
                "type"    => "table"
            );

            $options['performance'][] = array(
                "name"  => __( "Index variations", "advanced-woo-search" ),
                "desc"  => __( "Index or not content of product variations.", "advanced-woo-search" ),
                "id"    => "index_variations",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['performance'][] = array(
                "name"  => __( "Sync index table", "advanced-woo-search" ),
                "desc"  => __( "Automatically update plugin index table when product content was changed. This means that in search there will be always latest product data.", "advanced-woo-search" ) . '<br>' .
                    __( "Turn this off if you have any problems with performance.", "advanced-woo-search" ),
                "id"    => "autoupdates",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['performance'][] = array(
                "name"  => __( "Run shortcodes", "advanced-woo-search" ),
                "desc"  => __( "Execute or not any shortcodes inside product content.", "advanced-woo-search" ),
                "id"    => "index_shortcodes",
                "value" => 'true',
                "inherit" => "true",
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            // Search Form Settings
            $options['form'][] = array(
                "name"  => __( "Text for search field", "advanced-woo-search" ),
                "desc"  => __( "Text for search field placeholder.", "advanced-woo-search" ),
                "id"    => "search_field_text",
                "value" => __( "Search", "advanced-woo-search" ),
                "type"  => "text"
            );

            $options['form'][] = array(
                "name"  => __( "Text for show more button", "advanced-woo-search" ),
                "desc"  => __( "Text for link to search results page at the bottom of search results block.", "advanced-woo-search" ),
                "id"    => "show_more_text",
                "value" => __( "View all results", "advanced-woo-search" ),
                "type"  => "text"
            );

            $options['form'][] = array(
                "name"  => __( "Nothing found field", "advanced-woo-search" ),
                "desc"  => __( "Text when there is no search results.", "advanced-woo-search" ),
                "id"    => "not_found_text",
                "value" => __( "Nothing found", "advanced-woo-search" ),
                "type"  => "textarea",
                'allow_tags' => AWS_Helpers::kses_textarea_allowed_tags()
            );

            $options['form'][] = array(
                "name"  => __( "Minimum number of characters", "advanced-woo-search" ),
                "desc"  => __( "Minimum number of characters required to run ajax search.", "advanced-woo-search" ),
                "id"    => "min_chars",
                "value" => 1,
                "min" => 1,
                "type"  => "number"
            );

            $options['form'][] = array(
                "name"  => __( "AJAX search", "advanced-woo-search" ),
                "desc"  => __( "Use or not live search feature.", "advanced-woo-search" ),
                "id"    => "enable_ajax",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['form'][] = array(
                "name"  => __( "Show loader", "advanced-woo-search" ),
                "desc"  => __( "Show loader animation while searching.", "advanced-woo-search" ),
                "id"    => "show_loader",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['form'][] = array(
                "name"  => __( "Show clear button", "advanced-woo-search" ),
                "desc"  => __( "Show 'Clear search string' button for desktop devices ( for mobile it is always visible ).", "advanced-woo-search" ),
                "id"    => "show_clear",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['form'][] = array(
                "name"  => __( "Show 'View All Results'", "advanced-woo-search" ),
                "desc"  => __( "Show link to search results page at the bottom of search results block.", "advanced-woo-search" ),
                "id"    => "show_more",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' )
                )
            );

            $options['form'][] = array(
                "name"  => __( "Mobile full screen", "advanced-woo-search" ),
                "desc"  => __( "Full screen search on focus. Will not work if the search form is inside the block with position: fixed.", "advanced-woo-search" ),
                "id"    => "mobile_overlay",
                "value" => 'false',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' )
                )
            );

            $options['form'][] = array(
                "name"  => __( "Form Styling", "advanced-woo-search" ),
                "desc"  => __( "Choose search form layout", "advanced-woo-search" ) . '<br>' . __( "Filter button will be visible only if you have more than one active filter for current search form instance.", "advanced-woo-search" ),
                "id"    => "buttons_order",
                "value" => '2',
                "type"  => "radio-image",
                'choices' => array(
                    '1' => 'btn-layout1.png',
                    '2' => 'btn-layout2.png',
                    '3' => 'btn-layout3.png',
                )
            );

            // Search Results Settings
            $options['results'][] = array(
                "name"  => __( "Description source", "advanced-woo-search" ),
                "desc"  => __( "From where to take product description.<br>If first source is empty data will be taken from other sources.", "advanced-woo-search" ),
                "id"    => "desc_source",
                "value" => 'content',
                "type"  => "radio",
                'choices' => array(
                    'content'  => __( 'Content', 'advanced-woo-search' ),
                    'excerpt'  => __( 'Short description', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Description content", "advanced-woo-search" ),
                "desc"  => __( "What to show in product description?", "advanced-woo-search" ),
                "id"    => "mark_words",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( "Smart scraping sentences with searching terms from product description.", "advanced-woo-search" ),
                    'false' => __( "First N words of product description ( number of words that you choose below. )", "advanced-woo-search" ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Description length", "advanced-woo-search" ),
                "desc"  => __( "Maximal allowed number of words for product description.", "advanced-woo-search" ),
                "id"    => "excerpt_length",
                "value" => 20,
                "min" => 0,
                "type"  => "number"
            );

            $options['results'][] = array(
                "name"  => __( "Products number", "advanced-woo-search" ),
                "desc"  => __( "Maximum number of displayed products search results.", "advanced-woo-search" ),
                "id"    => "results_num",
                "value" => 10,
                "min" => 0,
                "type"  => "number"
            );

            $options['results'][] = array(
                "name"  => __( "Archive pages number", "advanced-woo-search" ),
                "desc"  => __( "Maximum number of displayed archive pages search results.", "advanced-woo-search" ),
                "id"    => "pages_results_num",
                "value" => 10,
                "min" => 0,
                "type"  => "number"
            );

            $options['results'][] = array(
                "name"  => __( "Show out-of-stock", "advanced-woo-search" ),
                "desc"  => __( "Show out-of-stock products in search", "advanced-woo-search" ),
                "id"    => "outofstock",
                "value" => $show_out_of_stock,
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'Show', 'advanced-woo-search' ),
                    'false'  => __( 'Hide', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"    => __( "View", "advanced-woo-search" ),
                "type"    => "heading"
            );

            $options['results'][] = array(
                "name"  => __( "Highlight words", "advanced-woo-search" ),
                "desc"  => __( "Highlight search words inside products content.", "advanced-woo-search" ),
                "id"    => "highlight",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show image", "advanced-woo-search" ),
                "desc"  => __( "Show product image for each search result.", "advanced-woo-search" ),
                "id"    => "show_image",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show description", "advanced-woo-search" ),
                "desc"  => __( "Show product description for each search result.", "advanced-woo-search" ),
                "id"    => "show_excerpt",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false'  => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show price", "advanced-woo-search" ),
                "desc"  => __( "Show product price for each search result.", "advanced-woo-search" ),
                "id"    => "show_price",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show price for out of stock", "advanced-woo-search" ),
                "desc"  => __( "Show product price for out of stock products.", "advanced-woo-search" ),
                "id"    => "show_outofstock_price",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show sale badge", "advanced-woo-search" ),
                "desc"  => __( "Show sale badge for products in search results.", "advanced-woo-search" ),
                "id"    => "show_sale",
                "value" => 'true',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show product SKU", "advanced-woo-search" ),
                "desc"  => __( "Show product SKU in search results.", "advanced-woo-search" ),
                "id"    => "show_sku",
                "value" => 'false',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show stock status", "advanced-woo-search" ),
                "desc"  => __( "Show stock status for every product in search results.", "advanced-woo-search" ),
                "id"    => "show_stock",
                "value" => 'false',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            $options['results'][] = array(
                "name"  => __( "Show featured icon", "advanced-woo-search" ),
                "desc"  => __( "Show or not star icon for featured products.", "advanced-woo-search" ),
                "id"    => "show_featured",
                "value" => 'false',
                "type"  => "radio",
                'choices' => array(
                    'true'  => __( 'On', 'advanced-woo-search' ),
                    'false' => __( 'Off', 'advanced-woo-search' ),
                )
            );

            /**
             * Filter admin page options
             * @since 2.15
             * @param array $options Array of options
             */
            $options = apply_filters( 'aws_admin_page_options', $options );

            return $options;

        }

    }

endif;