<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('woo_global_buttons_section', array(
    'title' => esc_attr__('Buttons', 'envo-extra'),
    'panel' => 'woo_section_main',
    'priority' => 4,
));

$devices = array(
    'desktop' => array(
        'media_query_key' => '',
        'media_query' => '',
        'description' => 'Desktop',
    ),
    'tablet' => array(
        'media_query_key' => 'media_query',
        'media_query' => '@media (max-width: 991px)',
        'description' => 'Tablet',
    ),
    'mobile' => array(
        'media_query_key' => 'media_query',
        'media_query' => '@media (max-width: 767px)',
        'description' => 'Mobile',
    ),
);

/**
 * Woo buttons styling
 */

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Button font', 'envo-extra'),
    'section' => 'woo_global_buttons_section',
    'settings' => 'woo_global_product_buttons_devices',
    'priority' => 10,
));
// Responsive field.
foreach ($devices as $key => $value) {
Kirki::add_field('envo_extra', array(
    'type' => 'typography',
    'settings' => 'woo_global_product_buttons_font' . $key,
	'description' => $value['description'],
    'section' => 'woo_global_buttons_section',
    'transport' => 'auto',
    'priority' => 15,
    'choices' => array(
        'fonts' => envo_extra_fonts(),
    ),
    'default' => array(
        'font-family' => '',
        'font-size' => '14px',
        'variant' => '300',
        'line-height' => '1.6',
        'letter-spacing' => '0px',
        'text-transform' => 'none',
        'text-decoration' => 'none',
        'word-spacing' => '0px',
        'text-align' => 'none',
        'margin-top' => '0px',
        'margin-bottom' => '0px',
    ),
    'output' => array(
        array(
            'element' => '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
			$value['media_query_key'] => $value['media_query'],
		),
    ),
));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'woo_global_product_buttons_separator',
    'section' => 'woo_global_buttons_section',
    'priority' => 20,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'woo_global_product_buttons_spacing',
    'label' => esc_attr__('Button padding', 'envo-extra'),
    'section' => 'woo_global_buttons_section',
    'priority' => 20,
    'transport' => 'auto',
    'default' => array(
        'top' => '6px',
        'right' => '20px',
        'bottom' => '6px',
        'left' => '20px',
    ),
    'output' => array(
        array(
            'property' => 'padding',
            'element' => '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
        ),
    ),
));

Kirki::add_field('envo_extra', array(
    'type' => 'slider',
    'settings' => 'woo_global_product_buttons_radius',
    'label' => esc_attr__('Button border radius', 'envo-extra'),
    'section' => 'woo_global_buttons_section',
    'default' => 0,
    'transport' => 'auto',
    'priority' => 20,
    'choices' => array(
        'min' => '0',
        'max' => '20',
        'step' => '1',
    ),
    'output' => array(
        array(
            'element' => '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
            'property' => 'border-radius',
            'units' => 'px',
        ),
    ),
));
