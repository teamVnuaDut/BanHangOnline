<?php
/**
 * CTA Section
 * 
 * @package Blossom_Shop
 */

if( is_active_sidebar( 'cta' ) ){ ?>
	<section id="cta_section" class="cta-section style-one">
		<?php dynamic_sidebar( 'cta' ); ?>
	</section> <!-- .cta-section -->
	<?php 
}