<?php
/**
 * Testimonial Section
 * 
 * @package Blossom_Shop
 */

$testimonial_title 			= get_theme_mod( 'testimonial_title', __( 'Our Happy Customers', 'blossom-shop' ) );
$testimonial_subtitle 		= get_theme_mod( 'testimonial_subtitle', __( 'Words of praise by our valuable customers', 'blossom-shop' ) );

if( is_active_sidebar( 'testimonial' ) ){ ?>
<section id="testimonial_section" class="testimonial-section style-two">
	<div class="container">
		<?php if( $testimonial_title || $testimonial_subtitle ) : ?>
			<div class="title-wrap">
				<?php if( $testimonial_title ) echo '<h2 class="section-title">' . esc_html( $testimonial_title ) . '</h2>'; ?>
				<?php if( $testimonial_subtitle ) echo '<div class="section-desc">' . esc_html( $testimonial_subtitle ) . '</div>'; ?>
	    	</div>
	    <?php endif; ?>
    	<div class="section-grid owl-carousel">
    		<?php dynamic_sidebar( 'testimonial' ); ?>
    	</div>
    </div>
</section> <!-- .testimonial-section -->
<?php
}