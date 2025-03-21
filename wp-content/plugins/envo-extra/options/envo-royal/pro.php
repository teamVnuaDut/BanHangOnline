<?php

$sections = array( 
	'colors_preset_section',
	'back_to_top', 
	'footer_credits', 
	'footer_widgets', 
	'theme_header',
	'header_title_tagline', 
	'main_colors_section', 
	'header_layout',
	'title_tagline',
	'category_menu', 
	'blog_posts', 
	'posts_pages', 
	'main_menu', 
	'top_bar', 
	'main_sidebar', 
	'woo_archive_global_section',
	'woo_archive_button_section',
	'woo_archive_excerpt',
	'woo_archive_gallery',
	'woo_archive_image_section',
	'woo_archive_product_section',
	'woo_archive_rating_section',
	'woo_archive_sale_section',
	'woo_compare_section',
	'woo_wishlist_section',
	'woo_account_section',
	'woo_archive_gallery', 
	'woo_cart_section', 
	'woo_search_section', 
	'woo_archive_off_canvas', 
	'woo_product_global_section',
	'woo_product_button',
	'woo_product_tabs',
	'woo_product_meta',
	'woo_product_sale',
	'woo_archive_quick_view', 
	'woo_sale_countdown', 
	'woo_social_sharing', 
	'woo_swatches_section' 
);
foreach ( $sections as $value ) {
		
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => $value . '_go_pro',
		'section'	 => $value,
		'priority'	 => 100,
		'default'	 => '<a href="https://envothemes.com/product/envo-royal-pro/" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/'. $value .'.webp"/></a>',
	) );
}

