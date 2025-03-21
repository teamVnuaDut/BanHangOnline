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
    'type' => 'slider',
    'settings' => 'woo_header_search_border_radius',
    'label' => esc_attr__('Rounded corners', 'envo-extra'),
    'section' => 'woo_search_section',
    'default' => 0,
    'priority' => 11,
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
            'setting' => 'header_icons_layout',
            'operator' => 'in',
            'value' => 'head_search',
        ),
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
    ),
));