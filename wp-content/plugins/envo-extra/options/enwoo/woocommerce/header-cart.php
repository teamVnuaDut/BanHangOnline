<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('woo_cart_section', array(
    'title' => esc_attr__('Cart', 'envo-extra'),
    'panel' => 'woo_cart_account',
    'priority' => 6,
));

/**
 * Header cart icon
 */
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('Cart', 'envo-extra'),
    'section' => 'woo_cart_section',
    'settings' => 'header_cart_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'priority' => 5,
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    ),
    'output' => array(
        array(
            'element' => '.header-cart, .mobile-cart .header-cart',
            'property' => 'display',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'woo_cart_color',
    'label' => esc_attr__('Header cart icon color', 'envo-extra'),
    'section' => 'woo_cart_section',
    'default' => '',
    'choices' => array(
        'alpha' => true,
    ),
    'transport' => 'auto',
    'priority' => 10,
    'output' => array(
        array(
            'element' => '.header-cart a.cart-contents i',
            'property' => 'color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_cart_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_cart_counter_color',
    'label' => esc_attr__('Header cart counter', 'envo-extra'),
    'section' => 'woo_cart_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'color' => esc_attr__('Color', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
        'border' => esc_attr__('Border', 'envo-extra'),
    ),
    'default' => array(
        'color' => '',
        'background' => '',
        'border' => '',
    ),
    'output' => array(
        array(
            'choice' => 'color',
            'element' => '.cart-contents span.count',
            'property' => 'color',
        ),
        array(
            'choice' => 'background',
            'element' => '.cart-contents span.count',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'border',
            'element' => '.cart-contents span.count',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_cart_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_cart_subtotal_color',
    'label' => esc_attr__('Header cart subtotal', 'envo-extra'),
    'section' => 'woo_cart_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'color' => esc_attr__('Color', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
    ),
    'default' => array(
        'color' => '',
        'background' => '',
    ),
    'output' => array(
        array(
            'choice' => 'color',
            'element' => '.amount-cart',
            'property' => 'color',
        ),
        array(
            'choice' => 'background',
            'element' => '.amount-cart',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'background',
            'element' => '.amount-cart:before',
            'property' => 'border-right-color',
        ),
        array(
            'choice' => 'background',
            'element' => '.rtl .amount-cart:before',
            'property' => 'border-left-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_cart_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_cart_open_box',
    'label' => esc_attr__('Header cart open box colors', 'envo-extra'),
    'section' => 'woo_cart_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'color' => esc_attr__('Text color', 'envo-extra'),
        'links' => esc_attr__('Links', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
        'border' => esc_attr__('Border', 'envo-extra'),
    ),
    'default' => array(
        'color' => '',
        'links' => '',
        'background' => '',
        'border' => '',
    ),
    'output' => array(
        array(
            'choice' => 'color',
            'element' => 'ul.site-header-cart, .product-added-to-cart ul.site-header-cart',
            'property' => 'color',
        ),
        array(
            'choice' => 'links',
            'element' => 'ul.site-header-cart a, .product-added-to-cart ul.site-header-cart a',
            'property' => 'color',
        ),
        array(
            'choice' => 'background',
            'element' => '.cart-open ul.site-header-cart, .product-added-to-cart .header-cart-block ul.site-header-cart',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'border',
            'element' => '.cart-open ul.site-header-cart, .product-added-to-cart .header-cart-block ul.site-header-cart',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_cart_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_cart_open_buttons',
    'label' => esc_attr__('Open cart buttons', 'envo-extra'),
    'section' => 'woo_cart_section',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'text' => esc_attr__('Text', 'envo-extra'),
        'bg' => esc_attr__('Background', 'envo-extra'),
        'border' => esc_attr__('Border', 'envo-extra'),
    ),
    'default' => array(
        'text' => '',
        'bg' => '',
        'border' => '',
    ),
    'output' => array(
        array(
            'choice' => 'text',
            'element' => 'ul.site-header-cart .buttons .button',
            'property' => 'color',
        ),
        array(
            'choice' => 'bg',
            'element' => '.cart-open ul.site-header-cart .buttons .button',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'border',
            'element' => '.cart-open ul.site-header-cart .buttons .button',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'header_cart_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
