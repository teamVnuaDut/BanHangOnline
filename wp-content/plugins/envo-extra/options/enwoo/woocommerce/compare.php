<?php

Kirki::add_section('woo_compare_section', array(
    'title' => esc_attr__('Compare', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

/**
 * Header my account icon
 */
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('Compare icon', 'envo-extra'),
    'section' => 'woo_compare_section',
    'settings' => 'header_compare_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.header-compare, .mobile-compare .header-compare',
            'property' => 'display',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'woo_compare_color',
    'label' => esc_attr__('Header compare icon color', 'envo-extra'),
    'section' => 'woo_compare_section',
    'default' => '',
    'choices' => array(
        'alpha' => true,
    ),
    'transport' => 'auto',
    'priority' => 10,
    'output' => array(
        array(
            'element' => '.header-compare a i',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_compare_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
