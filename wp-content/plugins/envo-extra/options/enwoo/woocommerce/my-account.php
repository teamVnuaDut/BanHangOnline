<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('woo_account_section', array(
    'title' => esc_attr__('My Account', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

/**
 * Header my account icon
 */
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('My Account icon', 'envo-extra'),
    'section' => 'woo_account_section',
    'settings' => 'header_account_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.header-my-account, .mobile-account .header-my-account',
            'property' => 'display',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'woo_account_color',
    'label' => esc_attr__('Header My Account icon color', 'envo-extra'),
    'section' => 'woo_account_section',
    'default' => '',
    'choices' => array(
        'alpha' => true,
    ),
    'transport' => 'auto',
    'priority' => 15,
    'output' => array(
        array(
            'element' => '.header-my-account a i',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_account_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
