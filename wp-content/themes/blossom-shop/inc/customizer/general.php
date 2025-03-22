<?php
/**
 * General Settings
 *
 * @package Blossom_Shop
 */

function blossom_shop_customize_register_general( $wp_customize ){
    
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 60,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'blossom-shop' ),
            'description' => __( 'Customize Header, Social, Sharing, SEO, Post/Page, Newsletter, Performance and Miscellaneous settings.', 'blossom-shop' ),
        ) 
    );

    /** Header Settings */
    $wp_customize->add_section(
        'header_settings',
        array(
            'title'    => __( 'Header Settings', 'blossom-shop' ),
            'priority' => 20,
            'panel'    => 'general_settings',
        )
    );  

    $wp_customize->add_setting(
        'ed_top_bar',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_top_bar',
            array(
                'section'     => 'header_settings',
                'label'       => __( 'Enable Top Bar', 'blossom-shop' ),
                'description' => __( 'Enable to show topbar in header.', 'blossom-shop' ),
            )
        )
    );
        
    /** Sticky Text */
    $wp_customize->add_setting(
        'notification_text',
        array(
            'default'           => __( 'End of Season SALE now on thru 1/21.','blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage', 
        )
    );
    
    $wp_customize->add_control(
        'notification_text',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Title', 'blossom-shop' ),
            'active_callback' => 'blossom_shop_topbar_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'notification_text', array(
        'selector' => '.sticky-bar-content .container span',
        'render_callback' => 'blossom_shop_get_sticky_text',
    ) ); 

    /** Sticky Button */
    $wp_customize->add_setting(
        'notification_label',
        array(
            'default'           => __( 'SHOP NOW','blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage', 
        )
    );
    
    $wp_customize->add_control(
        'notification_label',
        array(
            'type'    => 'text',
            'section' => 'header_settings',
            'label'   => __( 'Button Label', 'blossom-shop' ),
            'active_callback' => 'blossom_shop_topbar_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'notification_label', array(
        'selector' => '.sticky-bar-content .container a.btn-readmore',
        'render_callback' => 'blossom_shop_get_sticky_button',
    ) ); 

    /** Sticky Button URL*/
    $wp_customize->add_setting(
        'notification_btn_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw', 
        )
    );
    
    $wp_customize->add_control(
        'notification_btn_url',
        array(
            'type'    => 'url',
            'section' => 'header_settings',
            'label'   => __( 'Button URL', 'blossom-shop' ),
            'active_callback' => 'blossom_shop_topbar_ac'
        )
    );

    /** Enable Header Search */
    $wp_customize->add_setting( 
        'ed_header_search', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_header_search',
            array(
                'section'     => 'header_settings',
                'label'       => __( 'Enable Header Search', 'blossom-shop' ),
                'description' => __( 'Enable to show Search button in header.', 'blossom-shop' ),
            )
        )
    );
    
    /** User Login */
    $wp_customize->add_setting( 
        'ed_user_login', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_user_login',
            array(
                'section'         => 'header_settings',
                'label'           => __( 'User Login', 'blossom-shop' ),
                'description'     => __( 'Enable to show user login in the header.', 'blossom-shop' ),
                'active_callback' => 'blossom_shop_is_woocommerce_activated',
            )
        )
    );

    /** Shopping Cart */
    $wp_customize->add_setting( 
        'ed_shopping_cart', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_shopping_cart',
            array(
                'section'         => 'header_settings',
                'label'           => __( 'Shopping Cart', 'blossom-shop' ),
                'description'     => __( 'Enable to show Shopping cart in the header.', 'blossom-shop' ),
                'active_callback' => 'blossom_shop_is_woocommerce_activated'
            )
        )
    );

    $wp_customize->add_setting( 'header_singular_image',
        array(
            'default'           => esc_url( get_template_directory_uri() . '/images/page-header-bg.jpg' ),
            'sanitize_callback' => 'blossom_shop_sanitize_image',
        )
    );
    
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( $wp_customize, 'header_singular_image',
            array(
                'label'         => esc_html__( 'Background Image', 'blossom-shop' ),
                'description'   => esc_html__( 'Choose background Image of your choice. Recommended size for this image is 1920px by 232px.', 'blossom-shop' ),
                'section'       => 'header_settings',
                'type'          => 'image',
            )
        )
    );
    
    /** Header Settings Ends */

    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'blossom-shop' ),
            'priority' => 30,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_social_links',
            array(
                'section'     => 'social_media_settings',
                'label'       => __( 'Enable Social Links', 'blossom-shop' ),
                'description' => __( 'Enable to show social links at header.', 'blossom-shop' ),
            )
        )
    );
    
    $wp_customize->add_setting( 
        new Blossom_Shop_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => '',
                'sanitize_callback' => array( 'Blossom_Shop_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Control_Repeater(
            $wp_customize,
            'social_links',
            array(
                'section' => 'social_media_settings',               
                'label'   => __( 'Social Links', 'blossom-shop' ),
                'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'blossom-shop' ),
                        'description' => __( 'Example: fab fa-facebook-f', 'blossom-shop' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'blossom-shop' ),
                        'description' => __( 'Example: https://facebook.com', 'blossom-shop' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'blossom-shop' ),
                    'field' => 'link'
                ),
                'choices'   => array(
                    'limit' => 10
                )                         
            )
        )
    );
    /** Social Media Settings Ends */

    /** Social Sharing Starts */

    $wp_customize->add_section(
        'social_sharing_image_section',
        array(
            'title'    => __( 'Social Sharing', 'blossom-shop' ),
            'panel'    => 'general_settings',
            'priority'    => 35,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'social_sharing_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'social_sharing_text',
            array(
                'section'     => 'social_sharing_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'social_sharing_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'social_sharing_settings',
            array(
                'section'     => 'social_sharing_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/social-sharing.png',
                ),
            )
        )
    );

    /** SEO Settings */
    $wp_customize->add_section(
        'seo_settings',
        array(
            'title'    => __( 'SEO Settings', 'blossom-shop' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_post_update_date',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Last Update Post Date', 'blossom-shop' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'blossom-shop' ),
            )
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_breadcrumb', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_breadcrumb',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Breadcrumb', 'blossom-shop' ),
                'description' => __( 'Enable to show breadcrumb in inner pages.', 'blossom-shop' ),
            )
        )
    );
    
    /** Breadcrumb Home Text */
    $wp_customize->add_setting(
        'home_text',
        array(
            'default'           => __( 'Home', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field' 
        )
    );
    
    $wp_customize->add_control(
        'home_text',
        array(
            'type'    => 'text',
            'section' => 'seo_settings',
            'label'   => __( 'Breadcrumb Home Text', 'blossom-shop' ),
        )
    );  
    /** SEO Settings Ends */

    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'blossom-shop' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );

    /** Crop Disable */
    $wp_customize->add_setting( 
        'ed_crop_all', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_crop_all',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Product Image Crop', 'blossom-shop' ),
                'description' => __( 'Enable this to disable automatic cropping of product images on homepage sections.', 'blossom-shop' ),
            )
        )
    );
    
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_prefix_archive',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Prefix in Archive Page', 'blossom-shop' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'blossom-shop' ),
            )
        )
    );
    
    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_excerpt',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Enable Blog Excerpt', 'blossom-shop' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'blossom-shop' ),
            )
        )
    );
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 55,
            'sanitize_callback' => 'blossom_shop_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new blossom_shop_Slider_Control( 
            $wp_customize,
            'excerpt_length',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Excerpt Length', 'blossom-shop' ),
                'description' => __( 'Automatically generated excerpt length (in words).', 'blossom-shop' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 100,
                    'step'  => 5,
                )                 
            )
        )
    );
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'READ MORE', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'blossom-shop' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.entry-footer .btn-readmore',
        'render_callback' => 'blossom_shop_get_read_more',
    ) );
    
    /** Note */
    $wp_customize->add_setting(
        'post_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'post_note_text',
            array(
                'section'     => 'post_page_settings',
                'description' => sprintf( __( '%s These options affect your individual posts.', 'blossom-shop' ), '<hr/>' ),
            )
        )
    );
    
    /** Hide Author Section */
    $wp_customize->add_setting( 
        'ed_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Author Section', 'blossom-shop' ),
                'description' => __( 'Enable to hide author section.', 'blossom-shop' ),
            )
        )
    );
    
    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_related',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Related Posts', 'blossom-shop' ),
                'description' => __( 'Enable to show related posts in single page.', 'blossom-shop' ),
            )
        )
    );
    
    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'Recommended Articles', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'            => 'text',
            'section'         => 'post_page_settings',
            'label'           => __( 'Related Posts Section Title', 'blossom-shop' ),
            'active_callback' => 'blossom_shop_post_page_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector' => '.additional-post .title',
        'render_callback' => 'blossom_shop_get_related_title',
    ) );
    
    /** Comments */
    $wp_customize->add_setting(
        'ed_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_comments',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Comments', 'blossom-shop' ),
                'description' => __( 'Enable to show Comments in Single Post/Page.', 'blossom-shop' ),
            )
        )
    );
    
    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_category',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Category', 'blossom-shop' ),
                'description' => __( 'Enable to hide category.', 'blossom-shop' ),
            )
        )
    );
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_post_date',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Posted Date', 'blossom-shop' ),
                'description' => __( 'Enable to hide posted date.', 'blossom-shop' ),
            )
        )
    );
    
    /** Show Featured Image */
    $wp_customize->add_setting( 
        'ed_featured_image', 
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_featured_image',
            array(
                'section'         => 'post_page_settings',
                'label'           => __( 'Show Featured Image', 'blossom-shop' ),
                'description'     => __( 'Enable to show featured image in post detail (single post).', 'blossom-shop' ),
                'active_callback' => 'blossom_shop_post_page_ac'
            )
        )
    );

    /** Posts(Blog) & Pages Settings Ends */

    /** Performance Settings Starts */

    $wp_customize->add_section(
        'performance_image_section',
        array(
            'title'    => __( 'Performance Settings ', 'blossom-shop' ),
            'panel'    => 'general_settings',
            'priority'    => 55,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'performance_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'performance_text',
            array(
                'section'     => 'performance_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'performance_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'performance_settings',
            array(
                'section'     => 'performance_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/performance.png',
                ),
            )
        )
    );

    /** Instagram Settings */
    $wp_customize->add_section(
        'instagram_settings',
        array(
            'title'    => __( 'Instagram Settings', 'blossom-shop' ),
            'priority' => 65,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Instagram Section */
    $wp_customize->add_setting( 
        'ed_instagram', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_instagram',
            array(
                'section'     => 'instagram_settings',
                'label'       => __( 'Instagram Section', 'blossom-shop' ),
                'description' => __( 'Enable to show Instagram Section', 'blossom-shop' ),
            )
        )
    );

    $wp_customize->add_setting( 
        'instagram_shortcode', 
        array(
            'default'           => '[instagram-feed]',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    
    $wp_customize->add_control(
        'instagram_shortcode',
        array(
            'section'         => 'instagram_settings',
            'label'           => __( 'Shortcode', 'blossom-shop' ),
            'type'            => 'text',
            'description'     => __( 'Add shortcode for your instagram profile below:', 'blossom-shop' ),
            'active_callback' => 'blossom_shop_ed_instagram',
        )
    );

    /** Newsletter Settings */
    $wp_customize->add_section(
        'newsletter_settings',
        array(
            'title'    => __( 'Newsletter Settings', 'blossom-shop' ),
            'priority' => 70,
            'panel'    => 'general_settings',
        )
    );
    
    if( blossom_shop_is_btnw_activated() ){
        
        /** Enable Newsletter Section */
        $wp_customize->add_setting( 
            'ed_newsletter', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Shop_Toggle_Control( 
                $wp_customize,
                'ed_newsletter',
                array(
                    'section'     => 'newsletter_settings',
                    'label'       => __( 'Newsletter Section', 'blossom-shop' ),
                    'description' => __( 'Enable to show Newsletter Section', 'blossom-shop' ),
                )
            )
        );
        
        /** Newsletter Shortcode */
        $wp_customize->add_setting(
            'newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'newsletter_shortcode',
            array(
                'type'        => 'text',
                'section'     => 'newsletter_settings',
                'label'       => __( 'Newsletter Shortcode', 'blossom-shop' ),
                'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'blossom-shop' ),
            )
        ); 
    }else{
        /** Note */
        $wp_customize->add_setting(
            'newsletter_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Shop_Note_Control( 
                $wp_customize,
                'newsletter_text',
                array(
                    'section'     => 'newsletter_settings',
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s. After that option related with this section will be visible.', 'blossom-shop' ), '<a href="' . esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '" target="_blank">', '</a>' )
                )
            )
        );
    }
    /* Newsletter Section Ends */

    /** Misc Settings Starts */

    $wp_customize->add_section(
        'misc_image_section',
        array(
            'title'    => __( 'Misc Settings', 'blossom-shop' ),
            'panel'    => 'general_settings',
            'priority'    => 75,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'misc_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'misc_text',
            array(
                'section'     => 'misc_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'misc_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'misc_settings',
            array(
                'section'     => 'misc_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/misc.png',
                ),
            )
        )
    );

    /** Shop Settings */
    $wp_customize->add_section(
        'shop_settings',
        array(
            'title'    => __( 'Shop Settings', 'blossom-shop' ),
            'priority' => 90,
            'panel'    => 'general_settings',
            'active_callback' => 'blossom_shop_is_woocommerce_activated'
        )
    );

    $wp_customize->add_setting( 'shop_background_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_image',
        )
    );
    
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( $wp_customize, 'shop_background_image',
            array(
                'label'         => esc_html__( 'Shop Background Image', 'blossom-shop' ),
                'description'   => esc_html__( 'Choose Image of your choice. Recommended size for this image is 1920px by 550px.', 'blossom-shop' ),
                'section'       => 'shop_settings',
                'type'          => 'image',
            )
        )
    );

    /** Shop Page Description */
    $wp_customize->add_setting( 
        'ed_shop_archive_description', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_shop_archive_description',
            array(
                'section'         => 'shop_settings',
                'label'           => __( 'Shop Page Description', 'blossom-shop' ),
                'description'     => __( 'Enable to show Shop Page Description.', 'blossom-shop' ),
            )
        )
    );

    /** Shop Settings Ends */

    /** Google Analytics Settings Starts */

    $wp_customize->add_section(
        'google_image_section',
        array(
            'title'    => __( 'Google Analytics Settings ', 'blossom-shop' ),
            'panel'    => 'general_settings',
            'priority'    => 95,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'google_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'google_text',
            array(
                'section'     => 'google_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'google_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'google_settings',
            array(
                'section'     => 'google_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/google-analytics.png',
                ),
            )
        )
    );
    
}
add_action( 'customize_register', 'blossom_shop_customize_register_general' );