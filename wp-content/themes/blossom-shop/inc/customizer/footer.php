<?php
/**
 * Footer Setting
 *
 * @package Blossom_Shop
 */

function blossom_shop_customize_register_footer( $wp_customize ) {
    
    $wp_customize->add_section(
        'footer_settings',
        array(
            'title'      => __( 'Footer Settings', 'blossom-shop' ),
            'priority'   => 199,
            'capability' => 'edit_theme_options',
        )
    );

    /** Footer Copyright */
    $wp_customize->add_setting(
        'footer_copyright',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'footer_copyright',
        array(
            'label'       => __( 'Footer Copyright Text', 'blossom-shop' ),
            'section'     => 'footer_settings',
            'type'        => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'footer_copyright', array(
        'selector' => '.site-info .copyright',
        'render_callback' => 'blossom_shop_get_footer_copyright',
    ) );
    
    $wp_customize->add_setting( 'footer_payment_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_shop_sanitize_image',
        )
    );
    
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( $wp_customize, 'footer_payment_image',
            array(
                'label'         => esc_html__( 'Payment Support Image', 'blossom-shop' ),
                'description'   => esc_html__( 'Recommended Image size is 300px by 25px.', 'blossom-shop' ),
                'section'       => 'footer_settings',
                'type'          => 'image'
            )
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'footer_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'footer_text',
            array(
                'section'     => 'footer_settings',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'footer_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'footer_settings',
            array(
                'section'     => 'footer_settings',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/footer.png',
                ),
            )
        )
    );
}
add_action( 'customize_register', 'blossom_shop_customize_register_footer' );