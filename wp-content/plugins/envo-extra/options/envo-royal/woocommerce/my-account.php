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
