<?php
/**
 * Blossom Shop Custom functions and definitions
 *
 * @package Blossom_Shop
 */

if ( ! function_exists( 'blossom_shop_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blossom_shop_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Blossom Shop, use a find and replace
	 * to change 'blossom-shop' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'blossom-shop', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary', 'blossom-shop' ),
        'secondary' => esc_html__( 'Secondary', 'blossom-shop' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-list',
		'gallery',
		'caption',
	) );
    
    // Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'blossom_shop_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
    
	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 
        'custom-logo', 
        apply_filters( 
            'blossom_shop_custom_logo_args', 
            array( 
                'height'      => 70,
                'width'       => 70,
                'flex-height' => true,
                'flex-width'  => true,
                'header-text' => array( 'site-title', 'site-description' ) 
            )
        ) 
    );
    
    /**
     * Add support for custom header.
    */
    add_theme_support( 
        'custom-header', 
        apply_filters( 
            'blossom_shop_custom_header_args', 
            array(
        		'default-image' => get_template_directory_uri() . '/images/banner-img.jpg',
                'video'         => true,
        		'width'         => 1920,
        		'height'        => 806,
        		'header-text'   => false
            ) 
        ) 
    );

    // Register default headers.
    register_default_headers( array(
        'default-banner' => array(
            'url'           => '%s/images/banner-img.jpg',
            'thumbnail_url' => '%s/images/banner-img.jpg',
            'description'   => esc_html_x( 'Default Banner', 'header image description', 'blossom-shop' ),
        ),
    ) );
 
    /**
     * Add Custom Images sizes.
    */
    add_image_size( 'blossom-shop-blog', 829, 623, true );
    add_image_size( 'blossom-shop-blog-full', 1220, 623, true );
    add_image_size( 'blossom-shop-blog-list', 398, 297, true );
    add_image_size( 'blossom-shop-slider', 1751, 726, true );
    add_image_size( 'blossom-shop-featured', 860, 860, true );
    add_image_size( 'blossom-shop-recent', 540, 810, true );

    
    /** Starter Content */
    $starter_content = array(
        // Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array( 
            'home', 
            'blog', 
        ),
		
        // Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),
        
        // Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'primary' => array(
				'name' => __( 'Primary', 'blossom-shop' ),
				'items' => array(
					'page_home',
					'page_blog',
				)
			)
		),
    );
    
    $starter_content = apply_filters( 'blossom_shop_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
    
    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );

    // Add excerpt support for pages
    add_post_type_support( 'page', 'excerpt' );

    
    // Remove widget block.
    remove_theme_support( 'widgets-block-editor' );
}
endif;
add_action( 'after_setup_theme', 'blossom_shop_setup' );

if( ! function_exists( 'blossom_shop_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blossom_shop_content_width() {
	
    $GLOBALS['content_width'] = apply_filters( 'blossom_shop_content_width', 830 );
}
endif;
add_action( 'after_setup_theme', 'blossom_shop_content_width', 0 );

if( ! function_exists( 'blossom_shop_template_redirect_content_width' ) ) :
/**
* Adjust content_width value according to template.
*
* @return void
*/
function blossom_shop_template_redirect_content_width(){
	$sidebar = blossom_shop_sidebar();
    if( $sidebar ){	   
        $GLOBALS['content_width'] = 830;      
	}else{
        if( is_singular() ){
            if( blossom_shop_sidebar( true ) === 'full-width centered' ){
                $GLOBALS['content_width'] = 830;
            }else{
                $GLOBALS['content_width'] = 1220;                
            }                
        }else{
            $GLOBALS['content_width'] = 1220;
        }
	}
}
endif;
add_action( 'template_redirect', 'blossom_shop_template_redirect_content_width' );

if( ! function_exists( 'blossom_shop_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function blossom_shop_scripts() {
	// Use minified libraries if SCRIPT_DEBUG is false
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
        
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri(). '/css' . $build . '/owl.carousel' . $suffix . '.css', array(), '2.3.4' );
    wp_enqueue_style( 'animate', get_template_directory_uri(). '/css' . $build . '/animate' . $suffix . '.css', array(), '3.5.2' );
    if ( get_theme_mod( 'ed_localgoogle_fonts', false ) && ! is_customize_preview() && ! is_admin() && get_theme_mod( 'ed_preload_local_fonts', false ) ) {
        blossom_shop_preload_local_fonts( blossom_shop_fonts_url() );
    }
    wp_enqueue_style( 'blossom-shop-google-fonts', blossom_shop_fonts_url(), array(), null );
    wp_enqueue_style( 'blossom-shop-megamenu', get_template_directory_uri(). '/css' . $build . '/megamenu' . $suffix . '.css', array(), BLOSSOM_SHOP_THEME_VERSION );
    wp_enqueue_style( 'blossom-shop', get_stylesheet_uri(), array(), BLOSSOM_SHOP_THEME_VERSION );
    
    wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '6.1.1', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery', 'all' ), '6.1.1', true );
	
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js' . $build . '/owl.carousel' . $suffix . '.js', array( 'jquery' ), '2.3.4', true );
    wp_enqueue_script( 'owlcarousel2-a11ylayer', get_template_directory_uri() . '/js' . $build . '/owlcarousel2-a11ylayer' . $suffix . '.js', array( 'jquery', 'owl-carousel' ), '0.2.1', true );
	wp_enqueue_script( 'blossom-shop', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery' ), BLOSSOM_SHOP_THEME_VERSION, true );
    wp_enqueue_script( 'blossom-shop-modal', get_template_directory_uri() . '/js' . $build . '/modal-accessibility' . $suffix . '.js', array( 'jquery' ), BLOSSOM_SHOP_THEME_VERSION, true );

    $array = array( 
        'rtl'           => is_rtl(),
        'auto'          => (bool)get_theme_mod( 'slider_auto', true ),
        'animation'     => esc_attr( get_theme_mod( 'slider_animation' ) ),
    );
    
    wp_localize_script( 'blossom-shop', 'blossom_shop_data', $array );
      
    if ( blossom_shop_is_jetpack_activated( true ) ) {
        wp_enqueue_style( 'tiled-gallery', plugins_url() . '/jetpack/modules/tiled-gallery/tiled-gallery/tiled-gallery.css' );            
    }
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'blossom_shop_scripts' );

if( ! function_exists( 'blossom_shop_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function blossom_shop_admin_scripts( $hook ){    
    if( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'user-new.php' || $hook == 'user-edit.php' || $hook == 'profile.php' ){
        wp_enqueue_style( 'blossom-shop-admin', get_template_directory_uri() . '/inc/css/admin.css', '', BLOSSOM_SHOP_THEME_VERSION );
    }
}
endif; 
add_action( 'admin_enqueue_scripts', 'blossom_shop_admin_scripts' );

if( ! function_exists( 'blossom_shop_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blossom_shop_body_classes( $classes ) {
    
    // Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
    if ( get_background_image() ) {
        $classes[] = 'custom-background-image';
    }
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
        $classes[] = 'custom-background-color';
    }
    
    $classes[] = blossom_shop_sidebar( true );

    if( is_home() || is_search() || is_archive() ){
        $classes[] = get_theme_mod( 'blog_page_layout', 'classic-layout' );
    }

    if( is_single() || is_page() ){
        $classes[] = 'underline';
    }

    if( blossom_shop_is_woocommerce_activated() && is_product() ){
        $classes[] = 'bsp-style-one';
    }
    
	return $classes;
}
endif;
add_filter( 'body_class', 'blossom_shop_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function blossom_shop_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'blossom_shop_pingback_header' );

if( ! function_exists( 'blossom_shop_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function blossom_shop_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );    
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'blossom-shop' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'blossom-shop' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'blossom-shop' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'blossom-shop' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'blossom-shop' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'blossom-shop' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'blossom_shop_change_comment_form_default_fields' );

if( ! function_exists( 'blossom_shop_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function blossom_shop_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'blossom-shop' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'blossom-shop' ) . '" cols="45" rows="8" aria-required="true"></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'blossom_shop_change_comment_form_defaults' );

if ( ! function_exists( 'blossom_shop_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function blossom_shop_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}

endif;
add_filter( 'excerpt_more', 'blossom_shop_excerpt_more' );

if ( ! function_exists( 'blossom_shop_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function blossom_shop_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod( 'excerpt_length', 55 );
    return is_admin() ? $length : absint( $excerpt_length );    
}
endif;
add_filter( 'excerpt_length', 'blossom_shop_excerpt_length', 999 );

if( ! function_exists( 'blossom_shop_get_the_archive_title' ) ) :
/**
 * Filter Archive Title
*/
function blossom_shop_get_the_archive_title( $title ){

    $ed_prefix = get_theme_mod( 'ed_prefix_archive', true );
    if( is_post_type_archive( 'product' ) ){
        $title = '<h1 class="page-title">' . get_the_title( get_option( 'woocommerce_shop_page_id' ) ) . '</h1>';
    }else{
        if( is_category() ){
            if( $ed_prefix ) {
                $title = '<h1 class="page-title">' . esc_html( single_cat_title( '', false ) ) . '</h1>';
            }else{
                $title = '<span class="sub-title">'. esc_html__( 'Category Archives','blossom-shop' ) . '</span><h1 class="page-title">' . esc_html( single_cat_title( '', false ) ) . '</h1>';
            }
        }
        elseif( is_tag() ){
            if( $ed_prefix ) {
                $title = '<h1 class="page-title">' . esc_html( single_tag_title( '', false ) ) . '</h1>';
            }else{
                $title = '<span class="sub-title">'. esc_html__( 'Tags Archives','blossom-shop' ) . '</span><h1 class="page-title">' . esc_html( single_tag_title( '', false ) ) . '</h1>';
            }
        }elseif( is_year() ){
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'blossom-shop' ) ) . '</h1>';                   
            }else{
                $title = '<span class="sub-title">'. esc_html__( 'Year Archives','blossom-shop' ) . '</span><h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'blossom-shop' ) ) . '</h1>';
            }
        }elseif( is_month() ){
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'blossom-shop' ) ) . '</h1>';                                   
            }else{
                $title = '<span class="sub-title">'. esc_html__( 'Month Archives','blossom-shop' ) . '</span><h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'blossom-shop' ) ) . '</h1>';
            }
        }elseif( is_day() ){
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'blossom-shop' ) ) . '</h1>';                                   
            }else{
                $title = '<span class="sub-title">'. esc_html__( 'Day Archives','blossom-shop' ) . '</span><h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'blossom-shop' ) ) .  '</h1>';
            }
        }elseif( is_post_type_archive() ) {
            if( $ed_prefix ){
                $title = '<h1 class="page-title">'  . post_type_archive_title( '', false ) . '</h1>';                            
            }else{
                $title = '<span class="sub-title">'. esc_html__( 'Archives','blossom-shop' ) . '</span><h1 class="page-title">'  . post_type_archive_title( '', false ) . '</h1>';
            }
        }elseif( is_tax() ) {
            $tax = get_taxonomy( get_queried_object()->taxonomy );
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>';                                   
            }else{
                $title = '<span class="sub-title">' . $tax->labels->singular_name . '</span><h1 class="page-title">' . single_term_title( '', false ) . '</h1>';
            }
        }
    }    
    return $title;
}
endif;
add_filter( 'get_the_archive_title', 'blossom_shop_get_the_archive_title' );

if( ! function_exists( 'blossom_shop_remove_archive_description' ) ) :
/**
 * filter the_archive_description & get_the_archive_description to show post type archive
 * @param  string $description original description
 * @return string post type description if on post type archive
 */
function blossom_shop_remove_archive_description( $description ){
    $ed_shop_archive_description = get_theme_mod( 'ed_shop_archive_description', false );
    if( is_post_type_archive( 'product' ) ) {
        if( ! $ed_shop_archive_description ){
            $description = '';
        }
    }
    return wpautop( wp_kses_post( $description ) );
}
endif;
add_filter( 'get_the_archive_description', 'blossom_shop_remove_archive_description' );

if( ! function_exists( 'blossom_shop_get_comment_author_link' ) ) :
/**
 * Filter to modify comment author link
 * @link https://developer.wordpress.org/reference/functions/get_comment_author_link/
 */
function blossom_shop_get_comment_author_link( $return, $author, $comment_ID ){
    $comment = get_comment( $comment_ID );
    $url     = get_comment_author_url( $comment );
    $author  = get_comment_author( $comment );
 
    if ( empty( $url ) || 'http://' == $url )
        $return = '<span itemprop="name">'. esc_html( $author ) .'</span>';
    else
        $return = '<span itemprop="name"><a href=' . esc_url( $url ) . ' rel="external nofollow noopener" class="url" itemprop="url">' . esc_html( $author ) . '</a></span>';

    return $return;
}
endif;
add_filter( 'get_comment_author_link', 'blossom_shop_get_comment_author_link', 10, 3 );

if( ! function_exists( 'blossom_shop_filter_post_gallery' ) ) :
/**
 * Filter the output of the gallery. 
*/ 
function blossom_shop_filter_post_gallery( $output, $attr, $instance ){
    global $post, $wp_locale;

    $html5 = current_theme_supports( 'html5', 'gallery' );
    $atts = shortcode_atts( array(
    	'order'      => 'ASC',
    	'orderby'    => 'menu_order ID',
    	'id'         => $post ? $post->ID : 0,
    	'itemtag'    => $html5 ? 'figure'     : 'dl',
    	'icontag'    => $html5 ? 'div'        : 'dt',
    	'captiontag' => $html5 ? 'figcaption' : 'dd',
    	'columns'    => 3,
    	'size'       => 'thumbnail',
    	'include'    => '',
    	'exclude'    => '',
    	'link'       => ''
    ), $attr, 'gallery' );
    
    $id = intval( $atts['id'] );
    
    if ( ! empty( $atts['include'] ) ) {
    	$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    
    	$attachments = array();
    	foreach ( $_attachments as $key => $val ) {
    		$attachments[$val->ID] = $_attachments[$key];
    	}
    } elseif ( ! empty( $atts['exclude'] ) ) {
    	$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    } else {
    	$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    }
    
    if ( empty( $attachments ) ) {
    	return '';
    }
    
    if ( is_feed() ) {
    	$output = "\n";
    	foreach ( $attachments as $att_id => $attachment ) {
    		$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
    	}
    	return $output;
    }
    
    $itemtag = tag_escape( $atts['itemtag'] );
    $captiontag = tag_escape( $atts['captiontag'] );
    $icontag = tag_escape( $atts['icontag'] );
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) ) {
    	$itemtag = 'dl';
    }
    if ( ! isset( $valid_tags[ $captiontag ] ) ) {
    	$captiontag = 'dd';
    }
    if ( ! isset( $valid_tags[ $icontag ] ) ) {
    	$icontag = 'dt';
    }
    
    $columns = intval( $atts['columns'] );
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';
    
    $selector = "gallery-{$instance}";
    
    $gallery_style = '';
    
    /**
     * Filter whether to print default gallery styles.
     *
     * @since 3.1.0
     *
     * @param bool $print Whether to print default gallery styles.
     *                    Defaults to false if the theme supports HTML5 galleries.
     *                    Otherwise, defaults to true.
     */
    if ( apply_filters( 'blossom_shop_use_default_gallery_style', ! $html5 ) ) {
    	$gallery_style = "
    	<style type='text/css'>
    		#{$selector} {
    			margin: auto;
    		}
    		#{$selector} .gallery-item {
    			float: {$float};
    			margin-top: 10px;
    			text-align: center;
    			width: {$itemwidth}%;
    		}
    		#{$selector} img {
    			border: 2px solid #cfcfcf;
    		}
    		#{$selector} .gallery-caption {
    			margin-left: 0;
    		}
    		/* see gallery_shortcode() in wp-includes/media.php */
    	</style>\n\t\t";
    }
    
    $size_class = sanitize_html_class( $atts['size'] );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
    
    /**
     * Filter the default gallery shortcode CSS styles.
     *
     * @since 2.5.0
     *
     * @param string $gallery_style Default CSS styles and opening HTML div container
     *                              for the gallery shortcode output.
     */
    $output = apply_filters( 'blossom_shop_gallery_style', $gallery_style . $gallery_div );
    
    $i = 0; 
    foreach ( $attachments as $id => $attachment ) {
            
    	$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
    	if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
    		//$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr ); // for attachment url 
            $image_output = "<a href='" . wp_get_attachment_url( $id ) . "' data-fancybox='group{$columns}' data-caption='" . esc_attr( $attachment->post_excerpt ) . "'>";
            $image_output .= wp_get_attachment_image( $id, $atts['size'], false, $attr );
            $image_output .= "</a>";
    	} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
    		$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
    	} else {
    		$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr ); //for attachment page
    	}
    	$image_meta  = wp_get_attachment_metadata( $id );
    
    	$orientation = '';
    	if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
    		$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
    	}
    	$output .= "<{$itemtag} class='gallery-item'>";
    	$output .= "
    		<{$icontag} class='gallery-icon {$orientation}'>
    			$image_output
    		</{$icontag}>";
    	if ( $captiontag && trim($attachment->post_excerpt) ) {
    		$output .= "
    			<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
    			" . wptexturize($attachment->post_excerpt) . "
    			</{$captiontag}>";
    	}
    	$output .= "</{$itemtag}>";
    	if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
    		$output .= '<br style="clear: both" />';
    	}
    }
    
    if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
    	$output .= "
    		<br style='clear: both' />";
    }
    
    $output .= "
    	</div>\n";
    
    return $output;
}
endif;

if( class_exists( 'Jetpack' ) ){
    if( !Jetpack::is_module_active( 'carousel' ) ){
        add_filter( 'post_gallery', 'blossom_shop_filter_post_gallery', 10, 3 );
    }
}else{
    add_filter( 'post_gallery', 'blossom_shop_filter_post_gallery', 10, 3 );
}

if( ! function_exists( 'blossom_shop_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function blossom_shop_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'blossom_shop_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    $dismissnonce    = wp_create_nonce( 'blossom_shop_admin_notice' );
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'blossom-shop' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'blossom-shop' ), esc_html( $name ) ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=blossom-shop-dashboard' ) ); ?>" class="button button-primary" style="text-decoration: none;"><?php esc_html_e( 'Go to the dashboard.', 'blossom-shop' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?blossom_shop_admin_notice=1&_wpnonce=<?php echo esc_attr( $dismissnonce ); ?>"><?php esc_html_e( 'Dismiss', 'blossom-shop' ); ?></a></strong></p>
                </div>
            </div>
            <style>
                .notice-info .notice-text {
                   position: relative;
                }

                .notice-text p.dismiss-link {
                   position: absolute;
                   top: 0;
                   right: 0;
                   margin: 0;
                   padding: 0;
                }
            </style>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'blossom_shop_admin_notice' );

if( ! function_exists( 'blossom_shop_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function blossom_shop_update_admin_notice(){

    if (!current_user_can('manage_options')) {
        return;
    }

     // Bail if the nonce doesn't check out
     if ( ( isset( $_GET['blossom_shop_admin_notice'] ) && $_GET['blossom_shop_admin_notice'] = '1' ) && wp_verify_nonce( $_GET['_wpnonce'], 'blossom_shop_admin_notice' ) ) {
        update_option( 'blossom_shop_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'blossom_shop_update_admin_notice' );

if ( ! function_exists( 'blossom_shop_header_menu_desc' ) ) :
/**
 * Descriptions on Header Menu
 * @param string $item_output , HTML outputp for the menu item
 * @param object $item , menu item object
 * @param int $depth , depth in menu structure
 * @param object $args , arguments passed to wp_nav_menu()
 * @return string $item_output
 */
function blossom_shop_header_menu_desc( $item_output, $item, $depth, $args ) {

    if ( 'primary' == $args->theme_location && $item->description ){
        $item_output = str_replace( '</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output );
    }

    return $item_output;
}
endif;
add_filter( 'walker_nav_menu_start_el', 'blossom_shop_header_menu_desc', 10, 4 );

if ( ! function_exists( 'blossom_shop_get_fontawesome_ajax' ) ) :
/**
 * Return an array of all icons.
 */
function blossom_shop_get_fontawesome_ajax() {
    // Bail if the nonce doesn't check out
    if ( ! isset( $_POST['blossom_shop_customize_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['blossom_shop_customize_nonce'] ), 'blossom_shop_customize_nonce' ) ) {
        wp_die();
    }

    // Do another nonce check
    check_ajax_referer( 'blossom_shop_customize_nonce', 'blossom_shop_customize_nonce' );

    // Bail if user can't edit theme options
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_die();
    }

    // Get all of our fonts
    $fonts = blossom_shop_get_fontawesome_list();
    
    ob_start();
    if( $fonts ){ ?>
        <ul class="font-group">
            <?php 
                foreach( $fonts as $font ){
                    echo '<li data-font="' . esc_attr( $font ) . '"><i class="' . esc_attr( $font ) . '"></i></li>';                        
                }
            ?>
        </ul>
        <?php
    }
    echo ob_get_clean();

    // Exit
    wp_die();
}
endif;
add_action( 'wp_ajax_blossom_shop_get_fontawesome_ajax', 'blossom_shop_get_fontawesome_ajax' );