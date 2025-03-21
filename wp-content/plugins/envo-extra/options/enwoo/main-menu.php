<?php

if ( !class_exists( 'Kirki' ) ) {
	return;
}
Kirki::add_panel( 'theme_menus', array(
	'title'		 => esc_attr__( 'Menus', 'envo-extra' ),
	'panel'		 => 'theme_header',
	'priority'	 => 15,
) );

Kirki::add_section( 'main_menu', array(
	'title'		 => esc_attr__( 'Main Menu', 'envo-extra' ),
	'panel'		 => 'theme_menus',
	'priority'	 => 10,
) );
if ( get_theme_mod( 'header_layout', (envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'woonav' : 'busnav' ) ) == 'woonav' ) {
	Kirki::add_section( 'secondary_menu', array(
		'title'		 => esc_attr__( 'Secondary Menu', 'envo-extra' ),
		'panel'		 => 'theme_menus',
		'priority'	 => 20,
	) );

	Kirki::add_section( 'category_menu', array(
		'title'		 => esc_attr__( 'Category Menu', 'envo-extra' ),
		'panel'		 => 'theme_menus',
		'priority'	 => 30,
	) );
}
$devices = array(
	'desktop'	 => array(
		'media_query_key'	 => '',
		'media_query'		 => '',
		'description'		 => 'Desktop',
	),
	'tablet'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 991px)',
		'description'		 => 'Tablet',
	),
	'mobile'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 767px)',
		'description'		 => 'Mobile',
	),
);

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'menu_position',
	'label'		 => __( 'Menu Alignment', 'envo-extra' ),
	'section'	 => 'main_menu',
	'default'	 => 'left',
	'priority'	 => 10,
	'choices'	 => array(
		'left'	 => '<i class="dashicons dashicons-editor-alignleft"></i>',
		'center' => '<i class="dashicons dashicons-editor-aligncenter"></i>',
		'right'	 => '<i class="dashicons dashicons-editor-alignright"></i>',
	),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'typography_mainmenu_separator_top',
	'section'	 => 'main_menu',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Menu Font', 'envo-extra' ),
	'section'	 => 'main_menu',
	'settings'	 => 'typography_mainmenu_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'typography_mainmenu' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'main_menu',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'text-transform'	 => 'uppercase',
			'color'				 => '',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none'
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '#site-navigation, #site-navigation .navbar-nav > li > a, #site-navigation .dropdown-menu > li > a',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
			array(
				'choice'					 => 'color',
				'element'					 => '.open-panel span',
				'property'					 => 'background-color',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
			array(
				'choice'					 => 'color',
				'element'					 => '.navbar-default .navbar-brand.brand-absolute',
				'property'					 => 'color',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'typography_mainmenu_separator_bottom',
	'section'	 => 'main_menu',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'main_menu_colors',
	'label'		 => esc_attr__( 'Main Menu Colors', 'envo-extra' ),
	'section'	 => 'main_menu',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'bg_color_mainmenu'			 => esc_attr__( 'Menu Background', 'envo-extra' ),
		'text_hover_mainmenu'		 => esc_attr__( 'Font hover', 'envo-extra' ),
		'bg_text_hover_mainmenu'	 => esc_attr__( 'Item background hover', 'envo-extra' ),
		'active_text_mainmenu'		 => esc_attr__( 'Active item font', 'envo-extra' ),
		'active_text_bg_mainmenu'	 => esc_attr__( 'Active item background', 'envo-extra' ),
	),
	'default'	 => array(
		'bg_color_mainmenu'			 => '',
		'text_hover_mainmenu'		 => '',
		'bg_text_hover_mainmenu'	 => '',
		'active_text_mainmenu'		 => '',
		'active_text_bg_mainmenu'	 => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'bg_color_mainmenu',
			'element'	 => '#site-navigation, #site-navigation .dropdown-menu, #site-navigation.shrink, .header-cart-block .header-cart-inner ul.site-header-cart, .center-cart-middle, .main-menu, #second-site-navigation',
			'property'	 => 'background-color',
		),
		array(
			'choice'		 => 'bg_color_mainmenu',
			'element'		 => '#site-navigation .navbar-nav a, .openNav .menu-container',
			'property'		 => 'background-color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'	 => 'text_hover_mainmenu',
			'element'	 => '#site-navigation .navbar-nav > .open > a:hover, #site-navigation .navbar-nav > li > a:hover, #site-navigation .dropdown-menu > li > a:hover',
			'property'	 => 'color',
		),
		array(
			'choice'		 => 'text_hover_mainmenu',
			'element'		 => '#site-navigation .navbar-nav a:hover',
			'property'		 => 'color',
			'media_query'	 => '@media (max-width: 767px)',
			'suffix'		 => '!important',
		),
		array(
			'choice'	 => 'bg_text_hover_mainmenu',
			'element'	 => '#site-navigation .navbar-nav > li > a:hover, #site-navigation .dropdown-menu > li > a:hover, #site-navigation .nav .open > a, #site-navigation .nav .open > a:hover, #site-navigation .nav .open > a:focus',
			'property'	 => 'background-color',
		),
		array(
			'choice'		 => 'bg_text_hover_mainmenu',
			'element'		 => '#site-navigation .navbar-nav a:hover',
			'property'		 => 'background-color',
			'media_query'	 => '@media (max-width: 767px)',
			'suffix'		 => '!important',
		),
		array(
			'choice'	 => 'active_text_mainmenu',
			'element'	 => '#site-navigation .navbar-nav > li.active > a, #site-navigation .dropdown-menu > .active.current-menu-item > a, .dropdown-menu > .active > a, .home-icon.front_page_on i, .navbar-default .navbar-nav > .open > a',
			'property'	 => 'color',
		),
		array(
			'choice'		 => 'active_text_mainmenu',
			'element'		 => '#site-navigation .navbar-nav .active a',
			'property'		 => 'color',
			'media_query'	 => '@media (max-width: 767px)',
			'suffix'		 => '!important',
		),
		array(
			'choice'	 => 'active_text_bg_mainmenu',
			'element'	 => '#site-navigation .navbar-nav > li.active > a, #site-navigation .dropdown-menu > .active.current-menu-item > a, .dropdown-menu > .active > a, li.home-icon.front_page_on, li.home-icon.front_page_on:before',
			'property'	 => 'background-color',
		),
		array(
			'choice'		 => 'active_text_bg_mainmenu',
			'element'		 => '#site-navigation .navbar-nav .active.current-menu-item a, .dropdown-menu > .active > a',
			'property'		 => 'background-color',
			'media_query'	 => '@media (max-width: 767px)',
			'suffix'		 => '!important',
		),
	),
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'off_canvas_main_menu_colors',
	'label'		 => esc_attr__( 'Off-Canvas (Mobile) Menu Colors', 'envo-extra' ),
	'section'	 => 'main_menu',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'color_mainmenu'			 => esc_attr__( 'Text', 'envo-extra' ),
		'bg_color_mainmenu'			 => esc_attr__( 'Menu Background', 'envo-extra' ),
		'text_hover_mainmenu'		 => esc_attr__( 'Font hover', 'envo-extra' ),
		'bg_text_hover_mainmenu'	 => esc_attr__( 'Item background hover', 'envo-extra' ),
		'active_text_mainmenu'		 => esc_attr__( 'Active item font', 'envo-extra' ),
		'active_text_bg_mainmenu'	 => esc_attr__( 'Active item background', 'envo-extra' ),
	),
	'default'	 => array(
		'color_mainmenu'			 => '',
		'bg_color_mainmenu'			 => '',
		'text_hover_mainmenu'		 => '',
		'bg_text_hover_mainmenu'	 => '',
		'active_text_mainmenu'		 => '',
		'active_text_bg_mainmenu'	 => '',
	),
	'output'	 => array(
		array(
			'choice'		 => 'color_mainmenu',
			'element'		 => '.hc-offcanvas-nav .nav-item-link, .hc-offcanvas-nav li.nav-close a, .hc-offcanvas-nav .nav-back a',
			'property'		 => 'color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'		 => 'color_mainmenu',
			'element'		 => '.hc-offcanvas-nav .nav-next span::before, .hc-offcanvas-nav .nav-back span::before',
			'property'		 => 'border-color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'		 => 'color_mainmenu',
			'element'		 => '.hc-offcanvas-nav a.nav-next, .hc-offcanvas-nav .nav-item-link, .hc-offcanvas-nav li.nav-close a, .hc-offcanvas-nav .nav-back a',
			'property'		 => 'border-color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'	 => 'bg_color_mainmenu',
			'element'	 => '.hc-offcanvas-nav .nav-item-link, .hc-offcanvas-nav li.nav-close a, .hc-offcanvas-nav .nav-back a, .hc-offcanvas-nav .nav-next span',
			'property'	 => 'background-color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'	 => 'text_hover_mainmenu',
			'element'	 => '.hc-offcanvas-nav:not(.touch-device) li:not(.nav-item-custom) a:not([disabled]):hover',
			'property'	 => 'color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'	 => 'bg_text_hover_mainmenu',
			'element'	 => '.hc-offcanvas-nav:not(.touch-device) li:not(.nav-item-custom) a:not([disabled]):hover',
			'property'	 => 'background-color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'	 => 'active_text_mainmenu',
			'element'	 => '.hc-offcanvas-nav:not(.touch-device) li.active:not(.nav-item-custom) a:not([disabled])',
			'property'	 => 'color',
			'media_query'	 => '@media (max-width: 767px)',
		),
		array(
			'choice'	 => 'active_text_bg_mainmenu',
			'element'	 => '.hc-offcanvas-nav:not(.touch-device) li.active:not(.nav-item-custom) a:not([disabled])',
			'property'	 => 'background-color',
			'media_query'	 => '@media (max-width: 767px)',
		),

	),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'mainmenu_separator_top',
	'section'			 => 'main_menu',
	'priority'			 => 20,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'select',
	'settings'			 => 'mainmenu_border_style',
	'label'				 => esc_html__( 'Menu border', 'envo-extra' ),
	'section'			 => 'main_menu',
	'default'			 => 'none',
	'priority'			 => 20,
	'placeholder'		 => esc_html__( 'Choose an option', 'envo-extra' ),
	'choices'			 => array(
		'none'	 => esc_html__( 'None', 'envo-extra' ),
		'solid'	 => esc_html__( 'Solid', 'envo-extra' ),
		'double' => esc_html__( 'Double', 'envo-extra' ),
		'dotted' => esc_html__( 'Dotted', 'envo-extra' ),
		'dashed' => esc_html__( 'Dashed', 'envo-extra' ),
		'groove' => esc_html__( 'Groove', 'envo-extra' ),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element'	 => '#second-site-navigation',
			'property'	 => 'border-style',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'mainmenu_border_width',
	'label'				 => esc_attr__( 'Menu Border Width', 'envo-extra' ),
	'section'			 => 'main_menu',
	'priority'			 => 20,
	'default'			 => array(
		'border-top-width'		 => '0px',
		'border-right-width'	 => '0px',
		'border-bottom-width'	 => '0px',
		'border-left-width'		 => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'		 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width'	 => esc_attr__( 'Right', 'textdomain' ),
			'border-bottom-width'	 => esc_attr__( 'Bottom', 'textdomain' ),
			'border-left-width'		 => esc_attr__( 'Left', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '#second-site-navigation',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'mainmenu_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
		array(
			array(
				'setting'	 => 'header_layout',
				'operator'	 => '==',
				'value'		 => 'woonav',
			),
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'mainmenu_border_radius',
	'section'			 => 'main_menu',
	'label'				 => esc_attr__( 'Menu Border Radius', 'envo-extra' ),
	'priority'			 => 20,
	'default'			 => array(
		'border-top-left-radius'	 => '0px',
		'border-top-right-radius'	 => '0px',
		'border-bottom-left-radius'	 => '0px',
		'border-bottom-right-radius' => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-left-radius'	 => esc_attr__( 'Top Left', 'textdomain' ),
			'border-top-right-radius'	 => esc_attr__( 'Top Right', 'textdomain' ),
			'border-bottom-left-radius'	 => esc_attr__( 'Bottom Left', 'textdomain' ),
			'border-bottom-right-radius' => esc_attr__( 'Bottom Right', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '#second-site-navigation',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'mainmenu_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
		array(
			array(
				'setting'	 => 'header_layout',
				'operator'	 => '==',
				'value'		 => 'woonav',
			),
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'mainmenu_border_color',
	'label'				 => esc_attr__( 'Menu border color', 'envo-extra' ),
	'section'			 => 'main_menu',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 20,
	'output'			 => array(
		array(
			'element'	 => '#second-site-navigation',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'mainmenu_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
		array(
			array(
				'setting'	 => 'header_layout',
				'operator'	 => '==',
				'value'		 => 'woonav',
			),
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'mainmenu_border_separator_bottom',
	'section'			 => 'main_menu',
	'priority'			 => 20,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'text',
	'settings'			 => 'mainmenu_border_box_shadow_code',
	'label'				 => esc_html__( 'Menu shadow', 'envo-extra' ),
	'description'		 => esc_attr__( 'e.g. 5px 5px 15px 5px #000000', 'envo-extra' ),
	'section'			 => 'main_menu',
	'priority'			 => 20,
	'output'			 => array(
		array(
			'element'	 => '#second-site-navigation',
			'property'	 => 'box-shadow',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );
// Box shadow end.

Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'categories_search_bar_bg',
	'label'				 => esc_attr__( 'Secondary bar background color', 'envo-extra' ),
	'section'			 => 'main_menu',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 20,
	'output'			 => array(
		array(
			'element'	 => '#second-site-navigation',
			'property'	 => 'background-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'submenu_border_style_separator',
	'section'	 => 'main_menu',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'submenu_border_style',
	'label'			 => esc_html__( 'Sub-menu border', 'envo-extra' ),
	'section'		 => 'main_menu',
	'default'		 => 'solid',
	'priority'		 => 30,
	'placeholder'	 => esc_html__( 'Choose an option', 'envo-extra' ),
	'choices'		 => array(
		'none'	 => esc_html__( 'None', 'envo-extra' ),
		'solid'	 => esc_html__( 'Solid', 'envo-extra' ),
		'double' => esc_html__( 'Double', 'envo-extra' ),
		'dotted' => esc_html__( 'Dotted', 'envo-extra' ),
		'dashed' => esc_html__( 'Dashed', 'envo-extra' ),
		'groove' => esc_html__( 'Groove', 'envo-extra' ),
	),
	'transport'		 => 'auto',
	'output'		 => array(
		array(
			'element'	 => '.navbar-nav li:hover .dropdown-menu',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'submenu_border_width',
	'label'				 => esc_attr__( 'Sub-menu border width', 'envo-extra' ),
	'section'			 => 'main_menu',
	'priority'			 => 30,
	'default'			 => array(
		'border-top-width'		 => '1px',
		'border-bottom-width'	 => '1px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'	 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width' => esc_attr__( 'Bottom', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.navbar-nav li:hover .dropdown-menu',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'submenu_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'submenu_border_radius',
	'section'			 => 'main_menu',
	'label'				 => esc_attr__( 'Sub-menu border radius', 'envo-extra' ),
	'priority'			 => 30,
	'default'			 => array(
		'border-top-left-radius'	 => '0px',
		'border-top-right-radius'	 => '0px',
		'border-bottom-left-radius'	 => '0px',
		'border-bottom-right-radius' => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-left-radius'	 => esc_attr__( 'Top Left', 'textdomain' ),
			'border-top-right-radius'	 => esc_attr__( 'Top Right', 'textdomain' ),
			'border-bottom-left-radius'	 => esc_attr__( 'Bottom Left', 'textdomain' ),
			'border-bottom-right-radius' => esc_attr__( 'Bottom Right', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.navbar-nav li:hover .dropdown-menu',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'submenu_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'submenu_border_color',
	'label'				 => esc_attr__( 'Sub-menu border color', 'envo-extra' ),
	'section'			 => 'main_menu',
	'default'			 => '#f6f6f6',
	'transport'			 => 'auto',
	'priority'			 => 30,
	'output'			 => array(
		array(
			'element'	 => '.navbar-nav li:hover .dropdown-menu',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'submenu_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'submenu_box_shadow_top',
	'section'	 => 'main_menu',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'text',
	'settings'		 => 'submenu_box_shadow_code',
	'label'			 => esc_html__( 'Sub-menu box shadow', 'envo-extra' ),
	'description'	 => esc_attr__( 'e.g. 5px 5px 15px 5px #000000', 'envo-extra' ),
	'section'		 => 'main_menu',
	'priority'		 => 40,
	'output'		 => array(
		array(
			'element'	 => '.navbar-nav li:hover .dropdown-menu',
			'property'	 => 'box-shadow',
		),
	),
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'mainmenu_desc_color_top',
	'section'	 => 'main_menu',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'mainmenu_desc_color',
	'label'		 => esc_attr__( 'Menu badge (description) colors', 'envo-extra' ),
	'section'	 => 'main_menu',
	'default'	 => '',
	'transport'	 => 'auto',
	'priority'	 => 40,
	'choices'	 => array(
		'font'	 => esc_attr__( 'Font', 'envo-extra' ),
		'bg'	 => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'	 => array(
		'font'	 => '',
		'bg'	 => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'font',
			'element'	 => '.menu-item .menu-description, .mobile-cart .amount-cart, .mobile-cart .cart-contents span.count',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'bg',
			'element'	 => '.menu-item .menu-description, .mobile-cart .amount-cart, .mobile-cart .cart-contents span.count',
			'property'	 => 'background-color',
		),
		array(
			'choice'	 => 'bg',
			'element'	 => '.menu-item .menu-description:after',
			'property'	 => 'border-top-color',
		),
		array(
			'choice'	 => 'bg',
			'element'	 => '.mobile-cart .amount-cart:before',
			'property'	 => 'border-right-color',
		),
	),
) );

Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'hamburger_menu_color_bg',
	'label'				 => esc_attr__( 'Mobile hamburger menu icon color', 'envo-extra' ),
	'section'			 => 'main_menu',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 40,
	'output'			 => array(
		array(
			'element'	 => '.hc-nav-trigger span, .hc-nav-trigger span::before, .hc-nav-trigger span::after',
			'property'	 => 'background-color',
		),
	),
) );


Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('Category menu', 'envo-extra'),
    'section' => 'category_menu',
    'settings' => 'category_menu_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'priority' => 5,
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.navbar-nav.envo-categories-menu',
            'property' => 'display',
        ),
    ),
));

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'typography_category_menu_separator_top',
	'section'	 => 'category_menu',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Menu Font', 'envo-extra' ),
	'section'	 => 'category_menu',
	'settings'	 => 'typography_category_menu_devices',
	'priority'	 => 10,
	'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'typography_category_menu' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'category_menu',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'text-transform'	 => 'uppercase',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none',
			'color'				 => ''
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.envo-categories-menu, .navbar-nav > li > a.envo-categories-menu-first, .envo-categories-menu > li > a, .envo-categories-menu .dropdown-menu > li > a',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
		'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'categories_colors_separator',
	'section'	 => 'category_menu',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'categories_colors',
	'label'		 => esc_attr__( 'Category menu', 'envo-extra' ),
	'section'	 => 'category_menu',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'bg' => esc_attr__( 'Background', 'envo-extra' ),
		'bg-hover' => esc_attr__( 'Background hover', 'envo-extra' ),
		'font-hover' => esc_attr__( 'Font hover', 'envo-extra' ),
	),
	'default'	 => array(
		'bg' => '',
		'bg-hover' => '',
		'font-hover' => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'bg',
			'element'	 => '.navbar-nav.envo-categories-menu',
			'property'	 => 'background-color',
			'suffix'	 => '!important',
		),
		array(
			'choice'	 => 'bg-hover',
			'element'	 => '.navbar-nav.envo-categories-menu:hover a.envo-categories-menu-first',
			'property'	 => 'background-color',
			'suffix'	 => '!important',
		),
		array(
			'choice'	 => 'font-hover',
			'element'	 => '.navbar-nav.envo-categories-menu:hover a.envo-categories-menu-first',
			'property'	 => 'color',
			'suffix'	 => '!important',
		),
	),
	'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'category_menu_colors_separator',
	'section'	 => 'category_menu',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'category_menu_colors',
	'label'		 => esc_attr__( 'Sub-level menu colors', 'envo-extra' ),
	'section'	 => 'category_menu',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'bg_color_mainmenu'			 => esc_attr__( 'Menu Background', 'envo-extra' ),
		'text_hover_mainmenu'		 => esc_attr__( 'Font hover', 'envo-extra' ),
		'bg_text_hover_mainmenu'	 => esc_attr__( 'Item background hover', 'envo-extra' ),
		'active_text_mainmenu'		 => esc_attr__( 'Active item font', 'envo-extra' ),
		'active_text_bg_mainmenu'	 => esc_attr__( 'Active item background', 'envo-extra' ),
	),
	'default'	 => array(
		'bg_color_mainmenu'			 => '',
		'text_hover_mainmenu'		 => '',
		'bg_text_hover_mainmenu'	 => '',
		'active_text_mainmenu'		 => '',
		'active_text_bg_mainmenu'	 => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'bg_color_mainmenu',
			'element'	 => '.envo-categories-menu .dropdown-menu',
			'property'	 => 'background-color',
		),
		array(
			'choice'	 => 'text_hover_mainmenu',
			'element'	 => '.envo-categories-menu > a:hover, .envo-categories-menu > li > a:hover, .envo-categories-menu.nav.navbar-nav .dropdown-menu > li > a:hover',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'bg_text_hover_mainmenu',
			'element'	 => '.envo-categories-menu > li > a:hover, .envo-categories-menu .dropdown-menu > li > a:hover, .envo-categories-menu .dropdown-menu > a:hover',
			'property'	 => 'background-color',
		),
		array(
			'choice'	 => 'active_text_mainmenu',
			'element'	 => '.envo-categories-menu > li.active > a, .envo-categories-menu .dropdown-menu > .active.current-menu-item > a, .envo-categories-menu .dropdown-menu > .active > a',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'active_text_bg_mainmenu',
			'element'	 => '.envo-categories-menu > li.active > a, .envo-categories-menu .dropdown-menu > .active.current-menu-item > a, .envo-categories-menu .dropdown-menu > .active > a',
			'property'	 => 'background-color',
		),
	),
	'active_callback' => array(
        array(
            'setting' => 'category_menu_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'typography_secondary_menu_separator_top',
	'section'	 => 'secondary_menu',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Menu Font', 'envo-extra' ),
	'section'	 => 'secondary_menu',
	'settings'	 => 'typography_secondary_menu_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'typography_secondary_menu' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'secondary_menu',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '14px',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'text-transform'	 => 'none',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none',
			'color'				 => ''
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '#theme-menu-second .navbar-nav > li > a',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			)
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'typography_secondary_menu_separator_bottom',
	'section'	 => 'secondary_menu',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'secondary_menu_colors',
	'label'		 => esc_attr__( 'Secondary Menu', 'envo-extra' ),
	'section'	 => 'secondary_menu',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'font-hover'	 => esc_attr__( 'Font hover', 'envo-extra' ),
		'bg'			 => esc_attr__( 'Background', 'envo-extra' ),
		'bg-hover'		 => esc_attr__( 'Background hover', 'envo-extra' ),
		'font-active'	 => esc_attr__( 'Font active', 'envo-extra' ),
		'bg-active'		 => esc_attr__( 'Background active', 'envo-extra' ),
	),
	'default'	 => array(
		'bg'			 => '',
		'font-hover'	 => '',
		'bg-hover'		 => '',
		'font-active'	 => '',
		'bg-active'		 => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'bg',
			'element'	 => '#theme-menu-second .navbar-nav > li > a',
			'property'	 => 'background-color',
			'suffix'	 => '!important',
		),
		array(
			'choice'	 => 'font-hover',
			'element'	 => '#theme-menu-second .navbar-nav > li > a:hover',
			'property'	 => 'color',
			'suffix'	 => '!important',
		),
		array(
			'choice'	 => 'bg-hover',
			'element'	 => '#theme-menu-second .navbar-nav > li > a:hover',
			'property'	 => 'background-color',
			'suffix'	 => '!important',
		),
		array(
			'choice'	 => 'font-active',
			'element'	 => '#theme-menu-second .navbar-nav > li.active > a',
			'property'	 => 'color',
			'suffix'	 => '!important',
		),
		array(
			'choice'	 => 'bg-active',
			'element'	 => '#theme-menu-second .navbar-nav > li.active > a',
			'property'	 => 'background-color',
			'suffix'	 => '!important',
		),
	),
) );
