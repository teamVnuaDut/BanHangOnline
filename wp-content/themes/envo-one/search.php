<?php get_header(); ?>
<div class="row">
    <div class="envo-content col-md-<?php envo_one_main_content_width_columns(); ?>">
		<h1 class='search-head text-center'>
			<?php
			/* translators: %s: Search results */
			printf(esc_html__('Results for "%s"', 'envo-one'), esc_html( get_search_query() ))
			?>
		</h1>
        <?php do_action('envo_one_generate_the_content'); ?>
    </div>
    <?php get_sidebar('right'); ?>
</div>
<?php 
get_footer();
