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
            'color' => '',
            'word-spacing' => '0px',
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
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'main_color_content_bg',
    'label' => esc_attr__('Background', 'envo-extra'),
    'section' => 'main_colors_section',
    'default' => '',
    'transport' => 'auto',
    'priority' => 20,
    'output' => array(
        array(
            'element' => '.main-container, #sidebar .widget-title h3, .container-fluid.archive-page-header, #product-nav > a',
            'property' => 'background-color',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'main_color_links',
    'label' => esc_attr__('Links', 'envo-extra'),
    'section' => 'main_colors_section',
    'priority' => 20,
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
            'element' => 'a, .author-meta a, .tags-links a, .cat-links a, nav.navigation.pagination .nav-links a, .comments-meta a',
            'property' => 'color',
        ),
        array(
            'choice' => 'link',
            'element' => '.widget-title:before, nav.navigation.pagination .current:before',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'link',
            'element' => 'nav.navigation.pagination .current:before',
            'property' => 'border-color',
        ),
        array(
            'choice' => 'hover',
            'element' => 'a:active, a:hover, a:focus, .tags-links a:hover, .cat-links a:hover, .comments-meta a:hover',
            'property' => 'color',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'main_color_buttons',
    'label' => esc_attr__('Buttons', 'envo-extra'),
    'section' => 'main_colors_section',
    'priority' => 20,
    'transport' => 'auto',
    'choices' => array(
        'color' => esc_attr__('Color', 'envo-extra'),
        'bg' => esc_attr__('Background', 'envo-extra'),
        'border' => esc_attr__('Border', 'envo-extra'),
    ),
    'default' => array(
        'color' => '',
        'bg' => '',
        'border' => '',
    ),
    'output' => array(
        array(
            'choice' => 'color',
            'element' => '.read-more-button a, #searchsubmit, .btn-default, input[type="submit"], input#submit, input#submit:hover, button, a.comment-reply-link, .btn-default:hover, input[type="submit"]:hover, button:hover, a.comment-reply-link:hover',
            'property' => 'color',
        ),
        array(
            'choice' => 'bg',
            'element' => '.read-more-button a, #searchsubmit, .btn-default, input[type="submit"], input#submit, input#submit:hover, button, a.comment-reply-link, .btn-default:hover, input[type="submit"]:hover, button:hover, a.comment-reply-link:hover',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'border',
            'element' => '.read-more-button a, #searchsubmit, .btn-default, input[type="submit"], input#submit, input#submit:hover, button, a.comment-reply-link, .btn-default:hover, input[type="submit"]:hover, button:hover, a.comment-reply-link:hover',
            'property' => 'border-color',
        ),
    ),
));
