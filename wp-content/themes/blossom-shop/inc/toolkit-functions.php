<?php
/**
 * Toolkit Filters
 *
 * @package Blossom_Shop
 */

if( ! function_exists( 'blossom_shop_default_image_text_size' ) ) :
    function blossom_shop_default_image_text_size(){
        return 'blossom-shop-featured';
    }
endif;
add_filter( 'bttk_it_img_size', 'blossom_shop_default_image_text_size' );

if( ! function_exists( 'blossom_shop_author_image' ) ) :
   function blossom_shop_author_image(){
       return 'blossom-shop-blog-list';
   }
endif;
add_filter( 'author_bio_img_size', 'blossom_shop_author_image' );

if( ! function_exists( 'blossom_shop_defer_js_files' ) ) :
    function blossom_shop_defer_js_files(){
        $defer_js = get_theme_mod( 'ed_defer', false );

        return ( $defer_js ) ? false : true;

    }
endif;
add_filter( 'bttk_public_assets_enqueue', 'blossom_shop_defer_js_files' );

if( ! function_exists( 'blossom_shop_testimonial_widget_filter' ) ) :
/**
 * Filter for Featured page widget
*/
function blossom_shop_testimonial_widget_filter( $html, $args, $instance ){
    $obj = new BlossomThemes_Toolkit_Functions();
    $name   = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
    $designation   = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;        
    $testimonial = ! empty( $instance['testimonial'] ) ? $instance['testimonial'] : '';
    $image   = ! empty( $instance['image'] ) ? $instance['image'] : '';

    if( $image ){
        /** Added to work for demo testimonial compatible */
        $attachment_id = $image;
        if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
            $attachment_id = $obj->bttk_get_attachment_id( $image );
        }
    }
    
    ob_start(); ?>    
        <div class="bttk-testimonial-holder">
            <div class="bttk-testimonial-inner-holder">        
                <div class="text-holder">
                    <?php if( $image ){ ?>
                        <div class="img-holder">
                            <?php echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, 
                                        array( 'alt' => esc_attr( $name )));?>
                        </div>
                    <?php }?>
                    <div class="testimonial-meta">
                       <?php 
                            if( $name ) echo '<span class="name">' . esc_html( $name ) . '</span>';
                            if( isset( $designation ) && $designation!='' ){
                                echo '<span class="designation">' . esc_html( $designation ) . '</span>';
                            }
                        ?>
                    </div>                              
                    <?php if( $testimonial ) echo '<div class="testimonial-content">' . wpautop( wp_kses_post( $testimonial ) ) . '</div>'; ?>
                </div>
            </div>
        </div>
    <?php 
    $html = ob_get_clean();
    return $html;
}
endif;
add_filter( 'blossom_testimonial_widget_filter', 'blossom_shop_testimonial_widget_filter', 10, 3 );