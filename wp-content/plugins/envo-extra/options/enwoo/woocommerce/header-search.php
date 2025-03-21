<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('woo_search_section', array(
    'title' => esc_attr__('Search', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

/**
 * Header search form
 */
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('Search form', 'envo-extra'),
    'section' => 'woo_search_section',
    'settings' => 'header_search_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'priority' => 5,
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.header-search-form',
            'property' => 'display',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'woo_header_search_border_color',
    'label' => esc_attr__('Border color', 'envo-extra'),
    'section' => 'woo_search_section',
    'default' => '',
    'choices' => array(
        'alpha' => true,
    ),
    'transport' => 'auto',
    'priority' => 10,
    'output' => array(
        array(
            'element' => 'button.header-search-button, input.header-search-input, select.header-search-select, .header-search-form',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_search_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_header_search_button_color',
    'label' => esc_attr__('Search button colors', 'envo-extra'),
    'section' => 'woo_search_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'icon' => esc_attr__('Icon', 'envo-extra'),
        'icon-hover' => esc_attr__('Icon hover', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
        'background-hover' => esc_attr__('Background hover', 'envo-extra'),
    ),
    'default' => array(
        'icon' => '',
        'icon-hover' => '',
        'background' => '',
        'background-hover' => '',
    ),
    'output' => array(
        array(
            'choice' => 'icon',
            'element' => 'button.header-search-button .la',
            'property' => 'color',
        ),
        array(
            'choice' => 'icon-hover',
            'element' => 'button.header-search-button .la:hover',
            'property' => 'color',
        ),
        array(
            'choice' => 'background',
            'element' => 'button.header-search-button',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'background-hover',
            'element' => 'button.header-search-button:hover',
            'property' => 'background-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_search_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_header_search_input_color',
    'label' => esc_attr__('Search input colors', 'envo-extra'),
    'section' => 'woo_search_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'input' => esc_attr__('Background', 'envo-extra'),
        'input-text' => esc_attr__('Text', 'envo-extra'),
        'input-placeholder' => esc_attr__('Placeholder', 'envo-extra'),
    ),
    'default' => array(
        'input' => '',
        'input-text' => '',
        'input-placeholder' => '',
    ),
    'output' => array(
        array(
            'choice' => 'input',
            'element' => 'input.header-search-input',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'input-text',
            'element' => 'input.header-search-input',
            'property' => 'color',
        ),
        array(
            'choice' => 'input-placeholder',
            'element' => 'input.header-search-input::placeholder, input.header-search-input:-ms-input-placeholder, input.header-search-input::-webkit-input-placeholder, input.header-search-input::-moz-placeholder, input.header-search-input:-moz-placeholder',
            'property' => 'color',
            'suffix' => '!important',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_search_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_header_search_select_color',
    'label' => esc_attr__('Search categories colors', 'envo-extra'),
    'section' => 'woo_search_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'select' => esc_attr__('Background', 'envo-extra'),
        'select-text' => esc_attr__('Text', 'envo-extra'),
    ),
    'default' => array(
        'select' => '',
        'select-text' => '',
    ),
    'output' => array(
        array(
            'choice' => 'select',
            'element' => 'select.header-search-select',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'select-text',
            'element' => 'select.header-search-select',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_search_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'woo_header_search_border_radius',
    'label' => esc_attr__('Rounded corners', 'envo-extra'),
    'section' => 'woo_search_section',
    'default' => 0,
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'min' => 0,
        'max' => 25,
        'step' => 1,
    ),
    'output' => array(
        array(
            'element' => '.header-search-form',
            'property' => 'border-radius',
            'units' => 'px',
        ),
        array(
            'element' => 'input.header-search-input',
            'property' => 'border-top-left-radius',
            'units' => 'px',
        ),
        array(
            'element' => 'input.header-search-input',
            'property' => 'border-bottom-left-radius',
            'units' => 'px',
        ),
        array(
            'element' => 'button.header-search-button',
            'property' => 'border-top-right-radius',
            'units' => 'px',
        ),
        array(
            'element' => 'button.header-search-button',
            'property' => 'border-bottom-right-radius',
            'units' => 'px',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_search_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));