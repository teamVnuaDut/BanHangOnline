<?php

get_header();

if ( is_single() ) {
	do_action( 'envo_one_single_content_area' );
} else {
	do_action( 'envo_one_page_content_area' );
}

get_footer();
