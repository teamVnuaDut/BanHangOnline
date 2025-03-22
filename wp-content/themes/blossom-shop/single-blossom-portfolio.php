<?php
/**
 * Single Portfolio
*/
get_header();

while ( have_posts() ) : the_post(); ?>
    
    <div class="portfolio-holder">    	
        <div class="entry-content">
    		<?php the_content(); ?>
    	</div><!-- .entry-content -->
        
    </div>
    <?php 
    
    blossom_shop_navigation();
     
    $args = array(
        'post_type'      => 'blossom-portfolio',
        'posts_per_page' => 3,
        'post__not_in'   => array( get_the_ID() ),
        'orderby'        => 'rand'
    );
    
    $qry = new WP_Query( $args );
    if( $qry->have_posts() ){ ?>    
        <div class="related-portfolio">
        	<div class="related-portfolio-title"><?php esc_html_e( 'Related Projects', 'blossom-shop' ); ?></div>
            <div class="portfolio-img-holder">
        		<?php 
                    while( $qry->have_posts() ){ 
                        $qry->the_post();
                        $related_image_size = 'blossom-shop-blog-list'; ?>
                        <div class="portfolio-item">
                    		<div class="portfolio-item-inner">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if( has_post_thumbnail() ){
                                        the_post_thumbnail( $related_image_size );
                                    }else {
                                        blossom_shop_get_fallback_svg( $related_image_size );
                                    } ?>
                                </a>
                				<div class="portfolio-text-holder">
                					<?php 
                                        $term_list = get_the_term_list( get_the_ID(), 'blossom_portfolio_categories' );
                                        if( $term_list ) echo '<div class="portfolio-cat">' . $term_list . '</div>';
                                        
                                        the_title( '<div class="portfolio-img-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></div>' ); 
                                    ?>
                				</div>
                    		</div>
                    	</div>
                        <?php 
                    } 
                ?>
        	</div>
        </div>
    <?php
    }
    wp_reset_postdata();
        
endwhile; 
get_footer();