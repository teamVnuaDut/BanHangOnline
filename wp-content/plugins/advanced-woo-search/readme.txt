=== Advanced Woo Search ===
Contributors: Mihail Barinov
Donate link: https://www.paypal.com/donate/?hosted_button_id=FDRDNZE6XAKE8
Tags: woocommerce, search, product search, woocommerce search, live search
Requires at least: 4.0
Tested up to: 6.7
Stable tag: 3.29
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Advanced WooCommerce search plugin. Search inside any product field. Support for both AJAX search and search results page.

== Description ==

Advanced Woo Search - powerful search plugin for WooCommerce. Supports **AJAX** search and **search results page** display.

[Plugin home page](https://advanced-woo-search.com/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo) | [Features List](https://advanced-woo-search.com/features/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo) | [Docs](https://advanced-woo-search.com/guide/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo)

## Main Features

* **Products search** - Search across all your WooCommerce products
* **Search in** - Search in product **title**, **content**, **excerpt**, **categories**, **tags**, **ID** and **sku**. Or just in some of them
* **Terms search** - Search for product categories and tags
* **Smart ordering** - Search results ordered by the priority of source where they were found
* Supports **variable products**
* **Plurals** support
* **Synonyms** support
* **Misspelling** correction ( fuzzy search )
* **Search results page** support. Plugin search results will be integrated to your current page layout.
* **Settings page** - User-friendly settings page with lot of options
* **Shortcode** and **widget** - Use shortcode and widget to place search box anywhere you want
* **Product image** - Each search result contains product image
* **Product price** - Each search result contains product price
* **Fast** - Nothing extra. Just what you need for proper work
* **Stop Words** support to exclude certain words from search.
* Automatically synchronize all product data. No need to re-index all content manually after every change.
* Diacritical marks support
* Google Analytics support
* Seamless integration option for easy replacing your current search form
* **WPML**, **Polylang**, **WooCommerce Multilingual**, **qTranslate**, **GTranslate**, etc. support
* **WPML multi-currency** support
* Page builder plugins support: Gutenberg, Elementor, Beaver Builder, WPBakery, Divi Builder
* Custom Product Tabs for WooCommerce plugin support
* Search Exclude plugin support

## Premium Features

Additional features available only in the PRO plugin version.
	
* Search **results layouts**
* Search **form layouts**
* **Filters**. Switch between tabs to show different search results
* **Unlimited** amount of search form instances
* Search for custom taxonomies and attributes **archive pages**
* Support for **variable products**: show child products, parent product or both in search results.
* Product **GTIN**, **UPC**, **EAN** or **ISBN** fields search.
* Product **attributes** search ( including custom attributes)
* Product **custom taxonomies** search
* Product **custom fields** search
* **Users** search
* **Advanced settings page** with lot of options
* **Exclude/include** specific products by its ids, taxonomies or attributes from search results
* Ability to specify **source of image** for search results: featured image, gallery, product content, product short description or set default image if there is no other images
* **Visibility/stock status option** - choose what catalog visibility and stock status must be for product to displayed in search results
* Show product **categories** and **tags** in search results
* AND or OR search logic
* **Add to cart** button in search results
* Support for [WooCommerce Brands plugin](https://woocommerce.com/products/brands/)
* Support for **Advanced Custom Fields** plugin
* Support for **WCFM - WooCommerce Multivendor Marketplace** plugin
* Support for **Dokan – WooCommerce Multivendor Marketplace** plugin
* Support for **MultiVendorX – Multivendor Marketplace** plugin
* And the [huge list of other integrations](https://advanced-woo-search.com/guide-category/integrations/)

[Features list](https://advanced-woo-search.com/features/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo)

### More Plugins From Us

Here are some additional plugins that were made with love.

* [Advanced Woo Labels](https://wordpress.org/plugins/advanced-woo-labels/) - advanced labels for WooCommerce products
* [Share This Image](https://wordpress.org/plugins/share-this-image/) - image sharing plugin

### More useful links

* Plugin [homepage](https://advanced-woo-search.com/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo).
* Plugin [documentation](https://advanced-woo-search.com/guide/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo).
* Follow Advanced Woo Search on [Twitter](https://twitter.com/WooSearch)

== Installation ==

1. Upload advanced-woo-search to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the plugin shortcode [aws_search_form] into your template or post-page or just use build-in widget

== Frequently Asked Questions ==

Please visit our [Advanced Woo Search guide](https://advanced-woo-search.com/guide/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo) before requesting any support.

= What is Advanced Woo Search? =

Advanced Woo Search as is advanced search plugin for WooCommerce shops. Its packed with many usefull features like:

* Search by product title, content, short description, SKU, tags, categories, ID, custom fields, attributes, taxonomies.
* Support for variable product and its variations.
* Multilingual plugins support.
* Search and display product tags, categories, custom taxonomies.
* and many more...

Please visit [features page](https://advanced-woo-search.com/features/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo) for full list of available features.

= What are the requirements to use Advanced Woo Search? =

Advanced Woo Search is a plugin for self-hosted WordPress sites, or wordpress.com hosted sites that allow installation of third party plugins.
Plugin requires the following at minimum to work properly:

* WordPress 4.0 or greater
* WooCommerce 3.0.0 or greater
* PHP 5.5 or greater
* MySQL 5.6 or MariaDB 10.0 or greater
* Apache or Nginx server (recommended, but other options may work as well)

= How to insert search form? =

There are several ways you can add plugins search form on your site. The simplest way - is by turning on the **Seamless integration** option from the plugins settings page.

You can also use build-in widget to place plugins search form to your sidebar or any other available widget area.

Or just use shortcode for displaying form inside your post/page:

`[aws_search_form]`

Or insert this function inside php file ( often it used to insert form inside page templates files ):

`echo do_shortcode( '[aws_search_form]' );`

Also please read the guide article about search form placement: [Adding Search Form.](https://advanced-woo-search.com/guide/search-form/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo)

= What is the steps to make this plugin works on my site? =

In order to start using the plugin search form you need to take following steps:

* **Installation**. Install and activate the plugin. You can follow [these steps](https://advanced-woo-search.com/guide/installation/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo) if you face any problems.
* **Index plugin table**. Click on the **Reindex table** button inside the plugin settings page and wait till the index process is finished.
* **Set plugin settings**. Leave it to default values or customize some of them.
* **Add search form**. There are several ways you can add a search form to your site. Use the **Seamless integration** option, shortcode, widget or custom php function. Read more in the guide article: [Adding Search Form](https://advanced-woo-search.com/guide/search-form/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo).
* **Finish!** Now all is set and you can check your search form on the pages where you add it.

= Will this plugin work with my theme? =

Plugin search will works with most of the available WordPress themes. If you faced any problems using the plugin with your theme please [contact support](https://advanced-woo-search.com/contact/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo).

= Is it integrated with my plugin? =

Advanced Woo Search works with many plugins out-of-the-box. For some of the most popular plugins we manually check proper work of integration features. It is the plugins like **Advanced Custom Fields**, **WPML**, **Polylang**, **Elementor**, **Divi Builder**, **BeRocket AJAX Product Filters**, **FacetWP** and many more.

Please read some guide integrations articles: [Integrations](https://advanced-woo-search.com/guide-category/integrations/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo).

Note that if some of the plugin is not presented in the list it doesn't mean that it will not work with Advanced Woo Search. Many plugins will just work without any extra effort. But if you find any problem with your plugin and Advanced Woo Search please [contact support team](https://advanced-woo-search.com/contact/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo).

= Is this plugin compatible with latest version of Woocommerce? =

Yep. This plugin is always compatible with the latest version of Woocommerce?

== Screenshots ==

1. Search from front-end view
1. Search form in sidebar added as widget
2. Plugin settings page. General options
3. Plugin settings page. Search form options
4. Plugin settings page. Search results options

== Changelog ==

[View full changelog](https://advanced-woo-search.com/guide/free-version/?utm_source=wp-repo&utm_medium=listing&utm_campaign=aws-repo)

= 3.29 ( 2025-03-17 ) =
* Update - Integration for Elementor search form widget
* Fix - aws_search_terms parameters escaping
* Dev - Add aws_index_scraped_data filter

= 3.28 ( 2025-03-03 ) =
* Update - Tested with WC 9.7
* Fix - Bug with additional slashed for text options

= 3.27 ( 2025-02-17 ) =
* Fix - Highlight synonyms words for search results

= 3.26 ( 2025-02-03 ) =
* Update - Enable gutenberg search block for navigation section
* Update - Tested with WC 9.6

= 3.25 ( 2025-01-20 ) =
* Dev - Add filter for search form shortcode parameters

= 3.24 ( 2024-12-30 ) =
* Update - Tested with WC 9.5
* Update - Settings page pro block description

= 3.23 ( 2024-12-16 ) =
* Add - New shortcodes for similar terms, search terms
* Fix - Integration with Yoast Premium plugin. Allow additional parameters for permalinks
* Dev - Update forceNewSearch method to use additional parameters

= 3.22 ( 2024-11-29 ) =
* Update - Settings page descriptions

= 3.21 ( 2024-11-26 ) =
* Update - Tested with WC 9.4
* Update - Improve search speed and update index table structure
* Update - Structure of cache table
* Dev - Update conditions for table check

= 3.20 ( 2024-11-11 ) =
* Update - Integration with YITH WooCommerce Ajax Product Filter plugin
* Update - Taxonomies search when using Begins search logic
* Fix - Bug with diacritical characters replacement

= 3.19 ( 2024-10-28 ) =
* Update - Add different search rules for taxonomies search
* Update - Styles for search form. Fix min height for input
* Dev - Add aws_search_custom_top_results filter

= 3.18 ( 2024-10-14 ) =
* Add - Support for Jet Blocks plugin search widget
* Update - Support for Italian language
* Update - Admin page notices about integrations
* Dev - Update the structure of plugin tables

= 3.17 ( 2024-10-01 ) =
* Add - Support for Shopical theme
* Add - Support for WooCommerce Product Table by Barn2 plugin
* Fix - Index duplicated products when using WPML plugin
* Fix - Bug with quick edit feature when using WPML plugin
* Dev - New check for currently active plugins

= 3.16 ( 2024-09-16 ) =
* Add - Greek to latin characters replace
* Add - Plurals support for different languages
* Add - Support for Breakdance plugin
* Update - Tested with WC 9.3
* Update - On page stock status and sale filters
* Fix - Show clear button on first search when ajax is disabled

= 3.15 ( 2024-09-02 ) =
* Update - Tested with WC 9.2
* Update - Add new parameters for orderby
* Update - Settings page text
* Fix - Filter by attributes for the search page. Fix filtering by slugs with integers inside
* Dev - Add additional check for product IDs inside search page

= 3.14 ( 2024-08-12 ) =
* Update - Integration with Oxygen theme
* Fix - Filtering for search results page when using slugs with numbers inside URL for terms
* Fix - Add new parameters for orderby function
* Fix - Automatically index products that were restored from the trash
* Dev - Fix functions naming

= 3.13 ( 2024-08-05 ) =
* Add - Integration with EAN for WooCommerce by WPFactory plugin
* Fix - Bug with search results filtering via third party plugins
* Fix - Bug when filtering product variations on search results page
* Dev - New function to get current page id

= 3.12 ( 2024-07-19 ) =
* Fix - Bug with search results page taxonomies filters

= 3.11 ( 2024-07-15 ) =
* Add - Support for Filter Everything plugin
* Update - Tested with WC 9.1
* Update - Integration with WooCommerce Product Filter by WooBeWoo plugin
* Update - Integration with Product Filters for WooCommerce plugin
* Update - Integration with YITH WooCommerce Ajax Product Filter plugin
* Update - Integration with BeRocket WooCommerce AJAX Products Filter plugin
* Update - Filter for search results page. Inherit tax_query parameters
* Update - Integration with Avada theme. Move js scripts to footer
* Update - Integration with The7 theme. Fix submit button for search forms
* Update - Admin notice about reindex on first plugin activation
* Fix - Bug with calling search function inside wp admin orders page

= 3.10 ( 2024-07-01 ) =
* Add - New option to show fixed terms list inside search results
* Add - Support for Hitek theme
* Update - Change default value for Form Styling option
* Update - Tested with WC 9.0
* Update - Integration with Bricks theme
* Fix - Return content and short description for post object inside search page
* Dev - Add aws_search_fixed_terms_suggestions_num filter
* Dev - Add js event aws_search_force to force search with specific terms
* Dev - Add old/new pairs for similar terms output
* Dev - Add generate_combinations helper function
* Dev - Add aws_search_page_posts_objects_ids filter hook
* Dev - Add aws_search_notices filter

= 3.09 ( 2024-06-17 ) =
* Add - Support for The7 theme
* Update - Integration with Shoptimizer theme
* Fix - Disable misspelling fix for numeric search terms
* Dev - Add new parameter for aws_search_results_products_ids filter
* Dev - Add aws_search_no_results filter
* Dev - Add aws_search_custom_results_data and aws_search_top_text filters

= 3.08 ( 2024-06-05 ) =
* Add - Integration for YITH WooCommerce Ajax Product Filter plugin
* Update - Speed up search results page
* Update - Integration with WOOF plugin
* Update - Tested with WC 8.9
* Update - Reduce the size of search results output
* Fix - FaceWP plugin integration fix
* Fix - Misspelling function now respect search terms sources
* Dev - Replace aws_products_search_page_filtered and aws_products_order hooks
* Dev - Add show_wcfm_badge filter
* Dev - Update aws_searchpage_enabled function

= 3.07 ( 2024-05-20 ) =
* Add - Header for plugin admin page

= 3.06 ( 2024-05-06 ) =
* Add - Required plugins dependencies
* Update - Formula to calculate relevance score
* Update - Support for Zephyr theme
* Dev - New class for terms suggestions

= 3.05 ( 2024-04-22 ) =
* Add - Feature to fix misspellings inside search queries
* Add - Support for Open Shop theme
* Add - Support for Advance Product Search by themehunk
* Update - Tested with WC 8.8
* Fix - Search results sorting inside results page when using custom filters

= 3.04 ( 2024-04-08 ) =
* Add - Support for WooCommerce Product Search plugin
* Update - Values for search page filters

= 3.03 ( 2024-03-25 ) =
* Update - Tested with WC 8.7
* Update - Add loading icon for clear cache button inside settings page
* Update - New values for orderby for better integration with themes
* Fix - Bug with adding new products during index process

= 3.02 ( 2024-03-12 ) =
* Update - Integration for WPML plugin. Fix indexation for product taxonomies
* Dev - Add aws_index_before_scrapping and aws_index_after_scrapping hooks

= 3.01 ( 2024-02-26 ) =
* Update - Tested with WC 8.6
* Update - Indexation for synonyms phrases
* Fix - Bug with search results page integration with Elementor

= 3.00 ( 2024-02-12 ) =
* Add - New option to limit maximal number of search words

= 2.99 ( 2024-02-05 ) =
* Update - Plugin settings page. Set minimal values for some options
* Update - Support for Elessi theme. Fix shop filters
* Dev - Add aws_admin_capability filter

= 2.98 ( 2024-01-22 ) =
* Update - Remove plugin options during uninstall

= 2.97 ( 2024-01-10 ) =
* Update - Notices about plugin integrations
* Update - Tested with WC 8.5
* Update - Integration with Dynamic Content for Elementor plugin
* Fix - FacetWP plugin issue with pagination

= 2.96 ( 2024-01-08 ) =
* Update - SQL query for taxonomies search results. Speed up search for multilingual results
* Update - Woodmart theme support. Fix seamless integration for header

= 2.95 ( 2023-12-25 ) =
* Add - Support for WooCommerce Show Single Variations by Iconic plugin
* Update - Tested with WC 8.4
* Update - Integration with GA4
* Dev - Add awsAnalytics js event

= 2.94 ( 2023-12-12 ) =
* Add - Support for WooCommerce Memberships plugin
* Update - Notices about plugin integrations

= 2.93 ( 2023-11-27 ) =
* Update - WCFM plugin integration. Fix search form on stores list page sidebar
* Update - Tested with WC 8.3
* Fix - Integration with Elessi theme. Fix search form after using shop filters
* Dev - Add aws_index_started action

= 2.92 ( 2023-11-14 ) =
* Add - Support for Hestia theme

= 2.91 ( 2023-10-30 ) =
* Update - Support for Astra theme. Fix broken search form in the header