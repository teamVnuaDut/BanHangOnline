<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('footer_credits', array(
    'title' => esc_attr__('Copyright (Footer Credits)', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 50,
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
    'type' => 'editor',
    'settings' => 'footer-credits',
    'label' => __('Footer credits', 'envo-extra'),
    'description' => __('HTML is allowed.<br/> Use <code>%current_year%</code> to update year automatically.<br/> Use <code>%copy%</code> to include copyright symbol.', 'envo-extra'),
    'section' => 'footer_credits',
    'transport' => 'postMessage',
    'js_vars' => array(
        array(
            'element' => '.envo-credits-text',
            'function' => 'html',
        ),
    ),
    'default' => '',
    'priority' => 10,
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_credits_font_separator_top',
    'section' => 'footer_credits',
    'priority' => 10,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Font', 'envo-extra'),
    'section' => 'footer_credits',
    'settings' => 'footer_credits_font_devices',
    'priority' => 10,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'footer_credits_font' . $key,
        'description' => $value['description'],
        'section' => 'footer_credits',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '400',
            'letter-spacing' => '0px',
            'font-size' => '',
            'line-height' => '',
            'text-transform' => 'none',
            'word-spacing' => '0px',
            'text-align' => 'none',
        ),
        'priority' => 15,
        'output' => array(
            array(
                'element' => '.footer-credits-text',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_credits_font_separator_bottom',
    'section' => 'footer_credits',
    'priority' => 20,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'copyright_bg_color',
    'label' => esc_attr__('Copyright background', 'envo-extra'),
    'section' => 'footer_credits',
    'default' => '',
    'transport' => 'auto',
    'priority' => 20,
    'output' => array(
        array(
            'element' => '.footer-credits',
            'property' => 'background-color',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'copyright_links_color',
    'label' => esc_attr__('Copyright text colors', 'envo-extra'),
    'section' => 'footer_credits',
    'priority' => 20,
    'transport' => 'auto',
    'choices' => array(
        'color' => esc_attr__('Color', 'envo-extra'),
        'link' => esc_attr__('Links', 'envo-extra'),
        'hover' => esc_attr__('Hover', 'envo-extra'),
    ),
    'default' => array(
        'color' => '',
        'link' => '',
        'hover' => '',
    ),
    'output' => array(
        array(
            'choice' => 'color',
            'element' => '.footer-credits, .footer-credits-text',
            'property' => 'color',
        ),
        array(
            'choice' => 'link',
            'element' => '.footer-credits a',
            'property' => 'color',
        ),
        array(
            'choice' => 'hover',
            'element' => '.footer-credits a:hover',
            'property' => 'color',
        ),
    ),
));
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_credits_spacing_separator_top',
    'section' => 'footer_credits',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Copyright spacing', 'envo-extra'),
    'section' => 'footer_credits',
    'settings' => 'footer_credits_spacing_devices',
    'priority' => 40,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'dimensions',
        'settings' => 'footer_credits_spacing' . $key,
        'description' => $value['description'],
        'section' => 'footer_credits',
        'priority' => 45,
        'default' => array(
            'top' => '20px',
            'right' => '0px',
            'bottom' => '20px',
            'left' => '0px',
        ),
        'transport' => 'auto',
        'output' => array(
            array(
                'property' => 'padding',
                'element' => '.footer-credits-text',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'footer_credits_spacing_separator_bottom',
    'section' => 'footer_credits',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));