<?php
/*
 * Plugin Name: Envo Extra
 * Plugin URI: https://envothemes.com/
 * Description: Extra addon for EnvoThemes Themes
 * Version: 1.9.6
 * Author: EnvoThemes
 * Author URI: https://envothemes.com/
 * License: GPL-2.0+
 * Text Domain: envo-extra
 * Domain Path: /languages
 * WC requires at least: 3.3.0
 * WC tested up to: 9.7.0
 * Elementor tested up to: 3.27.0
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}
//define( 'KIRKI_TEST', true );
$plugin_data	 = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
$plugin_version	 = $plugin_data[ 'Version' ];
// Define WC_PLUGIN_FILE.
if ( !defined( 'ENVO_EXTRA_CURRENT_VERSION' ) ) {
	define( 'ENVO_EXTRA_CURRENT_VERSION', $plugin_version );
}

//plugin constants
define( 'ENVO_EXTRA_PATH', plugin_dir_path( __FILE__ ) );
define( 'ENVO_EXTRA_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'ENVO_EXTRA_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

add_action( 'init', 'envo_extra_load_textdomain' );

function envo_extra_load_textdomain() {
	load_plugin_textdomain( 'envo-extra', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

function envo_extra_is_gutenberg() {
      
    if ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) ) {
        return true;    
    } else {
        return false;
    }
}

function envo_extra_scripts() {
	if (envo_extra_is_gutenberg()) {
		wp_enqueue_style( 'envo-extra-gutenberg', plugin_dir_url( __FILE__ ) . 'css/gutenberg.css', array(), ENVO_EXTRA_CURRENT_VERSION );
	}
	wp_enqueue_style( 'envo-extra', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), ENVO_EXTRA_CURRENT_VERSION );
	wp_enqueue_script( 'envo-extra-js', plugin_dir_url( __FILE__ ) . 'js/envo.js', array( 'jquery' ), ENVO_EXTRA_CURRENT_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'envo_extra_scripts' );

//Dequeue Styles
function envo_extra_dequeue_unnecessary_styles() {
	$value = get_theme_mod( 'main_typographydesktop', array() );
	if (isset( $value['font-family'] ) && !empty($value['font-family'])) {
		wp_dequeue_style( 'enwoo-fonts' );
		wp_deregister_style( 'enwoo-fonts' );
	}
}
add_action( 'wp_print_styles', 'envo_extra_dequeue_unnecessary_styles' );

/**
 * Return theme slug
 */
function envo_extra_theme() {
	$theme	 = get_template();
	$slug	 = str_replace( '-', '_', $theme );
	return $slug;
}

/**
 * Footer copyright function
 */
if ( !function_exists( 'envo_extra_text' ) ) {

	function envo_extra_text( $rewritetexts ) {

		$currentyear = date( 'Y' );
		$copy		 = '&copy;';

		return str_replace(
		array( '%current_year%', '%copy%' ), array( $currentyear, $copy ), $rewritetexts
		);
	}

}

add_filter( 'envo_extra_footer_text', 'envo_extra_text' );

/**
 * Back to top
 */
function envo_extra_back_to_top() {
	if ( get_theme_mod( 'back_to_top_on_off', 'block' ) == 'block' ) {
		?>
		<!-- Return to Top -->
		<a href="javascript:" id="return-to-top"><i class="las la-chevron-up"></i></a>
		<?php
	}
}

/**
 * Footer extra actions - footer text and back to top
 */
function envo_extra_action() {
	remove_action( envo_extra_theme() . '_generate_footer', envo_extra_theme() . '_generate_construct_footer', 20 );
	add_action( envo_extra_theme() . '_generate_footer', 'envo_extra_generate_construct_footer' );
	add_action( 'wp_footer', 'envo_extra_back_to_top' );
    remove_theme_support( 'widgets-block-editor' );
}

/**
 * Footer footer text
 */
function envo_extra_generate_construct_footer() {
	if ( get_theme_mod( 'enwoo_custom_footer_on_off', '' ) == 'elementor' && get_theme_mod( 'enwoo_custom_footer', '' ) != '' && envo_extra_check_for_elementor() ) {
		$elementor_section_ID = get_theme_mod( 'enwoo_custom_footer', '' );
		?>
		<footer id="colophon" class="elementor-footer-credits">
			<?php echo do_shortcode( '[elementor-template id="' . $elementor_section_ID . '"]' ); ?>	
		</footer>
	<?php } elseif ( get_theme_mod( 'footer-credits', '' ) != '' ) { ?>
		<footer id="colophon" class="footer-credits container-fluid">
			<div class="container">
				<div class="footer-credits-text text-center">
					<div class="envo-credits-text">
						<?php echo apply_filters( 'envo_extra_footer_text', get_theme_mod( 'footer-credits', '' ) ); ?>
					</div>    
					<?php if ( get_theme_mod( 'enwoo_footer_credits_on_off', 1 ) == 1 ) { ?>
						<?php printf( get_site_option( 'et_fc' ) ); ?>
					<?php } ?>
				</div>
			</div>	
		</footer>   
	<?php } else { ?>
		<?php if ( get_theme_mod( 'enwoo_footer_credits_on_off', 1 ) == 1 ) { ?>
			<footer id="colophon" class="footer-credits container-fluid">
				<div class="container">
					<div class="footer-credits-text text-center">
						<?php printf( get_site_option( 'et_fc' ) ); ?>
					</div>
				</div>	
			</footer>
		<?php } ?>
		<?php
	}
}
function envo_extra_recommended_plugins() {
	add_theme_support('recommend-plugins', array(
		'woocommerce' => array(
			'name' => 'WooCommerce',
			'active_filename' => 'woocommerce/woocommerce.php',
			/* translators: %s plugin name string */
			'description' => sprintf(esc_attr__('To enable shop features, please install and activate the %s plugin.', 'envo-extra'), '<strong>WooCommerce</strong>'),
		),
		'elementor' => array(
			'name' => 'Elementor',
			'active_filename' => 'elementor/elementor.php',
			/* translators: %s plugin name string */
			'description' => sprintf(esc_attr__('The most advanced frontend drag & drop page builder.', 'envo-extra'), '<strong>Elementor</strong>'),
		),
	));
}

if ( !class_exists( 'Kirki' ) ) {
	include_once( plugin_dir_path( __FILE__ ) . 'include/kirki.php' );
}

/**
 * Remove Kirki telemetry
 */
function envo_extra_remove_kirki_module( $modules ) {
	unset( $modules[ 'telemetry' ] );
	unset( $modules[ 'gutenberg' ] );
	return $modules;
}

add_filter( 'kirki_modules', 'envo_extra_remove_kirki_module' );

/**
 * Add Kirki CSS into a file
 */
add_filter( 'kirki_output_inline_styles', function() {
	return true;
} );

/* Register the config */
Kirki::add_config( 'envo_extra', array(
	'capability'	 => 'edit_theme_options',
	'option_type'	 => 'theme_mod',
) );


/* Make the CSS of kirki tabs available after switch */
//add_filter('kirki_envo_extra_webfonts_skip_hidden', '__return_false', 99);
//add_filter('kirki_envo_extra_css_skip_hidden', '__return_false', 99);
//add_filter( 'kirki_dynamic_css_method', function() {
//    return 'file';
//} );

// Check if needed functions exists - if not, require them
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

function envo_extra_check_plugin_active( $plugin_slug ) {
    if ( is_plugin_active( $plugin_slug ) ) {
        return true;
    }

    return false;
}

/**
 * Add Kirki custom controls.
 *
 * @param WP_Customize_Manager $wp_customize Instance of WP_Customize_Manager.
 */
function envo_extra_custom_customizer_control( $wp_customize ) {

	// Custom controls.

	require ENVO_EXTRA_PATH . '/controls/responsive-devices/control-responsive-devices.php';
	require ENVO_EXTRA_PATH . '/controls/responsive-devices/control-responsive-devices-typography.php';
}

if (!function_exists('envo_extra_dashboard')) {

    function envo_extra_dashboard() {
	
		require_once( plugin_dir_path( __FILE__ ) . 'lib/admin/dashboard.php' );
    
	}
	
}
require_once( plugin_dir_path( __FILE__ ) . 'options/extra.php' );
$theme = wp_get_theme();
if ( 'Enwoo' == $theme->name || 'enwoo' == $theme->template ) {
	
	add_action( 'customize_register', 'envo_extra_custom_customizer_control' );
	
	
	require_once( plugin_dir_path( __FILE__ ) . 'lib/admin/metabox.php' );
	if (!envo_extra_check_for_pro('enwoo')) {	
		Kirki::add_section( 'pro', array(
			'title'       => esc_html__( 'Unlock More Options', 'kirki' ),
			'type'        => 'link',
			'button_text' => esc_html__( 'Enwoo PRO', 'envo-extra' ),
			'button_url'  => 'https://enwoo-wp.com/enwoo-pro/',
			'priority'	 => 1,
		) );
		require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/pro.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/envothemes-demo-import/includes/panel/pro-demos.php' );
	}

	Kirki::add_panel( 'envo_theme_panel', array(
		'title'		 => esc_attr__( 'Theme Options', 'envo-extra' ),
		'priority'	 => 5,
	) );

	function envo_extra_check_for_woocommerce() {
		if ( !defined( 'WC_VERSION' ) ) {
			// no woocommerce :(
		} else {
			require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/woocommerce.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'lib/woocommerce.php' );
		}
	}

	add_action( 'plugins_loaded', 'envo_extra_check_for_woocommerce' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/site-width.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/top-bar.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/header.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/main-menu.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/footer-credits.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/footer-widgets.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/main-colors.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/posts-pages.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/sidebar.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/back-to-top.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/enwoo/custom-codes.php' );
	
	add_action( 'init', 'envo_extra_dashboard' );
	add_action( 'init', 'envo_extra_recommended_plugins' );
	
	add_filter( 'use_widgets_block_editor', '__return_false' );
	
	add_action( 'after_setup_theme', 'envo_extra_action', 0 );
}

if ( 'Envo Royal' == $theme->name || 'envo-royal' == $theme->template ) {
	
	add_action( 'customize_register', 'envo_extra_custom_customizer_control' );
	
	
	require_once( plugin_dir_path( __FILE__ ) . 'lib/admin/metabox.php' );
	if (!envo_extra_check_for_pro('envo-royal')) {	
		Kirki::add_section( 'pro', array(
			'title'       => esc_html__( 'Unlock More Options', 'kirki' ),
			'type'        => 'link',
			'button_text' => esc_html__( 'Envo Royal PRO', 'envo-extra' ),
			'button_url'  => 'https://envothemes.com/product/envo-royal-pro/',
			'priority'	 => 1,
		) );
		require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/pro.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'lib/envothemes-demo-import/includes/panel/envo-royal-pro-demos.php' );
	}

	Kirki::add_panel( 'envo_theme_panel', array(
		'title'		 => esc_attr__( 'Theme Options', 'envo-extra' ),
		'priority'	 => 5,
	) );

	function envo_extra_check_for_woocommerce() {
		if ( !defined( 'WC_VERSION' ) ) {
			// no woocommerce :(
		} else {
			require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/woocommerce.php' );
			require_once( plugin_dir_path( __FILE__ ) . 'lib/woocommerce.php' );
		}
	}

	add_action( 'plugins_loaded', 'envo_extra_check_for_woocommerce' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/color-preset.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/top-bar.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/header.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/main-menu.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/footer-credits.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/footer-widgets.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/main-colors.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/posts-pages.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/sidebar.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/back-to-top.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'options/envo-royal/custom-codes.php' );
	
	add_action( 'init', 'envo_extra_dashboard' );
	add_action( 'init', 'envo_extra_recommended_plugins' );
	
	add_filter( 'use_widgets_block_editor', '__return_false' );
	
	add_action( 'after_setup_theme', 'envo_extra_action', 0 );
}

// Deactivate 3rd party plugin
if ( in_array( 'envothemes-demo-import/envothemes-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	deactivate_plugins('envothemes-demo-import/envothemes-demo-import.php');
}

add_action('plugins_loaded', 'envo_extra_plugin_load');
function envo_extra_plugin_load() {
	if ( !in_array( 'envothemes-demo-import/envothemes-demo-import.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'lib/envothemes-demo-import/envothemes-demo-import.php' );
	}
}

add_action( 'customize_register', 'envo_extra_theme_customize_register', 99 );

function envo_extra_theme_customize_register( $wp_customize ) {

	$wp_customize->remove_control( 'header_textcolor' );

	// relocating default background color
	$wp_customize->get_control( 'background_color' )->section = 'background_image';
}

function envo_extra_get_meta( $name = '', $output = '' ) {
	if ( is_singular( array( 'post', 'page' ) ) || ( function_exists( 'is_shop' ) && is_shop() ) ) {
		global $post;
		if ( ( function_exists( 'is_shop' ) && is_shop() ) ) {
			$post_id = get_option( 'woocommerce_shop_page_id' );
			;
		} else {
			$post_id = $post->ID;
		}
		$meta = get_post_meta( $post_id, 'envo_extra_meta_' . $name, true );
		if ( isset( $meta ) && $meta != '' ) {
			if ( $output == 'echo' ) {
				echo esc_html( $meta );
			} else {
				return $meta;
			}
		} else {
			return;
		}
	}
}

if ( !function_exists( 'envo_extra_widget_date_comments' ) ) :

	/**
	 * Returns date for widgets.
	 */
	function envo_extra_widget_date_comments() {
		?>
		<span class="extra-posted-date">
		<?php echo esc_html( get_the_date() ); ?>
		</span>
		<span class="extra-comments-meta">
		<?php
		if ( !comments_open() ) {
			esc_html_e( 'Off', 'envo-extra' );
		} else {
			?>
				<a href="<?php echo esc_url( get_comments_link() ); ?>" rel="nofollow" title="<?php esc_html_e( 'Comment on ', 'envo-extra' ) . the_title_attribute(); ?>">
				<?php echo absint( get_comments_number() ); ?>
				</a>
				<?php } ?>
			<i class="fa fa-comments-o"></i>
		</span>
		<?php
	}

endif;

/**
 * Check Elementor plugin
 */
function envo_extra_check_for_elementor() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	return is_plugin_active( 'elementor/elementor.php' );
}

/**
 * Check Elementor PRO plugin
 */
function envo_extra_check_for_elementor_pro() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	return is_plugin_active( 'elementor-pro/elementor-pro.php' );
}

/**
 * Register Elementor features
 */
if ( envo_extra_check_for_elementor() ) {
	if ( !envo_extra_check_for_elementor_pro() ) {
		include_once( plugin_dir_path( __FILE__ ) . 'lib/elementor/shortcode.php' );
	}
}

include_once( plugin_dir_path( __FILE__ ) . 'lib/elementor/widgets.php' );
function envo_extra_activate_fc() {
	$arr = array('');
	$theme = wp_get_theme();
	if ( 'Enwoo' == $theme->name || 'enwoo' == $theme->template ) {
		$arr = array(
			'Created with <a href="https://enwoo-wp.com/free-woocommerce-theme/" title="Free WooCommerce WordPress Theme">Enwoo</a> WordPress theme',
			'Created with <a href="https://enwoo-wp.com/free-business-wp-theme/" title="Free Business WordPress Theme">Enwoo</a> WordPress theme',
			'Created with <a href="https://enwoo-wp.com/" title="Free Multipurpose WordPress Theme">Enwoo</a> WordPress theme',
		);
	} elseif ( 'Envo Royal' == $theme->name || 'envo-royal' == $theme->template ) {
		$arr = array(
			'Created with <a href="https://envothemes.com/envo-royal-free-wp-theme/" title="Free Multipurpose WordPress Theme">Envo Royal</a> WordPress theme',
		);
	}

	$key = array_rand( $arr );

	update_site_option( 'et_fc', $arr[ $key ] );
}

add_action( 'after_switch_theme', 'envo_extra_activate_fc' );
register_activation_hook( __FILE__, 'envo_extra_activate_fc' );



register_activation_hook( __FILE__, 'envo_extra_plugin_activate' );
add_action( 'admin_init', 'envo_extra_plugin_redirect' );
add_action( 'after_switch_theme', 'envo_extra_theme_redirect' );

function envo_extra_plugin_activate() {
	add_option( 'envo_plugin_do_activation_redirect', true );
}

/**
 * Check PRO plugin
 */
function envo_extra_check_for_pro($plugin) {
	if ( in_array( $plugin . '-pro/' . $plugin . '-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	}
	return;
}

/**
 * Redirect after plugin activation
 */
function envo_extra_plugin_redirect() {
	if ( get_option( 'envo_plugin_do_activation_redirect', false ) ) {
		delete_option( 'envo_plugin_do_activation_redirect' );
		if ( !is_network_admin() || !isset( $_GET[ 'activate-multi' ] ) ) {
			wp_redirect( 'themes.php?page=envothemes-panel-install-demos' );
		}
	}
}

/**
 * Redirect after plugin activation
 */
function envo_extra_theme_redirect() {
		if ( !is_network_admin() || !isset( $_GET[ 'activate-multi' ] ) ) {
			wp_redirect( 'themes.php?page=envothemes-panel-install-demos' );
		}
}

/**
 * Adjust customizer preview.
 */
function envo_extra_customizer_responsive_sizes() {

	$medium_breakpoint	 = 990;
	$mobile_breakpoint	 = 480;

	$tablet_margin_left	 = -$medium_breakpoint / 2 . 'px';
	$tablet_width		 = $medium_breakpoint . 'px';

	$mobile_margin_left	 = -$mobile_breakpoint / 2 . 'px';
	$mobile_width		 = $mobile_breakpoint . 'px';
	?>

	<style>
		.wp-customizer .preview-tablet .wp-full-overlay-main {
			margin-left: <?php echo esc_attr( $tablet_margin_left ); ?>;
			width: <?php echo esc_attr( $tablet_width ); ?>;
		}
		.wp-customizer .preview-mobile .wp-full-overlay-main {
			margin-left: <?php echo esc_attr( $mobile_margin_left ); ?>;
			width: <?php echo esc_attr( $mobile_width ); ?>;
			height: 680px;
		}
	</style>

	<?php
}

add_action( 'customize_controls_print_styles', 'envo_extra_customizer_responsive_sizes' );

/**
 * Enqueue customizer scripts & styles.
 */
function envo_extra_customizer_scripts_styles() {

	wp_enqueue_style( 'responsive-controls', ENVO_EXTRA_PLUGIN_URL . 'css/responsive-controls.css', '', ENVO_EXTRA_CURRENT_VERSION );
	wp_enqueue_script( 'responsive-controls', ENVO_EXTRA_PLUGIN_URL . 'js/responsive-controls.js', array( 'jquery' ), ENVO_EXTRA_CURRENT_VERSION, true );
}

add_action( 'customize_controls_print_styles', 'envo_extra_customizer_scripts_styles' );

add_filter( 'body_class','envo_extra_body_classes' );
function envo_extra_body_classes( $classes ) {
	
	$theme = wp_get_theme();
	if ( 'Enwoo' == $theme->name || 'enwoo' == $theme->template ) {
		$header = get_theme_mod( 'header_layout', envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'woonav' : 'busnav' );
	} else {
		$header = get_theme_mod( 'header_layout', 'busnav' );
	}
	if ( $header != '' ) {
		$classes[] = 'header-' . $header;	
	}
	
	$woo_check = envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'woo-on' : '';
	if ( $header != '' ) {
		$classes[] = $woo_check;	
	}
	
	if(is_archive() || is_home())
		return $classes;
	
	if (envo_extra_is_gutenberg()) {
		$classes[] = 'gutenberg-on';
	}
	$title = get_post_meta( get_the_ID(), 'envo_extra_hide_title', true );
	if ( $title == 'on' ) {
		$classes[] = 'title-off';	
	}
	$sidebar = get_post_meta( get_the_ID(), 'envo_extra_hide_sidebar', true );
	if ( $sidebar == 'on' ) {
		$classes[] = 'sidebar-off';	
	}
	$transparent_header = get_post_meta( get_the_ID(), 'envo_extra_transparent_header', true ) !== '' ? get_post_meta( get_the_ID(), 'envo_extra_transparent_header', true ) : '';
	if ( $transparent_header == 'on' ) {
		$classes[] = 'transparent-header';	
	}
	     
    return $classes;
     
}

/**
 * Add custom CSS styles
 */
function envo_extra_enqueue_header_css() {

    $css = '';
	
    $transparent_header = get_post_meta( get_the_ID(), 'envo_extra_transparent_header', true );
	$transparent_header_color = get_post_meta( get_the_ID(), 'envo_extra_header_text_color', true );
	$theme = wp_get_theme();
	if ( 'Enwoo' == $theme->name || 'enwoo' == $theme->template ) {
		$header = get_theme_mod( 'header_layout', envo_extra_check_plugin_active( 'woocommerce/woocommerce.php' ) ? 'woonav' : 'busnav' );
	} else {
		$header = get_theme_mod( 'header_layout', 'busnav' );
	}
	if ( $transparent_header == 'on' ) {
		if ( $header == 'busnav' ) {
			$css .= '.transparent-header .site-header.business-heading:not(.shrink), .transparent-header .site-header.business-heading:not(.shrink) .navbar-default .navbar-nav > li > a, .transparent-header .site-header.business-heading:not(.shrink) a.cart-contents i, .transparent-header .site-header.business-heading:not(.shrink) .header-my-account a, .transparent-header .site-header.business-heading:not(.shrink) .header-wishlist a, .transparent-header .site-header.business-heading:not(.shrink) .header-compare a, .transparent-header .site-header.business-heading:not(.shrink) .header-search a, .transparent-header .site-header.business-heading:not(.shrink) .site-branding-text h1.site-title a, .transparent-header .site-header.business-heading:not(.shrink) .site-branding-text .site-title a, .transparent-header .site-header.business-heading:not(.shrink) #site-navigation .navbar-nav > li > a, .transparent-header .site-header.business-heading:not(.shrink) p.site-description {color: ' . $transparent_header_color . ';}';
			$css .= '.transparent-header .site-header.business-heading:not(.shrink) .hc-nav-trigger span,.transparent-header .site-header.business-heading:not(.shrink) .hc-nav-trigger span:before,.transparent-header .site-header.business-heading:not(.shrink) .hc-nav-trigger span:after {background-color: ' . $transparent_header_color . ';}';
		} elseif ( $header == 'woonav' ) {
			$css .= '.transparent-header #second-site-navigation, .transparent-header #second-site-navigation .navbar-default .navbar-nav > li > a, .transparent-header #second-site-navigation a.cart-contents i, .transparent-header #second-site-navigation .header-my-account a, .transparent-header #second-site-navigation .header-wishlist a, .transparent-header #second-site-navigation .header-compare a, .transparent-header #second-site-navigation .header-search a, .transparent-header #second-site-navigation .site-branding-text h1.site-title a, .transparent-header #second-site-navigation .site-branding-text .site-title a, .transparent-header #second-site-navigation #site-navigation .navbar-nav > li > a, .transparent-header #second-site-navigation p.site-description {color: ' . $transparent_header_color . ';}';	
			$css .= '.transparent-header #second-site-navigation .hc-nav-trigger span,.transparent-header #second-site-navigation .hc-nav-trigger span:before,.transparent-header #second-site-navigation .hc-nav-trigger span:after {color: ' . $transparent_header_color . ';}';	
		}	
	}
    
    wp_add_inline_style('envo-extra', $css, 9999);
}

add_action('wp_enqueue_scripts', 'envo_extra_enqueue_header_css', 9999);

function envo_extra_is_gutenberg_active() {
	$gutenberg    = false;
	$block_editor = false;

	if ( has_filter( 'replace_editor', 'gutenberg_init' ) ) {
		// Gutenberg is installed and activated.
		$gutenberg = true;
	}

	if ( version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' ) ) {
		// Block editor.
		$block_editor = true;
	}

	if ( ! $gutenberg && ! $block_editor ) {
		return false;
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
		return true;
	}

	if ( is_plugin_active( 'classic-editor/classic-editor.php' ) && get_option( 'classic-editor-replace' ) === 'block' ) {
		return true;
	}

}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'envo_action_links');

function envo_action_links($links) {
    $links['install_demos'] = sprintf('<a href="%1$s" class="install-demos">%2$s</a>', esc_url(admin_url('themes.php?page=envothemes-panel-install-demos')), esc_html__('Install Demos', 'envo-extra'));
	$theme = wp_get_theme();
	if ( 'Enwoo' == $theme->name || 'enwoo' == $theme->template ) {
		$url = 'https://enwoo-wp.com/enwoo-pro/';
	} elseif ( 'Entr' == $theme->name || 'entr' == $theme->template ){
		$url = 'https://envothemes.com/product/envo-pro/';
	} else {
		$url = 'https://envothemes.com/';
	}
    if (!envo_extra_check_for_pro('enwoo')) {
        $links['go_pro'] = sprintf('<a href="%1$s" target="_blank" class="elementor-plugins-gopro">%2$s</a>', esc_url($url), esc_html__('Go Pro', 'envo-extra'));
    }
    return $links;
}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );