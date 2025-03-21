<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('woo_wishlist_section', array(
    'title' => esc_attr__('Wishlist', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

/**
 * Header my account icon
 */
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('Wishlist icon', 'envo-extra'),
    'section' => 'woo_wishlist_section',
    'settings' => 'header_wishlist_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.header-wishlist, .mobile-wishlist .header-wishlist',
            'property' => 'display',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'woo_wishlist_color',
    'label' => esc_attr__('Header wishlist icon color', 'envo-extra'),
    'section' => 'woo_wishlist_section',
    'default' => '',
    'choices' => array(
        'alpha' => true,
    ),
    'transport' => 'auto',
    'priority' => 10,
    'output' => array(
        array(
            'element' => '.header-wishlist a i',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_wishlist_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
