<?php

if ( !class_exists( 'Kirki' ) ) {
	return;
}

Kirki::add_panel( 'woo_archive_section', array(
	'title'		 => esc_attr__( 'Archive/Shop', 'envo-extra' ),
	'panel'		 => 'woo_section_main',
	'priority'	 => 10,
) );

$devices = array(
	'desktop'	 => array(
		'media_query_key'	 => '',
		'media_query'		 => '',
		'description'		 => 'Desktop',
	),
	'tablet'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 991px)',
		'description'		 => 'Tablet',
	),
	'mobile'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 767px)',
		'description'		 => 'Mobile',
	),
);

Kirki::add_section( 'woo_archive_global_section', array(
	'title'		 => esc_attr__( 'Global options', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'slider',
	'settings'		 => 'archive_number_products',
	'label'			 => esc_attr__( 'Number of items', 'envo-extra' ),
	'description'	 => esc_attr__( 'Change number of products displayed per page in archive(shop) page.', 'envo-extra' ),
	'section'		 => 'woo_archive_global_section',
	'default'		 => 12,
	'priority'		 => 2,
	'choices'		 => array(
		'min'	 => 2,
		'max'	 => 64,
		'step'	 => 1,
	),
) );

Kirki::add_field( 'envo_extra', array(
	'type'			 => 'slider',
	'settings'		 => 'archive_number_columns',
	'label'			 => esc_attr__( 'Items per row', 'envo-extra' ),
	'description'	 => esc_attr__( 'Change the number of products columns per row in archive(shop) page.', 'envo-extra' ),
	'section'		 => 'woo_archive_global_section',
	'default'		 => 4,
	'priority'		 => 10,
	'choices'		 => array(
		'min'	 => 2,
		'max'	 => 5,
		'step'	 => 1,
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_archive_product_equal_height',
	'label'		 => esc_attr__( 'Products with equal height', 'envo-extra' ),
	'section'	 => 'woo_archive_global_section',
	'default'	 => 1,
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_archive_breadcrumbs',
	'label'		 => esc_attr__( 'Breadcrumbs', 'envo-extra' ),
	'section'	 => 'woo_archive_global_section',
	'default'	 => 1,
	'priority'	 => 10,
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'woo_archive_breadcrumb_font_separator_top',
	'section'			 => 'woo_archive_global_section',
	'priority'			 => 50,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_breadcrumbs',
			'operator'	 => '==',
			'value'		 => '1',
		),
	),
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'responsive_devices',
	'label'				 => esc_attr__( 'Breadcrubs font', 'envo-extra' ),
	'section'			 => 'woo_archive_global_section',
	'settings'			 => 'woo_archive_breadcrumb_font_devices',
	'priority'			 => 50,
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_breadcrumbs',
			'operator'	 => '==',
			'value'		 => '1',
		),
	),
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'				 => 'typography',
		'settings'			 => 'woo_archive_breadcrumb_font' . $key,
		'description'		 => $value[ 'description' ],
		'section'			 => 'woo_archive_global_section',
		'transport'			 => 'auto',
		'choices'			 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'			 => array(
			'font-family'		 => '',
			'font-size'			 => '13px',
			'variant'			 => '400',
			'line-height'		 => '1.4',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'left',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
		),
		'priority'			 => 55,
		'output'			 => array(
			array(
				'element'					 => '.archive .woo-breadcrumbs',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'woo_archive_breadcrumbs',
				'operator'	 => '==',
				'value'		 => '1',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'woo_archive_breadcrumb_font_separator_bottom',
	'section'			 => 'woo_archive_global_section',
	'priority'			 => 60,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_breadcrumbs',
			'operator'	 => '==',
			'value'		 => '1',
		),
	),
) );


Kirki::add_section( 'woo_archive_product_section', array(
	'title'		 => esc_attr__( 'Product', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_archive_product_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_archive_product_section',
	'default'		 => 'none',
	'priority'		 => 10,
	'placeholder'	 => esc_html__( 'Choose an option', 'envo-extra' ),
	'choices'		 => array(
		'none'	 => esc_html__( 'None', 'envo-extra' ),
		'solid'	 => esc_html__( 'Solid', 'envo-extra' ),
		'double' => esc_html__( 'Double', 'envo-extra' ),
		'dotted' => esc_html__( 'Dotted', 'envo-extra' ),
		'dashed' => esc_html__( 'Dashed', 'envo-extra' ),
		'groove' => esc_html__( 'Groove', 'envo-extra' ),
	),
	'transport'		 => 'auto',
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_product_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_archive_product_section',
	'priority'			 => 10,
	'default'			 => array(
		'border-top-width'		 => '0px',
		'border-right-width'	 => '0px',
		'border-bottom-width'	 => '0px',
		'border-left-width'		 => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'		 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width'	 => esc_attr__( 'Right', 'textdomain' ),
			'border-bottom-width'	 => esc_attr__( 'Bottom', 'textdomain' ),
			'border-left-width'		 => esc_attr__( 'Left', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_product_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_product_border_radius',
	'section'			 => 'woo_archive_product_section',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 10,
	'default'			 => array(
		'border-top-left-radius'	 => '0px',
		'border-top-right-radius'	 => '0px',
		'border-bottom-left-radius'	 => '0px',
		'border-bottom-right-radius' => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-left-radius'	 => esc_attr__( 'Top Left', 'textdomain' ),
			'border-top-right-radius'	 => esc_attr__( 'Top Right', 'textdomain' ),
			'border-bottom-left-radius'	 => esc_attr__( 'Bottom Left', 'textdomain' ),
			'border-bottom-right-radius' => esc_attr__( 'Bottom Right', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_product_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_archive_product_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_archive_product_section',
	'default'			 => '#f6f6f6',
	'transport'			 => 'auto',
	'priority'			 => 10,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_product_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_shadow_top',
	'section'	 => 'woo_archive_product_section',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'text',
	'settings'		 => 'woo_archive_product_shadow',
	'label'			 => esc_html__( 'Product box shadow', 'envo-extra' ),
	'description'	 => esc_attr__( 'e.g. 5px 5px 15px 5px #000000', 'envo-extra' ),
	'section'		 => 'woo_archive_product_section',
	'priority'		 => 10,
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product, .woocommerce-page ul.products li.product',
			'property'	 => 'box-shadow',
		),
	),
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_shadow_hover_top',
	'section'	 => 'woo_archive_product_section',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'text',
	'settings'		 => 'woo_archive_product_shadow_hover',
	'label'			 => esc_html__( 'Product shadow on hover', 'envo-extra' ),
	'description'	 => esc_attr__( 'e.g. 5px 5px 15px 5px #000000', 'envo-extra' ),
	'section'		 => 'woo_archive_product_section',
	'priority'		 => 10,
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product:hover, .woocommerce-page ul.products li.product:hover',
			'property'	 => 'box-shadow',
		),
	),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_section_sep_top',
	'section'	 => 'woo_archive_product_section',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_archive_product_section',
	'settings'	 => 'woo_archive_product_padding_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_product_padding' . $key,
		'section'		 => 'woo_archive_product_section',
		'priority'		 => 15,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '15px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce ul.products li.product',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_margin_sep_top',
	'section'	 => 'woo_archive_product_section',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Margin', 'envo-extra' ),
	'section'	 => 'woo_archive_product_section',
	'settings'	 => 'woo_archive_product_margin_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_product_margin' . $key,
		'section'		 => 'woo_archive_product_section',
		'priority'		 => 25,
		'default'		 => array(
			'top'	 => '0px',
			'bottom' => '2.992em',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'margin',
				'element'					 => '.woocommerce ul.products li.product',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_margin_sep_bottom',
	'section'	 => 'woo_archive_product_section',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'color',
	'settings'	 => 'woo_archive_product_bg',
	'label'		 => esc_attr__( 'Background', 'envo-extra' ),
	'section'	 => 'woo_archive_product_section',
	'default'	 => '',
	'choices'	 => array(
		'alpha' => true,
	),
	'transport'	 => 'auto',
	'priority'	 => 30,
	'output'	 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product, .woocommerce-page ul.products li.product, li.product-category.product, .woocommerce ul.products li.product .woocommerce-loop-category__title',
			'property'	 => 'background',
		),
	),
) );


Kirki::add_section( 'woo_archive_image_section', array(
	'title'		 => esc_attr__( 'Image', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_archive_image_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_archive_image_section',
	'default'		 => 'none',
	'priority'		 => 10,
	'placeholder'	 => esc_html__( 'Choose an option', 'envo-extra' ),
	'choices'		 => array(
		'none'	 => esc_html__( 'None', 'envo-extra' ),
		'solid'	 => esc_html__( 'Solid', 'envo-extra' ),
		'double' => esc_html__( 'Double', 'envo-extra' ),
		'dotted' => esc_html__( 'Dotted', 'envo-extra' ),
		'dashed' => esc_html__( 'Dashed', 'envo-extra' ),
		'groove' => esc_html__( 'Groove', 'envo-extra' ),
	),
	'transport'		 => 'auto',
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product a .archive-img-wrap img:not(.secondary-image)',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_image_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_archive_image_section',
	'priority'			 => 10,
	'default'			 => array(
		'border-top-width'		 => '0px',
		'border-right-width'	 => '0px',
		'border-bottom-width'	 => '0px',
		'border-left-width'		 => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'		 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width'	 => esc_attr__( 'Right', 'textdomain' ),
			'border-bottom-width'	 => esc_attr__( 'Bottom', 'textdomain' ),
			'border-left-width'		 => esc_attr__( 'Left', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product a .archive-img-wrap img:not(.secondary-image)',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_image_border_radius',
	'section'			 => 'woo_archive_image_section',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 10,
	'default'			 => array(
		'border-top-left-radius'	 => '0px',
		'border-top-right-radius'	 => '0px',
		'border-bottom-left-radius'	 => '0px',
		'border-bottom-right-radius' => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-left-radius'	 => esc_attr__( 'Top Left', 'textdomain' ),
			'border-top-right-radius'	 => esc_attr__( 'Top Right', 'textdomain' ),
			'border-bottom-left-radius'	 => esc_attr__( 'Bottom Left', 'textdomain' ),
			'border-bottom-right-radius' => esc_attr__( 'Bottom Right', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product a .archive-img-wrap img:not(.secondary-image)',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_archive_image_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_archive_image_section',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 10,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product a .archive-img-wrap img:not(.secondary-image)',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_image_padding_sep_top',
	'section'	 => 'woo_archive_image_section',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_archive_image_section',
	'settings'	 => 'woo_archive_image_padding_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_image_padding' . $key,
		'section'		 => 'woo_archive_image_section',
		'priority'		 => 25,
		'default'		 => array(
			'top'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
			'right'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce ul.products li.product a .archive-img-wrap img:not(.secondary-image)',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_image_margin_sep_top',
	'section'	 => 'woo_archive_image_section',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Margin', 'envo-extra' ),
	'section'	 => 'woo_archive_image_section',
	'settings'	 => 'woo_archive_image_margin_devices',
	'priority'	 => 30,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_image_margin' . $key,
		'section'		 => 'woo_archive_image_section',
		'priority'		 => 35,
		'default'		 => array(
			'top'	 => '0px',
			'bottom' => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'margin',
				'element'					 => '.woocommerce ul.products li.product a .archive-img-wrap',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_image_margin_sep_bottom',
	'section'	 => 'woo_archive_image_section',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'color',
	'settings'	 => 'woo_archive_image_bg',
	'label'		 => esc_attr__( 'Background', 'envo-extra' ),
	'section'	 => 'woo_archive_image_section',
	'default'	 => '',
	'choices'	 => array(
		'alpha' => true,
	),
	'transport'	 => 'auto',
	'priority'	 => 40,
	'output'	 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product a .archive-img-wrap',
			'property'	 => 'background-color',
		),
	),
) );


Kirki::add_section( 'woo_archive_title_section', array(
	'title'		 => esc_attr__( 'Title', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Font', 'envo-extra' ),
	'section'	 => 'woo_archive_title_section',
	'settings'	 => 'woo_archive_product_title_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_archive_product_title' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_archive_title_section',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '16px',
			'variant'			 => '700',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
		),
		'priority'		 => 20,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce ul.products li.product h3, li.product-category.product h3, .woocommerce ul.products li.product h2.woocommerce-loop-product__title, .woocommerce ul.products li.product h2.woocommerce-loop-category__title',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_title_sep_bottom',
	'section'	 => 'woo_archive_title_section',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );


Kirki::add_section( 'woo_archive_price_section', array(
	'title'		 => esc_attr__( 'Price', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_price_sep_top',
	'section'	 => 'woo_archive_price_section',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Price Font', 'envo-extra' ),
	'section'	 => 'woo_archive_price_section',
	'settings'	 => 'woo_archive_product_price_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_archive_product_price' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_archive_price_section',
		'transport'		 => 'auto',
		'priority'		 => 15,
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '16px',
			'variant'			 => '400',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
		),
		'output'		 => array(
			array(
				'element'					 => '.woocommerce ul.products li.product .price',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'property'					 => 'color',
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_price_del_sep_top',
	'section'	 => 'woo_archive_price_section',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Del Price Font', 'envo-extra' ),
	'section'	 => 'woo_archive_price_section',
	'settings'	 => 'woo_archive_product_price_del_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_archive_product_price_del' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_archive_price_section',
		'transport'		 => 'auto',
		'priority'		 => 25,
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '14px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'line-through',
			'word-spacing'		 => '0px',
		),
		'output'		 => array(
			array(
				'element'					 => '.woocommerce ul.products li.product .price del',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'property'					 => 'color',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_price_del_sep_bottom',
	'section'	 => 'woo_archive_price_section',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_section( 'woo_archive_categories_section', array(
	'title'		 => esc_attr__( 'Categories', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_archive_product_categories',
	'label'		 => esc_attr__( 'Categories', 'envo-extra' ),
	'section'	 => 'woo_archive_categories_section',
	'default'	 => 1,
	'priority'	 => 10,
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'woo_archive_categories_typo_sep_top',
	'section'			 => 'woo_archive_categories_section',
	'priority'			 => 10,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_product_categories',
			'operator'	 => '==',
			'value'		 => '1',
		),
	),
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'responsive_devices',
	'label'				 => esc_attr__( 'Font', 'envo-extra' ),
	'section'			 => 'woo_archive_categories_section',
	'settings'			 => 'woo_archive_categories_typo_devices',
	'priority'			 => 10,
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_product_categories',
			'operator'	 => '==',
			'value'		 => '1',
		),
	),
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'				 => 'typography',
		'settings'			 => 'woo_archive_categories_typo' . $key,
		'description'		 => $value[ 'description' ],
		'section'			 => 'woo_archive_categories_section',
		'transport'			 => 'auto',
		'choices'			 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'			 => array(
			'font-family'		 => '',
			'font-size'			 => '12px',
			'variant'			 => '400',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
		),
		'priority'			 => 15,
		'output'			 => array(
			array(
				'element'					 => '.archive-product-categories a, .archive-product-categories a:hover',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'woo_archive_product_categories',
				'operator'	 => '==',
				'value'		 => '1',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'woo_archive_categories_typo_sep_bottom',
	'section'			 => 'woo_archive_categories_section',
	'priority'			 => 20,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_product_categories',
			'operator'	 => '==',
			'value'		 => '1',
		),
	),
) );

Kirki::add_section( 'woo_archive_button_section', array(
	'title'		 => esc_attr__( 'Button', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Font', 'envo-extra' ),
	'section'	 => 'woo_archive_button_section',
	'settings'	 => 'woo_archive_product_buttons_font_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_archive_product_buttons_font' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_archive_button_section',
		'transport'		 => 'auto',
		'priority'		 => 15,
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '14px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
		),
		'output'		 => array(
			array(
				'element'					 => '.woocommerce ul.products li.product .button',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_buttons_padding_sep_top',
	'section'	 => 'woo_archive_button_section',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_archive_button_section',
	'settings'	 => 'woo_archive_product_buttons_padding_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_product_buttons_padding' . $key,
		'section'		 => 'woo_archive_button_section',
		'priority'		 => 25,
		'default'		 => array(
			'top'	 => '6px',
			'bottom' => '6px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce ul.products li.product .button',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_buttons_margin_sep_top',
	'section'	 => 'woo_archive_button_section',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Margin', 'envo-extra' ),
	'section'	 => 'woo_archive_button_section',
	'settings'	 => 'woo_archive_product_buttons_margin_devices',
	'priority'	 => 30,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_product_buttons_margin' . $key,
		'section'		 => 'woo_archive_button_section',
		'priority'		 => 35,
		'default'		 => array(
			'top'	 => '5px',
			'bottom' => '5px',
			'left'	 => '15px',
			'right'	 => '15px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'margin',
				'element'					 => '.woocommerce ul.products li.product .button',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_product_buttons_sep_top',
	'section'	 => 'woo_archive_button_section',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_archive_product_buttons',
	'label'		 => esc_attr__( 'Colors', 'envo-extra' ),
	'section'	 => 'woo_archive_button_section',
	'priority'	 => 40,
	'transport'	 => 'auto',
	'choices'	 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'	 => array(
		'link'		 => '',
		'background' => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.woocommerce ul.products li.product .button',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce ul.products li.product .button',
			'property'	 => 'background-color',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_archive_product_buttons_hover',
	'label'		 => esc_attr__( 'Colors on hover', 'envo-extra' ),
	'section'	 => 'woo_archive_button_section',
	'priority'	 => 40,
	'transport'	 => 'auto',
	'choices'	 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'border'	 => esc_attr__( 'Border', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'	 => array(
		'link'		 => '',
		'border'	 => '',
		'background' => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.woocommerce ul.products li.product .button:hover',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'border',
			'element'	 => '.woocommerce ul.products li.product .button:hover',
			'property'	 => 'border-color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce ul.products li.product .button:hover',
			'property'	 => 'background-color',
		),
	),
) );


// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_archive_button_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_archive_button_section',
	'default'		 => 'solid',
	'priority'		 => 40,
	'placeholder'	 => esc_html__( 'Choose an option', 'envo-extra' ),
	'choices'		 => array(
		'none'	 => esc_html__( 'None', 'envo-extra' ),
		'solid'	 => esc_html__( 'Solid', 'envo-extra' ),
		'double' => esc_html__( 'Double', 'envo-extra' ),
		'dotted' => esc_html__( 'Dotted', 'envo-extra' ),
		'dashed' => esc_html__( 'Dashed', 'envo-extra' ),
		'groove' => esc_html__( 'Groove', 'envo-extra' ),
	),
	'transport'		 => 'auto',
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product .button',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_button_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_archive_button_section',
	'priority'			 => 40,
	'default'			 => array(
		'border-top-width'		 => '1px',
		'border-right-width'	 => '1px',
		'border-bottom-width'	 => '1px',
		'border-left-width'		 => '1px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'		 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width'	 => esc_attr__( 'Right', 'textdomain' ),
			'border-bottom-width'	 => esc_attr__( 'Bottom', 'textdomain' ),
			'border-left-width'		 => esc_attr__( 'Left', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product .button',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_button_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_button_border_radius',
	'section'			 => 'woo_archive_button_section',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 40,
	'default'			 => array(
		'border-top-left-radius'	 => '0px',
		'border-top-right-radius'	 => '0px',
		'border-bottom-left-radius'	 => '0px',
		'border-bottom-right-radius' => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-left-radius'	 => esc_attr__( 'Top Left', 'textdomain' ),
			'border-top-right-radius'	 => esc_attr__( 'Top Right', 'textdomain' ),
			'border-bottom-left-radius'	 => esc_attr__( 'Bottom Left', 'textdomain' ),
			'border-bottom-right-radius' => esc_attr__( 'Bottom Right', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product .button',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_button_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_archive_button_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_archive_button_section',
	'default'			 => '#f6f6f6',
	'transport'			 => 'auto',
	'priority'			 => 40,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product .button',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_button_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_button_box_shadow_top',
	'section'	 => 'woo_archive_button_section',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'text',
	'settings'		 => 'woo_archive_button_box_shadow',
	'label'			 => esc_html__( 'Button box shadow', 'envo-extra' ),
	'description'	 => esc_attr__( 'e.g. 5px 5px 15px 5px #000000', 'envo-extra' ),
	'section'		 => 'woo_archive_button_section',
	'priority'		 => 40,
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product .button',
			'property'	 => 'box-shadow',
		),
	),
) );





Kirki::add_section( 'woo_archive_rating_section', array(
	'title'		 => esc_attr__( 'Rating stars', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'color',
	'settings'	 => 'woo_archive_product_rating',
	'label'		 => esc_attr__( 'Color', 'envo-extra' ),
	'section'	 => 'woo_archive_rating_section',
	'default'	 => '',
	'transport'	 => 'auto',
	'priority'	 => 10,
	'output'	 => array(
		array(
			'element'	 => '.woocommerce .star-rating span',
			'property'	 => 'color',
		),
	),
) );



Kirki::add_section( 'woo_archive_sale_section', array(
	'title'		 => esc_attr__( 'Sale badge', 'envo-extra' ),
	'panel'		 => 'woo_archive_section',
	'priority'	 => 10,
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_archive_sale_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_archive_sale_section',
	'default'		 => 'none',
	'priority'		 => 10,
	'placeholder'	 => esc_html__( 'Choose an option', 'envo-extra' ),
	'choices'		 => array(
		'none'	 => esc_html__( 'None', 'envo-extra' ),
		'solid'	 => esc_html__( 'Solid', 'envo-extra' ),
		'double' => esc_html__( 'Double', 'envo-extra' ),
		'dotted' => esc_html__( 'Dotted', 'envo-extra' ),
		'dashed' => esc_html__( 'Dashed', 'envo-extra' ),
		'groove' => esc_html__( 'Groove', 'envo-extra' ),
	),
	'transport'		 => 'auto',
	'output'		 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product .onsale',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_sale_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_archive_sale_section',
	'priority'			 => 10,
	'default'			 => array(
		'border-top-width'		 => '0px',
		'border-right-width'	 => '0px',
		'border-bottom-width'	 => '0px',
		'border-left-width'		 => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'		 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width'	 => esc_attr__( 'Right', 'textdomain' ),
			'border-bottom-width'	 => esc_attr__( 'Bottom', 'textdomain' ),
			'border-left-width'		 => esc_attr__( 'Left', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product .onsale',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_sale_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_archive_sale_border_radius',
	'section'			 => 'woo_archive_sale_section',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 10,
	'default'			 => array(
		'border-top-left-radius'	 => '0px',
		'border-top-right-radius'	 => '0px',
		'border-bottom-left-radius'	 => '0px',
		'border-bottom-right-radius' => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-left-radius'	 => esc_attr__( 'Top Left', 'textdomain' ),
			'border-top-right-radius'	 => esc_attr__( 'Top Right', 'textdomain' ),
			'border-bottom-left-radius'	 => esc_attr__( 'Bottom Left', 'textdomain' ),
			'border-bottom-right-radius' => esc_attr__( 'Bottom Right', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce ul.products li.product .onsale',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_sale_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_archive_sale_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_archive_sale_section',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 10,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce ul.products li.product .onsale',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_archive_sale_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_sale_padding_top_sep',
	'section'	 => 'woo_archive_sale_section',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_archive_sale_section',
	'settings'	 => 'woo_archive_sale_padding_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_archive_sale_padding' . $key,
		'section'		 => 'woo_archive_sale_section',
		'priority'		 => 15,
		'default'		 => array(
			'top'	 => '5px',
			'right'	 => '8px',
			'bottom' => '5px',
			'left'	 => '8px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce ul.products li.product .onsale',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_sale_padding_bottom_sep',
	'section'	 => 'woo_archive_sale_section',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_archive_product_sale_colors',
	'label'		 => esc_attr__( 'Colors', 'envo-extra' ),
	'section'	 => 'woo_archive_sale_section',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'	 => array(
		'link'		 => '',
		'background' => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.woocommerce ul.products li.product .onsale',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce ul.products li.product .onsale',
			'property'	 => 'background-color',
		),
	),
) );


// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_sale_pos_top_sep',
	'section'	 => 'woo_archive_sale_section',
	'priority'	 => 55,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Position from top', 'envo-extra' ),
	'section'	 => 'woo_archive_sale_section',
	'settings'	 => 'woo_archive_sale_pos_top_devices',
	'priority'	 => 55,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'slider',
		'settings'		 => 'woo_archive_sale_pos_top' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_archive_sale_section',
		'transport'		 => 'auto',
		'default'		 => 0,
		'priority'		 => 65,
		'choices'		 => array(
			'min'	 => '-200',
			'max'	 => '200',
			'step'	 => '1',
		),
		'output'		 => array(
			array(
				'element'					 => '.woocommerce ul.products li.product .onsale',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'property'					 => 'top',
				'units'						 => 'px',
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_sale_pos_left_sep_top',
	'section'	 => 'woo_archive_sale_section',
	'priority'	 => 70,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Position from right', 'envo-extra' ),
	'section'	 => 'woo_archive_sale_section',
	'settings'	 => 'woo_archive_sale_pos_left_devices',
	'priority'	 => 70,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'slider',
		'settings'		 => 'woo_archive_sale_pos_left' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_archive_sale_section',
		'transport'		 => 'auto',
		'priority'		 => 75,
		'choices'		 => array(
			'min'	 => '-200',
			'max'	 => '200',
			'step'	 => '1',
		),
		'default'		 => 0,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce ul.products li.product .onsale',
				'property'					 => 'right',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'units'						 => 'px',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_archive_sale_pos_left_sep_bottom',
	'section'	 => 'woo_archive_sale_section',
	'priority'	 => 80,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
