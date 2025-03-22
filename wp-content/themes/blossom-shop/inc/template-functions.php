<?php
/**
 * Blossom Shop Template Functions which enhance the theme by hooking into WordPress
 *
 * @package Blossom_Shop
 */

if( ! function_exists( 'blossom_shop_doctype' ) ) :
/**
 * Doctype Declaration
*/
function blossom_shop_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'blossom_shop_doctype', 'blossom_shop_doctype' );

if( ! function_exists( 'blossom_shop_head' ) ) :
/**
 * Before wp_head 
*/
function blossom_shop_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'blossom_shop_before_wp_head', 'blossom_shop_head' );

if( ! function_exists( 'blossom_shop_page_start' ) ) :
/**
 * Page Start
*/
function blossom_shop_page_start(){ ?>
    <div id="page" class="site">
    <a class="skip-link" href="#content"><?php esc_html_e( 'Skip to Content', 'blossom-shop' ); ?></a>
    <?php
}
endif;
add_action( 'blossom_shop_before_header', 'blossom_shop_page_start', 20 );

if( ! function_exists( 'blossom_shop_sticky_bar' ) ) :
/**
 * Sticky Bar
*/
function blossom_shop_sticky_bar(){ 
    $sticky_enable  = get_theme_mod( 'ed_top_bar', false );
    $sticky_text    = get_theme_mod( 'notification_text', __( 'End of Season SALE now on thru 1/21.','blossom-shop' ) );
    $sticky_button  = get_theme_mod( 'notification_label', __( 'SHOP NOW', 'blossom-shop' ) );
    $sticky_url     = get_theme_mod( 'notification_btn_url', '#' );
    
    if( $sticky_enable && ( $sticky_text || ( $sticky_button && $sticky_url ) ) ) : ?>
        <div class="sticky-t-bar active">
            <div class="sticky-bar-content">
                <div class="container">
                    <span><?php echo esc_html( $sticky_text ); ?></span>
                    <a href="<?php echo esc_url( $sticky_url ); ?>" class="btn-readmore"><?php echo esc_html( $sticky_button ); ?></a>
                </div>
            </div>
            <button class="close"></button>
        </div> <!-- .sticky-t-bar -->
    <?php endif; 
}
endif;
add_action( 'blossom_shop_header', 'blossom_shop_sticky_bar', 10 );

if( ! function_exists( 'blossom_shop_header' ) ) :
/**
 * Header Start
*/
function  blossom_shop_header(){ 

    $ed_cart   = get_theme_mod( 'ed_shopping_cart', true ); 
    ?>

    <header id="masthead" class="site-header header-three" itemscope itemtype="http://schema.org/WPHeader">
        <?php if( has_nav_menu( 'secondary' ) || blossom_shop_social_links( false ) ) : ?>
            <div class="header-t">
                <div class="container">
                    <?php blossom_shop_secondary_navigation(); ?>
                    <?php if( blossom_shop_social_links( false ) ) : ?>
                        <div class="right">
                            <?php blossom_shop_social_links(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- .header-t -->
        <?php endif; ?>
        <div class="header-main">
            <div class="container">
                <?php blossom_shop_site_branding(); ?>
                <?php blossom_shop_primary_nagivation(); ?>
                <div class="right">
                    <?php blossom_shop_header_search(); ?>
                    <?php blossom_shop_user_block(); ?>
                    <?php if( blossom_shop_is_woocommerce_activated() && $ed_cart ) blossom_shop_wc_cart_count(); ?>             
                </div>
            </div>
        </div><!-- .header-main -->
    </header><!-- #masthead -->
<?php }
endif;
add_action( 'blossom_shop_header', 'blossom_shop_header', 20 );

if( ! function_exists( 'blossom_shop_show_banner' ) ) :
/**
 * Display Banner section in Show banner
*/
function blossom_shop_show_banner(){
    
    $ed_banner = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    if( is_home() && $ed_banner ) get_template_part( 'sections/home/banner' );
}
endif;
add_action( 'blossom_shop_after_header', 'blossom_shop_show_banner', 5 );

if( ! function_exists( 'blossom_shop_featured_section' ) ) :
/**
 * Featured Section
*/
function blossom_shop_featured_section(){ 
    if( is_home() && is_active_sidebar( 'featured' ) ) { ?>
        <section class="blog-page-feature-section">
            <div class="container">
                <?php dynamic_sidebar( 'featured' ); ?>
            </div>
        </section> <!-- .blog-page-feature-section -->
    <?php 
    }
}
endif;
add_action( 'blossom_shop_after_header', 'blossom_shop_featured_section', 10 );

if( ! function_exists( 'blossom_shop_content_start' ) ) :
/**
 * Content Start
 *   
*/
function blossom_shop_content_start(){       
    $home_sections      = blossom_shop_get_home_sections();
    $background_image   = blossom_shop_singular_post_thumbnail();
    $shop_background_image   = get_theme_mod( 'shop_background_image', false );
    $add_style_one = '';

    $shop_background_class = ( blossom_shop_is_woocommerce_activated() && is_shop() && $shop_background_image ) ? ' has-bgimg' : '';

    if( blossom_shop_is_woocommerce_activated() && is_shop() && $shop_background_image ) { 
        $add_style_one = 'style="background-image: url(\'' . esc_url( $shop_background_image ) . '\')"' ;
    }

    if ( ! is_front_page() && ! is_home() ) blossom_shop_breadcrumb();

    if( ! ( is_front_page() && ! is_home() && $home_sections ) ){ ?>
        <div id="content" class="site-content">            
        <?php if( ! is_home() && !( blossom_shop_is_woocommerce_activated() && is_product() ) ) : ?>
            <header class="page-header<?php echo esc_attr( $shop_background_class ); ?>" <?php if( is_singular() || is_404() ) : ?> style="background-image: url('<?php echo esc_url( $background_image ); ?>')" <?php endif; ?> <?php echo $add_style_one; ?>>
                <div class="container">
        			<?php        
                        if( is_archive() ){ 
                            if( is_author() ){
                                $author_title = get_the_author_meta( 'display_name' );
                                $author_description = get_the_author_meta( 'description' ); ?>
                                <div class="author-section">
                                    <figure class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?></figure>
                                    <div class="author-content-wrap">
                                        <?php 
                                            echo '<h3 class="author-name">' . esc_html__( 'All Posts By: ','blossom-shop' ) . esc_html( $author_title ) . '</h3>';
                                            echo '<div class="author-content">' . wpautop( wp_kses_post( $author_description ) ) . '</div>';
                                        ?>      
                                    </div>
                                </div>
                                <?php 
                            }
                            else{
        					    the_archive_description( '<span class="sub-title">', '</span>' );
                                the_archive_title();
                            }             
                        }
                        
                        if( is_search() ){ 
                            echo '<span class="sub-title">' . esc_html__( 'SEARCH RESULTS FOR:', 'blossom-shop' ) . '</span>';
                            get_search_form();
                        }
                        
                        if( is_page() ){
                            the_title( '<h1 class="page-title">', '</h1>' );
                        }

                        if( is_404() ) {
                            echo '<h1 class="page-title">' . esc_html__( 'Uh-Oh...','blossom-shop' ) . '</h1>';
                            echo '<div class="page-desc">' . esc_html__( 'The page you are looking for may have been moved, deleted, or possibly never existed.','blossom-shop' ) . '</div>';
                        }

                        if( is_single() ) {
                            blossom_shop_category();
                            the_title( '<h1 class="entry-title">', '</h1>' );
                            if( 'post' === get_post_type() ){
                                echo '<div class="entry-meta">';
                                blossom_shop_posted_on();
                                blossom_shop_comment_count();
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
    		</header>
        <?php endif;  ?>
            <div class="container">
    <?php 
    }
}
endif;
add_action( 'blossom_shop_content', 'blossom_shop_content_start' );

if ( ! function_exists( 'blossom_shop_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function blossom_shop_post_thumbnail() {
	global $wp_query;
    $image_size  = 'thumbnail';
    $sidebar     = blossom_shop_sidebar();
    $image_size  = blossom_shop_blog_layout_image_size(); 
    
    if( is_home() || is_archive() || is_search() ){        
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '">';
        if( has_post_thumbnail() ){                        
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            blossom_shop_get_fallback_svg( $image_size );//fallback    
        }        
        echo '</a></figure>';
    }
}
endif;
add_action( 'blossom_shop_before_post_entry_content', 'blossom_shop_post_thumbnail', 10 );

if( ! function_exists( 'blossom_shop_entry_header' ) ) :
/**
 * Entry Header
*/
function blossom_shop_entry_header(){
    $blog_layout    = get_theme_mod( 'blog_page_layout', 'classic-layout' );

    if( is_single() ) {
        return false;
    } ?>
    <header class="entry-header">
        <?php             
            if( $blog_layout == 'classic-layout' ) echo '<div class="entry-meta">';
            blossom_shop_category();
            if( $blog_layout == 'classic-layout' ) echo '</div>';

            the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );       
            if( 'post' === get_post_type() && $blog_layout != 'classic-layout' ){
                echo '<div class="entry-meta">';
                blossom_shop_posted_on();
                blossom_shop_comment_count();
                echo '</div>';
            }
        ?>
    </header>         
    <?php    
}
endif;
add_action( 'blossom_shop_post_entry_content', 'blossom_shop_entry_header', 10 );

if( ! function_exists( 'blossom_shop_entry_content' ) ) :
/**
 * Entry Content
*/
function blossom_shop_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); ?>
    <div class="entry-content" itemprop="text">
		<?php
			if( is_singular() || ! $ed_excerpt || ( get_post_format() != false ) ){
                the_content();    
    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blossom-shop' ),
    				'after'  => '</div>',
    			) );
            }else{
                the_excerpt();
            }
		?>
	</div><!-- .entry-content -->
    <?php
}
endif;
add_action( 'blossom_shop_page_entry_content', 'blossom_shop_entry_content', 15 );
add_action( 'blossom_shop_post_entry_content', 'blossom_shop_entry_content', 15 );

if( ! function_exists( 'blossom_shop_entry_footer' ) ) :
/**
 * Entry Footer
*/
function blossom_shop_entry_footer(){ 
    $readmore = get_theme_mod( 'read_more_text', __( 'READ MORE', 'blossom-shop' ) ); 
    $blog_layout = get_theme_mod( 'blog_page_layout', 'classic-layout' ); ?>
	<footer class="entry-footer">
		<?php
			if( is_single() ){
			    blossom_shop_tag();
			}
            
            if( is_home() || is_archive() || is_search() ){
                echo '<div class="button-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="btn-readmore">' . esc_html( $readmore ) . '</a></div>';    
            }

            if( 'post' === get_post_type() && $blog_layout == 'classic-layout' && !is_single() ){
                echo '<div class="entry-right">';
                blossom_shop_posted_on();
                blossom_shop_comment_count();
                echo '</div>';
            }
            
            if( get_edit_post_link() ){
                edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'blossom-shop' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
            }
		?>
	</footer><!-- .entry-footer -->
	<?php 
}
endif;
add_action( 'blossom_shop_page_entry_content', 'blossom_shop_entry_footer', 20 );
add_action( 'blossom_shop_post_entry_content', 'blossom_shop_entry_footer', 20 );

if( ! function_exists( 'blossom_shop_navigation' ) ) :
/**
 * Navigation
*/
function blossom_shop_navigation(){
    if( is_single() ){
        $next_post = get_next_post();
        $prev_post = get_previous_post();

        if( $prev_post || $next_post ) { ?>            
            <nav class="post-navigation pagination" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'blossom-shop' ); ?></h2>
                <div class="nav-links">
                    <?php if( $prev_post ){ ?>
                    <div class="nav-previous">
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
                            <span class="meta-nav"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 8"><defs><style>.arla{fill:#999596;}</style></defs><path class="arla" d="M16.01,11H8v2h8.01v3L22,12,16.01,8Z" transform="translate(22 16) rotate(180)"/></svg><?php esc_html_e( 'Previous Post', 'blossom-shop' ); ?></span>
                            <span class="post-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
                        </a>
                        <figure class="post-img">
                            <?php
                            $prev_img = get_post_thumbnail_id( $prev_post->ID );
                            if( $prev_img ){
                                $prev_url = wp_get_attachment_image_url( $prev_img, 'thumbnail' );
                                echo '<img src="' . esc_url( $prev_url ) . '" alt="' . the_title_attribute( 'echo=0', $prev_post ) . '">';                                        
                            }else{
                                blossom_shop_get_fallback_svg( 'thumbnail' );
                            }
                            ?>
                        </figure>
                    </div>
                    <?php } ?>
                    <?php if( $next_post ){ ?>
                    <div class="nav-next">
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
                            <span class="meta-nav"><?php esc_html_e( 'Next Post', 'blossom-shop' ); ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 8"><defs><style>.arra{fill:#999596;}</style></defs><path class="arra" d="M16.01,11H8v2h8.01v3L22,12,16.01,8Z" transform="translate(-8 -8)"/></svg></span>
                            <span class="post-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                        </a>
                        <figure class="post-img">
                            <?php
                            $next_img = get_post_thumbnail_id( $next_post->ID );
                            if( $next_img ){
                                $next_url = wp_get_attachment_image_url( $next_img, 'thumbnail' );
                                echo '<img src="' . esc_url( $next_url ) . '" alt="' . the_title_attribute( 'echo=0', $next_post ) . '">';                                        
                            }else{
                                blossom_shop_get_fallback_svg( 'thumbnail' );
                            }
                            ?>
                        </figure>
                    </div>
                    <?php } ?>
                </div>
            </nav>        
            <?php
        }
    }else{
            
        the_posts_pagination( array(
            'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M152.485 396.284l19.626-19.626c4.753-4.753 4.675-12.484-.173-17.14L91.22 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H91.22l80.717-77.518c4.849-4.656 4.927-12.387.173-17.14l-19.626-19.626c-4.686-4.686-12.284-4.686-16.971 0L3.716 247.515c-4.686 4.686-4.686 12.284 0 16.971l131.799 131.799c4.686 4.685 12.284 4.685 16.97-.001z"></path></svg>',
            'next_text'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M295.515 115.716l-19.626 19.626c-4.753 4.753-4.675 12.484.173 17.14L356.78 230H12c-6.627 0-12 5.373-12 12v28c0 6.627 5.373 12 12 12h344.78l-80.717 77.518c-4.849 4.656-4.927 12.387-.173 17.14l19.626 19.626c4.686 4.686 12.284 4.686 16.971 0l131.799-131.799c4.686-4.686 4.686-12.284 0-16.971L312.485 115.716c-4.686-4.686-12.284-4.686-16.97 0z"></path></svg>',
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'blossom-shop' ) . ' </span>',
        ) );
    }
}
endif;
add_action( 'blossom_shop_after_post_content', 'blossom_shop_navigation', 25 );
add_action( 'blossom_shop_after_posts_content', 'blossom_shop_navigation' );

if( ! function_exists( 'blossom_shop_author' ) ) :
/**
 * Author Section
*/
function blossom_shop_author(){ 
    $ed_author_section = get_theme_mod( 'ed_author', false );
    $author_title = get_the_author_meta( 'display_name' );
    $author_description = get_the_author_meta( 'description' );
    if( !$ed_author_section && $author_title && $author_description ) { ?>
        <div class="author-section">
            <figure class="author-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?></figure>
            <div class="author-content-wrap">
                <?php 
                    echo '<h3 class="author-name">' . esc_html( $author_title ) . '</h3>';
                    echo '<div class="author-content">' . wpautop( wp_kses_post( $author_description ) ) . '</div>';
                ?>      
            </div>
        </div>
    <?php
    }
}
endif;
add_action( 'blossom_shop_after_post_content', 'blossom_shop_author', 20 );

if( ! function_exists( 'blossom_shop_related_posts' ) ) :
/**
 * Related Posts 
*/
function blossom_shop_related_posts(){ 
    $ed_related_post = get_theme_mod( 'ed_related', true );
    
    if( $ed_related_post ){
        blossom_shop_get_posts_list( 'related' );    
    }
}
endif;                                                                               
add_action( 'blossom_shop_after_post_content', 'blossom_shop_related_posts', 35 );

if( ! function_exists( 'blossom_shop_latest_posts' ) ) :
/**
 * Latest Posts
*/
function blossom_shop_latest_posts(){ 
    blossom_shop_get_posts_list( 'latest' );
}
endif;
add_action( 'blossom_shop_latest_posts', 'blossom_shop_latest_posts' );

if( ! function_exists( 'blossom_shop_comment' ) ) :
/**
 * Comments Template 
*/
function blossom_shop_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if( get_theme_mod( 'ed_comments', true ) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
}
endif;
add_action( 'blossom_shop_after_post_content', 'blossom_shop_comment', 45 );
add_action( 'blossom_shop_after_page_content', 'blossom_shop_comment' );

if( ! function_exists( 'blossom_shop_content_end' ) ) :
/**
 * Content End
*/
function blossom_shop_content_end(){ 
    $home_sections = blossom_shop_get_home_sections(); 
    if( ! ( is_front_page() && ! is_home() && $home_sections ) ){ ?>            
        </div><!-- .container -->        
    </div><!-- .site-content -->
    <?php
    }
}
endif;
add_action( 'blossom_shop_before_footer', 'blossom_shop_content_end', 20 );

if( ! function_exists( 'blossom_shop_instagram' ) ) :
/**
 * Blossom Instagram
*/
function blossom_shop_instagram(){
   
    $ed_instagram = get_theme_mod( 'ed_instagram', false );
    $insta_code   = get_theme_mod( 'instagram_shortcode', '[instagram-feed]' );

    if( $ed_instagram ){
        echo '<div class="instagram-section">';
        echo do_shortcode( $insta_code );
        echo '</div>';    
    }
    
}
endif;
add_action( 'blossom_shop_before_footer_start', 'blossom_shop_instagram', 10 );

if( ! function_exists( 'blossom_shop_newsletter' ) ) :
/**
 * Blossom Newsletter
*/
function blossom_shop_newsletter(){
    $ed_newsletter = get_theme_mod( 'ed_newsletter', false );
    $newsletter = get_theme_mod( 'newsletter_shortcode' );
    if( $ed_newsletter && !empty( $newsletter ) ){
        echo '<section class="newsletter-section">';
        echo do_shortcode( $newsletter );   
        echo '</section>';            
    }
}
endif;
add_action( 'blossom_shop_before_footer_start', 'blossom_shop_newsletter', 20 );

if( ! function_exists( 'blossom_shop_footer_start' ) ) :
/**
 * Footer Start
*/
function blossom_shop_footer_start(){
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'blossom_shop_footer', 'blossom_shop_footer_start', 10 );

if( ! function_exists( 'blossom_shop_footer_two' ) ) :
/**
 * Footer Top
*/
function blossom_shop_footer_two(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }
                 
    if( $active_sidebars ){ ?>
        <div class="footer-t">
    		<div class="container">
    			<div class="grid column-<?php echo esc_attr( $sidebar_count ); ?>">
                <?php foreach( $active_sidebars as $active ){ ?>
    				<div class="col">
    				   <?php dynamic_sidebar( $active ); ?>	
    				</div>
                <?php } ?>
                </div>
    		</div>
    	</div>
        <?php 
    }
}
endif;
add_action( 'blossom_shop_footer', 'blossom_shop_footer_two', 30 );

if( ! function_exists( 'blossom_shop_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function blossom_shop_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
			<div class="site-info">            
            <?php
                blossom_shop_get_footer_copyright();
                echo esc_html__( ' Blossom Shop | Developed By ', 'blossom-shop' ); 
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'blossom-shop' ) . '</a>.';                
                printf( esc_html__( ' Powered by %s. ', 'blossom-shop' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'blossom-shop' ) ) .'" target="_blank">WordPress</a>' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
            <?php 
                blossom_shop_payment_method();
                blossom_shop_back_to_top(); 
            ?>
		</div>
	</div>
    <?php
}
endif;
add_action( 'blossom_shop_footer', 'blossom_shop_footer_bottom', 40 );

if( ! function_exists( 'blossom_shop_footer_end' ) ) :
/**
 * Footer End 
*/
function blossom_shop_footer_end(){ ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'blossom_shop_footer', 'blossom_shop_footer_end', 50 );

if( ! function_exists( 'blossom_shop_page_end' ) ) :
/**
 * Page End
*/
function blossom_shop_page_end(){ ?>
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'blossom_shop_after_footer', 'blossom_shop_page_end', 20 );

if( ! function_exists( 'blossom_shop_posts_per_page_count' ) ):
/**
*   Counts the Number of total posts in Archive, Search and Author
*/
function blossom_shop_posts_per_page_count(){
    global $wp_query;
    if( is_archive() || is_search() && $wp_query->found_posts > 0 ) {
        $posts_per_page = get_option( 'posts_per_page' );
        $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
        $start_post_number = 0;
        $end_post_number   = 0;

        if( $wp_query->found_posts > 0 && !( blossom_shop_is_woocommerce_activated() && is_shop() ) ):                
            $start_post_number = 1;
            if( $wp_query->found_posts < $posts_per_page  ) {
                $end_post_number = $wp_query->found_posts;
            }else{
                $end_post_number = $posts_per_page;
            }

            if( $paged > 1 ){
                $start_post_number = $posts_per_page * ( $paged - 1 ) + 1;
                if( $wp_query->found_posts < ( $posts_per_page * $paged )  ) {
                    $end_post_number = $wp_query->found_posts;
                }else{
                    $end_post_number = $paged * $posts_per_page;
                }
            }

            printf( esc_html__( '%1$s Showing:  %2$s - %3$s of %4$s RESULTS %5$s', 'blossom-shop' ), '<span class="post-count">', absint( $start_post_number ), absint( $end_post_number ), esc_html( number_format_i18n( $wp_query->found_posts ) ), '</span>' );
        endif;
    }
}
endif; 
add_action( 'blossom_shop_before_posts_content' , 'blossom_shop_posts_per_page_count', 10 );