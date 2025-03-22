<?php
/**
 * Blossom Shop Custom Control
 * 
 * @package Blossom_Shop
*/

if( ! function_exists( 'blossom_shop_register_custom_controls' ) ) :
/**
 * Register Custom Controls
*/
function blossom_shop_register_custom_controls( $wp_customize ){    
    // Load our custom control.
    require_once get_template_directory() . '/inc/custom-controls/note/class-note-control.php';
    require_once get_template_directory() . '/inc/custom-controls/radioimg/class-radio-image-control.php';
    require_once get_template_directory() . '/inc/custom-controls/radiobtn/class-radio-buttonset-control.php';
    require_once get_template_directory() . '/inc/custom-controls/select/class-select-control.php';
    require_once get_template_directory() . '/inc/custom-controls/slider/class-slider-control.php';
    require_once get_template_directory() . '/inc/custom-controls/toggle/class-toggle-control.php';
    require_once get_template_directory() . '/inc/custom-controls/repeater/class-repeater-setting.php';
    require_once get_template_directory() . '/inc/custom-controls/repeater/class-control-repeater.php';
    require_once get_template_directory() . '/inc/custom-controls/typography/class-fonts.php';
    require_once get_template_directory() . '/inc/custom-controls/typography/class-typography-control.php';
            
    // Register the control type.
    $wp_customize->register_control_type( 'Blossom_Shop_Radio_Image_Control' );
    $wp_customize->register_control_type( 'Blossom_Shop_Select_Control' );
    $wp_customize->register_control_type( 'Blossom_Shop_Slider_Control' );
    $wp_customize->register_control_type( 'Blossom_Shop_Toggle_Control' );
    $wp_customize->register_control_type( 'Blossom_Shop_Typography_Control' );
    $wp_customize->register_control_type( 'Blossom_Shop_Radio_Buttonset_Control' );
}
endif;
add_action( 'customize_register', 'blossom_shop_register_custom_controls' );