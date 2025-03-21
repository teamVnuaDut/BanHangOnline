<?php
if ( !function_exists( 'envo_one_main_content_width_columns' ) ) {

	/**
	 * Set the content width based on enabled sidebar
	 */
	function envo_one_main_content_width_columns() {

		$columns		 = '12';
		$hide_sidebar	 = get_post_meta( get_the_ID(), 'envo_hide_sidebar', true );
		if ( is_active_sidebar( 'envo-one-right-sidebar' ) && is_singular() && $hide_sidebar == 'on' ) {
			$columns = '12';
		} elseif ( is_active_sidebar( 'envo-one-right-sidebar' ) ) {
			$columns = $columns - 3;
		}

		echo absint( $columns );
	}

}

if ( !function_exists( 'envo_one_single_generate_content' ) ) :

	/**
	 * Generate single content
	 */
	add_action( 'envo_one_single_content_area', 'envo_one_single_generate_content' );

	function envo_one_single_generate_content() {
		?>
		<div class="row single-post">      
			<article class="envo-content col-md-<?php envo_one_main_content_width_columns(); ?>">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>                         
						<div <?php post_class( 'single-post-content' ); ?>>
							<?php
							$contents = get_theme_mod( 'single_layout', array( 'image', 'title', 'meta', 'content', 'cats_tags', 'nav', 'comments' ) );

							// Loop parts.
							foreach ( $contents as $content ) {
								do_action( 'envo_one_single_' . $content );
							}
							?>
						</div>
					<?php endwhile; ?>        
				<?php else : ?>            
					<?php get_template_part( 'content', 'none' ); ?>        
				<?php endif; ?>    
			</article> 
			<?php do_action( 'envo_one_sidebar' ); ?>
		</div>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_page_generate_content' ) ) :

	/**
	 * Generate single content
	 */
	add_action( 'envo_one_page_content_area', 'envo_one_page_generate_content' );

	function envo_one_page_generate_content() {
		?>
		<div class="row single-page">
			<article class="envo-content col-md-<?php envo_one_main_content_width_columns(); ?>">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>                          
						<div <?php post_class('single-page-content'); ?>>
							<?php do_action( 'envo_one_page_content' ); ?>
						</div>
					<?php endwhile; ?>        
				<?php else : ?>            
					<?php get_template_part( 'content', 'none' ); ?>        
				<?php endif; ?>    
			</article>       
			<?php do_action( 'envo_one_sidebar' ); ?>
		</div>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_archive_generate_content' ) ) :

	/**
	 * Generate single content
	 */
	add_action( 'envo_one_archive_content_area', 'envo_one_archive_generate_content' );

	function envo_one_archive_generate_content() {
		?>
		<div class="row">
			<div class="envo-content col-md-<?php envo_one_main_content_width_columns(); ?>">
				<?php if ( have_posts() ) : ?>
					<header class="archive-page-header text-center">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
						?>
					</header>
					<?php
				endif;
				do_action( 'envo_one_generate_the_content' );
				?>
			</div>
			<?php get_sidebar( 'right' ); ?>
		</div>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_featured_image' ) ) :

	/**
	 * Generate featured image.
	 */
	add_action( 'envo_one_single_image', 'envo_one_featured_image', 10 );
	add_action( 'envo_one_archive_image', 'envo_one_featured_image', 10 );
	add_action( 'envo_one_page_content', 'envo_one_featured_image', 10 );

	function envo_one_featured_image() {
		if ( is_singular() ) {
			envo_one_thumb_img( 'envo-one-img', '', false, true );
		} else {
			envo_one_thumb_img( 'envo-one-img' );
		}
	}

endif;

if ( !function_exists( 'envo_one_title' ) ) :

	/**
	 * Generate title.
	 */
	add_action( 'envo_one_single_title', 'envo_one_title', 20 );
	add_action( 'envo_one_archive_title', 'envo_one_title', 20 );
	add_action( 'envo_one_page_content', 'envo_one_title', 20 );

	function envo_one_title() {
		$title = get_post_meta( get_the_ID(), 'envo_hide_title', true );
		if ( $title != 'on' ) {
			?>
			<div class="single-head">
				<?php
				if ( is_singular() ) {
					the_title( '<h1 class="single-title">', '</h1>' );
				} else {
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				}
				?> 
				<time class="posted-on published" datetime="<?php the_time( 'Y-m-d' ); ?>"></time>
			</div>
			<?php
		}
	}

endif;

if ( !function_exists( 'envo_one_meta_before' ) ) :

	/**
	 * Div for meta
	 */
	add_action( 'envo_one_single_meta', 'envo_one_meta_before', 25 );
	add_action( 'envo_one_archive_meta', 'envo_one_meta_before', 25 );

	function envo_one_meta_before() {
		?>
		<div class="article-meta">
			<?php
		}

	endif;
	if ( !function_exists( 'envo_one_meta_after' ) ) :

		/**
		 * Div for meta
		 */
		add_action( 'envo_one_single_meta', 'envo_one_meta_after', 55 );
		add_action( 'envo_one_archive_meta', 'envo_one_meta_after', 55 );

		function envo_one_meta_after() {
			?>
		</div>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_date' ) ) :

	/**
	 * Returns date.
	 */
	add_action( 'envo_one_single_meta', 'envo_one_date', 30 );
	add_action( 'envo_one_archive_meta', 'envo_one_date', 30 );

	function envo_one_date() {
		?>
		<span class="posted-date">
			<?php echo esc_html( get_the_date() ); ?>
		</span>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_author_meta' ) ) :

	/**
	 * Post author meta funciton
	 */
	add_action( 'envo_one_single_meta', 'envo_one_author_meta', 40 );
	add_action( 'envo_one_archive_meta', 'envo_one_author_meta', 40 );

	function envo_one_author_meta() {
		?>
		<span class="author-meta">
			<span class="author-meta-by"><?php esc_html_e( 'By', 'envo-one' ); ?></span>
			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>">
				<?php the_author(); ?>
			</a>
		</span>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_comments' ) ) :

	/**
	 * Returns comments.
	 */
	add_action( 'envo_one_single_meta', 'envo_one_comments', 50 );
	add_action( 'envo_one_archive_meta', 'envo_one_comments', 50 );

	function envo_one_comments() {
		?>
		<span class="comments-meta">
			<?php
			if ( !comments_open() ) {
				esc_html_e( 'Off', 'envo-one' );
			} else {
				?>
				<a href="<?php the_permalink(); ?>#comments" rel="nofollow" title="<?php esc_attr_e( 'Comment on ', 'envo-one' ) . the_title_attribute(); ?>">
					<?php echo absint( get_comments_number() ); ?>
				</a>
			<?php } ?>
			<i data-feather="message-square" class="la la-comments-o"></i>
		</span>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_post_author' ) ) :

	/**
	 * Returns post author
	 */
	add_action( 'envo_one_construct_post_author', 'envo_one_post_author' );

	function envo_one_post_author() {
		?>
		<div class="postauthor-container">			  
			<div class="postauthor-title">
				<h4 class="about">
					<?php esc_html_e( 'About The Author', 'envo-one' ); ?>
				</h4>
				<div class="">
					<span class="fn">
						<?php the_author_posts_link(); ?>
					</span>
				</div> 				
			</div>        	
			<div class="postauthor-content">	             						           
				<p>
					<?php the_author_meta( 'description' ) ?>
				</p>					
			</div>	 		
		</div>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_content' ) ) :

	/**
	 * Generate content.
	 */
	add_action( 'envo_one_single_content', 'envo_one_content', 60 );
	add_action( 'envo_one_page_content', 'envo_one_content', 60 );

	function envo_one_content() {
		?>
		<div class="single-content">
			<div class="single-entry-summary">
				<?php do_action( 'envo_one_before_content' ); ?> 
				<?php the_content(); ?>
				<?php do_action( 'envo_one_after_content' ); ?> 
			</div>
			<?php wp_link_pages(); ?>
		</div>
		<?php
		if ( get_edit_post_link() ) {
			edit_post_link();
		}
	}

endif;

if ( !function_exists( 'envo_one_excerpt' ) ) :

	/**
	 * Generate content.
	 */
	add_action( 'envo_one_archive_excerpt', 'envo_one_excerpt', 60 );

	function envo_one_excerpt() {
		?>
		<div class="post-excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php
	}

endif;

if ( !function_exists( 'envo_one_top_bar' ) ) :

	/**
	 * Returns top bar
	 */
	add_action( 'envo_one_construct_top_bar', 'envo_one_top_bar' );

	function envo_one_top_bar() {
		if ( is_active_sidebar( 'envo-one-top-bar-area' ) ) {
			?>
			<div class="top-bar-section container-fluid">
				<div class="<?php echo esc_attr( get_theme_mod( 'top_bar_content_width', 'container' ) ); ?>">
					<div class="row">
						<?php dynamic_sidebar( 'envo-one-top-bar-area' ); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}

endif;

if ( !function_exists( 'envo_one_generate_construct_the_content' ) ) :
	/**
	 * Build footer widgets
	 */
	add_action( 'envo_one_generate_the_content', 'envo_one_generate_construct_the_content' );

	function envo_one_generate_construct_the_content() {
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;
			the_posts_pagination();
		else :
			get_template_part( 'content', 'none' );
		endif;
	}

endif;

if ( !function_exists( 'envo_one_prev_next_links' ) ) :

	/**
	 * Single previous next links
	 */
	add_action( 'envo_one_single_nav', 'envo_one_prev_next_links', 70 );

	function envo_one_prev_next_links() {
		the_post_navigation(
		array(
			'prev_text'	 => '<span class="screen-reader-text">' . __( 'Previous Post', 'envo-one' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'envo-one' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper"><i data-feather="chevrons-left" class="la la-angle-double-left" aria-hidden="true"></i></span>%title</span>',
			'next_text'	 => '<span class="screen-reader-text">' . __( 'Next Post', 'envo-one' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'envo-one' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper"><i data-feather="chevrons-right" class="la la-angle-double-right" aria-hidden="true"></i></span></span>',
		)
		);
	}

endif;

if ( !function_exists( 'envo_one_generate_construct_author_comments' ) ) :
	/**
	 * Build author and comments area
	 */
	add_action( 'envo_one_single_comments', 'envo_one_generate_construct_author_comments', 80 );

	function envo_one_generate_construct_author_comments() {
		$authordesc = get_the_author_meta( 'description' );
		if ( !empty( $authordesc ) ) {
			?>
			<div class="single-footer row">
				<div class="col-md-4">
					<?php do_action( 'envo_one_construct_post_author' ); ?> 
				</div>
				<div class="col-md-8">
					<?php comments_template(); ?> 
				</div>
			</div>
		<?php } else { ?>
			<div class="single-footer">
				<?php comments_template(); ?> 
			</div>
			<?php
		}
	}

endif;

if ( !function_exists( 'envo_one_generate_sidebar' ) ) :
	/**
	 * Build author and comments area
	 */
	add_action( 'envo_one_sidebar', 'envo_one_generate_sidebar' );

	function envo_one_generate_sidebar() {
		$hide_sidebar = get_post_meta( get_the_ID(), 'envo_hide_sidebar', true );
		if ( $hide_sidebar != 'on' ) {
			get_sidebar( 'right' );
		}
	}

endif;

if ( !function_exists( 'envo_one_excerpt_more' ) ) :

	/**
	 * Excerpt more.
	 */
	function envo_one_excerpt_more( $more ) {
		return '&hellip;';
	}

	add_filter( 'excerpt_more', 'envo_one_excerpt_more' );

endif;

if ( !function_exists( 'envo_one_thumb_img' ) ) :

	/**
	 * Returns featured image.
	 */
	function envo_one_thumb_img( $img = 'full', $col = '', $link = true, $single = false ) {
		if ( ( has_post_thumbnail() && $link == true ) ) {
			?>
			<div class="news-thumb <?php echo esc_attr( $col ); ?>">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( $img ); ?>
				</a>
			</div><!-- .news-thumb -->
		<?php } elseif ( has_post_thumbnail() ) { ?>
			<div class="news-thumb <?php echo esc_attr( $col ); ?>">
				<?php the_post_thumbnail( $img ); ?>
			</div><!-- .news-thumb -->	
			<?php
		}
	}

endif;

if ( !function_exists( 'envo_one_entry_footer' ) ) :

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	add_action( 'envo_one_single_cats_tags', 'envo_one_entry_footer' );

	function envo_one_entry_footer() {

		// Get Categories for posts.
		$categories_list = get_the_category_list( ' ' );

		// Get Tags for posts.
		$tags_list = get_the_tag_list( '', ' ' );

		// We don't want to output .entry-footer if it will be empty, so make sure its not.
		if ( $categories_list || $tags_list ) {

			echo '<div class="entry-footer">';

			if ( 'post' === get_post_type() ) {
				if ( $categories_list || $tags_list ) {

					// Make sure there's more than one category before displaying.
					if ( $categories_list ) {
						echo '<div class="cat-links"><span class="space-right">' . esc_html__( 'Category', 'envo-one' ) . '</span>' . wp_kses_data( $categories_list ) . '</div>';
					}

					if ( $tags_list ) {
						echo '<div class="tags-links"><span class="space-right">' . esc_html__( 'Tags', 'envo-one' ) . '</span>' . wp_kses_data( $tags_list ) . '</div>';
					}
				}
			}

			echo '</div>';
		}
	}

endif;

if ( !function_exists( 'envo_one_generate_construct_footer_widgets' ) ) :
	/**
	 * Build footer widgets
	 */
	add_action( 'envo_one_generate_footer', 'envo_one_generate_construct_footer_widgets', 10 );

	function envo_one_generate_construct_footer_widgets() {
		if ( is_active_sidebar( 'envo-one-footer-area' ) ) {
			?>  				
			<div id="content-footer-section" class="container-fluid clearfix">
				<div class="container">
					<?php dynamic_sidebar( 'envo-one-footer-area' ); ?>
				</div>	
			</div>		
			<?php
		}
	}

endif;

if ( !function_exists( 'envo_one_generate_construct_footer' ) ) :
	/**
	 * Build footer
	 */
	add_action( 'envo_one_generate_footer', 'envo_one_generate_construct_footer', 20 );

	function envo_one_generate_construct_footer() {
		?>
		<footer id="colophon" class="footer-credits container-fluid">
			<div class="container">    
				<div class="footer-credits-text text-center list-unstyled">
					<?php
					/* translators: %1$s: Envo One theme author (do not translate) with envothemes.com URL */
					printf( esc_html__( 'Theme by %1$s', 'envo-one' ), '<a href="' . esc_url( 'https://envothemes.com/' ) . '">' . esc_html_x( 'EnvoThemes', 'Theme author', 'envo-one' ) . '</a>' );
					?>
				</div>
			</div>	
		</footer>
		<?php
	}

endif;
