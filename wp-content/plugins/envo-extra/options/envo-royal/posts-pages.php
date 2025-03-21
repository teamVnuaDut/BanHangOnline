<?php

if (!class_exists('Kirki')) {
    return;
}

Kirki::add_section('blog_posts', array(
    'title' => esc_attr__('Blog posts archive', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 25,
));

Kirki::add_section('posts_pages', array(
    'title' => esc_attr__('Single post and page', 'envo-extra'),
    'panel' => 'envo_theme_panel',
    'priority' => 30,
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
 * Single post and page
 */
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'posts_pages_header_image',
	'label'		 => esc_attr__( 'Heading featured image', 'envo-extra' ),
	'section'	 => 'posts_pages',
	'transport' => 'auto',
	'choices' => array(
        'block' => esc_html__('Visible', 'envo-extra'),
        'none' => esc_html__('Hidden', 'envo-extra'),
    ),
	'default'	 => 'block',
	'priority'	 => 5,
	'output' => array(
            array(
                'element' => '.singular-heading .single-image, .singular-heading .bg-single-image',
                'property' => 'display',
            ),
        ),
) );

// Separator.  
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'custom',
	'settings'	 => 'posts_pages_heading_image_height_bottom',
	'section'	 => 'posts_pages',
	'priority'	 => 20,
	'default'	 => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
) );

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Article title font', 'envo-extra'),
    'section' => 'posts_pages',
    'settings' => 'posts_pages_title_font_devices',
    'priority' => 21,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'posts_pages_title_font' . $key,
        'description' => $value['description'],
        'section' => 'posts_pages',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '',
            'letter-spacing' => '0px',
            'font-size' => '',
            'text-transform' => 'none',
            'text-align' => 'none',
			envo_extra_col() => '',
        ),
        'priority' => 25,
        'output' => array(
            array(
                'element' => 'h1.single-title',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'posts_pages_content_font_separator_top',
    'section' => 'posts_pages',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Content font', 'envo-extra'),
    'section' => 'posts_pages',
    'settings' => 'posts_pages_content_font_devices',
    'priority' => 30,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'posts_pages_content_font' . $key,
        'description' => $value['description'],
        'section' => 'posts_pages',
        'transport' => 'auto',
        'choices' => array(
            'use_media_queries' => true,
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '',
            'letter-spacing' => '0px',
            'font-size' => '',
            'text-transform' => 'none',
            'line-height' => '',
            'word-spacing' => '0px',
            'text-align' => 'none',
			envo_extra_col() => '',
        ),
        'priority' => 31,
        'output' => array(
            array(
                'element' => '.single article .post, .page article .page, .single article .posted-date, .single .cat-links span, .single .tags-links span',
                $value['media_query_key'] => $value['media_query'],
            ),
            array(
                'choice' => 'color',
                'property' => 'color',
                'element' => '.single span.comments-meta i',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'single_border_top',
    'section' => 'posts_pages',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Border start.
Kirki::add_field('envo_extra', array(
    'type' => 'select',
    'settings' => 'single_border_style',
    'label' => esc_html__('Article border', 'envo-extra'),
    'section' => 'posts_pages',
    'default' => 'none',
    'priority' => 40,
    'placeholder' => esc_html__('Choose an option', 'envo-extra'),
    'choices' => array(
        'none' => esc_html__('None', 'envo-extra'),
        'solid' => esc_html__('Solid', 'envo-extra'),
        'double' => esc_html__('Double', 'envo-extra'),
        'dotted' => esc_html__('Dotted', 'envo-extra'),
        'dashed' => esc_html__('Dashed', 'envo-extra'),
        'groove' => esc_html__('Groove', 'envo-extra'),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '.single-post-content',
            'property' => 'border-style',
        ),
    )
        )
);
Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'single_border_width',
    'label' => esc_attr__('Article Border Width', 'envo-extra'),
    'section' => 'posts_pages',
    'priority' => 40,
    'default' => array(
        'border-top-width' => '0px',
        'border-right-width' => '0px',
        'border-bottom-width' => '0px',
        'border-left-width' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-width' => esc_attr__('Top', 'textdomain'),
            'border-right-width' => esc_attr__('Bottom', 'textdomain'),
            'border-bottom-width' => esc_attr__('Left', 'textdomain'),
            'border-left-width' => esc_attr__('Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '.single-post-content',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'single_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'single_border_radius',
    'section' => 'posts_pages',
    'label' => esc_attr__('Article Border Radius', 'envo-extra'),
    'priority' => 40,
    'default' => array(
        'border-top-left-radius' => '0px',
        'border-top-right-radius' => '0px',
        'border-bottom-left-radius' => '0px',
        'border-bottom-right-radius' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-left-radius' => esc_attr__('Top Left', 'textdomain'),
            'border-top-right-radius' => esc_attr__('Top Right', 'textdomain'),
            'border-bottom-left-radius' => esc_attr__('Bottom Left', 'textdomain'),
            'border-bottom-right-radius' => esc_attr__('Bottom Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '.single-post-content',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'single_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'single_border_color',
    'label' => esc_attr__('Article border color', 'envo-extra'),
    'section' => 'posts_pages',
    'default' => '',
    'transport' => 'auto',
    'priority' => 40,
    'output' => array(
        array(
            'element' => '.single-post-content',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'single_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
// Border end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'single_border_style_separator_bottom',
    'section' => 'posts_pages',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

Kirki::add_field('envo_extra', array(
    'type' => 'text',
    'settings' => 'single_box_shadow_code',
    'label' => esc_html__('Article box shadow', 'envo-extra'),
    'description' => esc_attr__('e.g. 5px 5px 15px 5px #000000', 'envo-extra'),
    'section' => 'posts_pages',
    'priority' => 40,
    'output' => array(
        array(
            'element' => '.single-post-content',
            'property' => 'box-shadow',
        ),
    ),
));
// Box shadow end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'single_spacing_separator_top',
    'section' => 'posts_pages',
    'priority' => 40,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Article spacing', 'envo-extra'),
    'section' => 'posts_pages',
    'settings' => 'Single_spacing_devices',
    'priority' => 40,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'dimensions',
        'settings' => 'single_spacing' . $key,
        'section' => 'posts_pages',
        'priority' => 45,
        'default' => array(
            'top' => '0px',
            'right' => '0px',
            'bottom' => '0px',
            'left' => '0px',
        ),
        'transport' => 'auto',
        'output' => array(
            array(
                'property' => 'padding',
                'element' => '.single-post-content',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'single_spacing_separator_bottom',
    'section' => 'posts_pages',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));


/**
 * Blog posts archive
 */
Kirki::add_field( 'envo_extra', array(
	'type'		 => 'radio-buttonset',
	'settings'	 => 'blog_posts_header_image',
	'label'		 => esc_attr__( 'Header image', 'envo-extra' ),
	'section'	 => 'blog_posts',
	'transport' => 'auto',
	'choices' => array(
        'block' => esc_html__('Visible', 'envo-extra'),
        'none' => esc_html__('Hidden', 'envo-extra'),
    ),
	'default'	 => 'block',
	'priority'	 => 5,
	'output' => array(
            array(
                'element' => '.home-heading .single-image, .home-heading .bg-single-image',
                'property' => 'display',
            ),
        ),
) );

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_posts_title_font_separator_top',
    'section' => 'blog_posts',
    'priority' => 15,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Title Font', 'envo-extra'),
    'section' => 'blog_posts',
    'settings' => 'blog_posts_title_font_devices',
    'priority' => 15,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'blog_posts_title_font' . $key,
        'description' => $value['description'],
        'section' => 'blog_posts',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '',
            'letter-spacing' => '0px',
            'font-size' => '',
            'text-transform' => 'none',
            'line-height' => '',
            'word-spacing' => '0px',
            'text-align' => '',
			envo_extra_col() => '',
        ),
        'priority' => 15,
        'output' => array(
            array(
                'element' => '.archive-item h2.entry-title a, .archive-item h2.entry-title',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_posts_meta_font_separator_top',
    'section' => 'blog_posts',
    'priority' => 20,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Meta font', 'envo-extra'),
    'section' => 'blog_posts',
    'settings' => 'blog_posts_meta_font_devices',
    'priority' => 20,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'blog_posts_meta_font' . $key,
        'description' => $value['description'],
        'section' => 'blog_posts',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '',
            'letter-spacing' => '0px',
            'font-size' => '',
            'text-transform' => 'none',
            'line-height' => '',
            'word-spacing' => '0px',
            'text-align' => 'none',
			envo_extra_col() => '',
        ),
        'priority' => 25,
        'output' => array(
            array(
                'element' => '.archive-item .article-meta, .archive-item .posted-date, .archive-item .author-meta, .archive-item .comments-meta',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_posts_content_font_separator_top',
    'section' => 'blog_posts',
    'priority' => 30,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Excerpt font', 'envo-extra'),
    'section' => 'blog_posts',
    'settings' => 'blog_posts_content_font_devices',
    'priority' => 30,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'type' => 'typography',
        'settings' => 'blog_posts_content_font' . $key,
        'description' => $value['description'],
        'section' => 'blog_posts',
        'transport' => 'auto',
        'choices' => array(
            'fonts' => envo_extra_fonts(),
        ),
        'default' => array(
            'font-family' => '',
            'variant' => '',
            'letter-spacing' => '0px',
            'font-size' => '',
            'text-transform' => 'none',
            'line-height' => '',
            'word-spacing' => '0px',
            'text-align' => 'none',
			envo_extra_col() => '',
        ),
        'priority' => 35,
        'output' => array(
            array(
                'element' => '.archive-item .post-excerpt',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_border_style_separator_top',
    'section' => 'blog_posts',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Border start.
Kirki::add_field('envo_extra', array(
    'type' => 'select',
    'settings' => 'blog_border_style',
    'label' => esc_html__('Article border', 'envo-extra'),
    'section' => 'blog_posts',
    'default' => 'none',
    'priority' => 50,
    'placeholder' => esc_html__('Choose an option', 'envo-extra'),
    'choices' => array(
        'none' => esc_html__('None', 'envo-extra'),
        'solid' => esc_html__('Solid', 'envo-extra'),
        'double' => esc_html__('Double', 'envo-extra'),
        'dotted' => esc_html__('Dotted', 'envo-extra'),
        'dashed' => esc_html__('Dashed', 'envo-extra'),
        'groove' => esc_html__('Groove', 'envo-extra'),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '.archive-item',
            'property' => 'border-style',
        ),
    )
        )
);
Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'blog_border_width',
    'label' => esc_attr__('Article Border Width', 'envo-extra'),
    'section' => 'blog_posts',
    'priority' => 50,
    'default' => array(
        'border-top-width' => '0px',
        'border-right-width' => '0px',
        'border-bottom-width' => '0px',
        'border-left-width' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-width' => esc_attr__('Top', 'textdomain'),
            'border-right-width' => esc_attr__('Bottom', 'textdomain'),
            'border-bottom-width' => esc_attr__('Left', 'textdomain'),
            'border-left-width' => esc_attr__('Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '.archive-item',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'blog_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));


Kirki::add_field('envo_extra', array(
    'type' => 'dimensions',
    'settings' => 'blog_border_radius',
    'section' => 'blog_posts',
    'label' => esc_attr__('Article Border Radius', 'envo-extra'),
    'priority' => 50,
    'default' => array(
        'border-top-left-radius' => '0px',
        'border-top-right-radius' => '0px',
        'border-bottom-left-radius' => '0px',
        'border-bottom-right-radius' => '0px',
    ),
    'choices' => array(
        'labels' => array(
            'border-top-left-radius' => esc_attr__('Top Left', 'textdomain'),
            'border-top-right-radius' => esc_attr__('Top Right', 'textdomain'),
            'border-bottom-left-radius' => esc_attr__('Bottom Left', 'textdomain'),
            'border-bottom-right-radius' => esc_attr__('Bottom Right', 'textdomain'),
        ),
    ),
    'transport' => 'auto',
    'output' => array(
        array(
            'element' => '.archive-item',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'blog_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
Kirki::add_field('envo_extra', array(
    'type' => 'color',
    'settings' => 'blog_border_color',
    'label' => esc_attr__('Article border color', 'envo-extra'),
    'section' => 'blog_posts',
    'default' => '',
    'transport' => 'auto',
    'priority' => 50,
    'output' => array(
        array(
            'element' => '.archive-item',
            'property' => 'border-color',
        ),
    ),
    'active_callback' => array(
        array(
            'setting' => 'blog_border_style',
            'operator' => '!=',
            'value' => 'none',
        ),
    ),
));
// Border end.
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_box_shadow_separator_top',
    'section' => 'blog_posts',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

Kirki::add_field('envo_extra', array(
    'type' => 'text',
    'settings' => 'blog_box_shadow_code',
    'label' => esc_html__('Article box shadow', 'envo-extra'),
    'description' => esc_attr__('e.g. 5px 5px 15px 5px #000000', 'envo-extra'),
    'section' => 'blog_posts',
    'priority' => 50,
    'output' => array(
        array(
            'element' => '.archive-item',
            'property' => 'box-shadow',
        ),
    ),
));

// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_spacing_separator_top',
    'section' => 'blog_posts',
    'priority' => 50,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));

// Title.
Kirki::add_field('envo_extra', array(
    'type' => 'responsive_devices',
    'label' => esc_attr__('Article spacing', 'envo-extra'),
    'section' => 'blog_posts',
    'settings' => 'blog_spacing_devices',
    'priority' => 50,
));
// Responsive field.
foreach ($devices as $key => $value) {
    Kirki::add_field('envo_extra', array(
        'description' => $value['description'],
        'type' => 'dimensions',
        'settings' => 'blog_spacing' . $key,
        'section' => 'blog_posts',
        'priority' => 60,
        'default' => array(
            'top' => '0px',
            'right' => '0px',
            'bottom' => '0px',
            'left' => '0px',
        ),
        'transport' => 'auto',
        'output' => array(
            array(
                'property' => 'padding',
                'element' => '.archive-item',
                $value['media_query_key'] => $value['media_query'],
            ),
        ),
    ));
}
// Separator.  
Kirki::add_field('envo_extra', array(
    'type' => 'custom',
    'settings' => 'blog_spacing_separator_bottom',
    'section' => 'blog_posts',
    'priority' => 70,
    'default' => '<hr style="border-top: 1px solid #ccc; border-bottom: 1px solid #f8f8f8; margin: 0;">',
));
