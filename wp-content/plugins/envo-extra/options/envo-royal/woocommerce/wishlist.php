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

