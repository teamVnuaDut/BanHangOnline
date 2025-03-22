<?php
/**
 * Front Page
 * 
 * @package Blossom_Shop
 */

$home_sections = blossom_shop_get_home_sections();

if ( 'posts' == get_option( 'show_on_front' ) ) { //Show Static Blog Page
    include( get_home_template() );
}elseif( $home_sections ){ 
    get_header();
    //If any one section are enabled then show custom home page.
    echo '<div id="content" class="site-content">';
    foreach( $home_sections as $section ){
        get_template_part( 'sections/' . esc_attr( $section ) );  
    }
    echo '</div>';
    get_footer();
}else {
    //If all section are disabled then show respective page template. 
    include( get_page_template() );
}