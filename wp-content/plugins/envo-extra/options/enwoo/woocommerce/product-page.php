<?php

if ( !class_exists( 'Kirki' ) ) {
	return;
}

Kirki::add_panel( 'woo_product_section', array(
	'title'		 => esc_attr__( 'Product Page', 'envo-extra' ),
	'panel'		 => 'woo_section_main',
	'priority'	 => 20,
) );

$devices = array(
	'desktop'	 => array(
		'media_query_key'	 => '',
		'media_query'		 => '',
		'description'		 => 'Desktop',
		'image'				 => '48',
	),
	'tablet'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 991px)',
		'description'		 => 'Tablet',
		'image'				 => '48',
	),
	'mobile'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 767px)',
		'description'		 => 'Mobile',
		'image'				 => '100',
	),
);

Kirki::add_section( 'woo_product_global_section', array(
	'title'		 => esc_attr__( 'Global options', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_gallery_zoom',
	'label'		 => esc_attr__( 'Gallery zoom', 'envo-extra' ),
	'section'	 => 'woo_product_global_section',
	'default'	 => 1,
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_gallery_lightbox',
	'label'		 => esc_attr__( 'Gallery lightbox', 'envo-extra' ),
	'section'	 => 'woo_product_global_section',
	'default'	 => 1,
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_gallery_slider',
	'label'		 => esc_attr__( 'Gallery slider', 'envo-extra' ),
	'section'	 => 'woo_product_global_section',
	'default'	 => 1,
	'priority'	 => 10,
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_remove_related',
	'label'		 => esc_attr__( 'Related products', 'envo-extra' ),
	'section'	 => 'woo_product_global_section',
	'default'	 => 1,
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'woo_product_breadcrumbs',
	'label'		 => esc_attr__( 'Breadcrumbs', 'envo-extra' ),
	'section'	 => 'woo_product_global_section',
	'default'	 => 1,
	'priority'	 => 20,
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_breadcrumb_font_separator_top',
	'section'	 => 'woo_product_global_section',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
			array(
				'setting'	 => 'woo_product_breadcrumbs',
				'operator'	 => '==',
				'value'		 => '1',
			),
		),
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Breadcrubs font', 'envo-extra' ),
	'section'	 => 'woo_product_global_section',
	'settings'	 => 'woo_product_breadcrumb_font_devices',
	'priority'	 => 20,
	'active_callback'	 => array(
			array(
				'setting'	 => 'woo_product_breadcrumbs',
				'operator'	 => '==',
				'value'		 => '1',
			),
		),
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'				 => 'typography',
		'settings'			 => 'woo_product_breadcrumb_font' . $key,
		'description'		 => $value[ 'description' ],
		'section'			 => 'woo_product_global_section',
		'transport'			 => 'auto',
		'choices'			 => array(
			'use_media_queries'	 => true,
			'fonts'				 => envo_extra_fonts(),
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
		'priority'			 => 25,
		'output'			 => array(
			array(
				'element'					 => '.single-product .woo-breadcrumbs',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'woo_product_breadcrumbs',
				'operator'	 => '==',
				'value'		 => '1',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_breadcrumb_font_separator_bottom',
	'section'	 => 'woo_product_global_section',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
			array(
				'setting'	 => 'woo_product_breadcrumbs',
				'operator'	 => '==',
				'value'		 => '1',
			),
		),
) );

Kirki::add_section( 'woo_product_title', array(
	'title'		 => esc_attr__( 'Title', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_title',
	'section'	 => 'woo_single_product_title_separator_top',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Font', 'envo-extra' ),
	'section'	 => 'woo_product_title',
	'settings'	 => 'woo_single_product_title_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_single_product_title' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_title',
		'transport'		 => 'auto',
		'choices'		 => array(
			'use_media_queries'	 => true,
			'fonts'				 => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '36px',
			'variant'			 => '700',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '10px',
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce div.product .product_title',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_title_border_separator_top',
	'section'	 => 'woo_product_title',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_title_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_title',
	'default'		 => 'none',
	'priority'		 => 30,
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
			'element'	 => '.woocommerce div.product .product_title',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_title_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_title',
	'priority'			 => 30,
	'default'			 => array(
		'border-top-width'		 => '0px',
		'border-bottom-width'	 => '0px',
	),
	'choices'			 => array(
		'labels' => array(
			'border-top-width'	 => esc_attr__( 'Top', 'textdomain' ),
			'border-right-width' => esc_attr__( 'Bottom', 'textdomain' ),
		),
	),
	'transport'			 => 'auto',
	'output'			 => array(
		array(
			'element' => '.woocommerce div.product .product_title',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_title_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_title_border_radius',
	'section'			 => 'woo_product_title',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 30,
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
			'element' => '.woocommerce div.product .product_title',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_title_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_title_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_title',
	'default'			 => '#f6f6f6',
	'transport'			 => 'auto',
	'priority'			 => 30,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce div.product .product_title',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_title_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_title_padding_separator_top',
	'section'	 => 'woo_product_title',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_title',
	'settings'	 => 'woo_product_title_padding_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_title_padding' . $key,
		'section'		 => 'woo_product_title',
		'priority'		 => 45,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce div.product .product_title',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_title_padding_separator_bottom',
	'section'	 => 'woo_product_title',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );



Kirki::add_section( 'woo_product_price', array(
	'title'		 => esc_attr__( 'Price', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_single_product_price_bottom',
	'section'	 => 'woo_product_price',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Price', 'envo-extra' ),
	'section'	 => 'woo_product_price',
	'settings'	 => 'woo_single_product_price_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_single_product_price' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_price',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '18px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '10px',
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce div.product p.price, .woocommerce div.product span.price',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_single_product_price_del_top',
	'section'	 => 'woo_product_price',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Del price', 'envo-extra' ),
	'section'	 => 'woo_product_price',
	'settings'	 => 'woo_single_product_price_del_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_single_product_price_del' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_price',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '16px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'line-through',
			'word-spacing'		 => '0px',
		),
		'priority'		 => 25,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce div.product p.price del, .woocommerce div.product span.price del',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_price_border_separator_top',
	'section'	 => 'woo_product_price',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_price_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_price',
	'default'		 => 'none',
	'priority'		 => 30,
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
			'element'	 => '.woocommerce div.product p.price, .woocommerce div.product span.price',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_price_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_price',
	'priority'			 => 30,
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
			'element' => '.woocommerce div.product p.price, .woocommerce div.product span.price'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_price_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_price_border_radius',
	'section'			 => 'woo_product_price',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 30,
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
			'element' => '.woocommerce div.product p.price, .woocommerce div.product span.price'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_price_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_price_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_price',
	'default'			 => '#f6f6f6',
	'transport'			 => 'auto',
	'priority'			 => 30,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce div.product p.price, .woocommerce div.product span.price',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_price_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_price_padding_separator_top',
	'section'	 => 'woo_product_price',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_price',
	'settings'	 => 'woo_product_price_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_price_padding' . $key,
		'section'		 => 'woo_product_price',
		'priority'		 => 45,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce div.product p.price, .woocommerce div.product span.price',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_price_padding_separator_bottom',
	'section'	 => 'woo_product_price',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );


Kirki::add_section( 'woo_product_sum', array(
	'title'		 => esc_attr__( 'Summary', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Font', 'envo-extra' ),
	'section'	 => 'woo_product_sum',
	'settings'	 => 'woo_single_product_sum_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_single_product_sum' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_sum',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '0px',
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce-product-details__short-description',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_single_product_sum_border_separator_top',
	'section'	 => 'woo_product_sum',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_sum_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_sum',
	'default'		 => 'none',
	'priority'		 => 30,
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
			'element'	 => '.woocommerce-product-details__short-description',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_sum_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_sum',
	'priority'			 => 30,
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
			'element' => '.woocommerce-product-details__short-description'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_sum_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_sum_border_radius',
	'section'			 => 'woo_product_sum',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 30,
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
			'element' => '.woocommerce-product-details__short-description'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_sum_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_sum_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_sum',
	'default'			 => '#f6f6f6',
	'transport'			 => 'auto',
	'priority'			 => 30,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce-product-details__short-description',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_sum_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_sum_padding_sep_top',
	'section'	 => 'woo_product_sum',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_sum',
	'settings'	 => 'woo_product_sum_padding_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_sum_padding' . $key,
		'section'		 => 'woo_product_sum',
		'priority'		 => 45,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce-product-details__short-description',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_sum_padding_sep_bottom',
	'section'	 => 'woo_product_sum',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );


Kirki::add_section( 'woo_product_button', array(
	'title'		 => esc_attr__( 'Button and Quantity', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Button font', 'envo-extra' ),
	'section'	 => 'woo_product_button',
	'settings'	 => 'woo_single_product_button_font_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_single_product_button_font' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_button',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '0px',
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_single_product_button_font_separator_bottom',
	'section'	 => 'woo_product_button',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_button_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_button',
	'default'		 => 'solid',
	'priority'		 => 30,
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
			'element'	 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_button_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_button',
	'priority'			 => 30,
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
			'element' => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_button_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_button_border_radius',
	'section'			 => 'woo_product_button',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 30,
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
			'element' => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_button_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_button_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_button',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 30,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_button_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_button_padding_separator_top',
	'section'	 => 'woo_product_button',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_button',
	'settings'	 => 'woo_product_button_padding_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_button_padding' . $key,
		'section'		 => 'woo_product_button',
		'priority'		 => 45,
		'default'		 => array(
			'top'	 => '6px',
			'right'	 => '20px',
			'bottom' => '6px',
			'left'	 => '20px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_button_padding_separator_bottom',
	'section'	 => 'woo_product_button',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_single_product_buttons',
	'label'		 => esc_attr__( 'Button colors', 'envo-extra' ),
	'section'	 => 'woo_product_button',
	'priority'	 => 50,
	'transport'	 => 'auto',
	'choices'	 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'border'	 => esc_attr__( 'Border', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'	 => array(
		'link'		 => '',
		'border'	 => '',
		'background' => 'transparent',
	),
	'output'	 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'border',
			'element'	 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
			'property'	 => 'border-color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce .summary #respond input#submit, .woocommerce .summary a.button, .woocommerce .summary button.button, .woocommerce .summary input.button, .woocommerce .summary #respond input#submit.alt, .woocommerce .summary a.button.alt, .woocommerce .summary button.button.alt, .woocommerce .summary input.button.alt',
			'property'	 => 'background-color',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_single_product_buttons_hover',
	'label'		 => esc_attr__( 'Button colors on hover', 'envo-extra' ),
	'section'	 => 'woo_product_button',
	'priority'	 => 50,
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
			'element'	 => '.woocommerce .summary #respond input#submit:hover, .woocommerce .summary a.button:hover, .woocommerce .summary button.button:hover, .woocommerce .summary input.button:hover, .woocommerce .summary #respond input#submit.alt:hover, .woocommerce .summary a.button.alt:hover, .woocommerce .summary button.button.alt:hover, .woocommerce .summary input.button.alt:hover',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'border',
			'element'	 => '.woocommerce .summary #respond input#submit:hover, .woocommerce .summary a.button:hover, .woocommerce .summary button.button:hover, .woocommerce .summary input.button:hover, .woocommerce .summary #respond input#submit.alt:hover, .woocommerce .summary a.button.alt:hover, .woocommerce .summary button.button.alt:hover, .woocommerce .summary input.button.alt:hover',
			'property'	 => 'border-color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce .summary #respond input#submit:hover, .woocommerce .summary a.button:hover, .woocommerce .summary button.button:hover, .woocommerce .summary input.button:hover, .woocommerce .summary #respond input#submit.alt:hover, .woocommerce .summary a.button.alt:hover, .woocommerce .summary button.button.alt:hover, .woocommerce .summary input.button.alt:hover',
			'property'	 => 'background-color',
		),
	),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_button_quantity_height_separator_top',
	'section'	 => 'woo_product_button',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Quantity height', 'envo-extra' ),
	'section'	 => 'woo_product_button',
	'settings'	 => 'woo_product_button_quantity_height_devices',
	'priority'	 => 50,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'slider',
		'settings'		 => 'woo_product_quantity_height' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_button',
		'default'		 => 36,
		'transport'		 => 'auto',
		'priority'		 => 55,
		'choices'		 => array(
			'min'	 => '30',
			'max'	 => '80',
			'step'	 => '1',
		),
		'output'		 => array(
			array(
				'element'					 => '.woocommerce .quantity .qty, .single-product div.product form.cart .plus, .single-product div.product form.cart .minus',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'property'					 => 'height',
				'units'						 => 'px',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_button_quantity_height_separator_bottom',
	'section'	 => 'woo_product_button',
	'priority'	 => 60,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );


Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'woo_hide_plus_minus',
	'label'		 => esc_attr__( 'Quantity plus/minus', 'envo-extra' ),
	'section'	 => 'woo_product_button',
	'default'	 => 'block',
	'priority'	 => 60,
	'transport'	 => 'auto',
	'choices'	 => array(
		'block'	 => esc_attr__( 'Visible', 'envo-extra' ),
		'none'	 => esc_attr__( 'Hidden', 'envo-extra' ),
	),
	'output'	 => array(
		array(
			'element'	 => '.single-product div.product form.cart .plus, .single-product div.product form.cart .minus',
			'property'	 => 'display',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'multicolor',
	'settings'			 => 'woo_single_product_plus_minus',
	'label'				 => esc_attr__( 'Plus/Minus buttons', 'envo-extra' ),
	'section'			 => 'woo_product_button',
	'priority'			 => 60,
	'transport'			 => 'auto',
	'choices'			 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'border'	 => esc_attr__( 'Border', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'			 => array(
		'link'		 => '',
		'border'	 => '',
		'background' => 'transparent',
	),
	'output'			 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.single-product div.product form.cart .plus, .single-product div.product form.cart .minus',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'border',
			'element'	 => '.single-product div.product form.cart .plus, .single-product div.product form.cart .minus',
			'property'	 => 'border-color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.single-product div.product form.cart .plus, .single-product div.product form.cart .minus',
			'property'	 => 'background-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_hide_plus_minus',
			'operator'	 => '==',
			'value'		 => 'block',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'multicolor',
	'settings'			 => 'woo_product_plus_minus_hover',
	'label'				 => esc_attr__( 'Plus/Minus buttons hover', 'envo-extra' ),
	'section'			 => 'woo_product_button',
	'priority'			 => 60,
	'transport'			 => 'auto',
	'choices'			 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'border'	 => esc_attr__( 'Border', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
	),
	'default'			 => array(
		'link'		 => '',
		'border'	 => '',
		'background' => '',
	),
	'output'			 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.single-product div.product form.cart .plus:hover, .single-product div.product form.cart .minus:hover',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'border',
			'element'	 => '.single-product div.product form.cart .plus:hover, .single-product div.product form.cart .minus:hover',
			'property'	 => 'border-color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.single-product div.product form.cart .plus:hover, .single-product div.product form.cart .minus:hover',
			'property'	 => 'background-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_hide_plus_minus',
			'operator'	 => '==',
			'value'		 => 'block',
		),
	),
) );



Kirki::add_section( 'woo_product_image', array(
	'title'		 => esc_attr__( 'Image', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Image area width (in %)', 'envo-extra' ),
	'section'	 => 'woo_product_image',
	'settings'	 => 'woo_single_image_width_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'slider',
		'settings'	 => 'woo_single_image_width' . $key,
		'description'	 => $value[ 'description' ],
		'section'	 => 'woo_product_image',
		'default'	 => $value[ 'image' ],
		'priority'	 => 15,
		'choices'	 => array(
			'min'	 => '0',
			'max'	 => '100',
			'step'	 => '1',
		),
		'output'	 => array(
			array(
				'element'					 => '.woocommerce-page #content div.product div.images, .woocommerce-page div.product div.images',
				'property'					 => 'width',
				'units'						 => '%',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_image_border_top',
	'section'	 => 'woo_product_image',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_image_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_image',
	'default'		 => 'none',
	'priority'		 => 30,
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
			'element'	 => '.woocommerce div.product div.images .woocommerce-product-gallery__wrapper img',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_image_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_image',
	'priority'			 => 30,
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
			'element' => '.woocommerce div.product div.images .woocommerce-product-gallery__wrapper img'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_image_border_radius',
	'section'			 => 'woo_product_image',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 30,
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
			'element' => '.woocommerce div.product div.images .woocommerce-product-gallery__wrapper img'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_image_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_image',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 30,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce div.product div.images .woocommerce-product-gallery__wrapper img',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_image_padding_separator_top',
	'section'	 => 'woo_product_image',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_image',
	'settings'	 => 'woo_product_image_padding_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_image_padding' . $key,
		'section'		 => 'woo_product_image',
		'priority'		 => 45,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce div.product div.images .woocommerce-product-gallery__wrapper img',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_gallery_image_border_separator_top',
	'section'	 => 'woo_product_image',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_gallery_image_border_style',
	'label'			 => esc_html__( 'Gallery images border', 'envo-extra' ),
	'section'		 => 'woo_product_image',
	'default'		 => 'none',
	'priority'		 => 50,
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
			'element'	 => '.woocommerce div.product div.images .flex-control-thumbs img',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_gallery_image_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_image',
	'priority'			 => 50,
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
			'element' => '.woocommerce div.product div.images .flex-control-thumbs img'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_gallery_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_gallery_image_border_radius',
	'section'			 => 'woo_product_image',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 50,
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
			'element' => '.woocommerce div.product div.images .flex-control-thumbs img'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_gallery_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_gallery_image_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_image',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 50,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce div.product div.images .flex-control-thumbs img',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_gallery_image_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_gallery_image_padding_separator_top',
	'section'	 => 'woo_product_image',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Gallery images padding', 'envo-extra' ),
	'section'	 => 'woo_product_image',
	'settings'	 => 'woo_product_gallery_image_padding_devices',
	'priority'	 => 50,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_gallery_image_padding' . $key,
		'section'		 => 'woo_product_image',
		'priority'		 => 55,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce div.product div.images .flex-control-thumbs img',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_gallery_image_padding_separator_bottom',
	'section'	 => 'woo_product_image',
	'priority'	 => 60,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );


Kirki::add_section( 'woo_product_tabs', array(
	'title'		 => esc_attr__( 'Tabs', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_tabs_font_separator_top',
	'section'	 => 'woo_product_tabs',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Tabs font', 'envo-extra' ),
	'section'	 => 'woo_product_tabs',
	'settings'	 => 'woo_product_tabs_font_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_product_tabs_font' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_tabs',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce div.product .woocommerce-tabs ul.tabs.wc-tabs li a',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_tabs_separator_top',
	'section'	 => 'woo_product_tabs',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Tabs content font', 'envo-extra' ),
	'section'	 => 'woo_product_tabs',
	'settings'	 => 'woo_product_tabs_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_product_tabs_content_font' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_tabs',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '18px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '0px',
		),
		'priority'		 => 25,
		'output'		 => array(
			array(
				'element'					 => '.woocommerce div.product .woocommerce-tabs .panel',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_tabs_separator_bottom',
	'section'	 => 'woo_product_tabs',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'woo_single_tab_position',
	'label'		 => __( 'Tab titles align', 'envo-extra' ),
	'section'	 => 'woo_product_tabs',
	'default'	 => 'left',
	'transport'	 => 'auto',
	'priority'	 => 30,
	'choices'	 => array(
		'left'	 => '<i class="dashicons dashicons-editor-alignleft"></i>',
		'center' => '<i class="dashicons dashicons-editor-aligncenter"></i>',
		'right'	 => '<i class="dashicons dashicons-editor-alignright"></i>',
	),
	'output'	 => array(
		array(
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs',
			'property'	 => 'text-align',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_single_product_active_tabs',
	'label'		 => esc_attr__( 'Active tab', 'envo-extra' ),
	'section'	 => 'woo_product_tabs',
	'priority'	 => 30,
	'transport'	 => 'auto',
	'choices'	 => array(
		'link'		 => esc_attr__( 'Color', 'envo-extra' ),
		'background' => esc_attr__( 'Background', 'envo-extra' ),
		'line'		 => esc_attr__( 'Line', 'envo-extra' ),
	),
	'default'	 => array(
		'link'		 => '',
		'background' => '',
		'line'		 => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs.wc-tabs li.active a',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs.wc-tabs li.active a',
			'property'	 => 'background-color',
		),
		array(
			'choice'	 => 'line',
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs li.active',
			'property'	 => 'border-bottom-color',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_single_product_inactive_tabs',
	'label'		 => esc_attr__( 'Inactive tab', 'envo-extra' ),
	'section'	 => 'woo_product_tabs',
	'priority'	 => 30,
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
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs.wc-tabs li a',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs.wc-tabs li a',
			'property'	 => 'background-color',
		),
	),
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_tabs_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_tabs',
	'default'		 => 'solid',
	'priority'		 => 50,
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
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_tabs_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_tabs',
	'priority'			 => 50,
	'default'			 => array(
		'border-top-width'		 => '1px',
		'border-right-width'	 => '0px',
		'border-bottom-width'	 => '1px',
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
			'element' => '.woocommerce div.product .woocommerce-tabs ul.tabs'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_tabs_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_tabs_border_radius',
	'section'			 => 'woo_product_tabs',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 50,
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
			'element' => '.woocommerce div.product .woocommerce-tabs ul.tabs'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_tabs_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_tabs_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_tabs',
	'default'			 => '#e8e8e8',
	'transport'			 => 'auto',
	'priority'			 => 50,
	'output'			 => array(
		array(
			'element'	 => '.woocommerce div.product .woocommerce-tabs ul.tabs',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_tabs_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_tabs_content_padding_separator_top',
	'section'	 => 'woo_product_tabs',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Content padding', 'envo-extra' ),
	'section'	 => 'woo_product_tabs',
	'settings'	 => 'woo_product_tabs_content_padding_devices',
	'priority'	 => 50,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {

	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'dimensions',
		'settings'	 => 'woo_product_tabs_content_padding' . $key,
		'description' => $value['description'],
		'section'	 => 'woo_product_tabs',
		'priority'	 => 55,
		'default'	 => array(
			'top'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
			'right'	 => '0px',
		),
		'transport'	 => 'auto',
		'output'	 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.woocommerce div.product .woocommerce-tabs .panel',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_tabs_content_padding_separator_bottom',
	'section'	 => 'woo_product_tabs',
	'priority'	 => 60,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_section( 'woo_product_meta', array(
	'title'		 => esc_attr__( 'Meta', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'woo_hide_sku',
	'label'		 => esc_attr__( 'SKU', 'envo-extra' ),
	'section'	 => 'woo_product_meta',
	'default'	 => 'block',
	'priority'	 => 10,
	'transport'	 => 'auto',
	'choices'	 => array(
		'block'	 => esc_attr__( 'Visible', 'envo-extra' ),
		'none'	 => esc_attr__( 'Hidden', 'envo-extra' ),
	),
	'output'	 => array(
		array(
			'element'	 => '.woocommerce div.product .product_meta>span.sku_wrapper',
			'property'	 => 'display',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'woo_hide_cats',
	'label'		 => esc_attr__( 'Categories', 'envo-extra' ),
	'section'	 => 'woo_product_meta',
	'default'	 => 'block',
	'priority'	 => 10,
	'transport'	 => 'auto',
	'choices'	 => array(
		'block'	 => esc_attr__( 'Visible', 'envo-extra' ),
		'none'	 => esc_attr__( 'Hidden', 'envo-extra' ),
	),
	'output'	 => array(
		array(
			'element'	 => '.woocommerce div.product .product_meta>span.posted_in',
			'property'	 => 'display',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'woo_hide_tags',
	'label'		 => esc_attr__( 'Tags', 'envo-extra' ),
	'section'	 => 'woo_product_meta',
	'default'	 => 'block',
	'priority'	 => 10,
	'transport'	 => 'auto',
	'choices'	 => array(
		'block'	 => esc_attr__( 'Visible', 'envo-extra' ),
		'none'	 => esc_attr__( 'Hidden', 'envo-extra' ),
	),
	'output'	 => array(
		array(
			'element'	 => '.woocommerce div.product .product_meta>span.tagged_as',
			'property'	 => 'display',
		),
	),
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_meta_separator_top',
	'section'	 => 'woo_product_meta',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Font', 'envo-extra' ),
	'section'	 => 'woo_product_meta',
	'settings'	 => 'woo_product_meta_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'woo_product_meta_font' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_meta',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '300',
			'line-height'		 => '1.6',
			'letter-spacing'	 => '0px',
			'color'				 => '',
			'text-transform'	 => 'none',
			'text-decoration'	 => 'none',
			'word-spacing'		 => '0px',
			'text-align'		 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '0px',
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.product_meta',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_meta_separator_bottom',
	'section'	 => 'woo_product_meta',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_meta_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_meta',
	'default'		 => 'solid',
	'priority'		 => 20,
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
			'element'	 => '.product_meta',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_meta_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_meta',
	'priority'			 => 20,
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
			'element' => '.product_meta'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_meta_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_meta_border_radius',
	'section'			 => 'woo_product_meta',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 20,
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
			'element' => '.product_meta'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_meta_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_meta_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_meta',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 20,
	'output'			 => array(
		array(
			'element'	 => '.product_meta',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_meta_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_meta_padding_sep_top',
	'section'	 => 'woo_product_meta',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_meta',
	'settings'	 => 'woo_product_meta_padding_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_meta_padding' . $key,
		'section'		 => 'woo_product_meta',
		'priority'		 => 45,
		'default'		 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.product_meta',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_meta_padding_sep_bottom',
	'section'	 => 'woo_product_meta',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );


Kirki::add_section( 'woo_product_sale', array(
	'title'		 => esc_attr__( 'Sale', 'envo-extra' ),
	'panel'		 => 'woo_product_section',
	'priority'	 => 10,
) );

// Border start.
Kirki::add_field( 'envo_extra', array(
	'type'			 => 'select',
	'settings'		 => 'woo_product_sale_border_style',
	'label'			 => esc_html__( 'Border', 'envo-extra' ),
	'section'		 => 'woo_product_sale',
	'default'		 => 'none',
	'priority'		 => 20,
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
			'element'	 => '.single.woocommerce span.onsale',
			'property'	 => 'border-style',
		),
	)
)
);
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_sale_border_width',
	'label'				 => esc_attr__( 'Border width', 'envo-extra' ),
	'section'			 => 'woo_product_sale',
	'priority'			 => 20,
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
			'element' => '.single.woocommerce span.onsale'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_sale_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );


Kirki::add_field( 'envo_extra', array(
	'type'				 => 'dimensions',
	'settings'			 => 'woo_product_sale_border_radius',
	'section'			 => 'woo_product_sale',
	'label'				 => esc_attr__( 'Border radius', 'envo-extra' ),
	'priority'			 => 20,
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
			'element' => '.single.woocommerce span.onsale'
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_sale_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'color',
	'settings'			 => 'woo_product_sale_border_color',
	'label'				 => esc_attr__( 'Border color', 'envo-extra' ),
	'section'			 => 'woo_product_sale',
	'default'			 => '',
	'transport'			 => 'auto',
	'priority'			 => 20,
	'output'			 => array(
		array(
			'element'	 => '.single.woocommerce span.onsale',
			'property'	 => 'border-color',
		),
	),
	'active_callback'	 => array(
		array(
			'setting'	 => 'woo_product_sale_border_style',
			'operator'	 => '!=',
			'value'		 => 'none',
		),
	),
) );
// Border end.
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_sale_padding_sep_top',
	'section'	 => 'woo_product_sale',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Padding', 'envo-extra' ),
	'section'	 => 'woo_product_sale',
	'settings'	 => 'woo_product_sale_padding_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'woo_product_sale_padding' . $key,
		'section'		 => 'woo_product_sale',
		'priority'		 => 45,
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
				'element'					 => '.single.woocommerce span.onsale',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_sale_padding_sep_bottom',
	'section'	 => 'woo_product_sale',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'woo_product_sale_colors',
	'label'		 => esc_attr__( 'Colors', 'envo-extra' ),
	'section'	 => 'woo_product_sale',
	'priority'	 => 50,
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
			'element'	 => '.single.woocommerce span.onsale',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'background',
			'element'	 => '.single.woocommerce span.onsale',
			'property'	 => 'background-color',
		),
	),
) );


// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_sale_pos_top_sep',
	'section'	 => 'woo_product_sale',
	'priority'	 => 55,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Position from top', 'envo-extra' ),
	'section'	 => 'woo_product_sale',
	'settings'	 => 'woo_product_sale_pos_top_devices',
	'priority'	 => 55,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'slider',
		'settings'		 => 'woo_product_sale_pos_top' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'woo_product_sale',
		'transport'		 => 'auto',
		'default'		 => -5,
		'priority'		 => 65,
		'choices'		 => array(
			'min'	 => '-200',
			'max'	 => '200',
			'step'	 => '1',
		),
		'output'		 => array(
			array(
				'element'					 => '.single.woocommerce span.onsale',
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
	'settings'	 => 'woo_product_sale_pos_left_sep',
	'section'	 => 'woo_product_sale',
	'priority'	 => 70,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Position from left', 'envo-extra' ),
	'section'	 => 'woo_product_sale',
	'settings'	 => 'woo_product_sale_pos_left_devices',
	'priority'	 => 70,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'		 => 'slider',
		'settings'	 => 'woo_product_sale_pos_left' . $key,
		'description'	 => $value[ 'description' ],
		'section'	 => 'woo_product_sale',
		'transport'	 => 'auto',
		'priority'	 => 75,
		'choices'	 => array(
			'min'	 => '-200',
			'max'	 => '200',
			'step'	 => '1',
		),
		'default'	 => -5,
		'output'	 => array(
			array(
				'element'					 => '.single.woocommerce span.onsale',
				'property'					 => 'left',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'units'						 => 'px',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'woo_product_sale_pos_left_sep_bottom',
	'section'	 => 'woo_product_sale',
	'priority'	 => 80,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
