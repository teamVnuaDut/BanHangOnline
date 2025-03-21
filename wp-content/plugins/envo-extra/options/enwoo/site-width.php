<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('site_width_section', array(
    'title' => esc_attr__('Site Width Options', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 20,
));

// Top bar
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'top_bar_width_select',
    'label' => esc_attr__('Top Bar Width', 'envo-extra'),
    'section' => 'site_width_section',
    'default' => 'custom',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'custom' => esc_attr__('Custom', 'envo-extra'),
        '100' => esc_attr__('Full Width', 'envo-extra'),
    ),
    'output' => array(
        array(
            'choice' => '100',
            'element' => '.top-bar-section .container',
            'property' => 'width',
            'media_query' => '@media (min-width: 1430px)',
            'units' => '%',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'top_bar_width',
    'section' => 'site_width_section',
    'default' => '1170',
    'priority' => 10,
    'choices' => array(
        'min' => 960,
        'max' => 1980,
        'step' => 5,
    ),
    'output' => array(
        array(
            'element' => '.top-bar-section .container',
            'property' => 'width',
            'media_query' => envo_extra_media_width_css('top_bar'),
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'top_bar_width_select',
            'operator' => '==',
            'value' => 'custom',
        ),
    ),
));

// Header
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'header_width_select',
    'label' => esc_attr__('Header Width', 'envo-extra'),
    'section' => 'site_width_section',
    'default' => 'custom',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'custom' => esc_attr__('Custom', 'envo-extra'),
        '100' => esc_attr__('Full Width', 'envo-extra'),
    ),
    'output' => array(
        array(
            'choice' => '100',
            'element' => '.site-header .container',
            'property' => 'width',
            'media_query' => '@media (min-width: 1430px)',
            'units' => '%',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'header_width',
    'section' => 'site_width_section',
    'default' => '1170',
    'priority' => 10,
    'choices' => array(
        'min' => 960,
        'max' => 1980,
        'step' => 5,
    ),
    'output' => array(
        array(
            'element' => '.site-header .container',
            'property' => 'width',
            'media_query' => envo_extra_media_width_css('header'),
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_width_select',
            'operator' => '==',
            'value' => 'custom',
        ),
    ),
));

// Menu
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'menu_width_select',
    'label' => esc_attr__('Search Bar Width', 'envo-extra'),
    'section' => 'site_width_section',
    'default' => 'custom',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'custom' => esc_attr__('Custom', 'envo-extra'),
        '100' => esc_attr__('Full Width', 'envo-extra'),
    ),
    'output' => array(
        array(
            'choice' => '100',
            'element' => '.main-menu .container',
            'property' => 'width',
            'media_query' => '@media (min-width: 1430px)',
            'units' => '%',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'menu_width',
    'section' => 'site_width_section',
    'default' => '1170',
    'priority' => 10,
    'choices' => array(
        'min' => 960,
        'max' => 1980,
        'step' => 5,
    ),
    'output' => array(
        array(
            'element' => '.main-menu .container',
            'property' => 'width',
            'media_query' => envo_extra_media_width_css('menu'),
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'menu_width_select',
            'operator' => '==',
            'value' => 'custom',
        ),
    ),
));

// Content
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'content_width_select',
    'label' => esc_attr__('Content Width', 'envo-extra'),
    'section' => 'site_width_section',
    'default' => 'custom',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'custom' => esc_attr__('Custom', 'envo-extra'),
        '100' => esc_attr__('Full Width', 'envo-extra'),
    ),
    'output' => array(
        array(
            'choice' => '100',
            'element' => '#site-content.container',
            'property' => 'width',
            'media_query' => '@media (min-width: 1430px)',
            'units' => '%',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'content_width',
    'section' => 'site_width_section',
    'default' => '1170',
    'priority' => 10,
    'choices' => array(
        'min' => 960,
        'max' => 1980,
        'step' => 5,
    ),
    'output' => array(
        array(
            'element' => '#site-content.container',
            'property' => 'width',
            'media_query' => envo_extra_media_width_css('content'),
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'content_width_select',
            'operator' => '==',
            'value' => 'custom',
        ),
    ),
));

// Footer Widgets
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'footer_widgets_width_select',
    'label' => esc_attr__('Footer Widgets Width', 'envo-extra'),
    'section' => 'site_width_section',
    'default' => 'custom',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'custom' => esc_attr__('Custom', 'envo-extra'),
        '100' => esc_attr__('Full Width', 'envo-extra'),
    ),
    'output' => array(
        array(
            'choice' => '100',
            'element' => '#content-footer-section .container',
            'property' => 'width',
            'media_query' => '@media (min-width: 1430px)',
            'units' => '%',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'footer_widgets_width',
    'section' => 'site_width_section',
    'default' => '1170',
    'priority' => 10,
    'choices' => array(
        'min' => 960,
        'max' => 1980,
        'step' => 5,
    ),
    'output' => array(
        array(
            'element' => '#content-footer-section .container',
            'property' => 'width',
            'media_query' => envo_extra_media_width_css('footer_widgets'),
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_widgets_width_select',
            'operator' => '==',
            'value' => 'custom',
        ),
    ),
));

// Footer Credits
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'settings' => 'footer_credits_width_select',
    'label' => esc_attr__('Footer Credits Width', 'envo-extra'),
    'section' => 'site_width_section',
    'default' => 'custom',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'custom' => esc_attr__('Custom', 'envo-extra'),
        '100' => esc_attr__('Full Width', 'envo-extra'),
    ),
    'output' => array(
        array(
            'choice' => '100',
            'element' => '.footer-credits .container',
            'property' => 'width',
            'media_query' => '@media (min-width: 1430px)',
            'units' => '%',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'footer_credits_width',
    'section' => 'site_width_section',
    'default' => '1170',
    'priority' => 10,
    'choices' => array(
        'min' => 960,
        'max' => 1980,
        'step' => 5,
    ),
    'output' => array(
        array(
            'element' => '.footer-credits .container',
            'property' => 'width',
            'media_query' => envo_extra_media_width_css('footer_credits'),
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'footer_credits_width_select',
            'operator' => '==',
            'value' => 'custom',
        ),
    ),
));

function envo_extra_media_width_css($section = '') {

    $header_width = get_theme_mod($section . '_width', '1170');

    $media_width = $header_width + 30;

    return '@media (min-width: ' . $media_width . 'px)';
}
