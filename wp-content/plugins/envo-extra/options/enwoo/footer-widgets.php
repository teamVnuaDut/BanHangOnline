<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('footer_widgets', array(
    'title' => esc_attr__('Footer Widgets', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 45,
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
    'settings' => 'widgets-width',
    'label' => esc_attr__('Footer widgets columns', 'envo-extra'),
    'section' => 'footer_widgets',
    'default' => '23',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        '98' => '1',
        '49' => '2',
        '31.33333333' => '3',
        '23' => '4',
    ),
    'output' => array(
        array(
            'element' => '#content-footer-section .widget.col-md-3',
            'property' => 'width',
            'media_query' => '@media (min-width: 992px)',
            'units' => '%',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_font_color_separator_top',
    'section' => 'footer_widgets',
    'priority' => 10,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Font', 'envo-extra'),
    'section' => 'footer_widgets',
    'settings' => 'footer_font_color_devices',
    'priority' => 10,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'footer_font_color' . $key,
        'description' => $value['description'],
        'section' => 'footer_widgets',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '400',
            'letter-spacing' => '0px',
            'font-size' => '15px',
            'line-height' => '1.6',
            'text-transform' => 'none',
            'color' => '',
            'word-spacing' => '0px',
            'text-align' => 'none',
        ),
        'priority' => 15,
        'output' => array(
            array(
                'element' => '#content-footer-section .widget',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_widget_title_color_separator_top',
    'section' => 'footer_widgets',
    'priority' => 20,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Widget Titles', 'envo-extra'),
    'section' => 'footer_widgets',
    'settings' => 'footer_widget_title_color_devices',
    'priority' => 20,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'footer_widget_title_color' . $key,
        'description' => $value['description'],
        'section' => 'footer_widgets',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'font-size' => '18px',
            'variant' => '400',
            'line-height' => '1.6',
            'letter-spacing' => '0px',
            'color' => '',
            'text-transform' => 'none',
            'word-spacing' => '0px',
            'text-align' => 'none',
        ),
        'priority' => 25,
        'output' => array(
            array(
                'element' => '#content-footer-section .widget-title h3',
                $value['media_query_key'] => $value['media_query'],
            )
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_widget_title_color_separator_bottom',
    'section' => 'footer_widgets',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'footer_bg_color',
    'label' => esc_attr__('Background', 'envo-extra'),
    'section' => 'footer_widgets',
    'default' => '',
    'transport' => 'auto',
    'priority' => 30,
    'output' => array(
        array(
            'element' => '#content-footer-section',
            'property' => 'background-color',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'footer_links_color',
    'label' => esc_attr__('Links', 'envo-extra'),
    'section' => 'footer_widgets',
    'priority' => 30,
    'transport' => 'auto',
    'choices' => array(
        'link' => esc_attr__('Color', 'envo-extra'),
        'hover' => esc_attr__('Hover', 'envo-extra'),
    ),
    'default' => array(
        'link' => '',
        'hover' => '',
    ),
    'output' => array(
        array(
            'choice' => 'link',
            'element' => '#content-footer-section a',
            'property' => 'color',
        ),
        array(
            'choice' => 'hover',
            'element' => '#content-footer-section a:hover',
            'property' => 'color',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_sidebar_border_top',
    'section' => 'footer_widgets',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Border start.
Kirki::add_field('envo_extra', array(
    'type' => 'select',
    'settings' => 'footer_sidebar_border_style',
    'label' => esc_html__('Footer widgets area border', 'envo-extra'),
    'section' => 'footer_widgets',
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
            'element' => '#content-footer-section',
            'property' => 'border-style',
        ),
    )
        )
);
Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'footer_sidebar_border_width',
    'label' => esc_attr__('Footer widgets area border width', 'envo-extra'),
    'section' => 'footer_widgets',
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
            'element' => '#content-footer-section',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_sidebar_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'footer_sidebar_border_radius',
    'section' => 'footer_widgets',
    'label' => esc_attr__('Footer widgets area border radius', 'envo-extra'),
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
            'element' => '#content-footer-section',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_sidebar_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'footer_sidebar_border_color',
    'label' => esc_attr__('Footer widgets area border color', 'envo-extra'),
    'section' => 'footer_widgets',
    'default' => '',
    'transport' => 'auto',
    'priority' => 30,
    'output' => array(
        array(
            'element' => '#content-footer-section',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_sidebar_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
// Border end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_sidebar_box_shadow_top',
    'section' => 'footer_widgets',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
Kirki::add_field('envo_extra', array(
    'type' => 'text',
    'settings' => 'footer_sidebar_box_shadow_code',
    'label' => esc_html__('Footer widgets area box shadow', 'envo-extra'),
    'description' => esc_attr__('e.g. 5px 5px 15px 5px #000000', 'envo-extra'),
    'section' => 'footer_widgets',
    'priority' => 30,
    'output' => array(
        array(
            'element' => '#content-footer-section',
            'property' => 'box-shadow',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_sidebar_spacing_separator_top',
    'section' => 'footer_widgets',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Footer widgets area spacing', 'envo-extra'),
    'section' => 'footer_widgets',
    'settings' => 'footer_sidebar_spacing_devices',
    'priority' => 30,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'dimensions',
        'settings' => 'footer_sidebar_spacing' . $key,
        'section' => 'footer_widgets',
        'priority' => 35,
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
                'element' => '#content-footer-section',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_sidebar_spacing_separator_bottom',
    'section' => 'footer_widgets',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Border start.
Kirki::add_field('envo_extra', array(
    'type' => 'select',
    'settings' => 'footer_widgets_border_style',
    'label' => esc_html__('Footer widgets border', 'envo-extra'),
    'section' => 'footer_widgets',
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
            'element' => '#content-footer-section .widget',
            'property' => 'border-style',
        ),
    )
        )
);
Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'footer_widgets_border_width',
    'label' => esc_attr__('Footer widgets border width', 'envo-extra'),
    'section' => 'footer_widgets',
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
            'border-right-width' => esc_attr__('Bottom', 'textdomain'),
            'border-bottom-width' => esc_attr__('Left', 'textdomain'),
            'border-left-width' => esc_attr__('Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '#content-footer-section .widget',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_widgets_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'footer_widgets_border_radius',
    'section' => 'footer_widgets',
    'label' => esc_attr__('Footer widgets border radius', 'envo-extra'),
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
            'element' => '#content-footer-section .widget',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_widgets_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'footer_widgets_border_color',
    'label' => esc_attr__('Footer widgets border color', 'envo-extra'),
    'section' => 'footer_widgets',
    'default' => '',
    'transport' => 'auto',
    'priority' => 40,
    'output' => array(
        array(
            'element' => '#content-footer-section .widget',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_widgets_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
// Border end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_widgets_box_shadow_top',
    'section' => 'footer_widgets',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
Kirki::add_field('envo_extra', array(
    'type' => 'text',
    'settings' => 'footer_widgets_box_shadow_code',
    'label' => esc_html__('Footer widgets box shadow', 'envo-extra'),
    'description' => esc_attr__('e.g. 5px 5px 15px 5px #000000', 'envo-extra'),
    'section' => 'footer_widgets',
    'priority' => 40,
    'output' => array(
        array(
            'element' => '#content-footer-section .widget',
            'property' => 'box-shadow',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_widgets_spacing_separator_top',
    'section' => 'footer_widgets',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Footer widgets spacing', 'envo-extra'),
    'section' => 'footer_widgets',
    'settings' => 'footer_widgets_spacing_devices',
    'priority' => 40,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'dimensions',
        'settings' => 'footer_widgets_spacing' . $key,
        'section' => 'footer_widgets',
        'priority' => 45,
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
                'element' => '#content-footer-section .widget',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_widgets_spacing_separator_bottom',
    'section' => 'footer_widgets',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));