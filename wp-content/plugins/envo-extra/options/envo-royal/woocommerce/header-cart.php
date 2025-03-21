<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('woo_icons_order', array(
    'title' => esc_attr__('Icons order', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

Kirki::add_field('envo_extra', array(
    'type' => 'sortable',
    'settings' => 'header_icons_layout',
    'label' => esc_html__('Icons order', 'envo-extra'),
    'section' => 'woo_icons_order',
    'default' => array(
        'header_cart',
        'my_account',
        'head_wishlist',
        'head_compare',
        'head_search',
    ),
    'choices' => array(
        'header_cart' => esc_html__('Cart', 'envo-extra'),
        'my_account' => esc_html__('My Account', 'envo-extra'),
        'head_wishlist' => esc_html__('Wishlist', 'envo-extra'),
        'head_compare' => esc_html__('Compare', 'envo-extra'),
        'head_search' => esc_html__('Search', 'envo-extra'),
    ),
    'priority' => 10,
));

Kirki::add_section('woo_cart_section', array(
    'title' => esc_attr__('Cart', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

/**
 * Header cart icon
 */


