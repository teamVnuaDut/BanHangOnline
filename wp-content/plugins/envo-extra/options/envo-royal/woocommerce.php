<?php
add_action('customize_register', 'envo_extra_theme_customize_register_woo', 15);

function envo_extra_theme_customize_register_woo($wp_customize) {
    // relocating default WooCommerce sections
    $wp_customize->get_section('woocommerce_store_notice')->panel = 'woo_section_main';
    $wp_customize->get_section('woocommerce_product_catalog')->panel = 'woo_section_main';
    $wp_customize->get_section('woocommerce_product_images')->panel = 'woo_section_main';
    $wp_customize->get_section('woocommerce_checkout')->panel = 'woo_section_main';
}

add_action('after_setup_theme', 'envo_extra_images_action', 15);

function envo_extra_images_action() {

    if (get_theme_mod('woo_gallery_zoom', 1) == 0) {
        remove_theme_support('wc-product-gallery-zoom');
    }
    if (get_theme_mod('woo_gallery_lightbox', 1) == 0) {
        remove_theme_support('wc-product-gallery-lightbox');
    }
    if (get_theme_mod('woo_gallery_slider', 1) == 0) {
        remove_theme_support('wc-product-gallery-slider');
    }
    // Remove related products output
    if (get_theme_mod('woo_remove_related', 1) == 0) {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    }
}

add_filter('loop_shop_per_page', 'envo_extra_new_loop_shop_per_page', 20);

function envo_extra_new_loop_shop_per_page($cols) {
    // $cols contains the current number of products per page based on the value stored on Options -> Reading
    // Return the number of products you wanna show per page.
    $cols = absint(get_theme_mod('archive_number_products', 24));
    return $cols;
}

add_filter('loop_shop_columns', 'envo_extra_loop_columns');

if (!function_exists('envo_extra_loop_columns')) {

    function envo_extra_loop_columns() {
        return absint(get_theme_mod('archive_number_columns', 4));
    }

}

Kirki::add_panel('woo_section_main', array(
    'title' => esc_attr__('WooCommerce', 'envo-extra'),
    'priority' => 5,
));
Kirki::add_section('woo_section', array(
    'title' => esc_attr__('General Settings', 'envo-extra'),
    'panel' => 'woo_section_main',
    'priority' => 1,
));

require_once( plugin_dir_path(__FILE__) . 'woocommerce/archive-shop.php' );
require_once( plugin_dir_path(__FILE__) . 'woocommerce/product-page.php' );
require_once( plugin_dir_path(__FILE__) . 'woocommerce/buttons.php' );

Kirki::add_panel('woo_cart_account', array(
    'title' => esc_attr__('Header (Cart, My Account & Search)', 'envo-extra'),
    'panel' => 'woo_section_main',
    'priority' => 5,
));

require_once( plugin_dir_path(__FILE__) . 'woocommerce/header-cart.php' );
require_once( plugin_dir_path(__FILE__) . 'woocommerce/my-account.php' );
require_once( plugin_dir_path(__FILE__) . 'woocommerce/wishlist.php' );
require_once( plugin_dir_path(__FILE__) . 'woocommerce/compare.php' );
require_once( plugin_dir_path(__FILE__) . 'woocommerce/header-search.php' );


/**
 * Add custom CSS styles
 */
function envo_extra_woo_enqueue_header_css() {

    $css = '';
	$devices = array(
		'desktop'	 => array(
			'media_query_key'	 => '',
			'media_query'		 => '@media only screen and (min-width: 992px)',
			'image'				 => '48',

		),
		'tablet'	 => array(
			'media_query_key'	 => 'media_query',
			'media_query'		 => '@media only screen and (max-width: 991px)',
			'image'				 => '48',

		),
		'mobile'	 => array(
			'media_query_key'	 => 'media_query',
			'media_query'		 => '@media only screen and (max-width: 767px)',
			'image'				 => '100',

		),
	);
	foreach ( $devices as $key => $value ) {
		$img_width = get_theme_mod('woo_single_image_width' . $key, $value[ 'image' ]);
		$summary_width = ( 100 - $img_width );
		$summary_width = ( $summary_width == 0 ) ? 100 : $summary_width;
		if (is_rtl()) {
			$css .=  $value[ 'media_query' ] .' {.woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary{width: ' . $summary_width . '%; padding-right: 4%;}}';
		} else {
			$css .=  $value[ 'media_query' ] .' {.woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary{width: ' . $summary_width . '%; padding-left: 4%;}}';    
		}
	}
    
    $plus_minus = get_theme_mod('woo_hide_plus_minus', 'block');
    $equal_height = get_theme_mod('woo_archive_product_equal_height', 1);
    
    if ($plus_minus == 0) {
        $css .= '.woocommerce div.product form.cart div.quantity {margin-right: 4px!important;}';
    }
    if ($equal_height == 1) {
        $css .= '.woocommerce ul.products{display:flex;flex-wrap:wrap}.woocommerce li.product{display:flex;flex-direction:column}.woocommerce ul.products li.product a.button{margin-top:auto}@media (max-width:768px){.woocommerce ul.products[class*=columns-] li.product,.woocommerce-page ul.products[class*=columns-] li.product{margin-right:3.8%}.woocommerce ul.products[class*=columns-] li.product:nth-child(2n),.woocommerce-page ul.products[class*=columns-] li.product:nth-child(2n){margin-right:0}}';
    }
    wp_add_inline_style('woocommerce-inline', $css, 9999);
}

add_action('wp_enqueue_scripts', 'envo_extra_woo_enqueue_header_css', 9999);


// Add the opening div to the img
function envo_extra_add_img_wrapper_start() {
    echo '<div class="archive-img-wrap">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'envo_extra_add_img_wrapper_start', 8, 2 );

// Close the div that we just added
function envo_extra_add_img_wrapper_close() {
    echo '</div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'envo_extra_add_img_wrapper_close', 12, 2 );

add_action('woocommerce_before_shop_loop_item_title','envo_extra_custom_before_shop_loop_item_title', 2 ); 
function envo_extra_custom_before_shop_loop_item_title(){
    remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 10 );
    add_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash', 4 );
}

if (!function_exists('envo_extra_breadcrumbs')) :

    /**
     * Breadcrumbs
     */
    if (get_theme_mod('woo_archive_breadcrumbs', 1) == 1)  {
        add_action('woocommerce_archive_description', 'envo_extra_breadcrumbs', 5);
    }
    if (get_theme_mod('woo_product_breadcrumbs', 1) == 1) {
        add_action('woocommerce_single_product_summary', 'envo_extra_breadcrumbs', 1);
    }

    function envo_extra_breadcrumbs() {
        $args = array(
            'wrap_before' => '<div class="woo-breadcrumbs" itemprop="breadcrumb">',
            'wrap_after'  => '</div>',
        );
        woocommerce_breadcrumb($args);
    }

endif;