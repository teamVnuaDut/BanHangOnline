<?php
/**
 *
 * Template name: No Sidebar
 * 
 */
get_header();
?>
<div class="row single-page no-sidebar">
    <article class="col-md-12">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>                          
				<div <?php post_class(); ?>>
					<?php do_action( 'envo_one_page_content' ); ?>
				</div>
				<?php
			endwhile;
		else :
			get_template_part( 'content', 'none' );
		endif;
		?>    
    </article>
</div>
<?php
get_footer();
