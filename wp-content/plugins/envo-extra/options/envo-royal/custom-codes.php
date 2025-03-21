<?php

if (!class_exists('Kirki')) {
    return;
}

function envo_extra_do_not_filter_anything($value) {
    return $value;
}

Kirki::add_section('custom_code_section', array(
    'title' => esc_attr__('Custom Codes', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 100,
));

Kirki::add_field('envo_extra', array(
    'type' => 'textarea',
    'settings' => 'header-code',
    'label' => __('Code to be added to the HEAD', 'envo-extra'),
    'description' => __('Suitable for Google Analytics code', 'envo-extra'),
    'section' => 'custom_code_section',
    'transport' => 'postMessage',
    'sanitize_callback' => 'envo_extra_do_not_filter_anything',
    'default' => '',
    'priority' => 10,
));

add_action('wp_head', 'envo_extra_add_googleanalytics', 10);

function envo_extra_add_googleanalytics() {
    $header_code = get_theme_mod('header-code', '');
    if ($header_code) {
        echo get_theme_mod('header-code', '');
    }
}

Kirki::add_field('envo_extra', array(
    'type' => 'textarea',
    'settings' => 'footer-code',
    'label' => __('Code to be added to the footer', 'envo-extra'),
    'section' => 'custom_code_section',
    'transport' => 'postMessage',
    'sanitize_callback' => 'envo_extra_do_not_filter_anything',
    'default' => '',
    'priority' => 10,
));

add_action('wp_footer', 'envo_extra_add_footer_code');

function envo_extra_add_footer_code() {
    $header_code = get_theme_mod('footer-code', '');
    if ($header_code) {
        echo get_theme_mod('footer-code', '');
    }
}
