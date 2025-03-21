<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('main_sidebar', array(
    'title' => esc_attr__('Sidebar', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 40,
));

$devices = array(
    'desktop' => array(
        'media_query_key' => '',
        'media_query' => '',
        'description' => 'Desktop',
    ),
    'tablet' => array(
        'media_query_key' => 'media_query',
        'media_query' => '@media (max-width: 991px)',
        'description' => 'Tablet',
    ),
    'mobile' => array(
        'media_query_key' => 'media_query',
        'media_query' => '@media (max-width: 767px)',
        'description' => 'Mobile',
    ),
);

Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'main_sidebar_position',
    'label' => esc_attr__('Sidebar position', 'envo-extra'),
    'section' => 'main_sidebar',
    'default' => 'left',
    'priority' => 5,
    'transport' => 'auto',
    'choices' => array(
        'right' => esc_attr__('Left', 'envo-extra'),
        'left' => esc_attr__('Right', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.blog .page-area .col-md-9, .archive .page-area .col-md-9, article.col-md-9',
            'property' => 'float',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'widget_title_separator_top',
    'section' => 'main_sidebar',
    'priority' => 10,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Widget titles', 'envo-extra'),
    'section' => 'main_sidebar',
    'settings' => 'widget_title_devices',
    'priority' => 11,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'typography',
        'settings' => 'awidget_title_color' . $key,
        'section' => 'main_sidebar',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'font-size' => '15px',
            'variant' => '700',
            'line-height' => '1.6',
            'letter-spacing' => '0px',
            'color' => '',
            'text-transform' => 'uppercase',
            'word-spacing' => '0px',
            'text-decoration' => '',
            'text-align' => 'none',
        ),
        'priority' => 12,
        'output' => array(
            array(
                'element' => '#sidebar .widget-title h3, #sidebar h2.wp-block-heading',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'sidebar_widget_font_separator_top',
    'section' => 'main_sidebar',
    'priority' => 15,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Font', 'envo-extra'),
    'section' => 'main_sidebar',
    'settings' => 'sidebar_widget_font_devices',
    'priority' => 15,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'typography',
        'settings' => 'sidebar_widget_font' . $key,
        'section' => 'main_sidebar',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'font-size' => '15px',
            'variant' => '400',
            'line-height' => '1.6',
            'letter-spacing' => '0px',
            'color' => '',
            'text-transform' => 'uppercase',
            'word-spacing' => '0px',
            'text-decoration' => '',
            'text-align' => 'none',
        ),
        'priority' => 20,
        'output' => array(
            array(
                'element' => '#sidebar .widget',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'sidebar_widget_font_separator_bottom',
    'section' => 'main_sidebar',
    'priority' => 25,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'sidebar_links',
    'label' => esc_attr__('Widget links', 'envo-extra'),
    'section' => 'main_sidebar',
    'priority' => 30,
    'transport' => 'auto',
    'choices' => array(
        'link' => esc_attr__('Links', 'envo-extra'),
        'link-hover' => esc_attr__('Links hover', 'envo-extra'),
    ),
    'default' => array(
        'link' => '',
        'link-hover' => '',
    ),
    'output' => array(
        array(
            'choice' => 'link',
            'element' => '#sidebar .widget a',
            'property' => 'color',
        ),
        array(
            'choice' => 'link-hover',
            'element' => '#sidebar .widget a:hover',
            'property' => 'color',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'sidebar_border_style_separator_top',
    'section' => 'main_sidebar',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Border start.
Kirki::add_field('envo_extra', array(
    'type' => 'select',
    'settings' => 'sidebar_border_style',
    'label' => esc_html__('Sidebar border', 'envo-extra'),
    'section' => 'main_sidebar',
    'default' => 'none',
    'priority' => 30,
    'placeholder' => esc_html__('Choose an option', 'envo-extra'),
    'choices' => array(
        'none' => esc_html__('None', 'envo-extra'),
        'solid' => esc_html__('Solid', 'envo-extra'),
        'double' => esc_html__('Double', 'envo-extra'),
        'dotted' => esc_html__('Dotted', 'envo-extra'),
        'dashed' => esc_html__('Dashed', 'envo-extra'),
        'groove' => esc_html__('Groove', 'envo-extra'),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#sidebar',
            'property' => 'border-style',
        ),
    )
        )
);
Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'sidebar_border_width',
    'label' => esc_attr__('Sidebar Border Width', 'envo-extra'),
    'section' => 'main_sidebar',
    'priority' => 30,
    'default' => array(
        'border-top-width' => '0px',
        'border-right-width' => '0px',
        'border-bottom-width' => '0px',
        'border-left-width' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-width' => esc_attr__('Top', 'textdomain'),
            'border-right-width' => esc_attr__('Bottom', 'textdomain'),
            'border-bottom-width' => esc_attr__('Left', 'textdomain'),
            'border-left-width' => esc_attr__('Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#sidebar',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'sidebar_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'sidebar_border_radius',
    'section' => 'main_sidebar',
    'label' => esc_attr__('Sidebar Border Radius', 'envo-extra'),
    'priority' => 30,
    'default' => array(
        'border-top-left-radius' => '0px',
        'border-top-right-radius' => '0px',
        'border-bottom-left-radius' => '0px',
        'border-bottom-right-radius' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-left-radius' => esc_attr__('Top Left', 'textdomain'),
            'border-top-right-radius' => esc_attr__('Top Right', 'textdomain'),
            'border-bottom-left-radius' => esc_attr__('Bottom Left', 'textdomain'),
            'border-bottom-right-radius' => esc_attr__('Bottom Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#sidebar',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'sidebar_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'sidebar_border_color',
    'label' => esc_attr__('Sidebar border color', 'envo-extra'),
    'section' => 'main_sidebar',
    'default' => '',
    'transport' => 'auto',
    'priority' => 30,
    'output' => array(
        array(
            'element' => '#sidebar',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'sidebar_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
// Border end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'sidebar_border_style_separator_bottom',
    'section' => 'main_sidebar',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Box shadow start.
//Kirki::add_field( 'envo_extra', array(
//	'type'		 => 'dimensions',
//	'settings'	 => 'sidebar_box_shadow',
//	'section'	 => 'main_sidebar',
//	'label'		 => esc_attr__( 'Sidebar box shadow', 'envo-extra' ),
//	'transport'	 => 'auto',
//	'priority'	 => 30,
//	'default'	 => array(
//		'h-shadow'	 => '0px',
//		'v-shadow'	 => '0px',
//		'blur'		 => '0px',
//		'spread'	 => '0px',
//	),
//	'choices'	 => array(
//		'labels' => array(
//			'h-shadow'	 => esc_attr__( 'Horizontal', 'textdomain' ),
//			'v-shadow'	 => esc_attr__( 'Vertical', 'textdomain' ),
//			'blur'		 => esc_attr__( 'Blur', 'textdomain' ),
//			'spread'	 => esc_attr__( 'Spread ', 'textdomain' ),
//		),
//	),
//) );
//Kirki::add_field( 'envo_extra', array(
//	'type'		 => 'color',
//	'settings'	 => 'sidebar_box_shadow_color',
//	'label'		 => esc_attr__( 'Sidebar shadow color', 'envo-extra' ),
//	'section'	 => 'main_sidebar',
//	'default'	 => '',
//	'transport'	 => 'auto',
//	'priority'	 => 30,
//	'choices'	 => array(
//		'alpha' => true,
//	),
//	'output'	 => array(
//		array(
//			'element'			 => '#sidebar',
//			'property'			 => 'box-shadow',
//			'value_pattern'		 => 'h-shadow v-shadow blur spread $',
//			'pattern_replace'	 => array(
//				'h-shadow'	 => 'sidebar_box_shadow[h-shadow]',
//				'v-shadow'	 => 'sidebar_box_shadow[v-shadow]',
//				'blur'		 => 'sidebar_box_shadow[blur]',
//				'spread'	 => 'sidebar_box_shadow[spread]',
//			),
//		),
//	),
//) );
Kirki::add_field('envo_extra', array(
    'type' => 'text',
    'settings' => 'sidebar_box_shadow_code',
    'label' => esc_html__('Sidebar box shadow', 'envo-extra'),
    'description' => esc_attr__('e.g. 5px 5px 15px 5px #000000', 'envo-extra'),
    'section' => 'main_sidebar',
    'priority' => 30,
    'output' => array(
        array(
            'element' => '#sidebar',
            'property' => 'box-shadow',
        ),
    ),
));
// Box shadow end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'sidebar_spacing_separator_top',
    'section' => 'main_sidebar',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Sidebar spacing', 'envo-extra'),
    'section' => 'main_sidebar',
    'settings' => 'sidebar_spacing_devices',
    'priority' => 30,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'dimensions',
        'settings' => 'sidebar_spacing' . $key,
        'section' => 'main_sidebar',
        'priority' => 35,
        'default' => array(
            'top' => '0px',
            'right' => '15px',
            'bottom' => '0px',
            'left' => '15px',
        ),
        'transport' => 'auto',
        'output' => array(
            array(
                'property' => 'padding',
                'element' => '#sidebar',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'sidebar_spacing_separator_bottom',
    'section' => 'main_sidebar',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
// Border start.
Kirki::add_field('envo_extra', array(
    'type' => 'select',
    'settings' => 'widgets_border_style',
    'label' => esc_html__('Widgets border', 'envo-extra'),
    'section' => 'main_sidebar',
    'default' => 'none',
    'priority' => 40,
    'placeholder' => esc_html__('Choose an option', 'envo-extra'),
    'choices' => array(
        'none' => esc_html__('None', 'envo-extra'),
        'solid' => esc_html__('Solid', 'envo-extra'),
        'double' => esc_html__('Double', 'envo-extra'),
        'dotted' => esc_html__('Dotted', 'envo-extra'),
        'dashed' => esc_html__('Dashed', 'envo-extra'),
        'groove' => esc_html__('Groove', 'envo-extra'),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#sidebar .widget',
            'property' => 'border-style',
        ),
    )
        )
);
Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'widgets_border_width',
    'label' => esc_attr__('Widgets Border Width', 'envo-extra'),
    'section' => 'main_sidebar',
    'priority' => 40,
    'default' => array(
        'border-top-width' => '0px',
        'border-right-width' => '0px',
        'border-bottom-width' => '0px',
        'border-left-width' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-width' => esc_attr__('Top', 'textdomain'),
            'border-right-width' => esc_attr__('Right', 'textdomain'),
            'border-bottom-width' => esc_attr__('Bottom', 'textdomain'),
            'border-left-width' => esc_attr__('Left', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#sidebar .widget',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'widgets_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'widgets_border_radius',
    'section' => 'main_sidebar',
    'label' => esc_attr__('Widgets Border Radius', 'envo-extra'),
    'priority' => 40,
    'default' => array(
        'border-top-left-radius' => '0px',
        'border-top-right-radius' => '0px',
        'border-bottom-left-radius' => '0px',
        'border-bottom-right-radius' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-left-radius' => esc_attr__('Top Left', 'textdomain'),
            'border-top-right-radius' => esc_attr__('Top Right', 'textdomain'),
            'border-bottom-left-radius' => esc_attr__('Bottom Left', 'textdomain'),
            'border-bottom-right-radius' => esc_attr__('Bottom Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#sidebar .widget',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'widgets_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'widgets_border_color',
    'label' => esc_attr__('Widgets border color', 'envo-extra'),
    'section' => 'main_sidebar',
    'default' => '',
    'transport' => 'auto',
    'priority' => 40,
    'output' => array(
        array(
            'element' => '#sidebar .widget',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'widgets_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
// Border end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'widgets_border_bottom',
    'section' => 'main_sidebar',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Box shadow start.
//Kirki::add_field( 'envo_extra', array(
//	'type'		 => 'color',
//	'settings'	 => 'widgets_box_shadow_color',
//	'label'		 => esc_attr__( 'Widgets shadow color', 'envo-extra' ),
//	'section'	 => 'main_sidebar',
//	'default'	 => '',
//	'transport'	 => 'auto',
//	'priority'	 => 50,
//	'choices'	 => array(
//		'alpha' => true,
//	),
//	'output'	 => array(
//		array(
//			'element'			 => '#sidebar .widget',
//			'property'			 => 'box-shadow',
//			'value_pattern'		 => 'h-shadow v-shadow blur spread $',
//			'pattern_replace'	 => array(
//				'h-shadow'	 => 'widgets_box_shadow[h-shadow]',
//				'v-shadow'	 => 'widgets_box_shadow[v-shadow]',
//				'blur'		 => 'widgets_box_shadow[blur]',
//				'spread'	 => 'widgets_box_shadow[spread]',
//			),
//		),
//	),
//) );
//Kirki::add_field( 'envo_extra', array(
//	'type'		 => 'dimensions',
//	'settings'	 => 'widgets_box_shadow',
//	'section'	 => 'main_sidebar',
//	'label'		 => esc_attr__( 'Widgets box shadow', 'envo-extra' ),
//	'transport'	 => 'auto',
//	'priority'	 => 50,
//	'default'	 => array(
//		'h-shadow'	 => '0px',
//		'v-shadow'	 => '0px',
//		'blur'		 => '0px',
//		'spread'	 => '0px',
//	),
//	'choices'	 => array(
//		'labels' => array(
//			'h-shadow'	 => esc_attr__( 'Horizontal', 'envo-extra' ),
//			'v-shadow'	 => esc_attr__( 'Vertical', 'envo-extra' ),
//			'blur'		 => esc_attr__( 'Blur', 'envo-extra' ),
//			'spread'	 => esc_attr__( 'Spread ', 'envo-extra' ),
//		),
//	),
//) );
Kirki::add_field('envo_extra', array(
    'type' => 'text',
    'settings' => 'widgets_box_shadow_code',
    'label' => esc_html__('Widgets box shadow', 'envo-extra'),
    'description' => esc_attr__('e.g. 5px 5px 15px 5px #000000', 'envo-extra'),
    'section' => 'main_sidebar',
    'priority' => 50,
    'output' => array(
        array(
            'element' => '#sidebar .widget',
            'property' => 'box-shadow',
        ),
    ),
));
// Box shadow end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'widgets_spacing_separator_top',
    'section' => 'main_sidebar',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Widgets spacing', 'envo-extra'),
    'section' => 'main_sidebar',
    'settings' => 'widgets_spacing_devices',
    'priority' => 50,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'dimensions',
        'settings' => 'widgets_spacing' . $key,
        'section' => 'main_sidebar',
        'priority' => 60,
        'default' => array(
            'top' => '0px',
            'right' => '0px',
            'bottom' => '0px',
            'left' => '0px',
        ),
        'transport' => 'auto',
        'output' => array(
            array(
                'property' => 'padding',
                'element' => '#sidebar .widget',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'widgets_spacing_separator_bottom',
    'section' => 'main_sidebar',
    'priority' => 70,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));