<?php get_header(); ?>
<div class="row">
    <div class="envo-content col-md-<?php envo_one_main_content_width_columns(); ?>">
        <?php do_action('envo_one_generate_the_content'); ?>
    </div>
    <?php get_sidebar('right'); ?>		
</div>
<?php 
get_footer();
