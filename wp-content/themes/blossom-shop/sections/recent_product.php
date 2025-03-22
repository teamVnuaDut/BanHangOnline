<?php
/**
 * Recent Products Section
 * 
 * @package Blossom_Shop
 */
if( blossom_shop_is_woocommerce_activated() ) {
	
	$sec_title    	= get_theme_mod( 'recent_product_title', __( 'New Arrivals', 'blossom-shop' ) );
	$sub_title  = get_theme_mod( 'recent_product_subtitle', __( 'Add our new arrivals to your weekly lineup.', 'blossom-shop' ) );

	$ed_crop_all    = get_theme_mod( 'ed_crop_all', false );
    $image_size = ( $ed_crop_all ) ? 'full' : 'blossom-shop-recent';

	$args = array(
        'post_type'           => 'product',                        
        'ignore_sticky_posts' => true,
        'posts_per_page'	  => get_theme_mod( 'no_of_products', 5 ),  
    );
    
	$qry = new WP_Query( $args );

	if( $qry->have_posts() ) { ?>

		<section id="recent_product_section" class="recent-prod-section style-one">
			<div class="container">
				<?php if( $sec_title || $sub_title ){ ?>
	            	<div class="recent-prod-wrap">	
		                <?php
			            if( $sec_title ) echo '<h2 class="section-title">' . esc_html( $sec_title ) . '</h2>';
			            if( $sub_title ) echo '<div class="section-desc">' . esc_html( $sub_title ) . '</div>'; 
		        		?>
		    		</div>
		        <?php } ?>

	            <div class="recent-prod-grid">
	                <div class="recent-prod-slider owl-carousel">
	                <?php
	                    while( $qry->have_posts() ){
	                        $qry->the_post(); global $product; ?>
	                        <div class="item">
	                        	<?php
	                                $stock = get_post_meta( get_the_ID(), '_stock_status', true );
	                                
	                                if( $stock == 'outofstock' ){
	                                    echo '<span class="outofstock">' . esc_html__( 'Sold Out', 'blossom-shop' ) . '</span>';
	                                }else{
	                                    woocommerce_show_product_sale_flash();    
	                                }
	                            ?>	                            
	                            <div class="recent-prod-image">
	                                <a href="<?php the_permalink(); ?>" rel="bookmark">
	                                    <?php 
	                                    if( has_post_thumbnail() ){
	                                        the_post_thumbnail( $image_size );    
	                                    }else{
	                                        blossom_shop_get_fallback_svg( $image_size );
	                                    }
	                                    ?>
	                                </a>
	                                <?php woocommerce_template_loop_add_to_cart(); ?>
	                            </div>
	                            
	                            <?php                            
	                            the_title( '<h3><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); 
	                            echo wc_get_rating_html( $product->get_average_rating() );                                  
	                            woocommerce_template_single_price(); //price                                
	                            
	                        	?>
	                        </div>
	                        <?php
	                    }
	                    ?>
	                </div>
	            </div>
			</div>
		</section> <!-- .recent-prod-section -->
	<?php }
	wp_reset_postdata();
}