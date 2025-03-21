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
	'default'	 => 'busnav',
	'choices'	 => array(
		'woonav' => plugin_dir_url( __FILE__ ) . (envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'assets/img/woo-header-woo.jpg' : 'assets/img/woo-header.jpg'),
		'busnav' => plugin_dir_url( __FILE__ ) . (envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'assets/img/business-header-woo.jpg' : 'assets/img/business-header.jpg'),
	),
) );


// Homepage blog options.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'display_heading_logo',
	'label'		 => esc_attr__( 'Homepage heading logo', 'envo-extra' ),
	'section'	 => 'title_tagline',
	'default'	 => 1,
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'display_heading_title',
	'label'		 => esc_attr__( 'Homepage heading title', 'envo-extra' ),
	'section'	 => 'title_tagline',
	'default'	 => 1,
	'priority'	 => 10,
) );
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'toggle',
	'settings'	 => 'display_heading_tagline',
	'label'		 => esc_attr__( 'Homepage heading tagline', 'envo-extra' ),
	'section'	 => 'title_tagline',
	'default'	 => 1,
	'priority'	 => 10,
) );

// End homepage blog options.

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
			'variant'			 => '800',
			'letter-spacing'	 => '0px',
			'font-size'			 => '',
			'line-height'		 => '',
			'text-transform'	 => 'uppercase',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none',
			'margin-top'		 => '0px',
			'margin-bottom'		 => '0px',
			envo_extra_col() => '',
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
