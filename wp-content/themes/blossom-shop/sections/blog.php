<?php
/**
 * Blog Section
 * 
 * @package Blossom_Shop
 */

$sec_title    = get_theme_mod( 'blog_section_title', __( 'Our Blog', 'blossom-shop' ) );
$sub_title  = get_theme_mod( 'blog_section_subtitle', __( 'Our recent articles about fashion ideas products.', 'blossom-shop' ) );
$readmore = get_theme_mod( 'blog_readmore', __( 'READ MORE', 'blossom-shop' ) );
$blog     = get_option( 'page_for_posts' );
$label    = get_theme_mod( 'blog_view_all', __( 'READ ALL POSTS', 'blossom-shop' ) );

$ed_crop_all    = get_theme_mod( 'ed_crop_all', false );
$image_size = ( $ed_crop_all ) ? 'full' : 'blossom-shop-blog-list';

$args = array(
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true
);

$qry = new WP_Query( $args );

if( $sec_title || $sub_title || $qry->have_posts() ){ ?>

<section id="blog_section" class="blog-section">
	<div class="container">
        
        <?php if( $sec_title || $sub_title ){ ?>
            <div class="title-wrap">	
                <?php 
                    if( $sec_title ) echo '<h2 class="section-title">' . esc_html( $sec_title ) . '</h2>';
                    if( $sub_title ) echo '<div class="section-desc">' . esc_html( $sub_title ) . '</div>'; 
                ?>
    		</div>
        <?php } ?>
        
        <?php if( $qry->have_posts() ){ ?>
            <div class="section-grid">
    			<?php 
                while( $qry->have_posts() ){
                    $qry->the_post(); ?>
                    <article class="post">
        				<figure class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                            <?php 
                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                                }else{ 
                                    blossom_shop_get_fallback_svg( $image_size );//fallback
                                }                            
                            ?>                        
                            </a>
                        </figure>
    					<header class="entry-header">
                            <div class="entry-meta">
                                <?php blossom_shop_category(); ?>
                            </div>
    						<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php if( $readmore ) : ?> 
                                <a href="<?php the_permalink(); ?>" class="btn-readmore"><?php echo esc_html( $readmore ); ?></a>
                            <?php endif; ?> 
    					</header>
        			</article>			
        			<?php 
                }
                ?>
    		</div>
    		
            <?php if( $blog && $label ){ ?>
                <div class="button-wrap">
        			<a href="<?php the_permalink( $blog ); ?>" class="bttn"><?php echo esc_html( $label ); ?></a>
        		</div>
            <?php } ?>
        
        <?php } 
        wp_reset_postdata(); ?>
	</div>
</section>
<?php 
}