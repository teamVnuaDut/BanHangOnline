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
    'type' => 'multicolor',
    'settings' => 'woo_global_product_buttons',
    'label' => esc_attr__('Buttons', 'envo-extra'),
    'section' => 'woo_global_buttons_section',
    'priority' => 20,
    'transport' => 'auto',
    'choices' => array(
        'link' => esc_attr__('Color', 'envo-extra'),
        'border' => esc_attr__('Border', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
    ),
    'default' => array(
        'link' => '',
        'border' => '',
        'background' => 'transparent',
    ),
    'output' => array(
        array(
            'choice' => 'link',
            'element' => '.woocommerce #respond input#submit, .woocommerce a.button, #sidebar .widget.widget_shopping_cart a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
            'property' => 'color',
        ),
        array(
            'choice' => 'border',
            'element' => '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
            'property' => 'border-color',
        ),
        array(
            'choice' => 'background',
            'element' => '.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
            'property' => 'background-color',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'woo_global_product_buttons_hover',
    'label' => esc_attr__('Buttons hover', 'envo-extra'),
    'section' => 'woo_global_buttons_section',
    'priority' => 20,
    'transport' => 'auto',
    'choices' => array(
        'link' => esc_attr__('Color', 'envo-extra'),
        'border' => esc_attr__('Border', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
    ),
    'default' => array(
        'link' => '',
        'border' => '',
        'background' => '',
    ),
    'output' => array(
        array(
            'choice' => 'link',
            'element' => '.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, #sidebar .widget.widget_shopping_cart a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover',
            'property' => 'color',
        ),
        array(
            'choice' => 'border',
            'element' => '.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover',
            'property' => 'border-color',
        ),
        array(
            'choice' => 'background',
            'element' => '.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover',
            'property' => 'background-color',
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

Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'woo_filter_by_price',
    'label' => esc_attr__('Filter by price sider', 'envo-extra'),
    'section' => 'woo_global_buttons_section',
    'default' => '',
    'choices' => array(
        'alpha' => true,
    ),
    'transport' => 'auto',
    'priority' => 20,
    'output' => array(
        array(
            'element' => '.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
            'property' => 'background-color',
        ),
    ),
));
