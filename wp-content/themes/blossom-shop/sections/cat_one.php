<?php
/**
 * Category One Section
 * 
 * @package Blossom_Shop
 */

if( blossom_shop_is_woocommerce_activated() ) {
    global $product;
    $sec_title    	  = get_theme_mod( 'cat_one_title', __( 'Best Sellers', 'blossom-shop' ) );
    $sub_title        = get_theme_mod( 'cat_one_subtitle', __( 'Check out our best sellers products.', 'blossom-shop' ) );
    $cat_one_select   = get_theme_mod( 'cat_one_select' ); 
    $featured_image   = get_theme_mod( 'cat_one_featured_image' ); 
    $feat_title 	  = get_theme_mod( 'cat_one_featured_title', __( 'STREET TRENDING 2019','blossom-shop' ) );
    $feat_subtitle    = get_theme_mod( 'cat_one_featured_subtitle', __( 'SUMMER EXCLUSIVE COLLECTION','blossom-shop' ) );
    $feat_url 		  = get_theme_mod( 'cat_one_featured_url', '#' );
    $feat_label		  = get_theme_mod( 'cat_one_featured_label', __( 'DISCOVER NOW','blossom-shop' ) );

    $label    = get_theme_mod( 'cat_one_all', __( 'SHOP ALL PRODUCTS', 'blossom-shop' ) );
    
    $ed_crop_all    = get_theme_mod( 'ed_crop_all', false );
    $image_size = ( $ed_crop_all ) ? 'full' : 'blossom-shop-recent';

    if( $cat_one_select ) {

        $cat_one_term = get_term( $cat_one_select, 'product_cat' );
        $term_count = $cat_one_term->count;
        $posts_per_page   = ( $term_count > 4 ) ? 4 : $term_count;
        
        $args = array(
            'post_type'           => 'product',            
            'posts_per_page'      => $posts_per_page,
            'tax_query'           => array( 
                array(
                    'taxonomy'          => 'product_cat',
                    'terms'             => $cat_one_select,
                    'include_children'  => false,
                ),
            ),
        );
        
    	$qry_cat_one = new WP_Query( $args ); 
        
    	if( $qry_cat_one->have_posts() ){ ?>
    		<section id="cat_one_section" class="first-cat-section style-three">
    			<div class="container">
    				<?php if( $sec_title || $sub_title ){ ?>
    	            	<div class="cat-wrap">	
    		                <?php
    			            if( $sec_title ) echo '<h2 class="section-title">' . esc_html( $sec_title ) . '</h2>';
    			            if( $sub_title ) echo '<div class="section-desc">' . esc_html( $sub_title ) . '</div>'; 
    		        		?>
    		    		</div>
    		        <?php }				
    				 
    				if( $featured_image || $feat_title || $feat_subtitle || ( $feat_url && $feat_label ) ) { ?>
    					<div class="cat-feature">
    						<?php if( $featured_image ) echo '<img src="' . esc_url( $featured_image ) .'" alt="' . esc_attr( $feat_title ) . '"/>'; ?>
    						<div class="product-title-wrap">
                                <?php
                                if( $feat_title || $feat_subtitle ){ ?>
        			            	<div class="cat-wrap">	
        				                <?php
        					            if( $feat_title ) echo '<h4 class="pp-title">' . esc_html( $feat_title ) . '</h2>';
        					            if( $feat_subtitle ) echo '<div class="pp-desc">' . esc_html(  $feat_subtitle ) . '</div>'; 
        				        		?>
        				    		</div>
        				        <?php }
        				        if( $feat_url && $feat_label ){ ?>
        							<div class="button-wrap">
        			        			<a href="<?php echo esc_url( $feat_url ); ?>" class="btn-readmore"><?php echo esc_html( $feat_label ); ?></a>
        			        		</div>
        				        <?php } ?>
                            </div>
    					</div>
    				<?php } ?> 
    	            <div class="cat-grid">
                    	<?php
                        while( $qry_cat_one->have_posts() ){
                            $qry_cat_one->the_post(); global $product; ?>
                            <div class="item">
                            	<?php
                                    $stock = get_post_meta( get_the_ID(), '_stock_status', true );
                                    
                                    if( $stock == 'outofstock' ){
                                        echo '<span class="outofstock">' . esc_html__( 'Sold Out', 'blossom-shop' ) . '</span>';
                                    }else{
                                        woocommerce_show_product_sale_flash();    
                                    }
                                ?>	                            
                                <div class="cat-image">
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
                    
                    <?php if( $cat_one_select && $label ){ ?>
                        <div class="button-wrap">
                            <a href="<?php echo esc_url( get_category_link( $cat_one_select ) ); ?>" class="btn-readmore"><?php echo esc_html( $label ); ?></a>
                        </div>
                    <?php } ?>		        
    			</div>
    		</section> <!-- .first-cat-section -->
    	<?php 
    	}
        wp_reset_postdata();
    }
}