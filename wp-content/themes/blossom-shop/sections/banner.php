<?php
/**
 * Banner Section
 * 
 * @package Blossom_Shop
 */

$ed_banner        = get_theme_mod( 'ed_banner_section', 'slider_banner' );
$slider_type      = get_theme_mod( 'slider_type', 'latest_posts' ); 
$slider_cat       = get_theme_mod( 'slider_cat' );
$posts_per_page   = get_theme_mod( 'no_of_slides', 3 );
$ed_caption       = get_theme_mod( 'slider_caption', true );
$read_more        = get_theme_mod( 'slider_readmore', __( 'SHOP NEW ARRIVALS', 'blossom-shop' ) );
$banner_title     = get_theme_mod( 'banner_title', __( 'Find Your Best Holiday', 'blossom-shop' ) );
$banner_subtitle  = get_theme_mod( 'banner_subtitle', __( 'Find great adventure holidays and activities around the planet.', 'blossom-shop' ) );
$banner_label     = get_theme_mod( 'banner_label', __( 'Purchase', 'blossom-shop' ) );
$banner_link      = get_theme_mod( 'banner_link', '#' );
$caption_align    = get_theme_mod( 'banner_caption_align', 'left-align' );
$image_size       = 'blossom-shop-slider';
       
if( ( $ed_banner == 'static_banner' ) && has_custom_header() ){ 
    if( has_header_video() ) {
        $custom_header_class = ' video-banner';
    }else{
        $custom_header_class = ' static-banner';
    } ?>
    <div id="banner_section" class="site-banner<?php echo esc_attr( $custom_header_class ); ?> <?php echo esc_attr( $caption_align ); ?>">
        <?php 
            the_custom_header_markup(); 
                if( $ed_banner == 'static_banner' && ( $banner_title || $banner_subtitle || ( $banner_label && $banner_link ) ) ){
                    echo '<div class="banner-caption"><div class="container">';
                    if( $banner_title ) echo '<h2 class="banner-title">' . esc_html( $banner_title ) . '</h2>';
                    if( $banner_subtitle ) echo '<div class="banner-desc">' . esc_html( $banner_subtitle ) . '</div>';
            		if( $banner_label && $banner_link ) echo '<a class="btn-readmore" href="' . esc_url( $banner_link ) . '">' . esc_html( $banner_label ) . '</a>';
                    echo '</div></div>';
                }  
        ?>
    </div>
<?php
}elseif( $ed_banner == 'slider_banner' ){
    if( $slider_type == 'latest_posts' || $slider_type == 'cat' ){
        $args = array(            
            'ignore_sticky_posts' => true,
            'post_type'           => 'post',
        );
        
        if( $slider_type === 'cat' && $slider_cat ){
            $args['cat']            = $slider_cat; 
            $args['posts_per_page'] = -1;  
        }else{
            $args['posts_per_page'] = $posts_per_page;
        }
            
        $qry = new WP_Query( $args );
        
        if( $qry->have_posts() ){ ?>
            <div id="banner_section" class="site-banner banner-one">
                <div class="item-wrap owl-carousel">            
        			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                    <div class="item left">
        				<?php 
                        if( has_post_thumbnail() ){
        				    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        				}else{ 
        				    blossom_shop_get_fallback_svg( $image_size );//fallback
                        } ?>                        
        				<div class="banner-caption left">
        					<div class="container">
        						<div class="text-holder">
        							<?php
                                        blossom_shop_category_slider();
                                        the_title( '<h2 class="banner-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                                        if( $read_more ) echo '<div class="button-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="btn-readmore">' . esc_html( $read_more ) . '</a></div>';                              
                                    ?>
        						</div>
        					</div>
        				</div>
        			</div>
        			<?php } ?>
                </div>                                        
            </div>
        <?php
        }
        wp_reset_postdata();
    
    }
}