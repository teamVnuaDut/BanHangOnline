<?php

if ( !class_exists( 'Kirki' ) ) {
	return;
}
Kirki::add_panel( 'theme_header', array(
	'title'		 => esc_attr__( 'Header', 'envo-extra' ),
	'panel'		 => 'envo_theme_panel',
	'priority'	 => 10,
) );
Kirki::add_section( 'header_title_tagline', array(
	'title'		 => esc_attr__( 'Header', 'envo-extra' ),
	'panel'		 => 'theme_header',
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

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio_image',
	'settings'	 => 'header_layout',
	'label'		 => esc_html__( 'Header layout', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'priority'	 => 5,
	'default'	 => (envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'woonav' : 'busnav'),
	'choices'	 => array(
		'woonav' => plugin_dir_url( __FILE__ ) . (envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'assets/img/woo-header-woo.jpg' : 'assets/img/woo-header.jpg'),
		'busnav' => plugin_dir_url( __FILE__ ) . (envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'assets/img/business-header-woo.jpg' : 'assets/img/business-header.jpg'),
	),
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'color',
	'settings'	 => 'header_bg_color',
	'label'		 => esc_attr__( 'Header background', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'default'	 => '',
	'transport'	 => 'auto',
	'priority'	 => 10,
	'output'	 => array(
		array(
			'element'	 => '.site-header',
			'property'	 => 'background-color',
		),
	),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'header_spacing_separator_top',
	'section'	 => 'header_title_tagline',
	'priority'	 => 10,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Header spacing', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'settings'	 => 'header_spacing_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'header_spacing' . $key,
		'section'		 => 'header_title_tagline',
		'priority'		 => 15,
		'default'		 => array(
			'top'	 => '15px',
			'bottom' => '15px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.site-header',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'logo_spacing_separator_top',
	'section'	 => 'header_title_tagline',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Logo spacing', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'settings'	 => 'logo_spacing_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'logo_spacing' . $key,
		'section'		 => 'header_title_tagline',
		'priority'		 => 25,
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
				'element'					 => '.site-branding-logo',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'title_spacing_separator_top',
	'section'	 => 'header_title_tagline',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Site Title and Tagline spacing', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'settings'	 => 'title_spacing_devices',
	'priority'	 => 30,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'title_spacing' . $key,
		'section'		 => 'header_title_tagline',
		'priority'		 => 35,
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
				'element'					 => '.site-branding-text',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'title_logo_width_sep',
	'section'	 => 'header_title_tagline',
	'priority'	 => 40,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Title/Logo min. width', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'settings'	 => 'title_logo_width_devices',
	'priority'	 => 40,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'slider',
		'settings'		 => 'title_logo_width' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'header_title_tagline',
		'transport'		 => 'auto',
		'default'		 => 10,
		'priority'		 => 45,
		'choices'		 => array(
			'min'	 => '0',
			'max'	 => '100',
			'step'	 => '1',
		),
		'output'		 => array(
			array(
				'element'					 => '.site-heading',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
				'property'					 => 'min-width',
				'units'						 => '%',
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'header_typography_site_title_separator_top',
	'section'	 => 'header_title_tagline',
	'priority'	 => 50,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Site title font', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'settings'	 => 'header_typography_site_title_devices',
	'priority'	 => 50,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'header_typography_site_title' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'header_title_tagline',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'color'				 => '',
			'variant'			 => '700',
			'letter-spacing'	 => '0px',
			'font-size'			 => '',
			'line-height'		 => '',
			'text-transform'	 => 'none',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
		),
		'priority'		 => 55,
		'output'		 => array(
			array(
				'element'					 => '.site-branding-text h1.site-title a:hover, .site-branding-text .site-title a:hover, .site-branding-text h1.site-title, .site-branding-text .site-title, .site-branding-text h1.site-title a, .site-branding-text .site-title a',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'header_typography_site_desc_separator_top',
	'section'	 => 'header_title_tagline',
	'priority'	 => 60,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Site description font', 'envo-extra' ),
	'section'	 => 'header_title_tagline',
	'settings'	 => 'header_typography_site_desc_devices',
	'priority'	 => 60,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'header_typography_site_desc' . $key,
		'transport'		 => 'auto',
		'description'	 => $value[ 'description' ],
		'section'		 => 'header_title_tagline',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'color'				 => '',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'font-size'			 => '',
			'line-height'		 => '',
			'text-transform'	 => 'none',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
		),
		'priority'		 => 65,
		'output'		 => array(
			array(
				'element'					 => 'p.site-description',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'header_typography_widgets_separator_top',
	'section'			 => 'header_title_tagline',
	'priority'			 => 70,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'responsive_devices',
	'label'				 => esc_attr__( 'Header widget area font', 'envo-extra' ),
	'section'			 => 'header_title_tagline',
	'settings'			 => 'header_typography_widgets_devices',
	'priority'			 => 70,
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'				 => 'typography',
		'settings'			 => 'header_typography_widgets' . $key,
		'transport'			 => 'auto',
		'description'		 => $value[ 'description' ],
		'section'			 => 'header_title_tagline',
		'choices'			 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'			 => array(
			'font-family'		 => '',
			'color'				 => '',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'font-size'			 => '',
			'line-height'		 => '',
			'text-transform'	 => 'none',
			'margin-top'		 => '5px',
			'margin-bottom'		 => '5px',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none'
		),
		'priority'			 => 75,
		'output'			 => array(
			array(
				'element'					 => '.site-heading-sidebar',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'header_layout',
				'operator'	 => '==',
				'value'		 => 'woonav',
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'header_typography_widgets_separator_bottom',
	'section'			 => 'header_title_tagline',
	'priority'			 => 80,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'responsive_devices',
	'label'				 => esc_attr__( 'Widget/Search area spacing', 'envo-extra' ),
	'section'			 => 'header_title_tagline',
	'settings'			 => 'header_search_widget_spacing_devices',
	'priority'			 => 80,
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'		 => $value[ 'description' ],
		'type'				 => 'dimensions',
		'settings'			 => 'header_search_widget_spacing' . $key,
		'section'			 => 'header_title_tagline',
		'priority'			 => 85,
		'default'			 => array(
			'top'	 => '0px',
			'right'	 => '0px',
			'bottom' => '0px',
			'left'	 => '0px',
		),
		'transport'			 => 'auto',
		'output'			 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.header-search-widget',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'header_layout',
				'operator'	 => '==',
				'value'		 => 'woonav',
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'				 => 'custom',
	'settings'			 => 'header_search_widget_spacing_separator_bottom',
	'section'			 => 'header_title_tagline',
	'priority'			 => 90,
	'default'			 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
	'active_callback'	 => array(
		array(
			'setting'	 => 'header_layout',
			'operator'	 => '==',
			'value'		 => 'woonav',
		),
	),
) );
