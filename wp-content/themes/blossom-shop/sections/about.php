<?php
/**
 * About Section
 * 
 * @package Blossom_Shop
 */

if( is_active_sidebar( 'about' ) ){ ?>
<section id="about_section" class="about-section style-one">
	<div class="container">
    	<?php dynamic_sidebar( 'about' ); ?>
	</div>
</section><!-- .about-section -->
<?php
}