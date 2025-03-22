<?php
/**
 * Active Callback
 * 
 * @package Blossom_Shop
*/

/**
 * Active Callback for Banner Slider
*/
function blossom_shop_banner_ac( $control ){
    $banner      = $control->manager->get_setting( 'ed_banner_section' )->value();
    $slider_type = $control->manager->get_setting( 'slider_type' )->value();
    $control_id  = $control->id;
    
    if ( $control_id == 'header_image' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'header_video' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'external_header_video' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'banner_title' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_subtitle' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_label' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_link' && $banner == 'static_banner' ) return true;
    
    if ( $control_id == 'slider_type' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_auto' && $banner == 'slider_banner' ) return true;          
    if ( $control_id == 'slider_readmore' && $banner == 'slider_banner' ) return true;    
    if ( $control_id == 'slider_cat' && $banner == 'slider_banner' && $slider_type == 'cat' ) return true;
    if ( $control_id == 'no_of_slides' && $banner == 'slider_banner' && $slider_type == 'latest_posts' ) return true;
    if ( $control_id == 'slider_animation' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'hr' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'banner_caption_align' && $banner == 'static_banner' ) return true;
    
    return false;
}

/**
 * Active Callback for Blog View All Button
*/
function blossom_shop_blog_view_all_ac( $control ){
    $ed_blog   = $control->manager->get_setting( 'ed_blog_section' )->value();                                         
    $blog = get_option( 'page_for_posts' );
    if( $ed_blog && $blog ) return true;
    
    return false; 
}

/**
 * Active Callback for post/page
*/
function blossom_shop_post_page_ac( $control ){
    
    $ed_related = $control->manager->get_setting( 'ed_related' )->value();
    $control_id = $control->id;
    
    if ( $control_id == 'related_post_title' && $ed_related == true ) return true;
    if ( $control_id == 'ed_featured_image' ) return true;
    
    return false;
}

/**
 * Active Callback for recent product
*/
function blossom_shop_recent_product_ac( $control ){
    
    $ed_recent_product   = $control->manager->get_setting( 'ed_recent_product_section' )->value();
    
    if ( $ed_recent_product ) return true;
    
    return false;
}

/**
 * Active Callback for featured content
*/
function blossom_shop_featured_ac( $control ){
    
    $ed_featured_area   = $control->manager->get_setting( 'ed_featured_section' )->value();
    $featured_type      = $control->manager->get_setting( 'featured_type' )->value();
    $control_id         = $control->id;

    if( $control_id == 'featured_type' && $ed_featured_area ) return true;
    if( $control_id == 'featured_content_one' && ( $featured_type == 'feat_page' && $ed_featured_area ) ) return true;
    if( $control_id == 'featured_content_two' && ( $featured_type == 'feat_page' && $ed_featured_area ) ) return true;
    if( $control_id == 'featured_content_three' && ( $featured_type == 'feat_page' && $ed_featured_area ) ) return true;
    if( $control_id == 'cat_featured_one' && ( $featured_type == 'feat_cat' && $ed_featured_area ) ) return true;
    if( $control_id == 'cat_featured_two' && ( $featured_type == 'feat_cat' && $ed_featured_area ) ) return true;
    if( $control_id == 'cat_featured_three' && ( $featured_type == 'feat_cat' && $ed_featured_area ) ) return true;
    
    return false;
}

/**
 * Active Callback for blog content
*/
function blossom_shop_blog_ac( $control ){
    
    $ed_blog   = $control->manager->get_setting( 'ed_blog_section' )->value();    
    if ( $ed_blog ) return true;
    
    return false;
}

/**
 * Active Callback for category one content
*/
function blossom_shop_cat_one_ac( $control ){
    
    $ed_cat_one      = $control->manager->get_setting( 'ed_cat_one_section' )->value();
    
    if ( $ed_cat_one ) return true;

    return false;
}

/**
 * Active Callback for Top Bar.
*/
function blossom_shop_topbar_ac( $control ){
    
    $ed_top_bar    = $control->manager->get_setting( 'ed_top_bar' )->value();
    $control_id    = $control->id;
    
    if ( $control_id == 'notification_text' && $ed_top_bar ) return true;
    if ( $control_id == 'notification_label' && $ed_top_bar ) return true;
    if ( $control_id == 'notification_btn_url' && $ed_top_bar ) return true;

    return false;
}

/**
 * Active Callback for local fonts
*/
function blossom_shop_ed_localgoogle_fonts(){
    $ed_localgoogle_fonts = get_theme_mod( 'ed_localgoogle_fonts' , false );

    if( $ed_localgoogle_fonts ) return true;
    
    return false; 
}

/**
 * Active Callback for Instagram.
*/
function blossom_shop_ed_instagram(){
    
    $ed_instagram = get_theme_mod( 'ed_instagram', false );

    if ( $ed_instagram ) return true;

    return false;
}