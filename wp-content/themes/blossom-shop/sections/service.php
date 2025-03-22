<?php
/**
 * Service Section
 * 
 * @package Blossom_Shop
 */

if( is_active_sidebar( 'service' ) ){ ?>
<section id="service_section" class="top-service-section style-three has-bg">
	<div class="container">
    	<?php dynamic_sidebar( 'service' ); ?>
	</div>
</section> <!-- .top-service-section -->
<?php
}