<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_panel('envo_theme_panel', array(
    'priority' => 1,
    'title' => esc_attr__('Theme Options', 'envo-extra'),
));

Kirki::add_section('main_colors_section', array(
    'title' => esc_attr__('Content colors and typography', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 20,
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

/**
 * Colors
 */
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'main_typography_separator_top',
    'section' => 'main_colors_section',
    'priority' => 10,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Site font', 'envo-extra'),
    'section' => 'main_colors_section',
    'settings' => 'main_typography_devices',
    'priority' => 10,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'main_typography' . $key,
        'description' => $value['description'],
        'section' => 'main_colors_section',
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
            'word-spacing' => '0px',
			envo_extra_col() => '',
        ),
        'priority' => 15,
        'output' => array(
            array(
                'element' => 'body, nav.navigation.post-navigation a, .nav-subtitle',
                $value['media_query_key'] => $value['media_query'],
            ),
            array(
                'choice' => 'color',
                'element' => '.comments-meta a, .the-product-share ul li a .product-share-text',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'main_typography_separator_bottom',
    'section' => 'main_colors_section',
    'priority' => 20,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

