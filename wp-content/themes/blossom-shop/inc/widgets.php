<?php
/**
 * Blossom Shop Widget Areas
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package Blossom_Shop
 */

function blossom_shop_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'blossom-shop' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'blossom-shop' ),
        ),
        'service' => array(
            'name'        => __( 'Service Section', 'blossom-shop' ),
            'id'          => 'service', 
            'description' => __( 'Add "Text" and "Blossom: Icon Text" widget for service section.', 'blossom-shop' ),
        ),
        'about' => array(
            'name'        => __( 'About Section', 'blossom-shop' ),
            'id'          => 'about', 
            'description' => __( 'Add "Blossom: Featured Page" widget for about section.', 'blossom-shop' ),
        ),
        'cta' => array(
            'name'        => __( 'Call To Action Section', 'blossom-shop' ),
            'id'          => 'cta', 
            'description' => __( 'Add "Blossom: Call To Action" widget for Call to Action section.', 'blossom-shop' ),
        ),
        'testimonial' => array(
            'name'        => __( 'Testimonial Section', 'blossom-shop' ),
            'id'          => 'testimonial', 
            'description' => __( 'Add "Blossom: Testimonial" widget for testimonial section.', 'blossom-shop' ),
        ),
        'client' => array(
            'name'        => __( 'Client Section', 'blossom-shop' ),
            'id'          => 'client', 
            'description' => __( 'Add "Blossom: Client Logo" widget for client section.', 'blossom-shop' ),
        ),
        'featured' => array(
            'name'        => __( 'Blog Featured Section', 'blossom-shop' ),
            'id'          => 'featured', 
            'description' => __( 'Add "Blossom: Image Text" widget for featured section. It is recommended to upload the same dimension for all the featured boxes to avoid inconsistency.', 'blossom-shop' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'blossom-shop' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'blossom-shop' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'blossom-shop' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'blossom-shop' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'blossom-shop' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'blossom-shop' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'blossom-shop' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'blossom-shop' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }

}
add_action( 'widgets_init', 'blossom_shop_widgets_init' );