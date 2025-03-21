<?php
/**
 * The current version of the theme.
 */
$the_theme = wp_get_theme();
define('ENVO_ONE', $the_theme->get( 'Version' ));

add_action('after_setup_theme', 'envo_one_setup');

if (!function_exists('envo_one_setup')) :

    /**
     * Global functions
     */
    function envo_one_setup() {

        // Theme lang.
        load_theme_textdomain('envo-one', get_template_directory() . '/languages');

        // Add Title Tag Support.
        add_theme_support('title-tag');

        // Register Menus.
		$menus = array('main_menu' => esc_html__('Main Menu', 'envo-one'),'main_menu_right' => esc_html__('Menu Right', 'envo-one'), );
        register_nav_menus($menus);

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(300, 300, true);
        add_image_size('envo-one-img', 1250, 540, true);

        // Add Custom Background Support.
        $args = array(
            'default-color' => 'ffffff',
        );
        add_theme_support('custom-background', $args);

        add_theme_support('custom-logo', array(
            'height' => 60,
            'width' => 200,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('site-title', 'site-description'),
        ));

        // Adds RSS feed links to for posts and comments.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         */
        add_theme_support('title-tag');

        // Set the default content width.
        $GLOBALS['content_width'] = 1140;

        add_theme_support('custom-header', apply_filters('envo_one_custom_header_args', array(
            'width' => 2000,
            'height' => 60,
            'default-text-color' => '',
            'wp-head-callback' => 'envo_one_header_style',
        )));

        // WooCommerce support.
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('html5', array('search-form'));
		    add_theme_support('align-wide');
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('assets/css/bootstrap.css', envo_one_fonts_url(), 'assets/css/editor-style.css'));

    }

endif;

if (!function_exists('envo_one_header_style')) :

    /**
     * Styles the header image and text displayed on the blog.
     */
    function envo_one_header_style() {
        $header_image = get_header_image();
        $header_text_color = get_header_textcolor();
        if (get_theme_support('custom-header', 'default-text-color') !== $header_text_color || !empty($header_image)) {
            ?>
            <style type="text/css" id="envo-one-header-css">
            <?php
            // Has a Custom Header been added?
            if (!empty($header_image)) :
                ?>
                    .site-header {
                        background-image: url(<?php header_image(); ?>);
                        background-repeat: no-repeat;
                        background-position: 50% 50%;
                        -webkit-background-size: cover;
                        -moz-background-size:    cover;
                        -o-background-size:      cover;
                        background-size:         cover;
                    }
            <?php endif; ?>	
            <?php
            // Has the text been hidden?
            if ('blank' === $header_text_color) :
                ?>
                    .site-title,
                    .site-description {
                        position: absolute;
                        clip: rect(1px, 1px, 1px, 1px);
                    }
            <?php elseif ('' !== $header_text_color) : ?>
                    .site-title a, 
                    .site-title, 
                    .site-description {
                        color: #<?php echo esc_attr($header_text_color); ?>;
                    }
            <?php endif; ?>	
            </style>
            <?php
        }
    }

endif; // envo_one_header_style

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function envo_one_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}

add_action('wp_head', 'envo_one_pingback_header');

/**
 * Set Content Width
 */
function envo_one_content_width() {

    $content_width = $GLOBALS['content_width'];

    if (is_active_sidebar('envo-one-right-sidebar')) {
        $content_width = 847;
    } else {
        $content_width = 1140;
    }

    /**
     * Filter content width of the theme.
     */
    $GLOBALS['content_width'] = apply_filters('envo_one_content_width', $content_width);
}

add_action('template_redirect', 'envo_one_content_width', 0);

/**
 * Register custom fonts.
 */
function envo_one_fonts_url() {
    $fonts_url = '';

    /**
     * Translators: If there are characters in your language that are not
     * supported by Lato, translate this to 'off'. Do not translate
     * into your own language.
     */
    $font = get_theme_mod('main_typographydesktop', '');

    if ('' == $font) {
        $font_families = array();

        $font_families[] = 'Lato:300,400,700,900';

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return esc_url_raw($fonts_url);
}

/**
 * Add preconnect for Google Fonts.
 */
function envo_one_resource_hints($urls, $relation_type) {
    if (wp_style_is('envo-one-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}

add_filter('wp_resource_hints', 'envo_one_resource_hints', 10, 2);

/**
 * Enqueue Styles (normal style.css and bootstrap.css)
 */
function envo_one_theme_stylesheets() {
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('envo-one-fonts', envo_one_fonts_url(), array(), null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', array('hc-offcanvas-nav'), '3.3.7');
	  wp_enqueue_style('hc-offcanvas-nav', get_template_directory_uri() . '/assets/css/hc-offcanvas-nav.min.css', array(), ENVO_ONE);
    // Theme stylesheet.
    wp_enqueue_style('envo-one-stylesheet', get_stylesheet_uri(), array('bootstrap'), ENVO_ONE);
    // WooCommerce stylesheet.
  	if (class_exists('WooCommerce')) {
  		wp_enqueue_style('envo-one-woo-stylesheet', get_template_directory_uri() . '/assets/css/woocommerce.css', array('envo-one-stylesheet', 'woocommerce-general'), ENVO_ONE);
  	}
}

add_action('wp_enqueue_scripts', 'envo_one_theme_stylesheets');

/**
 * Register jquery
 */
function envo_one_theme_js() {
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
    wp_enqueue_script('envo-one-theme-js', get_template_directory_uri() . '/assets/js/envo-one.js', array('jquery'), ENVO_ONE, true);
	  wp_enqueue_script('hc-offcanvas-nav', get_template_directory_uri() . '/assets/js/hc-offcanvas-nav.min.js', array('jquery'), ENVO_ONE, true);
    wp_enqueue_script('feather-icons', get_template_directory_uri() . '/assets/js/feather.min.js', array('jquery'), ENVO_ONE, true);
}

add_action('wp_enqueue_scripts', 'envo_one_theme_js');

if ( !function_exists( 'envo_one_envo_extra_is_activated' ) ) {

	/**
	 * Query Envo extra activation
	 */
	function envo_one_envo_extra_is_activated() {
		return defined( 'ENVO_EXTRA_CURRENT_VERSION' ) ? true : false;
	}

}

if (!function_exists('envo_one_title_logo')) {
    
	add_action('envo_one_top_header', 'envo_one_title_logo', 10);
    /**
     * Title, logo code
     */
    function envo_one_title_logo() {
        ?>
        <div class="site-heading">    
		<div class="site-branding-logo">
			<?php the_custom_logo(); ?>
		</div>
		<div class="site-branding-text">
			<?php if ( is_front_page() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>

			<?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) :
				?>
				<p class="site-description">
					<?php echo esc_html( $description ); ?>
				</p>
			<?php endif; ?>
		</div><!-- .site-branding-text -->
	</div>
        <?php
    }

}

if (!function_exists('envo_one_menu')) {

		add_action('envo_one_header', 'envo_one_menu', 20);


    /**
     * Menu
     */
    function envo_one_menu() {
        ?>
		<div class="menu-heading">
			<nav id="site-navigation" class="navbar navbar-default">
				<?php
				wp_nav_menu( array(
					'theme_location'	 => 'main_menu',
					'depth'				 => 5,
					'container_id'		 => 'theme-menu',
					'container'			 => 'div',
					'container_class'	 => 'menu-container',
					'menu_class'		 => 'nav navbar-nav navbar-' . get_theme_mod( 'menu_position', 'left' ),
					'fallback_cb'		 => 'Envo_One_WP_Bootstrap_Navwalker::fallback',
					'walker'			 => new Envo_One_WP_Bootstrap_Navwalker(),
				) ); 
				wp_nav_menu(array(
					'theme_location' => 'main_menu_right',
					'depth' => 1,
					'container_id' => 'my-menu-right',
					'container' => 'div',
					'container_class' => 'menu-container',
					'menu_class' => 'nav navbar-nav navbar-right',
					'fallback_cb' => 'Envo_One_WP_Bootstrap_Navwalker::fallback',
					'walker' => new Envo_One_WP_Bootstrap_Navwalker(),
				));      
				?>
			</nav>
		</div>
        <?php
    }

}

/**
 * Add header widget area
 */

if (!function_exists('envo_one_header_widget_area')) {
    
	add_action('envo_one_top_header', 'envo_one_header_widget_area', 20);
	function envo_one_header_widget_area() {
		?>
		<div class="header-widget-area">
			<?php if ( is_active_sidebar( 'envo-one-header-area' ) ) { ?>
				<div class="site-heading-sidebar" >
					<?php dynamic_sidebar( 'envo-one-header-area' ); ?>
				</div>
			<?php } ?>
			<?php if ( class_exists( 'WooCommerce' ) ) { ?>
			  <div class="menu-search-widget">
					<div class="header-search-form">
						<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input type="hidden" name="post_type" value="product" />
							<input class="header-search-input" name="s" type="text" placeholder="<?php esc_attr_e( 'Search products...', 'envo-one' ); ?>"/>
							<select class="header-search-select" name="product_cat">
								<option value=""><?php esc_html_e( 'All Categories', 'envo-one' ); ?></option> 
								<?php
								$categories = get_categories( 'taxonomy=product_cat' );
								foreach ( $categories as $category ) {
									$option = '<option value="' . esc_attr( $category->category_nicename ) . '">';
									$option .= esc_html( $category->cat_name );
									$option .= ' <span>(' . absint( $category->category_count ) . ')</span>';
									$option .= '</option>';
									echo $option; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</select>
							<button class="header-search-button" type="submit"><i data-feather="search" class="la la-search" aria-hidden="true"></i></button>
						</form>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}

add_action('envo_one_top_header', 'envo_one_head_start', 25);
function envo_one_head_start() {
    echo '<div class="header-right" >';
}

add_action('envo_one_top_header', 'envo_one_head_end', 80);
function envo_one_head_end() {
    echo '</div>';
}
if (!function_exists('envo_one_menu_button')) {
    
	add_action('envo_one_top_header', 'envo_one_menu_button', 28);
    /**
     * Mobile menu button
     */
    function envo_one_menu_button() {
        ?>
        <div class="menu-button visible-xs" >
            <div class="navbar-header">
				<a href="#" id="main-menu-panel" class="toggle menu-panel" data-panel="main-menu-panel">
					<span></span>
				</a>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('envo_one_top_bar_header_code')) :

    /**
     * Create top bar widget area
     */
    add_action('envo_one_top_bar_header', 'envo_one_top_bar_header_code');

    function envo_one_top_bar_header_code() {
        if (is_active_sidebar('envo-one-top-bar')) { ?>
            <div class="top-bar-section container-fluid">
                <div class="<?php echo esc_attr(get_theme_mod('top_bar_content_width', 'container')); ?>">
                    <div class="row">
                        <?php dynamic_sidebar('envo-one-top-bar'); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="site-header title-header container-fluid">
  				<div class="container" >
  					<div class="heading-row row" >
  						<?php do_action( 'envo_one_top_header' ); ?>
  					</div>
  				</div>
  			</div>
        <?php
    }

endif;

/**
 * Register Custom Navigation Walker include custom menu widget to use walkerclass
 */
require_once( trailingslashit(get_template_directory()) . 'lib/wp_bootstrap_navwalker.php' );

/**
 * Register Theme Info Page
 */
require_once( trailingslashit(get_template_directory()) . 'lib/envo-one-dashboard.php' );
if ( is_admin() ) {
	require_once( trailingslashit( get_template_directory() ) . 'lib/envo-one-plugin-install.php' );
}
if ( envo_one_envo_extra_is_activated() ) {
	add_action( 'init', 'envo_extra_dashboard' );
	add_action( 'init', 'envo_extra_recommended_plugins' );
}

if (class_exists('WooCommerce')) {

    /**
     * WooCommerce options
     */
    require_once( trailingslashit(get_template_directory()) . 'lib/envo-one-woocommerce.php' );
}

require_once( trailingslashit(get_template_directory()) . 'lib/envo-one-extra.php' );

/**
 * Customizer options
 */
if (!function_exists('envo_one_customizer')) {

    function envo_one_customizer() {
	
		require_once( trailingslashit(get_template_directory()) . 'lib/customizer-recommend.php' );
    
	}
	if ( !is_child_theme() ) {
		add_action( 'init', 'envo_one_customizer' );
	}
	
}

add_action('widgets_init', 'envo_one_widgets_init');

/**
 * Register the Sidebar(s)
 */
function envo_one_widgets_init() {
    register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'envo-one'),
			'id' => 'envo-one-right-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><h3>',
			'after_title' => '</h3></div>',
		)
    );
    register_sidebar(
		array(
			'name' => esc_html__('Footer Section', 'envo-one'),
			'id' => 'envo-one-footer-area',
			'before_widget' => '<div id="%1$s" class="widget %2$s col-md-3">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title"><h3>',
			'after_title' => '</h3></div>',
		)
    );
	register_sidebar(
		array(
			'name'			 => esc_html__( 'Header Section', 'envo-one' ),
			'id'			 => 'envo-one-header-area',
			'before_widget'	 => '<div id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<div class="widget-title"><h3>',
			'after_title'	 => '</h3></div>',
		) 
	);
	register_sidebar(
	  array(
		  'name'			 => esc_html__( 'Top bar', 'envo-one' ),
		  'id'			 => 'envo-one-top-bar',
		  'before_widget'	 => '<div id="%1$s" class="widget %2$s col-sm-4">',
		  'after_widget'	 => '</div>',
		  'before_title'	 => '<div class="widget-title"><h3>',
		  'after_title'	 => '</h3></div>',
	  )
	);
}


if (!function_exists('wp_body_open')) :

    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         */
        do_action('wp_body_open');
    }

endif;

/**
 * Skip to content link
 */
function envo_one_body_header_code() {
	?>
	<a class="skip-link screen-reader-text" href="#site-content"><?php esc_html_e('Skip to the content', 'envo-one'); ?></a>
	<div class="page-wrap">
		<?php do_action('envo_one_top_bar_header'); ?>
		<div class="site-header menu-header container-fluid">
			<div class="<?php echo esc_attr( get_theme_mod( 'header_content_width', 'container' ) ); ?>" >
				<div class="heading-row row" >
					<?php do_action( 'envo_one_header' ); ?>
				</div>
			</div>
		</div>
		<?php if ( is_page_template( 'templates/template-page-builders.php' ) || is_singular( 'elementor_library' )  ) {return;} ?>
			<div id="site-content" class="container main-container" role="main">
				<div class="page-area">
		<?php 
}

add_action('wp_body_open', 'envo_one_body_header_code', 5);

function envo_one_wp_footer_code() {
	?>
			</div><!-- end main-container -->
		</div><!-- end page-area -->
		<?php do_action('envo_one_generate_footer'); ?>
	</div><!-- end page-wrap -->
	<?php 
}

add_action('wp_footer', 'envo_one_wp_footer_code', 5);



add_filter( 'body_class','envo_one_body_classes' );

function envo_one_body_classes( $classes ) {
	
	if ( !class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woo-off';
	}
	if ( !is_active_sidebar( 'envo-one-header-area' ) && !class_exists( 'WooCommerce' ) ) {
		$classes[] = 'header-widgets-off';	
	}
	if ( !is_active_sidebar( 'envo-one-right-sidebar' ) ) {
		$classes[] = 'sidebar-off';	
	}
	if (!has_nav_menu('main_menu_right')) {
		$classes[] = 'menu-right-off';
	}
	
  return $classes;
     
}