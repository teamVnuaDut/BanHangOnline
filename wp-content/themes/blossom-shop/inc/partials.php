<?php
/**
 * Blossom Shop Customizer Partials
 *
 * @package Blossom_Shop
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blossom_shop_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blossom_shop_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if( ! function_exists( 'blossom_shop_get_slider_readmore' ) ) :
/**
 * Slider Readmore
*/
function blossom_shop_get_slider_readmore(){
    return esc_html( get_theme_mod( 'slider_readmore', __( 'Continue Reading', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_banner_title' ) ) :
/**
 * Banner Title
*/
function blossom_shop_get_banner_title(){
    return esc_html( get_theme_mod( 'banner_title', __( 'Find Your Best Holiday', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_banner_subtitle' ) ) :
/**
 * Banner SubTitle
*/
function blossom_shop_get_banner_subtitle(){
    return esc_html( get_theme_mod( 'banner_subtitle', __( 'Find great adventure holidays and activities around the planet.', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_banner_label' ) ) :
/**
 * Banner Label
*/
function blossom_shop_get_banner_label(){
    return esc_html( get_theme_mod( 'banner_label', __( 'Purchase', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_read_more' ) ) :
/**
 * Display blog readmore button
*/
function blossom_shop_get_read_more(){
    return esc_html( get_theme_mod( 'read_more_text', __( 'Continue Reading', 'blossom-shop' ) ) );    
}
endif;

if( ! function_exists( 'blossom_shop_get_related_title' ) ) :
/**
 * Display blog readmore button
*/
function blossom_shop_get_related_title(){
    return esc_html( get_theme_mod( 'related_post_title', __( 'Recommended Articles', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_sticky_text' ) ) :
/**
 * Display header sticky text
*/
function blossom_shop_get_sticky_text(){
    return esc_html( get_theme_mod( 'notification_text', __( 'End of Season SALE now on thru 1/21.','blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_sticky_button' ) ) :
/**
 * Display header sticky button
*/
function blossom_shop_get_sticky_button(){
    return esc_html( get_theme_mod( 'notification_label', __( 'SHOP NOW', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_recent_product_title' ) ) :
/**
 * Display Recent Product Title
*/
function blossom_shop_get_recent_product_title(){
    return esc_html( get_theme_mod( 'recent_product_title', __( 'New Arrivals', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_recent_product_subtitle' ) ) :
/**
 * Display Recent Product SubTitle
*/
function blossom_shop_get_recent_product_subtitle(){
    return esc_html( get_theme_mod( 'recent_product_subtitle', __( 'Add our new arrivals to your weekly lineup.', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_cat_one_title' ) ) :
/**
 * Display Category One Title
*/
function blossom_shop_get_cat_one_title(){
    return esc_html( get_theme_mod( 'cat_one_title', __( 'Best Sellers', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_cat_one_subtitle' ) ) :
/**
 * Display Category One SubTitle
*/
function blossom_shop_get_cat_one_subtitle(){
    return esc_html( get_theme_mod( 'cat_one_subtitle', __( 'Check out our best sellers products.', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_cat_one_featured_title' ) ) :
/**
 * Display Category One Featured Title
*/
function blossom_shop_get_cat_one_featured_title(){
    return esc_html( get_theme_mod( 'cat_one_featured_title', __( 'STREET TRENDING 2019', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_cat_one_featured_subtitle' ) ) :
/**
 * Display Category One Featured SubTitle
*/
function blossom_shop_get_cat_one_featured_subtitle(){
    return esc_html( get_theme_mod( 'cat_one_featured_subtitle', __( 'SUMMER EXCLUSIVE COLLECTION', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_cat_one_featured_label' ) ) :
/**
 * Display Category One Featured Label
*/
function blossom_shop_get_cat_one_featured_label(){
    return esc_html( get_theme_mod( 'cat_one_featured_label', __( 'DISCOVER NOW', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_cat_one_all' ) ) :
/**
 * Display Category One View All
*/
function blossom_shop_get_cat_one_all(){
    return esc_html( get_theme_mod( 'cat_one_all', __( 'SHOP ALL PRODUCTS', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_testimonial_title' ) ) :
/**
 * Display Testimonial Title
*/
function blossom_shop_get_testimonial_title(){
    return esc_html( get_theme_mod( 'testimonial_title', __( 'Our Happy Customers', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_testimonial_subtitle' ) ) :
/**
 * Display Testimonial SubTitle
*/
function blossom_shop_get_testimonial_subtitle(){
    return esc_html( get_theme_mod( 'testimonial_subtitle', __( 'Words of praise by our valuable customers', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_blog_section_title' ) ) :
/**
 * Display Blog Title
*/
function blossom_shop_get_blog_section_title(){
    return esc_html( get_theme_mod( 'blog_section_title', __( 'Our Blog', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_blog_section_subtitle' ) ) :
/**
 * Display Blog SubTitle
*/
function blossom_shop_get_blog_section_subtitle(){
    return esc_html( get_theme_mod( 'blog_section_subtitle', __( 'Our recent articles about fashion ideas products.', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_blog_readmore' ) ) :
/**
 * Display Blog Readmore
*/
function blossom_shop_get_blog_readmore(){
    return esc_html( get_theme_mod( 'blog_readmore', __( 'READ MORE', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_blog_view_all' ) ) :
/**
 * Display Blog View All
*/
function blossom_shop_get_blog_view_all(){
    return esc_html( get_theme_mod( 'blog_view_all', __( 'READ ALL POSTS', 'blossom-shop' ) ) );
}
endif;

if( ! function_exists( 'blossom_shop_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function blossom_shop_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );
    echo '<span class="copyright">';
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'blossom-shop' );
        echo date_i18n( esc_html__( 'Y', 'blossom-shop' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'blossom-shop' );
    }
    echo '</span>'; 
}
endif;