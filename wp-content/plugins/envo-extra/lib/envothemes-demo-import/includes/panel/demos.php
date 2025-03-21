<?php
/**
 * Demos
 *
 * @package EnvoThemes_Demo_Import
 * @category Core
 * @author EnvoThemes
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( !class_exists( 'EnvoThemes_Demos' ) ) {

	class EnvoThemes_Demos {

		/**
		 * Start things up
		 */
		public function __construct() {

			// Return if not in admin
			if ( !is_admin() || is_customize_preview() ) {
				return;
			}

			// Import demos page
			if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
				require_once( ENVO_PATH . '/includes/panel/classes/importers/class-helpers.php' );
				require_once( ENVO_PATH . '/includes/panel/classes/class-install-demos.php' );
			}

			// Disable Woo Wizard
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
			add_filter( 'woocommerce_show_admin_notice', '__return_false' );
			add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_false' );

			// Start things
			add_action( 'admin_init', array( $this, 'init' ) );

			// Demos scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

			// Allows xml uploads
			add_filter( 'upload_mimes', array( $this, 'allow_xml_uploads' ) );

			// Demos popup
			add_action( 'admin_footer', array( $this, 'popup' ) );
		}

		/**
		 * Register the AJAX methods
		 *
		 * @since 1.0.0
		 */
		public function init() {

			// Demos popup ajax
			add_action( 'wp_ajax_envo_ajax_get_demo_data', array( $this, 'ajax_demo_data' ) );
			add_action( 'wp_ajax_envo_ajax_required_plugins_activate', array( $this, 'ajax_required_plugins_activate' ) );

			// Get data to import
			add_action( 'wp_ajax_envo_ajax_get_import_data', array( $this, 'ajax_get_import_data' ) );

			// Import XML file
			add_action( 'wp_ajax_envo_ajax_import_xml', array( $this, 'ajax_import_xml' ) );

			// Import customizer settings
			add_action( 'wp_ajax_envo_ajax_import_theme_settings', array( $this, 'ajax_import_theme_settings' ) );

			// Import widgets
			add_action( 'wp_ajax_envo_ajax_import_widgets', array( $this, 'ajax_import_widgets' ) );

			// Import widgets
			add_action( 'wp_ajax_envo_ajax_envo_activate_enwoo', array( $this, 'ajax_activate_enwoo' ) );

			// After import
			add_action( 'wp_ajax_envo_after_import', array( $this, 'ajax_after_import' ) );
		}

		/**
		 * Load scripts
		 *
		 * @since 1.4.5
		 */
		public static function scripts( $hook_suffix ) {

			if ( 'appearance_page_envothemes-panel-install-demos' == $hook_suffix ) {

				// CSS
				wp_enqueue_style( 'envo-demos-style', plugins_url( '/assets/css/demos.css', __FILE__ ) );

				// JS
				wp_enqueue_script( 'envo-demos-js', plugins_url( '/assets/js/demos.js', __FILE__ ), array( 'jquery', 'wp-util', 'updates' ), '1.0', true );

				wp_localize_script( 'envo-demos-js', 'envoDemos', array(
					'ajaxurl'					 => admin_url( 'admin-ajax.php' ),
					'demo_data_nonce'			 => wp_create_nonce( 'get-demo-data' ),
					'envo_import_data_nonce'	 => wp_create_nonce( 'envo_import_data_nonce' ),
					'content_importing_error'	 => esc_html__( 'There was a problem during the importing process resulting in the following error from your server:', 'envothemes-demo-import' ),
					'button_activating'			 => esc_html__( 'Activating', 'envothemes-demo-import' ) . '&hellip;',
					'button_active'				 => esc_html__( 'Active', 'envothemes-demo-import' ),
				) );
			}

			//wp_enqueue_style('envo-notices', plugins_url('/assets/css/notify.css', __FILE__));
		}

		/**
		 * Allows xml uploads so we can import from server
		 *
		 * @since 1.0.0
		 */
		public function allow_xml_uploads( $mimes ) {
			$mimes = array_merge( $mimes, array(
				'xml' => 'application/xml'
			) );
			return $mimes;
		}

		/**
		 * Get demos data to add them in the Demo Import and Pro Demos plugins
		 *
		 * @since 1.4.5
		 */
		public static function get_demos_data() {
			$theme		 = wp_get_theme();
			$enwoo_data	 = array();
			$data_royal	 = array();
			$data_one	 = array();
			$data		 = array();
			// Demos url
			$img_url	 = ENVO_URL . 'img/demos/' . $theme->template . '/';
			$url		 = 'http://envothemes.com/wp-content/uploads/demo-import/' . $theme->template . '/';

			if ( 'Envo Shop' == $theme->name || 'envo-shop' == $theme->template ) {
				$url		 = 'http://envo-demos.com/wp-content/uploads/demo-import/' . $theme->template . '/';
				$data = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'WooCommerce' ),
						'xml_file'			 => $url . 'default/default-content.xml',
						'theme_settings'	 => $url . 'default/default-customizer.dat',
						'widgets_file'		 => $url . 'default/default-widgets.wie',
						'screenshot'		 => $img_url . 'default/screenshot.webp',
						'demo_link'			 => 'https://envo-demos.com/envo-shop/',
						'home_title'		 => 'Home',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1400',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '1',
						'woo_crop_height'	 => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
								),
							),
							'premium'		 => array(),
						),
					),
				);
			} elseif ( 'Envo Shopper' == $theme->name || 'envo-shopper' == $theme->template || 'Envo Online Store' == $theme->name || 'envo-online-store' == $theme->template || 'Envo Marketplace' == $theme->name || 'envo-marketplace' == $theme->template ) {
				$data = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'WooCommerce' ),
						'xml_file'			 => $url . 'default/default-content.xml',
						'theme_settings'	 => $url . 'default/default-customizer.dat',
						'widgets_file'		 => $url . 'default/default-widgets.wie',
						'screenshot'		 => $img_url . 'default/screenshot.webp',
						'demo_link'			 => 'https://envothemes.com/' . $theme->template,
						'home_title'		 => 'Home',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
								),
							),
							'premium'		 => array(),
						),
					),
				);
			} elseif ( 'Envo eCommerce' == $theme->name || 'envo-ecommerce' == $theme->template || 'Envo Storefront' == $theme->name || 'envo-storefront' == $theme->template ) {
				$data = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'WooCommerce' ),
						'xml_file'			 => $url . 'default/default-content.xml',
						'theme_settings'	 => $url . 'default/default-customizer.dat',
						'widgets_file'		 => $url . 'default/default-widgets.wie',
						'screenshot'		 => $img_url . 'default/screenshot.webp',
						'demo_link'			 => 'https://envothemes.com/' . $theme->template,
						'home_title'		 => 'Home',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '1',
						'woo_crop_height'	 => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
					),
				);
			} elseif ( 'Entr' == $theme->name ) {
				$data = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'WooCommerce', 'Elementor', 'Free', 'Business', 'Landing Page' ),
						'xml_file'			 => $url . 'content.xml',
						'theme_settings'	 => $url . 'customizer.dat',
						'widgets_file'		 => $url . 'widgets.wie',
						'screenshot'		 => $img_url . 'screenshot.webp',
						'demo_link'			 => 'https://envothemes.com/entr/',
						'home_title'		 => 'Entr Homepage',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '1',
						'woo_crop_height'	 => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '(Only for shop purposes.)',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '(Only for shop purposes.)',
								),
							),
							'premium'		 => array(),
						),
					),
				);
			} elseif ( 'Altr' == $theme->name ) {
				$img_url = ENVO_URL . 'img/demos/altr/';
				$url	 = 'http://envothemes.com/wp-content/uploads/demo-import/altr/';
				$data	 = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'			 => $url . 'content.xml',
						'theme_settings'	 => $url . 'customizer.dat',
						'widgets_file'		 => $url . 'widgets.wie',
						'screenshot'		 => $img_url . 'screenshot.webp',
						'home_title'		 => 'Altr Homepage',
						'demo_link'			 => 'http://envothemes.com/altr/',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
					),
				);
			} elseif ( 'Spacr' == $theme->name ) {
				$img_url = ENVO_URL . 'img/demos/spacr/';
				$url	 = 'http://envothemes.com/wp-content/uploads/demo-import/spacr/';
				$data	 = array(
					$theme->template => array(
						'demo_name'						 => $theme->name,
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot.webp',
						'home_title'					 => 'Spacr Homepage',
						'demo_link'						 => 'http://envothemes.com/spacr/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1140',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '2',
						'woo_crop_height'				 => '3',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
					),
					'homepage-2'	 => array(
						'demo_name'						 => 'Spacr #2',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot-2.webp',
						'home_title'					 => 'Homepage #2',
						'demo_link'						 => 'http://envothemes.com/spacr/homepage-2/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1140',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '2',
						'woo_crop_height'				 => '3',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
					),
					'homepage-3'	 => array(
						'demo_name'						 => 'Spacr #3',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot-3.webp',
						'home_title'					 => 'Homepage #3',
						'demo_link'						 => 'http://envothemes.com/spacr/homepage-3/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1140',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '2',
						'woo_crop_height'				 => '3',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
					),
					'homepage-4'	 => array(
						'demo_name'						 => 'Spacr #4',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot-4.webp',
						'home_title'					 => 'Homepage #4',
						'demo_link'						 => 'http://envothemes.com/spacr/homepage-4/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1140',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '2',
						'woo_crop_height'				 => '3',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
					),
					'homepage-5'	 => array(
						'demo_name'						 => 'Spacr #5',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot-5.webp',
						'home_title'					 => 'Homepage #5',
						'demo_link'						 => 'http://envothemes.com/spacr/homepage-5/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1140',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '2',
						'woo_crop_height'				 => '3',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
					),
				);				
			} elseif ( 'Lupr' == $theme->name || 'lupr' == $theme->template ) {
				$data = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'WooCommerce', 'Gutenberg', 'Free', 'Business', 'Landing Page', 'FSE' ),
						'xml_file'			 => $url . 'content.xml',
						'theme_settings'	 => $url . 'customizer.dat',
						'widgets_file'		 => $url . 'widgets.wie',
						'screenshot'		 => $img_url . 'screenshot.webp',
						'demo_link'			 => 'http://envothemes.com/lupr/',
						//'home_title' => 'Lupr Homepage',
						//'blog_title' => 'Blog',
						//'posts_to_show' => '6',
						//'elementor_width' => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '1',
						'woo_crop_height'	 => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '(Only for shop purposes.)',
								),
							),
							'premium'		 => array(),
						),
					),
				);
			} elseif ( 'Lancr' == $theme->name || 'lancr' == $theme->template ) {
				$img_url	 = ENVO_URL . 'img/demos/' . $theme->template . '/';
				$enwoo_url		 = 'http://enwoo-wp.com/wp-content/uploads/demo-import/';
				$data			 = array(
					$theme->template => array(
						'demo_name'			 => $theme->name,
						'categories'		 => array( 'Gutenberg', 'Free', 'Blog', 'Newspaper', 'FSE' ),
						'xml_file'			 => $url . 'content.xml',
						'theme_settings'	 => $url . 'customizer.dat',
						'widgets_file'		 => $url . 'widgets.wie',
						'screenshot'		 => $img_url . 'screenshot.webp',
						'demo_link'			 => 'https://envothemes.com/fse-demo/',
						//'home_title' => 'Lancr Homepage',
						//'blog_title' => 'Blog',
						//'posts_to_show' => '6',
						//'elementor_width' => '1140',
						'is_shop'			 => false,
//                        'woo_image_size' => '600',
//                        'woo_thumb_size' => '300',
//                        'woo_crop_width' => '1',
//                        'woo_crop_height' => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
					),
					'free-demo-9'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #9',
						'categories'		 => array( 'Gutenberg', 'Free', 'Blog', 'Newspaper' ),
						'xml_file'			 => $enwoo_url . 'free-9/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-9/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-9/widgets.wie',
						'screenshot'		 => $img_url . 'free-9/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-9/',
						//'home_title' => 'Lancr Homepage',
						//'blog_title' => 'Blog',
						//'posts_to_show' => '6',
						//'elementor_width' => '1140',
						'is_shop'			 => false,
//                        'woo_image_size' => '600',
//                        'woo_thumb_size' => '300',
//                        'woo_crop_width' => '1',
//                        'woo_crop_height' => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-10'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #10',
						'categories'		 => array( 'Gutenberg', 'Free', 'Blog', 'Newspaper' ),
						'xml_file'			 => $enwoo_url . 'free-10/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-10/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-10/widgets.wie',
						'screenshot'		 => $img_url . 'free-10/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-10/',
						//'home_title' => 'Lancr Homepage',
						//'blog_title' => 'Blog',
						//'posts_to_show' => '6',
						//'elementor_width' => '1140',
						'is_shop'			 => false,
//                        'woo_image_size' => '600',
//                        'woo_thumb_size' => '300',
//                        'woo_crop_width' => '1',
//                        'woo_crop_height' => '1',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
				);
			} elseif ( 'Enwoo' == $theme->name ) {
				// Enwoo Demos url
				$img_url	 = ENVO_URL . 'img/demos/enwoo/';
				$enwoo_url		 = 'http://enwoo-wp.com/wp-content/uploads/demo-import/';

				$data	 = array(
					'free-demo-8'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #8',
						'categories'		 => array( 'Elementor', 'Free', 'Business', 'WooCommerce' ),
						'xml_file'			 => $enwoo_url . 'free-8/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-8/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-8/widgets.wie',
						'screenshot'		 => $img_url . 'free-8/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-8/',
						'home_title'		 => 'Home Free #8',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '(Only for shop purposes.)',
								),
							),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-1'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #1',
						'categories'		 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'			 => $enwoo_url . 'free-1/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-1/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-1/widgets.wie',
						'screenshot'		 => $img_url . 'free-1/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-1/',
						'home_title'		 => 'Home Free #1',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
								),
							),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-2'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #2',
						'categories'		 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'			 => $enwoo_url . 'free-2/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-2/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-2/widgets.wie',
						'screenshot'		 => $img_url . 'free-2/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-2/',
						'home_title'		 => 'Home Free #2',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
								),
							),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-3'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #3',
						'categories'		 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'			 => $enwoo_url . 'free-3/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-3/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-3/widgets.wie',
						'screenshot'		 => $img_url . 'free-3/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-3/',
						'home_title'		 => 'Home Free #3',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-4'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #4',
						'categories'		 => array( 'WooCommerce', 'Elementor', 'Free', 'Business' ),
						'xml_file'			 => $enwoo_url . 'free-4/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-4/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-4/widgets.wie',
						'screenshot'		 => $img_url . 'free-4/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-4/',
						'home_title'		 => 'Home Free #4',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-5'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #5',
						'categories'		 => array( 'WooCommerce', 'Gutenberg', 'Free', 'Business' ),
						'xml_file'			 => $enwoo_url . 'free-5/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-5/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-5/widgets.wie',
						'screenshot'		 => $img_url . 'free-5/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-5/',
						'home_title'		 => 'Home Free #5',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-6'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #6',
						'categories'		 => array( 'Gutenberg', 'Free', 'Business' ),
						'xml_file'			 => $enwoo_url . 'free-6/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-6/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-6/widgets.wie',
						'screenshot'		 => $img_url . 'free-6/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-6/',
						'home_title'		 => 'Home Free #6',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
					'free-demo-7'	 => array(
						'demo_name'			 => 'Enwoo Free Demo #7',
						'categories'		 => array( 'Elementor', 'Free', 'Business', 'WooCommerce' ),
						'xml_file'			 => $enwoo_url . 'free-7/content.xml',
						'theme_settings'	 => $enwoo_url . 'free-7/customizer.dat',
						'widgets_file'		 => $enwoo_url . 'free-7/widgets.wie',
						'screenshot'		 => $img_url . 'free-7/screenshot.webp',
						'demo_link'			 => 'https://enwoo-demos.com/free-demo-7/',
						'home_title'		 => 'Home Free #7',
						'blog_title'		 => 'Blog',
						'posts_to_show'		 => '6',
						'elementor_width'	 => '1140',
						'is_shop'			 => true,
						'woo_image_size'	 => '600',
						'woo_thumb_size'	 => '300',
						'woo_crop_width'	 => '2',
						'woo_crop_height'	 => '3',
						'required_plugins'	 => array(
							'free'			 => array(
								array(
									'slug'	 => 'envo-extra',
									'init'	 => 'envo-extra/envo-extra.php',
									'name'	 => 'Envo Extra',
								),
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
								),
							),
							'recommended'	 => array(),
							'premium'		 => array(),
						),
						'required_theme'	 => 'Enwoo',
						'required_theme_url'	 => 'https://enwoo-wp.com/',
					),
				);
			}
			
			$img_url = ENVO_URL . 'img/demos/envo-royal/';
			$url		= 'http://envo-demos.com/wp-content/uploads/demo-import/envo-royal/envo-royal/';
			$url_woo	= 'http://envo-demos.com/wp-content/uploads/demo-import/envo-royal/envo-royal-woocommerce/';
			$data_royal	 = array(
				'home-free-1' => array(
					'demo_name'						 => 'Envo Royal Home #1',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url . 'content.xml',
					'theme_settings'				 => $url . 'customizer.dat',
					'widgets_file'					 => $url . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-1-new.webp',
					'home_title'					 => 'Home Free #1',
					'demo_link'						 => 'http://envo-demos.com/envo-royal/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
								'notice' => 'For Shop purposes only',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
				'home-free-2'	 => array(
					'demo_name'						 => 'Envo Royal Home #2',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url . 'content.xml',
					'theme_settings'				 => $url . 'customizer.dat',
					'widgets_file'					 => $url . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-2.webp',
					'home_title'					 => 'Home Free #2',
					'demo_link'						 => 'http://envo-demos.com/envo-royal/home-free-2/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
							array(
								'slug'	 => 'envo-elementor-for-woocommerce',
								'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
								'name'	 => 'Elementor Templates and Widgets',
								'notice' => '',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
								'notice' => 'For Shop purposes only',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
				'home-free-3'	 => array(
					'demo_name'						 => 'Envo Royal Home #3',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url . 'content.xml',
					'theme_settings'				 => $url . 'customizer.dat',
					'widgets_file'					 => $url . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-3.webp',
					'home_title'					 => 'Home Free #3',
					'demo_link'						 => 'http://envo-demos.com/envo-royal/home-free-3/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
							array(
								'slug'	 => 'envo-elementor-for-woocommerce',
								'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
								'name'	 => 'Elementor Templates and Widgets',
								'notice' => '',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
								'notice' => 'For Shop purposes only',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
				'home-free-4'	 => array(
					'demo_name'						 => 'Envo Royal Home #4',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url . 'content.xml',
					'theme_settings'				 => $url . 'customizer.dat',
					'widgets_file'					 => $url . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-4.webp',
					'home_title'					 => 'Home Free #4',
					'demo_link'						 => 'http://envo-demos.com/envo-royal/home-free-4/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
							array(
								'slug'	 => 'envo-elementor-for-woocommerce',
								'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
								'name'	 => 'Elementor Templates and Widgets',
								'notice' => '',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
								'notice' => 'For Shop purposes only',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
				'home-free-woo-1'	 => array(
					'demo_name'						 => 'Envo Royal Woo Home #1',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url_woo . 'content.xml',
					'theme_settings'				 => $url_woo . 'customizer.dat',
					'widgets_file'					 => $url_woo . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-woo-1.webp',
					'home_title'					 => 'Home Free WooCommerce #1',
					'demo_link'						 => 'http://envo-demos.com/envo-royal-woocommerce/home-free-woocommerce-1/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
							array(
								'slug'	 => 'envo-elementor-for-woocommerce',
								'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
								'name'	 => 'Elementor Templates and Widgets',
							),
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'yith-woocommerce-wishlist',
								'init'	 => 'yith-woocommerce-wishlist/init.php',
								'name'	 => 'YITH WooCommerce Wishlist',
							),
							array(
								'slug'	 => 'yith-woocommerce-compare',
								'init'	 => 'yith-woocommerce-compare/init.php',
								'name'	 => 'YITH WooCommerce Compare',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
				'home-free-woo-2'	 => array(
					'demo_name'						 => 'Envo Royal Woo Home #2',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url_woo . 'content.xml',
					'theme_settings'				 => $url_woo . 'customizer.dat',
					'widgets_file'					 => $url_woo . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-woo-2.webp',
					'home_title'					 => 'Home Free WooCommerce #2',
					'demo_link'						 => 'http://envo-demos.com/envo-royal-woocommerce/home-free-woocommerce-2/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
							array(
								'slug'	 => 'envo-elementor-for-woocommerce',
								'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
								'name'	 => 'Elementor Templates and Widgets',
							),
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'yith-woocommerce-wishlist',
								'init'	 => 'yith-woocommerce-wishlist/init.php',
								'name'	 => 'YITH WooCommerce Wishlist',
							),
							array(
								'slug'	 => 'yith-woocommerce-compare',
								'init'	 => 'yith-woocommerce-compare/init.php',
								'name'	 => 'YITH WooCommerce Compare',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
				'home-free-woo-3'	 => array(
					'demo_name'						 => 'Envo Royal Woo Home #3',
					'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
					'xml_file'						 => $url_woo . 'content.xml',
					'theme_settings'				 => $url_woo . 'customizer.dat',
					'widgets_file'					 => $url_woo . 'widgets.wie',
					'screenshot'					 => $img_url . 'screenshot-woo-3.webp',
					'home_title'					 => 'Home Free WooCommerce #3',
					'demo_link'						 => 'http://envo-demos.com/envo-royal-woocommerce/home-free-woocommerce-3/',
					'blog_title'					 => 'Blog',
					'posts_to_show'					 => '6',
					'elementor_width'				 => '1140',
					'is_shop'						 => true,
					'elementor_experiment-container' => 'active',
					'woo_image_size'				 => '600',
					'woo_thumb_size'				 => '300',
					'woo_crop_width'				 => '2',
					'woo_crop_height'				 => '3',
					'required_plugins'				 => array(
						'free'			 => array(
							array(
								'slug'	 => 'elementor',
								'init'	 => 'elementor/elementor.php',
								'name'	 => 'Elementor',
							),
							array(
								'slug'	 => 'envo-elementor-for-woocommerce',
								'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
								'name'	 => 'Elementor Templates and Widgets',
							),
							array(
								'slug'	 => 'woocommerce',
								'init'	 => 'woocommerce/woocommerce.php',
								'name'	 => 'WooCommerce',
							),
						),
						'recommended'	 => array(
							array(
								'slug'	 => 'yith-woocommerce-wishlist',
								'init'	 => 'yith-woocommerce-wishlist/init.php',
								'name'	 => 'YITH WooCommerce Wishlist',
							),
							array(
								'slug'	 => 'yith-woocommerce-compare',
								'init'	 => 'yith-woocommerce-compare/init.php',
								'name'	 => 'YITH WooCommerce Compare',
							),
						),
						'premium'		 => array(),
					),
					'required_theme'		=> 'Envo Royal',
					'required_theme_url'	=> 'https://envothemes.com/envo-royal-free-wp-theme/',
				),
			);
			if ( 'Envo Royal' !== $theme->name ) {
				$img_url = ENVO_URL . 'img/demos/envo-one/';
				$url	 = 'http://envo-demos.com/wp-content/uploads/demo-import/envo-one/';
				$data_one	 = array(
					'envo-one-homepage-1' => array(
						'demo_name'						 => 'Envo One Home #1',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot.webp',
						'home_title'					 => 'Envo One Homepage #1',
						'demo_link'						 => 'http://envo-demos.com/envo-one/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1270',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '1',
						'woo_crop_height'				 => '1',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
						'required_theme'		=> 'Envo One',
						'required_theme_url'	=> 'https://envothemes.com/envo-one-woocommerce/',
					),
					'envo-one-homepage-2'	 => array(
						'demo_name'						 => 'Envo One Home #2',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot-2.webp',
						'home_title'					 => 'Envo One Homepage #2',
						'demo_link'						 => 'http://envo-demos.com/envo-one/homepage-2/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1270',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '1',
						'woo_crop_height'				 => '1',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
						'required_theme'		=> 'Envo One',
						'required_theme_url'	=> 'https://envothemes.com/envo-one-woocommerce/',
					),
					'envo-one-homepage-3'	 => array(
						'demo_name'						 => 'Envo One Home #3',
						'categories'					 => array( 'WooCommerce', 'Elementor', 'Free' ),
						'xml_file'						 => $url . 'content.xml',
						'theme_settings'				 => $url . 'customizer.dat',
						'widgets_file'					 => $url . 'widgets.wie',
						'screenshot'					 => $img_url . 'screenshot-3.webp',
						'home_title'					 => 'Envo One Homepage #3',
						'demo_link'						 => 'http://envo-demos.com/envo-one/homepage-3/',
						'blog_title'					 => 'Blog',
						'posts_to_show'					 => '6',
						'elementor_width'				 => '1270',
						'is_shop'						 => true,
						'elementor_experiment-container' => 'active',
						'woo_image_size'				 => '600',
						'woo_thumb_size'				 => '300',
						'woo_crop_width'				 => '1',
						'woo_crop_height'				 => '1',
						'required_plugins'				 => array(
							'free'			 => array(
								array(
									'slug'	 => 'elementor',
									'init'	 => 'elementor/elementor.php',
									'name'	 => 'Elementor',
								),
								array(
									'slug'	 => 'woocommerce',
									'init'	 => 'woocommerce/woocommerce.php',
									'name'	 => 'WooCommerce',
									'notice' => '',
								),
								array(
									'slug'	 => 'envo-elementor-for-woocommerce',
									'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
									'name'	 => 'Elementor Templates and Widgets for WooCommerce',
									'notice' => '',
								),
							),
							'recommended'	 => array(
								array(
									'slug'	 => 'yith-woocommerce-wishlist',
									'init'	 => 'yith-woocommerce-wishlist/init.php',
									'name'	 => 'YITH WooCommerce Wishlist',
									'notice' => 'WooCommerce Whishlist Function',
								),
								array(
									'slug'	 => 'yith-woocommerce-compare',
									'init'	 => 'yith-woocommerce-compare/init.php',
									'name'	 => 'YITH WooCommerce Compare',
									'notice' => 'WooCommerce Compare Function',
								),
							),
							'premium'		 => array(),
						),
						'required_theme'		=> 'Envo One',
						'required_theme_url'	=> 'https://envothemes.com/envo-one-woocommerce/',
					),
				);				
			}
			if (defined('ENVO_PRO_CURRENT_VERSION') || defined('ENWOO_PRO_CURRENT_VERSION') || defined('ENVO_SHOPPER_PRO_CURRENT_VERSION') || defined('ENVO_ECOMMERCE_PRO_CURRENT_VERSION') || defined('ENVO_STOREFRONT_PRO_CURRENT_VERSION') || defined('ENVO_SHOP_PRO_CURRENT_VERSION') || defined('ENVO_ONLINE_STORE_PRO_CURRENT_VERSION') || defined('ENVO_MARKETPLACE_PRO_CURRENT_VERSION') || defined('ENVO_SHOPPER_PRO_CURRENT_VERSION')) {
				$data_royal = array();
				$data_one = array();
			}
			// combine the two arrays
			$data		 = array_merge( $data, $data_one );
			$data		 = array_merge( $data, $data_royal );
			// Return
			return apply_filters( 'envo_demos_data', $data );
		}

		/**
		 * Get the category list of all categories used in the predefined demo imports array.
		 *
		 * @since 1.4.5
		 */
		public static function get_demo_all_categories( $demo_imports ) {
			$categories = array();

			foreach ( $demo_imports as $item ) {
				if ( !empty( $item[ 'categories' ] ) && is_array( $item[ 'categories' ] ) ) {
					foreach ( $item[ 'categories' ] as $category ) {
						$categories[ sanitize_key( $category ) ] = $category;
					}
				}
			}

			if ( empty( $categories ) ) {
				return false;
			}

			return $categories;
		}

		/**
		 * Return the concatenated string of demo import item categories.
		 * These should be separated by comma and sanitized properly.
		 *
		 * @since 1.4.5
		 */
		public static function get_demo_item_categories( $item ) {
			$sanitized_categories = array();

			if ( isset( $item[ 'categories' ] ) ) {
				foreach ( $item[ 'categories' ] as $category ) {
					$sanitized_categories[] = sanitize_key( $category );
				}
			}

			if ( !empty( $sanitized_categories ) ) {
				return implode( ',', $sanitized_categories );
			}

			return false;
		}

		/**
		 * Demos popup
		 *
		 * @since 1.4.5
		 */
		public static function popup() {
			global $pagenow;
			if ( isset( $_GET[ 'page' ] ) ) {
				// Display on the demos pages
				if ( ( 'themes.php' == $pagenow && 'envothemes-panel-install-demos' == $_GET[ 'page' ] ) ) {
					?>

					<div id="envo-demo-popup-wrap">
						<div class="envo-demo-popup-container">
							<div class="envo-demo-popup-content-wrap">
								<div class="envo-demo-popup-content-inner">
									<a href="#" class="envo-demo-popup-close">×</a>
									<div id="envo-demo-popup-content"></div>
								</div>
							</div>
						</div>
						<div class="envo-demo-popup-overlay"></div>
					</div>

					<?php
				}
			}
		}

		/**
		 * Demos popup ajax.
		 *
		 * @since 1.4.5
		 */
		public static function ajax_demo_data() {

			if ( !current_user_can( 'manage_options' ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET[ 'demo_data_nonce' ] ) ), 'get-demo-data' ) ) {
				die( 'This action was stopped for security purposes.' );
			}

			// Database reset url
			if ( is_plugin_active( 'wordpress-database-reset/wp-reset.php' ) ) {
				$plugin_link = admin_url( 'tools.php?page=database-reset' );
			} else {
				$plugin_link = admin_url( 'plugin-install.php?s=WordPress+Database+Reset&tab=search' );
			}

			// Get all demos
			$demos = self::get_demos_data();

			// Get selected demo
			if ( isset( $_GET[ 'demo_name' ] ) ) {
				$demo = sanitize_text_field( wp_unslash( $_GET[ 'demo_name' ] ) );
			}

			// Get required plugins
			$plugins = isset( $demos[ $demo ][ 'required_plugins' ] ) ? $demos[ $demo ][ 'required_plugins' ] : '';

			// Get free plugins
			$free = isset( $plugins[ 'free' ] ) ? $plugins[ 'free' ] : '';

			// Get premium plugins
			$premium = isset( $plugins[ 'premium' ] ) ? $plugins[ 'premium' ] : '';

			// Get recommended plugins
			$recommended = isset( $plugins[ 'recommended' ] ) ? $plugins[ 'recommended' ] : '';

			// Get required plugins
			$gutenberg = isset( $demos[ $demo ][ 'categories' ] ) ? $demos[ $demo ][ 'categories' ] : '';

			// Get required theme
			$required_theme	 = isset( $demos[ $demo ][ 'required_theme' ] ) ? $demos[ $demo ][ 'required_theme' ] : '';
			$required_theme_url	 = isset( $demos[ $demo ][ 'required_theme_url' ] ) ? $demos[ $demo ][ 'required_theme_url' ] : '';
			$theme			 = wp_get_theme();
			?>

			<div id="envo-demo-plugins">

				<h2 class="title"><?php echo sprintf( esc_html__( 'Import the %1$s demo', 'envothemes-demo-import' ), esc_attr( $demos[ $demo ][ 'demo_name' ] ) ); ?></h2>

				<div class="envo-popup-text">

					<p><?php
					echo
					sprintf(
					esc_html__( 'Importing demo data allow you to quickly edit everything instead of creating content from scratch. It is recommended uploading sample data on a fresh WordPress install to prevent conflicts with your current content. You can use this plugin to reset your site if needed: %1$sWordpress Database Reset%2$s.', 'envothemes-demo-import' ), '<a href="' . esc_url( $plugin_link ) . '" target="_blank">', '</a>'
					);
					?>
					</p>

					<div class="envo-required-plugins-wrap">
						<h3><?php esc_html_e( 'Required Plugins', 'envothemes-demo-import' ); ?></h3>
						<p><?php esc_html_e( 'For your site to look exactly like this demo, the plugins below need to be activated.', 'envothemes-demo-import' ); ?></p>
						<div class="envo-required-plugins oe-plugin-installer">
						<?php
						self::required_plugins( $free, 'free' );
						self::required_plugins( $premium, 'premium' );
						?>
						</div>
							<?php if ( isset( $recommended ) && !empty( $recommended ) ) { ?>
							<h3><?php esc_html_e( 'Recommended Plugins', 'envothemes-demo-import' ); ?></h3>
							<p><?php esc_html_e( 'These plugins are not required for the demo import. However, if you do not install them, some demo features will not be included.', 'envothemes-demo-import' ); ?></p>
							<div class="envo-required-plugins oe-plugin-installer">
								<?php self::required_plugins( $recommended, 'recommended' ); ?>
							</div>
							<?php } ?>
							<?php if ( (isset( $required_theme ) && !empty( $required_theme )) && ($theme->name != $required_theme) ) { ?>
							<h3><?php esc_html_e( 'Required Theme', 'envo-extra' ); ?></h3>
							<p><?php echo sprintf( esc_html__( 'This demo requires a different active theme. Theme %1$s will be activated after the import.', 'envo-extra' ), '<a href="' . esc_url( $required_theme_url ) . '" target="_blank">' . esc_html( $required_theme ) . '</a>' ); ?></p>
						<?php } ?>	
					</div>

				</div>

				<?php if ( !defined( 'ENWOO_PRO_CURRENT_VERSION' ) && !empty( $premium ) && $premium[ '0' ][ 'slug' ] == 'enwoo-pro' ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( 'https://enwoo-wp.com/enwoo-pro/' ); ?>" target="_blank" >
							<?php esc_html_e( 'Install and activate Enwoo PRO', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } elseif ( defined( 'ENWOO_PRO_CURRENT_VERSION' ) && !defined( 'ENWOO_PRO_STATUS' ) && !empty( $premium ) && $premium[ '0' ][ 'slug' ] == 'enwoo-pro' ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( network_admin_url( 'options-general.php?page=enwoo-license-options' ) ) ?>" >
							<?php esc_html_e( 'Activate Enwoo PRO license', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } elseif ( defined( 'ENWOO_PRO_CURRENT_VERSION' ) && (defined( 'ENWOO_PRO_STATUS' ) && ENWOO_PRO_STATUS == 'expired') && !empty( $premium ) && $premium[ '0' ][ 'slug' ] == 'enwoo-pro' ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( 'https://enwoo-wp.com/my-account/orders/' ) ?>" >
							<?php esc_html_e( 'Product expired! Renew the Enwoo PRO license', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } elseif ( !defined( 'ENVO_ROYAL_PRO_CURRENT_VERSION' ) && !empty( $premium ) && $premium[ '0' ][ 'slug' ] == 'envo-royal-pro' ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( 'https://envothemes.com/product/envo-royal-pro/' ); ?>" target="_blank" >
							<?php esc_html_e( 'Install and activate Envo Royal PRO', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } elseif ( defined( 'ENVO_ROYAL_PRO_CURRENT_VERSION' ) && !defined( 'ENVO_ROYAL_PRO_STATUS' ) && !empty( $premium ) && $premium[ '0' ][ 'slug' ] == 'envo-royal-pro' ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( network_admin_url( 'options-general.php?page=envo-royal-license-options' ) ) ?>" >
							<?php esc_html_e( 'Activate Envo Royal PRO license', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } elseif ( defined( 'ENVO_ROYAL_PRO_CURRENT_VERSION' ) && (defined( 'ENVO_ROYAL_PRO_STATUS' ) && ENVO_ROYAL_PRO_STATUS == 'expired') && !empty( $premium ) && $premium[ '0' ][ 'slug' ] == 'envo-royal-pro' ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( 'https://envothemes.com/my-account/orders/' ) ?>" >
							<?php esc_html_e( 'Product expired! Renew the Envo Royal PRO license', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } elseif ( !envo_extra_is_gutenberg_active() && strpos( json_encode( $gutenberg ), "Gutenberg" ) !== false ) { ?>
					<div class="envo-button envo-plugins-pro">
						<a href="<?php echo esc_url( network_admin_url( 'options-writing.php#classic-editor-options' ) ) ?>" >
							<?php esc_html_e( 'Gutenberg is disabled on your site! Activate it.', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } else { ?>
					<div class="envo-button envo-plugins-next">
						<a href="#">
							<?php esc_html_e( 'Go to the next step', 'envo-extra' ); ?>
						</a>
					</div>
				<?php } ?>

			</div>

			<form method="post" id="envo-demo-import-form">

				<input id="envo_import_demo" type="hidden" name="envo_import_demo" value="<?php echo esc_attr( $demo ); ?>" />

				<div class="envo-demo-import-form-types">

					<h2 class="title"><?php esc_html_e( 'Select what you want to import:', 'envothemes-demo-import' ); ?></h2>

					<ul class="envo-popup-text">
						<li>
							<label for="envo_import_xml">
								<input id="envo_import_xml" type="checkbox" name="envo_import_xml" checked="checked" />
								<strong><?php esc_html_e( 'Import XML Data', 'envothemes-demo-import' ); ?></strong> (<?php esc_html_e( 'pages, posts, images, menus, etc...', 'envothemes-demo-import' ); ?>)
							</label>
						</li>

						<li>
							<label for="envo_theme_settings">
								<input id="envo_theme_settings" type="checkbox" name="envo_theme_settings" checked="checked" />
								<strong><?php esc_html_e( 'Import Customizer Settings', 'envothemes-demo-import' ); ?></strong>
							</label>
						</li>

						<li>
							<label for="envo_import_widgets">
								<input id="envo_import_widgets" type="checkbox" name="envo_import_widgets" checked="checked"/>
								<strong><?php esc_html_e( 'Import Widgets', 'envothemes-demo-import' ); ?></strong>
							</label>
						</li>
						<?php if ( $required_theme == 'Envo Royal' && 'Envo Royal' != $theme->name ) { ?>
							<li>
								<label for="envo_activate_enwoo">
									<input id="envo_activate_enwoo" type="checkbox" name="envo_activate_enwoo" checked="checked" disabled/>
									<strong>
										<?php echo sprintf( esc_html__( 'Activate %1$s theme', 'envothemes-demo-import' ), esc_html( $required_theme ) ); ?>
									</strong>
								</label>
							</li>
						<?php } ?>
					</ul>

				</div>

			<?php wp_nonce_field( 'envo_import_demo_data_nonce', 'envo_import_demo_data_nonce' ); ?>
				<input type="submit" name="submit" class="envo-button envo-import" value="<?php esc_html_e( 'Install this demo', 'envothemes-demo-import' ); ?>"  />

			</form>

			<div class="envo-loader">
				<h2 class="title"><?php esc_html_e( 'The import process could take some time, please be patient', 'envothemes-demo-import' ); ?></h2>
				<div class="envo-import-status envo-popup-text"></div>
			</div>

			<div class="envo-last">
				<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"></circle><path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path></svg>
				<h3><?php esc_html_e( 'Demo Imported!', 'envothemes-demo-import' ); ?></h3>
				<a href="<?php echo esc_url( get_home_url() ); ?>" target="_blank"><?php esc_html_e( 'See the result', 'envothemes-demo-import' ); ?></a>
			</div>

			<?php
			die();
		}

		/**
		 * Required plugins.
		 *
		 * @since 1.4.5
		 */
		public static function required_plugins( $plugins, $return ) {

			foreach ( $plugins as $key => $plugin ) {

				$api = array(
					'slug'	 => isset( $plugin[ 'slug' ] ) ? $plugin[ 'slug' ] : '',
					'init'	 => isset( $plugin[ 'init' ] ) ? $plugin[ 'init' ] : '',
					'name'	 => isset( $plugin[ 'name' ] ) ? $plugin[ 'name' ] : '',
					'notice' => isset( $plugin[ 'notice' ] ) ? $plugin[ 'notice' ] : '',
				);

				if ( !is_wp_error( $api ) ) { // confirm error free
					// Installed but Inactive.
					if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin[ 'init' ] ) && is_plugin_inactive( $plugin[ 'init' ] ) ) {

						$button_classes	 = 'button activate-now button-primary';
						$button_text	 = esc_html__( 'Activate', 'envothemes-demo-import' );

						// Not Installed.
					} elseif ( !file_exists( WP_PLUGIN_DIR . '/' . $plugin[ 'init' ] ) ) {

						$button_classes	 = 'button install-now';
						$button_text	 = esc_html__( 'Install Now', 'envothemes-demo-import' );

						// Active.
					} else {
						$button_classes	 = 'button disabled';
						$button_text	 = esc_html__( 'Activated', 'envothemes-demo-import' );
					}
					?>

					<div class="envo-plugin envo-clr envo-plugin-<?php echo esc_attr( $api[ 'slug' ] ); ?>" data-slug="<?php echo esc_attr( $api[ 'slug' ] ); ?>" data-init="<?php echo esc_attr( $api[ 'init' ] ); ?>">
						<h2><?php echo esc_html( $api[ 'name' ] ); ?></h2>
					<?php if ( $api[ 'notice' ] != '' ) { ?>
							<div class="envo-plugin-notice"><?php esc_html_e( $api[ 'notice' ] ); ?></div>
						<?php } ?>

						<?php
						// If premium plugins and not installed
						if ( 'premium' == $return && !file_exists( WP_PLUGIN_DIR . '/' . $plugin[ 'init' ] ) ) {
							$url = $api[ 'slug' ] == "enwoo-pro "? 'https://enwoo-wp.com' : 'https://envothemes.com';
							?>
							<a class="button" href="<?php echo esc_url( $url ); ?>/product/<?php echo esc_attr( $api[ 'slug' ] ); ?>/" target="_blank"><?php esc_html_e( 'Get This Addon', 'envothemes-demo-import' ); ?></a>
						<?php } else { ?>
							<button class="<?php echo esc_attr( $button_classes ); ?>" data-init="<?php echo esc_attr( $api[ 'init' ] ); ?>" data-slug="<?php echo esc_attr( $api[ 'slug' ] ); ?>" data-name="<?php echo esc_attr( $api[ 'name' ] ); ?>"><?php echo esc_html( $button_text ); ?></button>
						<?php } ?>
					</div>

						<?php
					}
				}
			}

			/**
			 * Required plugins activate
			 *
			 * @since 1.4.5
			 */
			public function ajax_required_plugins_activate() {

				check_ajax_referer( 'envo_import_data_nonce', 'security' );

				if ( !current_user_can( 'install_plugins' ) || !isset( $_POST[ 'init' ] ) || !sanitize_text_field( wp_unslash( $_POST[ 'init' ] ) ) ) {
					wp_send_json_error(
					array(
						'success'	 => false,
						'message'	 => __( 'No plugin specified', 'envothemes-demo-import' ),
					)
					);
				}

				$plugin_init = ( isset( $_POST[ 'init' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'init' ] ) ) : '';
				$activate	 = activate_plugin( $plugin_init, '', false, true );

				if ( is_wp_error( $activate ) ) {
					wp_send_json_error(
					array(
						'success'	 => false,
						'message'	 => $activate->get_error_message(),
					)
					);
				}

				wp_send_json_success(
				array(
					'success'	 => true,
					'message'	 => __( 'Plugin Successfully Activated', 'envothemes-demo-import' ),
				)
				);
			}

			/**
			 * Returns an array containing all the importable content
			 *
			 * @since 1.4.5
			 */
			public function ajax_get_import_data() {
				if ( !current_user_can( 'manage_options' ) ) {
					die( 'This action was stopped for security purposes.' );
				}
				check_ajax_referer( 'envo_import_data_nonce', 'security' );

				echo json_encode(
					array(
						array(
							'input_name' => 'envo_import_xml',
							'action'	 => 'envo_ajax_import_xml',
							'method'	 => 'ajax_import_xml',
							'loader'	 => esc_html__( 'Importing XML Data', 'envothemes-demo-import' )
						),
						array(
							'input_name' => 'envo_theme_settings',
							'action'	 => 'envo_ajax_import_theme_settings',
							'method'	 => 'ajax_import_theme_settings',
							'loader'	 => esc_html__( 'Importing Customizer Settings', 'envothemes-demo-import' )
						),
						array(
							'input_name' => 'envo_import_widgets',
							'action'	 => 'envo_ajax_import_widgets',
							'method'	 => 'ajax_import_widgets',
							'loader'	 => esc_html__( 'Importing Widgets', 'envothemes-demo-import' )
						),
						array(
							'input_name' => 'envo_activate_enwoo',
							'action'	 => 'envo_ajax_envo_activate_enwoo',
							'method'	 => 'ajax_activate_enwoo',
							'loader'	 => esc_html__( 'Activating Required Theme', 'envothemes-demo-import' )
						),
					)
				);

				die();
			}

			public function ajax_activate_enwoo() {
				if ( !current_user_can( 'manage_options' ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo_data_nonce' ] ) ), 'envo_import_demo_data_nonce' ) ) {
					die( 'This action was stopped for security purposes.' );
				}
				$result = '';
				
				// Get the selected demo
				if ( isset( $_POST[ 'envo_import_demo' ] ) ) {
					$demo_type = sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo' ] ) );
				}

				// Get demos data
				$demo = EnvoThemes_Demos::get_demos_data()[ $demo_type ];

				// Settings file
				$theme_settings = isset( $demo[ 'theme_settings' ] ) ? $demo[ 'theme_settings' ] : '';
				$required_theme	 = isset( $demo[ 'required_theme' ] ) ? $demo[ 'required_theme' ] : '';
				$required_theme = str_replace(' ', '-', strtolower($required_theme));

				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				include_once ABSPATH . 'wp-admin/includes/theme.php';

				$api = themes_api(
					'theme_information', array(
						'slug'	 => $required_theme,
						'fields' => array( 'sections' => false ),
					)
				);

				if ( is_wp_error( $api ) ) {
					$status[ 'errorMessage' ] = $api->get_error_message();
					wp_send_json_error( $status );
				}

				$skin		 = new WP_Ajax_Upgrader_Skin();
				$upgrader	 = new Theme_Upgrader( $skin );
				$result		 = $upgrader->install( $api->download_link );


				// Include settings importer
				include ENVO_PATH . 'includes/panel/classes/importers/class-settings-importer.php';

				switch_theme( $required_theme );

				// Import settings.
				$settings_importer	 = new envo_Settings_Importer();
				$result				 = $settings_importer->process_import_file( $theme_settings );

				if ( is_wp_error( $result ) ) {
					echo json_encode( $result->errors );
				} else {
					echo 'successful import';
				}
				die();
			}

			/**
			 * Import XML file
			 *
			 * @since 1.4.5
			 */
			public function ajax_import_xml() {
				if ( !current_user_can( 'manage_options' ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo_data_nonce' ] ) ), 'envo_import_demo_data_nonce' ) ) {
					die( 'This action was stopped for security purposes.' );
				}

				// Get the selected demo
				if ( isset( $_POST[ 'envo_import_demo' ] ) ) {
					$demo_type = sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo' ] ) );
				}

				// Get demos data
				$demo = EnvoThemes_Demos::get_demos_data()[ $demo_type ];

				// Content file
				$xml_file = isset( $demo[ 'xml_file' ] ) ? $demo[ 'xml_file' ] : '';

				// Delete the default post and page
				$sample_page		 = get_page_by_path( 'sample-page', OBJECT, 'page' );
				$hello_world_post	 = get_page_by_path( 'hello-world', OBJECT, 'post' );

				if ( !is_null( $sample_page ) ) {
					wp_delete_post( $sample_page->ID, true );
				}

				if ( !is_null( $hello_world_post ) ) {
					wp_delete_post( $hello_world_post->ID, true );
				}

				// Import Posts, Pages, Images, Menus.
				$result = $this->process_xml( $xml_file );

				if ( is_wp_error( $result ) ) {
					echo json_encode( $result->errors );
				} else {
					echo 'successful import';
				}

				die();
			}

			/**
			 * Import customizer settings
			 *
			 * @since 1.4.5
			 */
			public function ajax_import_theme_settings() {
				if ( !current_user_can( 'manage_options' ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo_data_nonce' ] ) ), 'envo_import_demo_data_nonce' ) ) {
					die( 'This action was stopped for security purposes.' );
				}

				// Include settings importer
				include ENVO_PATH . 'includes/panel/classes/importers/class-settings-importer.php';

				// Get the selected demo
				if ( isset( $_POST[ 'envo_import_demo' ] ) ) {
					$demo_type = sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo' ] ) );
				}

				// Get demos data
				$demo = EnvoThemes_Demos::get_demos_data()[ $demo_type ];

				// Settings file
				$theme_settings = isset( $demo[ 'theme_settings' ] ) ? $demo[ 'theme_settings' ] : '';

				// Import settings.
				$settings_importer	 = new envo_Settings_Importer();
				$result				 = $settings_importer->process_import_file( $theme_settings );

				if ( is_wp_error( $result ) ) {
					echo json_encode( $result->errors );
				} else {
					echo 'successful import';
				}

				die();
			}

			/**
			 * Import widgets
			 *
			 * @since 1.4.5
			 */
			public function ajax_import_widgets() {
				if ( !current_user_can( 'manage_options' ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo_data_nonce' ] ) ), 'envo_import_demo_data_nonce' ) ) {
					die( 'This action was stopped for security purposes.' );
				}

				// Include widget importer
				include ENVO_PATH . 'includes/panel/classes/importers/class-widget-importer.php';

				// Get the selected demo
				if ( isset( $_POST[ 'envo_import_demo' ] ) ) {
					$demo_type = sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo' ] ) );
				}

				// Get demos data
				$demo = EnvoThemes_Demos::get_demos_data()[ $demo_type ];

				// Widgets file
				$widgets_file = isset( $demo[ 'widgets_file' ] ) ? $demo[ 'widgets_file' ] : '';

				// Import settings.
				$widgets_importer	 = new Envo_Widget_Importer();
				$result				 = $widgets_importer->process_import_file( $widgets_file );

				if ( is_wp_error( $result ) ) {
					echo json_encode( $result->errors );
				} else {
					echo 'successful import';
				}

				die();
			}
			
			public function new_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
				$args  = array(
					'title'                  => $page_title,
					'post_type'              => $post_type,
					'post_status'            => get_post_stati(),
					'posts_per_page'         => 1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'no_found_rows'          => true,
					'orderby'                => 'post_date ID',
					'order'                  => 'ASC',
				);
				$query = new WP_Query( $args );
				$pages = $query->posts;

				if ( empty( $pages ) ) {
					return null;
				}

				return get_post( $pages[0], $output );
			}

			/**
			 * After import
			 *
			 * @since 1.4.5
			 */
			public function ajax_after_import() {
				if ( !current_user_can( 'manage_options' ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo_data_nonce' ] ) ), 'envo_import_demo_data_nonce' ) ) {
					die( 'This action was stopped for security purposes.' );
				}

				// Get the selected demo
				if ( isset( $_POST[ 'envo_import_demo' ] ) ) {
					$demo_type = sanitize_text_field( wp_unslash( $_POST[ 'envo_import_demo' ] ) );
				}
				// Get demos data
				$demo = EnvoThemes_Demos::get_demos_data()[ $demo_type ];

				// If XML file is imported
				if ( $_POST[ 'envo_import_is_xml' ] === 'true' ) {

					// Elementor width setting
					$elementor_width = isset( $demo[ 'elementor_width' ] ) ? $demo[ 'elementor_width' ] : '';

					// Elementor experiment container
					$elementor_container = isset( $demo[ 'elementor_experiment-container' ] ) ? $demo[ 'elementor_experiment-container' ] : '';

					// Reading settings
					$homepage_title	 = isset( $demo[ 'home_title' ] ) ? $demo[ 'home_title' ] : 'Home';
					$blog_title		 = isset( $demo[ 'blog_title' ] ) ? $demo[ 'blog_title' ] : '';

					// Posts to show on the blog page
					$posts_to_show = isset( $demo[ 'posts_to_show' ] ) ? $demo[ 'posts_to_show' ] : '';

					// If shop demo
					$shop_demo = isset( $demo[ 'is_shop' ] ) ? $demo[ 'is_shop' ] : false;

					// Product image size
					$image_size		 = isset( $demo[ 'woo_image_size' ] ) ? $demo[ 'woo_image_size' ] : '';
					$thumbnail_size	 = isset( $demo[ 'woo_thumb_size' ] ) ? $demo[ 'woo_thumb_size' ] : '';
					$crop_width		 = isset( $demo[ 'woo_crop_width' ] ) ? $demo[ 'woo_crop_width' ] : '';
					$crop_height	 = isset( $demo[ 'woo_crop_height' ] ) ? $demo[ 'woo_crop_height' ] : '';

					// Assign WooCommerce pages if WooCommerce Exists
					if ( class_exists( 'WooCommerce' ) && true == $shop_demo ) {

						$woopages = array(
							'woocommerce_shop_page_id'				 => 'Shop',
							'woocommerce_cart_page_id'				 => 'Cart',
							'woocommerce_checkout_page_id'			 => 'Checkout',
							'woocommerce_pay_page_id'				 => 'Checkout &#8594; Pay',
							'woocommerce_thanks_page_id'			 => 'Order Received',
							'woocommerce_myaccount_page_id'			 => 'My Account',
							'woocommerce_edit_address_page_id'		 => 'Edit My Address',
							'woocommerce_view_order_page_id'		 => 'View Order',
							'woocommerce_change_password_page_id'	 => 'Change Password',
							'woocommerce_logout_page_id'			 => 'Logout',
							'woocommerce_lost_password_page_id'		 => 'Lost Password'
						);

						foreach ( $woopages as $woo_page_name => $woo_page_title ) {

							$woopage = $this->new_get_page_by_title( $woo_page_title );
							if ( isset( $woopage ) && $woopage->ID ) {
								update_option( $woo_page_name, $woopage->ID );
							}
						}

						// We no longer need to install pages
						delete_option( '_wc_needs_pages' );
						delete_transient( '_wc_activation_redirect' );

						// Get products image size
						update_option( 'woocommerce_single_image_width', $image_size );
						update_option( 'woocommerce_thumbnail_image_width', $thumbnail_size );
						update_option( 'woocommerce_thumbnail_cropping', 'custom' );
						update_option( 'woocommerce_thumbnail_cropping_custom_width', $crop_width );
						update_option( 'woocommerce_thumbnail_cropping_custom_height', $crop_height );
					}

					// Set imported menus to registered theme locations
					$locations	 = get_theme_mod( 'nav_menu_locations' );
					$menus		 = wp_get_nav_menus();

					if ( $menus ) {

						foreach ( $menus as $menu ) {

							if ( $menu->name == 'Main Menu' ) {
								$locations[ 'main_menu' ] = $menu->term_id;
							}
							if ( $menu->name == 'Main Menu Right' ) {
								$locations[ 'main_menu_right' ] = $menu->term_id;
							}
							if ( $menu->name == 'Categories Menu' ) {
								$locations[ 'main_menu_cats' ] = $menu->term_id;
							}
							if ( $menu->name == 'Homepage Main Menu' ) {
								$locations[ 'main_menu_home' ] = $menu->term_id;
							}
						}
					}

					// Set menus to locations
					set_theme_mod( 'nav_menu_locations', $locations );

					// Disable Elementor default settings
					//update_option( 'elementor_disable_color_schemes', 'yes' );
					//update_option( 'elementor_disable_typography_schemes', 'yes' );
					if ( !empty( $elementor_width ) ) {
						update_option( 'elementor_container_width', $elementor_width );
					}
					if ( !empty( $elementor_container ) ) {
						update_option( 'elementor_experiment-container', $elementor_container );
					}

					// Assign front page and posts page (blog page).
					$home_page	 = $this->new_get_page_by_title( $homepage_title );
					$blog_page	 = $this->new_get_page_by_title( $blog_title );

					update_option( 'show_on_front', 'page' );

					if ( is_object( $home_page ) ) {
						update_option( 'page_on_front', $home_page->ID );
					}

					if ( is_object( $blog_page ) ) {
						update_option( 'page_for_posts', $blog_page->ID );
					}

					// Posts to show on the blog page
					if ( !empty( $posts_to_show ) ) {
						update_option( 'posts_per_page', $posts_to_show );
					}
				}

				die();
			}

			/**
			 * Import XML data
			 *
			 * @since 1.0.0
			 */
			public function process_xml( $file ) {

				$response = envo_Demos_Helpers::get_remote( $file );
				$theme		 = wp_get_theme();
				$url		 = 'https://envothemes.com/docs/docs/' . $theme->template . '/one-click-demo-import/import-failed-manual-import/';

				// No sample data found
				if ( $response === false ) {
					return new WP_Error( 'xml_import_error', sprintf(__( 'Can not retrieve sample data xml file. The server may be down at the moment please try again later or try manual import. How to: %1$s', 'envothemes-demo-import' ), $url ) );
				}

				// Write sample data content to temp xml file
				$temp_xml = ENVO_PATH . 'includes/panel/classes/importers/temp.xml';
				file_put_contents( $temp_xml, $response );

				// Set temp xml to attachment url for use
				$attachment_url = $temp_xml;

				// If file exists lets import it
				if ( file_exists( $attachment_url ) ) {
					$this->import_xml( $attachment_url );
				} else {
					// Import file can't be imported - we should die here since this is core for most people.
					return new WP_Error( 'xml_import_error', sprintf(__( 'The xml import file could not be accessed. Please try again or try manual import. How to: %1$s', 'envothemes-demo-import' ), $url ) );
				}
			}

			/**
			 * Import XML file
			 *
			 * @since 1.0.0
			 */
			private function import_xml( $file ) {

				// Make sure importers constant is defined
				if ( !defined( 'WP_LOAD_IMPORTERS' ) ) {
					define( 'WP_LOAD_IMPORTERS', true );
				}

				// Import file location
				$import_file = ABSPATH . 'wp-admin/includes/import.php';

				// Include import file
				if ( !file_exists( $import_file ) ) {
					return;
				}

				// Include import file
				require_once( $import_file );

				// Define error var
				$importer_error = false;

				if ( !class_exists( 'WP_Importer' ) ) {
					$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

					if ( file_exists( $class_wp_importer ) ) {
						require_once $class_wp_importer;
					} else {
						$importer_error = __( 'Can not retrieve class-wp-importer.php', 'envothemes-demo-import' );
					}
				}

				if ( !class_exists( 'WP_Import' ) ) {
					$class_wp_import = ENVO_PATH . 'includes/panel/classes/importers/class-wordpress-importer.php';

					if ( file_exists( $class_wp_import ) ) {
						require_once $class_wp_import;
					} else {
						$importer_error = __( 'Can not retrieve wordpress-importer.php', 'envothemes-demo-import' );
					}
				}

				// Display error
				if ( $importer_error ) {
					return new WP_Error( 'xml_import_error', $importer_error );
				} else {

					// No error, lets import things...
					if ( !is_file( $file ) ) {
						$importer_error = __( 'Sample data file appears corrupt or can not be accessed.', 'envothemes-demo-import' );
						return new WP_Error( 'xml_import_error', $importer_error );
					} else {
						$importer					 = new WP_Import();
						$importer->fetch_attachments = true;
						$importer->import( $file );

						// Clear sample data content from temp xml file
						$temp_xml = ENVO_PATH . 'includes/panel/classes/importers/temp.xml';
						file_put_contents( $temp_xml, '' );
					}
				}
			}

		}

	}
	new EnvoThemes_Demos();
	