<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Blossom_Shop
 */
    /**
     * Doctype Hook
     * 
     * @hooked blossom_shop_doctype
    */
    do_action( 'blossom_shop_doctype' );
?>
<head itemscope itemtype="http://schema.org/WebSite">
	<?php 
    /**
     * Before wp_head
     * 
     * @hooked blossom_shop_head
    */
    do_action( 'blossom_shop_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

<?php
    
    wp_body_open();
    
    /**
     * Before Header
     *  
     * @hooked blossom_shop_page_start - 20 
    */
    do_action( 'blossom_shop_before_header' );
    
    /**
     * Header
     * @hooked blossom_shop_sticky_bar - 10
     * @hooked blossom_shop_header - 20     
    */
    do_action( 'blossom_shop_header' );
    
    /**
     * Before Content
     * 
     * @hooked blossom_shop_show_banner - 5
     * @hooked blossom_shop_featured_section - 10
    */
    do_action( 'blossom_shop_after_header' );
    
    /**
     * Content
     * 
     * @hooked blossom_shop_content_start
    */
    do_action( 'blossom_shop_content' );