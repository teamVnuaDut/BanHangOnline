<?php
 
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'sticky_menu_pro',
	'section'	 => 'main_menu',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#sticky-menu" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/sticky-menu.jpg"/></a>',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'sticky_sidebar_pro',
	'section'	 => 'main_sidebar',
	'priority'	 => 9,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#sticky-sidebar" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/sticky-sidebar.jpg"/></a>',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'disable_credits_pro',
	'section'	 => 'footer_credits',
	'priority'	 => 9,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#footer-credits" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/footer-credits.jpg"/></a>',
) );
if ( envo_extra_check_for_elementor() ) { // Enable only with Elementor
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => 'custom_elementor_footer_pro',
		'section'	 => 'footer_credits',
		'priority'	 => 4,
		'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#elementor-section" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/custom-elementor-footer.jpg"/></a>',
	) );

	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => 'custom_elementor_header_pro',
		'section'	 => 'header_title_tagline',
		'priority'	 => 4,
		'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#elementor-section" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/custom-elementor-header.jpg"/></a>',
	) );

	Kirki::add_section('404_page', array(
		'title' => esc_attr__('404 page', 'envo-extra'),
		'panel' => 'envo_theme_panel',
		'priority' => 80,
	));

	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'custom',
		'settings'	 => 'custom_elementor_404_pro',
		'section'	 => '404_page',
		'priority'	 => 4,
		'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#elementor-section" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/custom-elementor-404.jpg"/></a>',
	) );
}
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_global_pro',
	'section'	 => 'woo_archive_global_section',
	'priority'	 => 5,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#image-flipper" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-archive-global.jpg"/></a>',
) );

Kirki::add_section( 'woo_archive_excerpt_section', array(
	'title'		 => esc_attr__( 'Excerpt', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 11,
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_excerpt_pro',
	'section'	 => 'woo_archive_excerpt_section',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#excerpt" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-excerpt.jpg"/></a>',
) );

Kirki::add_section('woo_archive_gallery', array(
    'title' => esc_attr__('Archive gallery images', 'envo-extra'),
    'panel' => 'woo_section_main',
    'priority' => 8,
));

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_gallery_images_pro',
	'section'	 => 'woo_archive_gallery',
	'priority'	 => 5,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#gallery-images" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-gallery-images.jpg"/></a>',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_cart_pro',
	'section'	 => 'woo_cart_section',
	'priority'	 => 9,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#popup-cart" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-cart.jpg"/></a>',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_search_pro',
	'section'	 => 'woo_search_section',
	'priority'	 => 9,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#search-in-sku" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-search-in-sku.jpg"/></a>',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_my_acocunt_pro',
	'section'	 => 'woo_account_section',
	'priority'	 => 11,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#popup-account" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-popup-account.jpg"/></a>',
) );

Kirki::add_section( 'woo_archive_off_canvas', array(
	'title'		 => esc_attr__( 'Off Canvas Filter', 'envo-extra' ),
	'panel'		 => 'woo_section_main',
	'priority'	 => 9,
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_off_canvas_pro',
	'section'	 => 'woo_archive_off_canvas',
	'priority'	 => 9,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#off-canvas" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-off-canvas.jpg"/></a>',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_ajax_add_cart_pro',
	'section'	 => 'woo_product_global_section',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#ajax-add-cart" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-ajax-add-cart.jpg"/></a>',
) );

Kirki::add_section( 'woo_archive_quick_view', array(
	'title'		 => esc_attr__( 'Quick View', 'envo-extra' ),
	'panel'		 => 'woo_section_main',
	'priority'	 => 8,
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_quick_view_pro',
	'section'	 => 'woo_archive_quick_view',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#quick-view" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-quick-view.jpg"/></a>',
) );

Kirki::add_section( 'woo_sale_countdown', array(
	'title'		 => esc_attr__( 'Sale Countdown', 'envo-extra' ),
	'panel'		 => 'woo_section_main',
	'priority'	 => 7,
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_sale_countdown_pro',
	'section'	 => 'woo_sale_countdown',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#sale-countdown" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-sale-countdown.jpg"/></a>',
) );

Kirki::add_section('woo_social_sharing', array(
    'title' => esc_attr__('Social sharing', 'envo-extra'),
    'panel' => 'woo_section_main',
    'priority' => 8,
));

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_social_sharing_pro',
	'section'	 => 'woo_social_sharing',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#social-sharing" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-social-sharing.jpg"/></a>',
) );

Kirki::add_section('woo_swatches_section', array(
    'title' => esc_attr__('Variation Swatches', 'envo-extra'),
    'panel' => 'woo_section_main',
    'priority' => 7,
));

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_swatches_section_pro',
	'section'	 => 'woo_swatches_section',
	'priority'	 => 10,
	'default'	 => '<a href="https://enwoo-wp.com/enwoo-pro/#variation-swatches" target="_blank"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/img/pro/woo-variation-swatches.jpg"/></a>',
) );