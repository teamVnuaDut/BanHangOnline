<?php
/**
 * Front Page Settings
 *
 * @package Blossom_Shop
 */

function blossom_shop_customize_register_frontpage( $wp_customize ) {
	
    /** Front Page Settings */
    $wp_customize->add_panel( 
        'frontpage_settings',
         array(
            'priority'    => 40,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Front Page Settings', 'blossom-shop' ),
            'description' => __( 'Static Home Page settings.', 'blossom-shop' ),
        ) 
    );      
    
    /** Slider Settings Starts */
    $wp_customize->get_section( 'header_image' )->panel                    = 'frontpage_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'blossom-shop' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_control( 'header_image' )->active_callback          = 'blossom_shop_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback          = 'blossom_shop_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'blossom_shop_banner_ac';
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
        'ed_banner_section',
        array(
            'default'           => 'slider_banner',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'ed_banner_section',
            array(
                'label'       => __( 'Banner Options', 'blossom-shop' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'blossom-shop' ),
                'section'     => 'header_image',
                'choices'     => array(
                    'no_banner'        => __( 'Disable Banner Section', 'blossom-shop' ),
                    'static_banner'    => __( 'Static/Video CTA Banner', 'blossom-shop' ),
                    'slider_banner'    => __( 'Banner as Slider', 'blossom-shop' ),
                ),
                'priority' => 5 
            )            
        )
    );
    
    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'Find Your Best Holiday', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Title', 'blossom-shop' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_shop_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector' => '.site-banner .banner-caption h2.banner-title',
        'render_callback' => 'blossom_shop_get_banner_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Find great adventure holidays and activities around the planet.', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Subtitle', 'blossom-shop' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_shop_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector' => '.site-banner .banner-caption .banner-desc',
        'render_callback' => 'blossom_shop_get_banner_subtitle',
    ) );
    
    /** Banner Label */
    $wp_customize->add_setting(
        'banner_label',
        array(
            'default'           => __( 'Purchase', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_label',
        array(
            'label'           => __( 'Banner Label', 'blossom-shop' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_shop_banner_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_label', array(
        'selector' => '.site-banner .banner-caption a.btn-readmore',
        'render_callback' => 'blossom_shop_get_banner_label',
    ) );
    
    
    /** Banner Link */
    $wp_customize->add_setting(
        'banner_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_link',
        array(
            'label'           => __( 'Banner Link', 'blossom-shop' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_shop_banner_ac'
        )
    );

    $wp_customize->add_setting(
        'banner_caption_align',
        array(
            'default'           => 'left-align',
            'sanitize_callback' => 'blossom_shop_sanitize_radio',
        )
    );
    
    $wp_customize->add_control( 
        new Blossom_Shop_Radio_Buttonset_Control (
            $wp_customize,
            'banner_caption_align',
            array(
                'label'   => esc_html__( 'Banner Caption Alignment', 'blossom-shop' ),
                'section' => 'header_image',
                'choices' => array(
                    'left-align'   => esc_html__( 'Left', 'blossom-shop' ),
                    'center-align' => esc_html__( 'Center', 'blossom-shop' ),
                    'right-align'  => esc_html__( 'Right', 'blossom-shop' )
                ),
                'active_callback'  => 'blossom_shop_banner_ac'
            )
        )
    );
    
    /** Slider Content Style */
    $wp_customize->add_setting(
        'slider_type',
        array(
            'default'           => 'latest_posts',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'slider_type',
            array(
                'label'   => __( 'Slider Content Style', 'blossom-shop' ),
                'section' => 'header_image',
                'choices' => array(
                    'latest_posts' => __( 'Latest Posts', 'blossom-shop' ),
                    'cat'          => __( 'Category', 'blossom-shop' ),
                ),
                'active_callback' => 'blossom_shop_banner_ac'   
            )
        )
    );
    
    /** Slider Category */
    $wp_customize->add_setting(
        'slider_cat',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'slider_cat',
            array(
                'label'           => __( 'Slider Category', 'blossom-shop' ),
                'section'         => 'header_image',
                'choices'         => blossom_shop_get_categories(),
                'active_callback' => 'blossom_shop_banner_ac'   
            )
        )
    );
    
    /** No. of slides */
    $wp_customize->add_setting(
        'no_of_slides',
        array(
            'default'           => 3,
            'sanitize_callback' => 'blossom_shop_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
        new blossom_shop_Slider_Control( 
            $wp_customize,
            'no_of_slides',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Number of Slides', 'blossom-shop' ),
                'description' => __( 'Choose the number of slides you want to display', 'blossom-shop' ),
                'choices'     => array(
                    'min'   => 1,
                    'max'   => 20,
                    'step'  => 1,
                ),
                'active_callback' => 'blossom_shop_banner_ac'                 
            )
        )
    );

    
    /** HR */
    $wp_customize->add_setting(
        'hr',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'hr',
            array(
                'section'     => 'header_image',
                'description' => '<hr/>',
                'active_callback' => 'blossom_shop_banner_ac'
            )
        )
    ); 
    
    /** Slider Auto */
    $wp_customize->add_setting(
        'slider_auto',
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'slider_auto',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Slider Auto', 'blossom-shop' ),
                'description' => __( 'Enable slider auto transition.', 'blossom-shop' ),
                'active_callback' => 'blossom_shop_banner_ac'
            )
        )
    );
    
    /** Slider Animation */
    $wp_customize->add_setting(
        'slider_animation',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'slider_animation',
            array(
                'label'       => __( 'Slider Animation', 'blossom-shop' ),
                'section'     => 'header_image',
                'choices'     => array(
                    'fadeOut'        => __( 'Fade Out', 'blossom-shop' ),
                    'fadeOutLeft'    => __( 'Fade Out Left', 'blossom-shop' ),
                    'fadeOutRight'   => __( 'Fade Out Right', 'blossom-shop' ),
                    'fadeOutUp'      => __( 'Fade Out Up', 'blossom-shop' ),
                    'fadeOutDown'    => __( 'Fade Out Down', 'blossom-shop' ),
                    ''               => __( 'Slide', 'blossom-shop' ),
                    'slideOutLeft'   => __( 'Slide Out Left', 'blossom-shop' ),
                    'slideOutRight'  => __( 'Slide Out Right', 'blossom-shop' ),
                    'slideOutUp'     => __( 'Slide Out Up', 'blossom-shop' ),
                    'slideOutDown'   => __( 'Slide Out Down', 'blossom-shop' ),                    
                ),
                'active_callback' => 'blossom_shop_banner_ac'                                   
            )
        )
    );
    
    /** Readmore Text */
    $wp_customize->add_setting(
        'slider_readmore',
        array(
            'default'           => __( 'SHOP NEW ARRIVALS', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'slider_readmore',
        array(
            'type'            => 'text',
            'section'         => 'header_image',
            'label'           => __( 'Slider Read More', 'blossom-shop' ),
            'active_callback' => 'blossom_shop_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'slider_readmore', array(
        'selector' => '.site-banner .banner-caption .button-wrap a.btn-readmore',
        'render_callback' => 'blossom_shop_get_slider_readmore',
    ) );
    /** Slider Settings Ends */
    
    /** Recent Product Settings Starts */
    if( blossom_shop_is_woocommerce_activated() ){

        /** Recent Product Section */
        $wp_customize->add_section(
            'recent_product',
            array(
                'title'    => __( 'Recent Product Section', 'blossom-shop' ),
                'priority' => 30,
                'panel'    => 'frontpage_settings',
            )
        );

        /** Enable Featured Area */
        $wp_customize->add_setting( 
            'ed_recent_product_section', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Shop_Toggle_Control( 
                $wp_customize,
                'ed_recent_product_section',
                array(
                    'section'     => 'recent_product',
                    'label'       => __( 'Enable Recent Product', 'blossom-shop' ),
                    'description' => __( 'Enable to show recent product section in home page.', 'blossom-shop' ),
                )
            )
        );

        /** Recent Product Title  */
        $wp_customize->add_setting(
            'recent_product_title',
            array(
                'default'           => __( 'New Arrivals', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'recent_product_title',
            array(
                'label'           => __( 'Recent Product Section Title', 'blossom-shop' ),
                'description'     => __( 'Add title for recent product section.', 'blossom-shop' ),
                'section'         => 'recent_product',
                'active_callback' => 'blossom_shop_recent_product_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'recent_product_title', array(
            'selector' => '.recent-prod-section h2.section-title',
            'render_callback' => 'blossom_shop_get_recent_product_title',
        ) );

        /** Recent Product SubTitle  */
        $wp_customize->add_setting(
            'recent_product_subtitle',
            array(
                'default'           => __( 'Add our new arrivals to your weekly lineup.', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'recent_product_subtitle',
            array(
                'label'           => __( 'Recent Product Section SubTitle', 'blossom-shop' ),
                'description'     => __( 'Add subtitle for recent product section.', 'blossom-shop' ),
                'section'         => 'recent_product',
                'type'            => 'text',
                'active_callback' => 'blossom_shop_recent_product_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'recent_product_subtitle', array(
            'selector' => '.recent-prod-section .section-desc',
            'render_callback' => 'blossom_shop_get_recent_product_subtitle',
        ) );

        /** No. of products */
        $wp_customize->add_setting(
            'no_of_products',
            array(
                'default'           => 5,
                'sanitize_callback' => 'blossom_shop_sanitize_number_absint'
            )
        );

        $wp_customize->add_control(
            new Blossom_Shop_Slider_Control( 
                $wp_customize,
                'no_of_products',
                array(
                    'section'     => 'recent_product',
                    'label'       => __( 'Number of Products', 'blossom-shop' ),
                    'description' => __( 'Choose the number of products you want to display', 'blossom-shop' ),
                    'choices'     => array(
                        'min'   => 1,
                        'max'   => 20,
                        'step'  => 1,
                    ),
                    'active_callback' => 'blossom_shop_recent_product_ac',                 
                )
            )
        );    

    }   
    /** Recent Product Settings Ends */

    /** Featured Area Settings */
    $wp_customize->add_section(
        'featured_section',
        array(
            'title'    => __( 'Featured Section', 'blossom-shop' ),
            'priority' => 40,
            'panel'    => 'frontpage_settings',
        )
    );
    
    /** Enable Featured Area */
    $wp_customize->add_setting( 
        'ed_featured_section', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_featured_section',
            array(
                'section'     => 'featured_section',
                'label'       => __( 'Enable Featured Area', 'blossom-shop' ),
                'description' => __( 'Enable to show featured section area section in home page.', 'blossom-shop' ),
            )
        )
    );

    $wp_customize->add_setting( 
        'featured_type', 
        array(
            'default'           => 'feat_page',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
		new Blossom_Shop_Radio_Buttonset_Control(
			$wp_customize,
			'featured_type',
			array(
				'section'	  => 'featured_section',
				'label'       => __( 'Featured Content Type', 'blossom-shop' ),
                'description' => __( 'Choose featured content type.', 'blossom-shop' ),
				'choices'     => blossom_shop_featured_options(),
                'active_callback' => 'blossom_shop_featured_ac',
			)
		)
	);
    
    /** Featured Content One */
    $wp_customize->add_setting(
        'featured_content_one',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'featured_content_one',
            array(
                'label'           => __( 'Featured Content One', 'blossom-shop' ),
                'section'         => 'featured_section',
                'choices'         => blossom_shop_get_posts( 'page' ),  
                'active_callback' => 'blossom_shop_featured_ac'
            )
        )
    );
    
    /** Featured Content Two */
    $wp_customize->add_setting(
        'featured_content_two',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'featured_content_two',
            array(
                'label'           => __( 'Featured Content Two', 'blossom-shop' ),
                'section'         => 'featured_section',
                'choices'         => blossom_shop_get_posts( 'page' ),
                'active_callback' => 'blossom_shop_featured_ac' 
            )
        )
    );
    
    /** Featured Content Three */
    $wp_customize->add_setting(
        'featured_content_three',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'featured_content_three',
            array(
                'label'           => __( 'Featured Content Three', 'blossom-shop' ),
                'section'         => 'featured_section',
                'choices'         => blossom_shop_get_posts( 'page' ),  
                'active_callback' => 'blossom_shop_featured_ac'
            )
        )
    );

    /** Popular Product Starts */

    $wp_customize->add_section(
        'pop_product_image_section',
        array(
            'title'    => __( 'Popular Product Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 42,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'pop_product_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'pop_product_text',
            array(
                'section'     => 'pop_product_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'pop_product_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'pop_product_settings',
            array(
                'section'     => 'pop_product_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/popular-product-design.png',
                    'two'       => get_template_directory_uri() . '/images/pro/popular-product.png',
                ),
            )
        )
    );

     /** Product Deal Starts */
    $wp_customize->add_section(
        'product_deal_image_section',
        array(
            'title'    => __( 'Product Deal Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 42,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'product_deal_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'product_deal_text',
            array(
                'section'     => 'product_deal_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'product_deal_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'product_deal_settings',
            array(
                'section'     => 'product_deal_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/product-deal-design.png',
                    'two'       => get_template_directory_uri() . '/images/pro/product-deal.png',
                ),
            )
        )
    );


    /** Category One Section Starts */
    if( blossom_shop_is_woocommerce_activated() ){

        /** Featured Category One */
        $wp_customize->add_setting(
            'cat_featured_one',
            array(
                'default'           => '',
                'sanitize_callback' => 'blossom_shop_sanitize_select'
            )
        );

        $wp_customize->add_control(
            new Blossom_Shop_Select_Control(
                $wp_customize,
                'cat_featured_one',
                array(
                    'label'   => __( 'Featured Category One', 'blossom-shop' ),
                    'section' => 'featured_section',
                    'choices' => blossom_shop_get_categories( true, 'product_cat' ),
                    'active_callback' => 'blossom_shop_featured_ac'
                )
            )
        );

        /** Featured Category Two */
        $wp_customize->add_setting(
            'cat_featured_two',
            array(
                'default'           => '',
                'sanitize_callback' => 'blossom_shop_sanitize_select'
            )
        );

        $wp_customize->add_control(
            new Blossom_Shop_Select_Control(
                $wp_customize,
                'cat_featured_two',
                array(
                    'label'   => __( 'Featured Category Two', 'blossom-shop' ),
                    'section' => 'featured_section',
                    'choices' => blossom_shop_get_categories( true, 'product_cat' ),
                    'active_callback' => 'blossom_shop_featured_ac'
                )
            )
        );

        /** Featured Category Two */
        $wp_customize->add_setting(
            'cat_featured_three',
            array(
                'default'           => '',
                'sanitize_callback' => 'blossom_shop_sanitize_select'
            )
        );

        $wp_customize->add_control(
            new Blossom_Shop_Select_Control(
                $wp_customize,
                'cat_featured_three',
                array(
                    'label'   => __( 'Featured Category Three', 'blossom-shop' ),
                    'section' => 'featured_section',
                    'choices' => blossom_shop_get_categories( true, 'product_cat' ),
                    'active_callback' => 'blossom_shop_featured_ac'
                )
            )
        );

        /** Featured Area Settings Ends */

        /** Category One Section */
        $wp_customize->add_section(
            'cat_one',
            array(
                'title'    => __( 'Category One Section', 'blossom-shop' ),
                'priority' => 63,
                'panel'    => 'frontpage_settings',
            )
        );

        /** Enable Popular Product */
        $wp_customize->add_setting( 
            'ed_cat_one_section', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Shop_Toggle_Control( 
                $wp_customize,
                'ed_cat_one_section',
                array(
                    'section'     => 'cat_one',
                    'label'       => __( 'Enable Category One', 'blossom-shop' ),
                    'description' => __( 'Enable to show category one section in home page.', 'blossom-shop' ),
                )
            )
        );

        /** Category One Title  */
        $wp_customize->add_setting(
            'cat_one_title',
            array(
                'default'           => __( 'Best Sellers', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'cat_one_title',
            array(
                'label'           => __( 'Category One Section Title', 'blossom-shop' ),
                'description'     => __( 'Add title for category one section.', 'blossom-shop' ),
                'section'         => 'cat_one',
                'active_callback' => 'blossom_shop_cat_one_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'cat_one_title', array(
            'selector' => '.first-cat-section h2.section-title',
            'render_callback' => 'blossom_shop_get_cat_one_title',
        ) );

        /** Category One SubTitle  */
        $wp_customize->add_setting(
            'cat_one_subtitle',
            array(
                'default'           => __( 'Check out our best sellers products.', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'cat_one_subtitle',
            array(
                'label'           => __( 'Category One Section SubTitle', 'blossom-shop' ),
                'description'     => __( 'Add subtitle for category one section.', 'blossom-shop' ),
                'section'         => 'cat_one',
                'type'            => 'text',
                'active_callback' => 'blossom_shop_cat_one_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'cat_one_subtitle', array(
            'selector' => '.first-cat-section .section-desc',
            'render_callback' => 'blossom_shop_get_cat_one_subtitle',
        ) );

        /** Category One Featured Image  */
        $wp_customize->add_setting( 'cat_one_featured_image',
            array(
                'default'           => '',
                'sanitize_callback' => 'blossom_shop_sanitize_image',
            )
        );
        
        $wp_customize->add_control( 
            new WP_Customize_Image_Control( $wp_customize, 'cat_one_featured_image',
                array(
                    'label'         => esc_html__( 'Featured Image', 'blossom-shop' ),
                    'description'   => esc_html__( 'Choose Image of your choice. Recommended size for this image is 1920px by 1080px.', 'blossom-shop' ),
                    'section'       => 'cat_one',
                    'type'          => 'image',
                    'active_callback' => 'blossom_shop_cat_one_ac'
                )
            )
        );

        /** Category One Featured Title  */
        $wp_customize->add_setting(
            'cat_one_featured_title',
            array(
                'default'           => __( 'STREET TRENDING 2019', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'cat_one_featured_title',
            array(
                'label'           => __( 'Category One Featured Title', 'blossom-shop' ),
                'description'     => __( 'Add title for category one featured section.', 'blossom-shop' ),
                'section'         => 'cat_one',
                'active_callback' => 'blossom_shop_cat_one_ac'
            )
        );

        $wp_customize->selective_refresh->add_partial( 'cat_one_featured_title', array(
            'selector' => '.first-cat-section h4.pp-title',
            'render_callback' => 'blossom_shop_get_cat_one_featured_title',
        ) );

        /** Category One Featured SubTitle  */
        $wp_customize->add_setting(
            'cat_one_featured_subtitle',
            array(
                'default'           => __( 'SUMMER EXCLUSIVE COLLECTION', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'cat_one_featured_subtitle',
            array(
                'label'           => __( 'Category One Featured SubTitle', 'blossom-shop' ),
                'description'     => __( 'Add subtitle for category one featured section.', 'blossom-shop' ),
                'section'         => 'cat_one',
                'type'            => 'text',
                'active_callback' => 'blossom_shop_cat_one_ac'
            )
        );

        $wp_customize->selective_refresh->add_partial( 'cat_one_featured_subtitle', array(
            'selector' => '.first-cat-section .pp-desc',
            'render_callback' => 'blossom_shop_get_cat_one_featured_subtitle',
        ) );

        /** Category One Featured Label */
        $wp_customize->add_setting(
            'cat_one_featured_label',
            array(
                'default'           => __( 'DISCOVER NOW', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'cat_one_featured_label',
            array(
                'label'           => __( 'Category One Featured Button Label', 'blossom-shop' ),
                'section'         => 'cat_one',
                'type'            => 'text',
                'active_callback' => 'blossom_shop_cat_one_ac'
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'cat_one_featured_label', array(
            'selector' => '.first-cat-section .product-title-wrap .button-wrap a.btn-readmore',
            'render_callback' => 'blossom_shop_get_cat_one_featured_label',
        ) );

        /** Category One Featured URL */
        $wp_customize->add_setting(
            'cat_one_featured_url',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'cat_one_featured_url',
            array(
                'label'           => __( 'Category One Featured Button URL', 'blossom-shop' ),
                'section'         => 'cat_one',
                'type'            => 'url',
                'active_callback' => 'blossom_shop_cat_one_ac'
            )
        ); 

        /** Category One Content Style */
        $wp_customize->add_setting(
            'cat_one_select',
            array(
                'default'           => '',
                'sanitize_callback' => 'blossom_shop_sanitize_select'
            )
        );

        $wp_customize->add_control(
            new Blossom_Shop_Select_Control(
                $wp_customize,
                'cat_one_select',
                array(
                    'label'   => __( 'Category One Content', 'blossom-shop' ),
                    'section' => 'cat_one',
                    'choices' => blossom_shop_get_categories( true, 'product_cat' ),
                    'active_callback' => 'blossom_shop_cat_one_ac',
                )
            )
        );

        /** Category One Button Label  */
        $wp_customize->add_setting(
            'cat_one_all',
            array(
                'default'           => __( 'SHOP ALL PRODUCTS', 'blossom-shop' ),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'cat_one_all',
            array(
                'label'           => __( 'Category One Button Label', 'blossom-shop' ),
                'description'     => __( 'Add button label for category one section.', 'blossom-shop' ),
                'section'         => 'cat_one',
                'active_callback' => 'blossom_shop_cat_one_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial( 'cat_one_all', array(
            'selector' => '.first-cat-section .button-wrap a.btn-readmore',
            'render_callback' => 'blossom_shop_get_cat_one_all',
        ) );
        
    }
    /** Category One Section Ends */ 


    
    /** Category Tab Starts */

    $wp_customize->add_section(
        'cat_tab_image_section',
        array(
            'title'    => __( 'Category Tab Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 65,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'cat_tab_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'cat_tab_text',
            array(
                'section'     => 'cat_tab_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'cat_tab_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'cat_tab_settings',
            array(
                'section'     => 'cat_tab_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/category-tab.png',
                ),
            )
        )
    );

    /** Category Two Starts */

    $wp_customize->add_section(
        'cat_two_image_section',
        array(
            'title'    => __( 'Category Two Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 66,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'cat_two_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'cat_two_text',
            array(
                'section'     => 'cat_two_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'cat_two_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'cat_two_settings',
            array(
                'section'     => 'cat_two_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/category-two.png',
                ),
            )
        )
    );

    /** Category Three Starts */

    $wp_customize->add_section(
        'cat_three_image_section',
        array(
            'title'    => __( 'Category Three Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 82,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'cat_three_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'cat_three_text',
            array(
                'section'     => 'cat_three_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'cat_three_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'cat_three_settings',
            array(
                'section'     => 'cat_three_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/category-three.png',
                ),
            )
        )
    );


    /** Category Four Starts */

    $wp_customize->add_section(
        'cat_four_image_section',
        array(
            'title'    => __( 'Category Four Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 83,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'cat_four_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'cat_four_text',
            array(
                'section'     => 'cat_four_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'cat_four_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'cat_four_settings',
            array(
                'section'     => 'cat_four_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/category-four.png',
                ),
            )
        )
    );

    
    /** Testimonial Section */
    $wp_customize->add_section(
        'testimonial',
        array(
            'title'    => __( 'Testimonial Section', 'blossom-shop' ),
            'priority' => 84,
            'panel'    => 'frontpage_settings',
        )
    );

    /** Testimonial Title  */
    $wp_customize->add_setting(
        'testimonial_title',
        array(
            'default'           => __( 'Our Happy Customers', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_title',
        array(
            'label'           => __( 'Testimonial Section Title', 'blossom-shop' ),
            'description'     => __( 'Add title for testimonial section.', 'blossom-shop' ),
            'section'         => 'testimonial',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_title', array(
        'selector' => '.testimonial-section h2.section-title',
        'render_callback' => 'blossom_shop_get_testimonial_title',
    ) );
    
    /** Testimonial SubTitle  */
    $wp_customize->add_setting(
        'testimonial_subtitle',
        array(
            'default'           => __( 'Words of praise by our valuable customers', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'testimonial_subtitle',
        array(
            'label'           => __( 'Testimonial Section SubTitle', 'blossom-shop' ),
            'description'     => __( 'Add subtitle for testimonial section.', 'blossom-shop' ),
            'section'         => 'testimonial',
            'type'            => 'text',
            'priority'    => -1
        )
    );

    $wp_customize->selective_refresh->add_partial( 'testimonial_subtitle', array(
        'selector' => '.testimonial-section .section-desc',
        'render_callback' => 'blossom_shop_get_testimonial_title',
    ) );

    $wp_customize->add_setting(
        'testimonial_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'testimonial_note_text',
            array(
                'section'     => 'testimonial',
                'description' => __( '<hr/>Add "Blossom: Testimonial" widget for testimonial section.', 'blossom-shop' ),
                'priority'    => -1
            )
        )
    );

    $testimonial_section = $wp_customize->get_section( 'sidebar-widgets-testimonial' );
    if ( ! empty( $testimonial_section ) ) {

        $testimonial_section->panel     = 'frontpage_settings';
        $testimonial_section->priority  = 80;
        $wp_customize->get_control( 'testimonial_note_text' )->section = 'sidebar-widgets-testimonial';
        $wp_customize->get_control( 'testimonial_title' )->section     = 'sidebar-widgets-testimonial';
        $wp_customize->get_control( 'testimonial_subtitle' )->section  = 'sidebar-widgets-testimonial';
    }  
    
    /** Testimonial Section Ends */ 

    /** Blog Section */
    $wp_customize->add_section(
        'blog_section',
        array(
            'title'    => __( 'Blog Section', 'blossom-shop' ),
            'priority' => 100,
            'panel'    => 'frontpage_settings',
        )
    );

    /** Enable Blog Section */
    $wp_customize->add_setting( 
        'ed_blog_section', 
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_blog_section',
            array(
                'section'     => 'blog_section',
                'label'       => __( 'Enable Blog Section', 'blossom-shop' ),
                'description' => __( 'Enable to show blog section in home page.', 'blossom-shop' ),
            )
        )
    );

    /** Blog title */
    $wp_customize->add_setting(
        'blog_section_title',
        array(
            'default'           => __( 'Our Blog', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_title',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Blog Title', 'blossom-shop' ),
            'type'    => 'text',
            'active_callback' => 'blossom_shop_blog_ac',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'blog_section_title', array(
        'selector' => '.blog-section h2.section-title',
        'render_callback' => 'blossom_shop_get_blog_section_title',
    ) ); 

    /** Blog description */
    $wp_customize->add_setting(
        'blog_section_subtitle',
        array(
            'default'           => __( 'Our recent articles about fashion ideas products.', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_subtitle',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Blog Description', 'blossom-shop' ),
            'type'    => 'text',
            'active_callback' => 'blossom_shop_blog_ac',
        )
    ); 

    $wp_customize->selective_refresh->add_partial( 'blog_section_subtitle', array(
        'selector' => '.blog-section .section-desc',
        'render_callback' => 'blossom_shop_get_blog_section_subtitle',
    ) ); 
    
    /** Readmore Label */
    $wp_customize->add_setting(
        'blog_readmore',
        array(
            'default'           => __( 'READ MORE', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_readmore',
        array(
            'label'           => __( 'Read More Label', 'blossom-shop' ),
            'section'         => 'blog_section',
            'type'            => 'text',
            'active_callback' => 'blossom_shop_blog_ac',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_readmore', array(
        'selector' => '.blog-section .entry-header a.btn-readmore',
        'render_callback' => 'blossom_shop_get_blog_readmore',
    ) ); 
    
    /** View All Label */
    $wp_customize->add_setting(
        'blog_view_all',
        array(
            'default'           => __( 'READ ALL POSTS', 'blossom-shop' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_view_all',
        array(
            'label'           => __( 'View All Label', 'blossom-shop' ),
            'section'         => 'blog_section',
            'type'            => 'text',
            'active_callback' => 'blossom_shop_blog_view_all_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_view_all', array(
        'selector' => '.blog-section .button-wrap a.bttn',
        'render_callback' => 'blossom_shop_get_blog_view_all',
    ) ); 
    /** Blog Section Ends */

    /** Sort Section */

    $wp_customize->add_section(
        'sort_image_section',
        array(
            'title'    => __( 'Sort Front Page Section', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 111,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'sort_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'sort_text',
            array(
                'section'     => 'sort_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'sort_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'sort_settings',
            array(
                'section'     => 'sort_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/sort.png',
                ),
            )
        )
    );

    /** One Page Starts */

    $wp_customize->add_section(
        'one_page_image_section',
        array(
            'title'    => __( 'One Page Settings', 'blossom-shop' ),
            'panel'    => 'frontpage_settings',
            'priority'    => 112,
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'one_page_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'one_page_text',
            array(
                'section'     => 'one_page_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'one_page_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'one_page_settings',
            array(
                'section'     => 'one_page_image_section',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/one-page.png',
                ),
            )
        )
    );
}
add_action( 'customize_register', 'blossom_shop_customize_register_frontpage' );