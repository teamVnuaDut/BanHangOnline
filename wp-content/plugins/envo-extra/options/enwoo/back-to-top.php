<?php

if (!class_exists('Kirki')) {
    return;
}
Kirki::add_section('back_to_top', array(
    'title' => esc_attr__('Back to top', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 80,
));
$devices = array(
    'desktop' => array(
        'media_query_key' => '',
        'media_query' => '',
    ),
    'tablet' => array(
        'media_query_key' => 'media_query',
        'media_query' => '@media (max-width: 991px)',
    ),
    'mobile' => array(
        'media_query_key' => 'media_query',
        'media_query' => '@media (max-width: 767px)',
    ),
);
/**
 * Header cart icon
 */
Kirki::add_field('envo_extra', array(
    'type' => 'radio-buttonset',
    'label' => esc_html__('Back to top button', 'envo-extra'),
    'section' => 'back_to_top',
    'settings' => 'back_to_top_on_off',
    'default' => 'block',
    'transport' => 'auto',
    'choices' => array(
        'block' => esc_html__('On', 'envo-extra'),
        'none' => esc_html__('Off', 'envo-extra'),
    )
));
Kirki::add_field('envo_extra', array(
    'type' => 'multicolor',
    'settings' => 'back_to_top_color',
    'label' => esc_attr__('Colors', 'envo-extra'),
    'section' => 'back_to_top',
    'priority' => 10,
    'transport' => 'auto',
    'choices' => array(
        'color' => esc_attr__('Color', 'envo-extra'),
        'background' => esc_attr__('Background', 'envo-extra'),
        'color-hover' => esc_attr__('Color hover', 'envo-extra'),
        'background-hover' => esc_attr__('Background hover', 'envo-extra'),
    ),
    'default' => array(
        'color' => '',
        'background' => '',
        'color-hover' => '',
        'background-hover' => '',
    ),
    'output' => array(
        array(
            'choice' => 'color',
            'element' => '#return-to-top i',
            'property' => 'color',
        ),
        array(
            'choice' => 'background',
            'element' => '#return-to-top',
            'property' => 'background-color',
        ),
        array(
            'choice' => 'color-hover',
            'element' => '#return-to-top:hover i',
            'property' => 'color',
        ),
        array(
            'choice' => 'background-hover',
            'element' => '#return-to-top:hover',
            'property' => 'background-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'back_to_top_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'back_to_top_pos_left_separator',
    'section' => 'back_to_top',
    'priority' => 10,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
    'active_callback' => array(
        array(
            'setting' => 'back_to_top_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Position from right', 'envo-extra'),
    'section' => 'back_to_top',
    'settings' => 'back_to_top_pos_left_devices',
    'priority' => 11,
    'active_callback' => array(
        array(
            'setting' => 'back_to_top_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));

// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'slider',
        'settings' => 'back_to_top_pos_left' . $key,
        'section' => 'back_to_top',
        'default' => 2,
        'priority' => 12,
        'transport' => 'auto',
        'choices' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
        'output' => array(
            array(
                'element' => '#return-to-top',
                'property' => 'right',
                'units' => '%',
                $value['media_query_key'] => $value['media_query'],
            ),
            array(
                'element' => '.rtl #return-to-top',
                'property' => 'left',
                'units' => '%',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
        'active_callback' => array(
            array(
                'setting' => 'back_to_top_on_off',
                'operator' => '==',
                'value' => 'block',
            ),
        ),
    ));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'back_to_top_separator_top',
    'section' => 'back_to_top',
    'priority' => 15,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
    'active_callback' => array(
        array(
            'setting' => 'back_to_top_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Position from bottom', 'envo-extra'),
    'section' => 'back_to_top',
    'settings' => 'back_to_top_pos_bottom_devices',
    'priority' => 16,
    'active_callback' => array(
        array(
            'setting' => 'back_to_top_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'slider',
        'settings' => 'back_to_top_pos_right' . $key,
        'section' => 'back_to_top',
        'default' => 4,
        'priority' => 20,
        'transport' => 'auto',
        'choices' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
        'output' => array(
            array(
                'element' => '#return-to-top',
                'property' => 'bottom',
                'units' => '%',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
        'active_callback' => array(
            array(
                'setting' => 'back_to_top_on_off',
                'operator' => '==',
                'value' => 'block',
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'back_to_top_separator_bottom',
    'section' => 'back_to_top',
    'priority' => 25,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
    'active_callback' => array(
        array(
            'setting' => 'back_to_top_on_off',
            'operator' => '==',
            'value' => 'block',
        ),
    ),
));