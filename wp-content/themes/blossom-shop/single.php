<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blossom_Shop
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_type() );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
        
        <?php
        /**  
         * @hooked blossom_shop_author               - 20
         * @hooked blossom_shop_navigation           - 25
         * @hooked blossom_shop_related_posts        - 35
         * @hooked blossom_shop_comment              - 45
        */
        do_action( 'blossom_shop_after_post_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();