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
if ( get_theme_mod( 'header_layout', 'busnav' ) == 'woonav' ) {
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
	'default'	 => 'right',
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
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none',
			envo_extra_col() => '',
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
			envo_extra_col() => '',
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
			envo_extra_col() => '',
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

