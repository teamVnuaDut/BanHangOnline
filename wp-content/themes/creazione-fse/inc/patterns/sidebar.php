<?php 
/**
 * Default Sidebar
 */
return array(
	'title'      => esc_html__( 'Sidebar', 'creazione-fse' ),
	'categories' => array( 'creazione-fse', 'sidebar' ),
	'content'    => '<!-- wp:group {"className":"sidebar-blog","layout":{"type":"default"}} -->
<div class="wp-block-group sidebar-blog"><!-- wp:group {"className":"st-widgetBX","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"10px","width":"0px","style":"none"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group st-widgetBX" style="border-style:none;border-width:0px;border-radius:10px;margin-bottom:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"700"}},"textColor":"heading","fontFamily":"archivo"} -->
<h2 class="wp-block-heading has-heading-color has-text-color has-link-color has-archivo-font-family" style="font-size:30px;font-style:normal;font-weight:700">Latest Posts</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":70,"featuredImageSizeHeight":70} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"st-widgetBX","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group st-widgetBX" style="border-radius:10px;margin-bottom:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"700"}},"textColor":"heading","fontFamily":"archivo"} -->
<h2 class="wp-block-heading has-heading-color has-text-color has-link-color has-archivo-font-family" style="font-size:30px;font-style:normal;font-weight:700">Categories</h2>
<!-- /wp:heading -->

<!-- wp:categories {"showPostCounts":true} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"st-widgetBX","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group st-widgetBX" style="border-radius:10px;margin-bottom:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"700"}},"textColor":"heading","fontFamily":"archivo"} -->
<h2 class="wp-block-heading has-heading-color has-text-color has-link-color has-archivo-font-family" style="font-size:30px;font-style:normal;font-weight:700">Archives</h2>
<!-- /wp:heading -->

<!-- wp:archives /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"st-widgetBX","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group st-widgetBX" style="border-radius:10px;margin-bottom:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"700"}},"textColor":"heading","fontFamily":"archivo"} -->
<h2 class="wp-block-heading has-heading-color has-text-color has-link-color has-archivo-font-family" style="font-size:30px;font-style:normal;font-weight:700">Latest Comments</h2>
<!-- /wp:heading -->

<!-- wp:latest-comments {"displayExcerpt":false} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"st-widgetBX","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"margin":{"bottom":"var:preset|spacing|60"}},"border":{"radius":"10px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group st-widgetBX" style="border-radius:10px;margin-bottom:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:heading {"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading"}}},"typography":{"fontSize":"30px","fontStyle":"normal","fontWeight":"700"}},"textColor":"heading","fontFamily":"archivo"} -->
<h2 class="wp-block-heading has-heading-color has-text-color has-link-color has-archivo-font-family" style="font-size:30px;font-style:normal;font-weight:700">Follow Us</h2>
<!-- /wp:heading -->

<!-- wp:social-links {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
<ul class="wp-block-social-links"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"x"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"linkedin"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);