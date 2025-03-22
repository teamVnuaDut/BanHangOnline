<?php
/**
 * Layout Settings
 *
 * @package Blossom_Shop
 */

function blossom_shop_customize_register_layout( $wp_customize ) {

    /** Layout Settings */
    $wp_customize->add_panel( 
        'layout_settings',
         array(
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Layout Settings', 'blossom-shop' ),
            'description' => __( 'Change different page layout from here.', 'blossom-shop' ),
        ) 
    );

    /** Header Layout Starts */

    $wp_customize->add_section(
        'header_layout_section',
        array(
            'title'    => __( 'Header Layout', 'blossom-shop' ),
            'panel'    => 'layout_settings',
            'priority'    => 9,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'header_layout_img_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'header_layout_img_text',
            array(
                'section'     => 'header_layout_section',
                'priority'    => 50,
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'header_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'header_layout_settings',
            array(
                'section'     => 'header_layout_section',
                'priority'    => 50,
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/header-layout.png',
                ),
            )
        )
    );

    /** Slider Layout Starts */

    $wp_customize->add_section(
        'slider_layout_image_section',
        array(
            'title'    => __( 'Slider Layout', 'blossom-shop' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'slider_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'slider_layout_text',
            array(
                'section'     => 'slider_layout_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'slider_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'slider_layout_settings',
            array(
                'section'     => 'slider_layout_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/slider-layout.png',
                ),
            )
        )
    );
    

    /** Blog Page Layout Settings */
    $wp_customize->add_section(
        'blog_layout',
        array(
            'title'    => __( 'Blog Page Layout', 'blossom-shop' ),
            'panel'    => 'layout_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'blog_page_layout', 
        array(
            'default'           => 'classic-layout',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'blog_page_layout',
            array(
                'section'     => 'blog_layout',
                'label'       => __( 'Blog Page Layout', 'blossom-shop' ),
                'description' => __( 'Choose the blog page layout for your site.', 'blossom-shop' ),
                'choices'     => array(
                    'classic-layout' => esc_url( get_template_directory_uri() . '/images/blog/classic.jpg' ),
                    'grid-layout'    => esc_url( get_template_directory_uri() . '/images/blog/grid.jpg' ),
                    'list-layout'    => esc_url( get_template_directory_uri() . '/images/blog/listing.jpg' ),
                )
            )
        )
    );

    /** Product Single Layout Starts */

    $wp_customize->add_section(
        'product_single_image_section',
        array(
            'title'    => __( 'Product Single Layout', 'blossom-shop' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'product_single_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'product_single_text',
            array(
                'section'     => 'product_single_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'product_single_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'product_single_settings',
            array(
                'section'     => 'product_single_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/product-single-layout.png',
                ),
            )
        )
    );

    /** General layout Settings */
    $wp_customize->add_section(
        'general_layout_settings',
        array(
            'title'    => __( 'General Sidebar Layout', 'blossom-shop' ),
            'panel'    => 'layout_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'page_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'page_sidebar_layout',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Page Sidebar Layout', 'blossom-shop' ),
                'description' => __( 'This is the general sidebar layout for pages. You can override the sidebar layout for individual page in respective page.', 'blossom-shop' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'post_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'post_sidebar_layout',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Post Sidebar Layout', 'blossom-shop' ),
                'description' => __( 'This is the general sidebar layout for posts & custom post. You can override the sidebar layout for individual post in respective post.', 'blossom-shop' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'layout_style', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'layout_style',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Default Sidebar Layout', 'blossom-shop' ),
                'description' => __( 'This is the general sidebar layout for whole site.', 'blossom-shop' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );

    /** Pagination Setting Starts */

    $wp_customize->add_section(
        'pagination_image_section',
        array(
            'title'    => __( 'Pagination Setting', 'blossom-shop' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'pagination_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'pagination_text',
            array(
                'section'     => 'pagination_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'pagination_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'pagination_settings',
            array(
                'section'     => 'pagination_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/pagination-settings.png',
                ),
            )
        )
    );
}
add_action( 'customize_register', 'blossom_shop_customize_register_layout' );