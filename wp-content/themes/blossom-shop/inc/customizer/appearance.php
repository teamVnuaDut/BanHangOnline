<?php
/**
 * Appearance Settings
 *
 * @package Blossom_Shop
 */

function blossom_shop_customize_register_appearance( $wp_customize ) {
    
    $wp_customize->add_panel( 
        'appearance_settings', 
        array(
            'title'       => __( 'Appearance Settings', 'blossom-shop' ),
            'priority'    => 25,
            'capability'  => 'edit_theme_options',
            'description' => __( 'Customize Typography, Background Image & Color.', 'blossom-shop' ),
        ) 
    );

    /** Primary Color*/
    $wp_customize->add_setting( 
        'primary_color', 
        array(
            'default'           => '#dde9ed',
            'sanitize_callback' => 'sanitize_hex_color',
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'primary_color', 
            array(
                'label'       => __( 'Primary Color', 'blossom-shop' ),
                'description' => __( 'Primary color of the theme.', 'blossom-shop' ),
                'section'     => 'colors',
                'priority'    => 5,
            )
        )
    );
    
    /** Secondary Color*/
    $wp_customize->add_setting( 
        'secondary_color', 
        array(
            'default'           => '#ee7f4b',
            'sanitize_callback' => 'sanitize_hex_color'
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'secondary_color', 
            array(
                'label'       => __( 'Secondary Color', 'blossom-shop' ),
                'description' => __( 'Secondary color of the theme.', 'blossom-shop' ),
                'section'     => 'colors',
            )
        )
    );
    
    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'blossom-shop' ),
            'priority' => 20,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default'           => 'Nunito Sans',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'primary_font',
            array(
                'label'       => __( 'Primary Font', 'blossom-shop' ),
                'description' => __( 'Primary font of the site.', 'blossom-shop' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_shop_get_all_fonts(),  
            )
        )
    );
    
    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'Cormorant',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __( 'Secondary Font', 'blossom-shop' ),
                'description' => __( 'Secondary font of the site.', 'blossom-shop' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_shop_get_all_fonts(),  
            )
        )
    );

    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Load Google Fonts Locally', 'blossom-shop' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'blossom-shop' )
            )
        )
    );   

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Preload Local Fonts', 'blossom-shop' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'blossom-shop' ),
                'active_callback' => 'blossom_shop_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'blossom-shop' ); ?></span>
        
        <input type="button" class="button button-primary blossom-shop-flush-local-fonts-button" name="blossom-shop-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'blossom-shop' ); ?>" />
    <?php
    $blossom_shop_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'blossom-shop' ),
            'section'       => 'typography_settings',
            'description'   => $blossom_shop_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'blossom_shop_ed_localgoogle_fonts'
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'typography_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Note_Control( 
            $wp_customize,
            'typography_text',
            array(
                'section'     => 'typography_settings',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'blossom-shop' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/blossom-shop-pro/?utm_source=blossom-shop&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );


    $wp_customize->add_setting( 
        'typography_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'typography_settings',
            array(
                'section'     => 'typography_settings',
                'choices'     => array(
                    'one'       => get_template_directory_uri() . '/images/pro/typography.png',
                ),
            )
        )
    );
    
    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'colors' )->panel              = 'appearance_settings';
    $wp_customize->get_section( 'colors' )->priority           = 10;
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 15;  
}
add_action( 'customize_register', 'blossom_shop_customize_register_appearance' );