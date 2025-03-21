<?php

if ( !class_exists( 'Kirki' ) ) {
	return;
}

Kirki::add_section( 'top_bar', array(
	'title'		 => esc_attr__( 'Top Bar', 'envo-extra' ),
	'panel'		 => 'theme_header',
	'priority'	 => 5,
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

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Top bar font', 'envo-extra' ),
	'section'	 => 'top_bar',
	'settings'	 => 'topmenu_typography_devices',
	'priority'	 => 10,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'topmenu_typography' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'top_bar',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '12px',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'text-transform'	 => 'none',
			'color'				 => '',
			'line-height'		 => '1.5',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none'
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '.top-bar-section',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'top_bar_bg_color_sep_top',
	'section'	 => 'top_bar',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'color',
	'settings'	 => 'top_bar_bg_color',
	'label'		 => esc_attr__( 'Top bar background', 'envo-extra' ),
	'section'	 => 'top_bar',
	'default'	 => '',
	'transport'	 => 'auto',
	'priority'	 => 20,
	'output'	 => array(
		array(
			'element'	 => '.top-bar-section',
			'property'	 => 'background-color',
		),
	),
) );

Kirki::add_field( 'envo_extra', array(
	'type'		 => 'multicolor',
	'settings'	 => 'top_bar_links_color',
	'label'		 => esc_attr__( 'Top bar links', 'envo-extra' ),
	'section'	 => 'top_bar',
	'priority'	 => 20,
	'transport'	 => 'auto',
	'choices'	 => array(
		'link'	 => esc_attr__( 'Links', 'envo-extra' ),
		'hover'	 => esc_attr__( 'Hover', 'envo-extra' ),
	),
	'default'	 => array(
		'color'	 => '',
		'link'	 => '',
		'hover'	 => '',
	),
	'output'	 => array(
		array(
			'choice'	 => 'link',
			'element'	 => '.top-bar-section a',
			'property'	 => 'color',
		),
		array(
			'choice'	 => 'hover',
			'element'	 => '.top-bar-section a:hover',
			'property'	 => 'color',
		),
	),
) );
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'top_bar_spacing_sep_top',
	'section'	 => 'top_bar',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'responsive_devices',
	'label'		 => esc_attr__( 'Top bar spacing', 'envo-extra' ),
	'section'	 => 'top_bar',
	'settings'	 => 'top_bar_spacing_devices',
	'priority'	 => 20,
) );
// Responsive field.
foreach ( $devices as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'description'	 => $value[ 'description' ],
		'type'			 => 'dimensions',
		'settings'		 => 'top_bar_spacing' . $key,
		'section'		 => 'top_bar',
		'priority'		 => 25,
		'default'		 => array(
			'top'	 => '5px',
			'right'	 => '0px',
			'bottom' => '5px',
			'left'	 => '0px',
		),
		'transport'		 => 'auto',
		'output'		 => array(
			array(
				'property'					 => 'padding',
				'element'					 => '.top-bar-section',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'top_bar_spacing_sep_bottom',
	'section'	 => 'top_bar',
	'priority'	 => 30,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );
