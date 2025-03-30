<?php

namespace Automattic\WooCommerce\Analytics\Admin;

use Automattic\Jetpack\Assets;
use Automattic\Jetpack\Connection\Initial_State as Connection_Initial_State;
use Automattic\WooCommerce\Analytics\HelperTraits\Utilities;
use Automattic\WooCommerce\Analytics\Internal\DI\RegistrableInterface;
use Automattic\WooCommerce\Analytics\Utilities\Features;

/**
 * Class Admin
 *
 * @package Automattic\WooCommerce\Analytics\Admin
 */
class Admin implements RegistrableInterface {

	use Utilities;

	/**
	 * We bump the assets version when the WooCommerce Analytics plugin is not compatible with the CDN assets anymore.
	 *
	 * @var string
	 */
	const ANALYTICS_ASSETS_VERSION = 'v1';

	/**
	 * The cache key for the assets data.
	 *
	 * @var string
	 */
	const ANALYTICS_ASSETS_DATA_CACHE_KEY = 'woocommerce_analytics_assets_data';

	/**
	 * Register our hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		// Initialize features.
		Features::init();

		add_action( 'admin_enqueue_scripts', array( $this, 'analytics_load_custom_wp_admin_scripts' ) );
		add_action( 'admin_menu', array( $this, 'analytics_add_admin_menu_main_page' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		if ( Features::is_enabled( 'orderAttribution' ) ) {
			add_filter( 'woocommerce_analytics_report_menu_items', array( $this, 'analytics_add_report_menu_items' ) );
		}

		if ( Features::is_enabled( 'mvp' ) ) {
			add_action( 'admin_menu', array( $this, 'analytics_add_mvp_menu_items' ) );
		}

		if ( Features::is_enabled( 'newAdminLayout' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'analytics_enqueue_new_admin_layout_app' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_sections_structure_script' ) );
		}
	}

	/**
	 * Add the report menu items.
	 *
	 * @param array $report_pages The array of report menu pages.
	 * @return array The updated array of report menu pages.
	 */
	public function analytics_add_report_menu_items( $report_pages ) {
		$order_attribution_report = array(
			'id'       => 'woocommerce-analytics-order-attribution',
			'title'    => __( 'Order attribution', 'woocommerce-analytics' ),
			'parent'   => 'woocommerce-analytics',
			'path'     => '/analytics/order-attribution',
			'nav_args' => array(
				// After "Orders" and before "Variations", 40 and 50 respectively.
				'order'  => 45,
				'parent' => 'woocommerce-analytics',
			),
		);

		// Insert the order attribution report after the "Orders" report and before the "Variations" report.
		$found_key = array_search( 'woocommerce-analytics-variations', array_column( $report_pages, 'id' ), true );
		array_splice(
			$report_pages,
			$found_key,
			0,
			array( $order_attribution_report )
		);

		return $report_pages;
	}

	/**
	 * Add tentative MVP reports menu items.
	 *
	 * @return void
	 */
	public function analytics_add_mvp_menu_items(): void {
		if ( ! function_exists( 'wc_admin_register_page' ) ) {
			return;
		}

		/**
		 * Filter to control the visibility of the Analytics (Dev) menu in WooCommerce Analytics.
		 * This menu is used for development and debugging purposes.
		 *
		 * By default, this menu is enabled/disabled based on
		 * the 'addDevMenu' feature flag.
		 * - Disabled in production.
		 * - Enabled in development.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $show_menu Whether to show the Analytics (Dev) menu. Default is false.
		 */
		if ( ! apply_filters( 'wc_analytics_show_analytics_dev_menu', Features::is_enabled( 'addDevMenu' ) ) ) {
			return;
		}

		// New Admin Layout.
		if ( Features::is_enabled( 'newAdminLayout' ) ) {
			$this->analytics_register_new_admin_pages();

			// Bail early when the new admin layout is enabled.
			return;
		}

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-mvp',
				'title'    => __( 'Analytics (MVP)', 'woocommerce-analytics' ),
				'path'     => '/woocommerce-analytics/dashboard',
				'icon'     => 'dashicons-chart-bar',
				'position' => 58,
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-dashboard',
				'title'    => __( 'Dashboard', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics-mvp',
				'path'     => '/woocommerce-analytics/dashboard',
				'nav_args' => array(
					'order'  => 40,
					'parent' => 'woocommerce-analytics-mvp',
				),
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-reports',
				'title'    => __( 'Reports', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics-mvp',
				'path'     => '/woocommerce-analytics/reports',
				'nav_args' => array(
					'order'  => 40,
					'parent' => 'woocommerce-analytics-mvp',
				),
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-orders-over-time',
				'title'    => __( 'Orders Over Time', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics-mvp',
				'path'     => '/woocommerce-analytics/orders-over-time',
				'nav_args' => array(
					'order'  => 40,
					'parent' => 'woocommerce-analytics-mvp',
				),
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-avg-order-value-over-time',
				'title'    => __( 'Average Order Value Over Time', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics-mvp',
				'path'     => '/woocommerce-analytics/avg-order-value-over-time',
				'nav_args' => array(
					'order'  => 40,
					'parent' => 'woocommerce-analytics-mvp',
				),
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-avg-items-per-order-over-time',
				'title'    => __( 'Average Items Per Order Over Time', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics-mvp',
				'path'     => '/woocommerce-analytics/avg-items-per-order-over-time',
				'nav_args' => array(
					'order'  => 40,
					'parent' => 'woocommerce-analytics-mvp',
				),
			)
		);
	}

	/**
	 * Register the new admin pages for the new Admin Layout (MVP).
	 *
	 * @return void
	 */
	public function analytics_register_new_admin_pages(): void {
		add_menu_page(
			__( 'Analytics (MVP)', 'woocommerce-analytics' ),
			__( 'Analytics (MVP)', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics',
			array( $this, 'render_admin_layout_container' ),
			'dashicons-chart-bar',
			58
		);

		// Dashboard.
		add_submenu_page(
			'wc-analytics',
			__( 'Analytics Dashboard', 'woocommerce-analytics' ),
			__( 'Dashboard', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/dashboard',
			array( $this, 'render_admin_layout_container' )
		);

		// Reports.
		add_submenu_page(
			'wc-analytics',
			__( 'Analytics Reports', 'woocommerce-analytics' ),
			__( 'Reports', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > All Reports.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / All', 'woocommerce-analytics' ),
			__( 'All reports', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/all',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Finances.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Finances', 'woocommerce-analytics' ),
			__( 'Finances', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/finances',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Inventory.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Inventory', 'woocommerce-analytics' ),
			__( 'Inventory', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/inventory',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Orders.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Orders', 'woocommerce-analytics' ),
			__( 'Orders', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/orders',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Orders Over Time.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Orders Over Time', 'woocommerce-analytics' ),
			__( 'Orders Over Time', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/orders-over-time',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Average Order Value Over Time.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Average Order Value Over Time', 'woocommerce-analytics' ),
			__( 'Average Order Value Over Time', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/avg-order-value-over-time',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Average Items Per Order Over Time.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Average Items Per Order Over Time', 'woocommerce-analytics' ),
			__( 'Average Items Per Order Over Time', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/avg-items-per-order-over-time',
			array( $this, 'render_admin_layout_container' ),
		);

		// Reports > Sales.
		add_submenu_page(
			'woocommerce-analytics-reports.php', // Hack to make the page hidden from the menu.
			__( 'Reports / Sales', 'woocommerce-analytics' ),
			__( 'Sales', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/reports/sales',
			array( $this, 'render_admin_layout_container' ),
		);

		// Settings.
		add_submenu_page(
			'wc-analytics',
			__( 'Settings', 'woocommerce-analytics' ),
			__( 'Settings', 'woocommerce-analytics' ),
			'view_woocommerce_reports',
			'wc-analytics/settings',
			array( $this, 'render_admin_layout_container' )
		);
	}

	/**
	 * Render the element placeholder for the admin page.
	 * It will be replaced by the React app.
	 */
	public function render_admin_layout_container(): void {
		?>
		<div id="wc-analytics-admin-app" class="wc-analytics-admin-app admin-app">
			<?php esc_html_e( 'Loading…', 'woocommerce-analytics' ); ?>
		</div>
		<?php
	}

	/**
	 * Add the admin menu.
	 *
	 * @return void
	 */
	public function analytics_add_admin_menu_main_page(): void {
		if ( ! function_exists( 'wc_admin_register_page' ) ) {
			return;
		}

		/**
		 * Filter to control the visibility of the Analytics (Dev) menu in WooCommerce Analytics.
		 * This menu is used for development and debugging purposes.
		 *
		 * By default, this menu is enabled/disabled based on
		 * the 'addDevMenu' feature flag.
		 * - Disabled in production.
		 * - Enabled in development.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $show_menu Whether to show the Analytics (Dev) menu. Default is false.
		 */
		if ( ! apply_filters( 'wc_analytics_show_analytics_dev_menu', Features::is_enabled( 'addDevMenu' ) ) ) {
			return;
		}

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics',
				'title'    => __( 'Analytics (Dev)', 'woocommerce-analytics' ),
				'path'     => '/woocommerce-analytics/settings',
				'icon'     => 'dashicons-chart-bar',
				'position' => 58, // After Marketing page.
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-settings',
				'title'    => __( 'Settings', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics',
				'path'     => '/woocommerce-analytics/settings',
				'nav_args' => array(
					'order'  => 20,
					'parent' => 'woocommerce-analytics',
				),
			)
		);

		wc_admin_register_page(
			array(
				'id'       => 'woocommerce-analytics-connect-your-store',
				'title'    => __( 'Connect your store', 'woocommerce-analytics' ),
				'parent'   => 'woocommerce-analytics',
				'path'     => '/woocommerce-analytics/connect-your-store',
				'nav_args' => array(
					'order'  => 30,
					'parent' => 'woocommerce-analytics',
				),
			)
		);
	}

	/**
	 * Check if the current page is a new admin layout page.
	 *
	 * @param string $hook The hook name of the page.
	 * @return bool
	 */
	public function is_new_admin_layout_page( $hook ) {
		$new_admin_pages = array(
			'toplevel_page_wc-analytics',

			'analytics-mvp_page_wc-analytics/dashboard',

			'analytics-mvp_page_wc-analytics/reports',
			'admin_page_wc-analytics/reports/all',
			'admin_page_wc-analytics/reports/finances',
			'admin_page_wc-analytics/reports/inventory',
			'admin_page_wc-analytics/reports/orders',
			'admin_page_wc-analytics/reports/sales',
			'admin_page_wc-analytics/reports/orders-over-time',
			'admin_page_wc-analytics/reports/avg-order-value-over-time',
			'admin_page_wc-analytics/reports/avg-items-per-order-over-time',
			'analytics-mvp_page_wc-analytics/settings',
		);

		return in_array( $hook, $new_admin_pages, true );
	}

	/**
	 * Load the admin script.
	 *
	 * @param string $hook The hook name of the page.
	 */
	public function analytics_load_custom_wp_admin_scripts( $hook ): void {
		if (
			'woocommerce_page_wc-admin' !== $hook && // WooCommerce Admin pages.
			! $this->is_new_admin_layout_page( $hook ) // Analytics pages.
		) {
			return;
		}

		if ( $this->is_asset_local( 'index.js' ) ) {
			// Load local assets for the convenience of development.
			Assets::register_script(
				'analytics-main-app',
				'index.js',
				$this->get_local_build_path() . 'index.js',
				array(
					'enqueue'    => true,
					'in_footer'  => true,
					'textdomain' => 'woocommerce-analytics',
				)
			);
			Assets::enqueue_script( 'analytics-main-app' );
		} else {
			// Get the CDN URL for the assests.
			$build_dir = $this->get_cdn_url( self::ANALYTICS_ASSETS_VERSION );

			// Try to get cached assets data.
			$assets_data = get_transient( self::ANALYTICS_ASSETS_DATA_CACHE_KEY );

			if ( false === $assets_data || ! is_array( $assets_data ) || ! isset( $assets_data['dependencies'] ) || ! isset( $assets_data['version'] ) || ! is_array( $assets_data['dependencies'] ) ) {
				// Dynamically get the dependencies and version from the CDN.
				$assets_data = $this->get_assets_data( $build_dir );
				set_transient( self::ANALYTICS_ASSETS_DATA_CACHE_KEY, $assets_data, 15 * MINUTE_IN_SECONDS );
			}

			$dependencies = $assets_data['dependencies'];
			// We add &minify=false to the version to prevent minification from the CDN.
			// The file is already minified in the build process and the additional minification was causing issues.
			$version = $assets_data['version'] . '&minify=false';

			// Enqueue CSS dependencies.
			foreach ( $dependencies as $style ) {
				wp_enqueue_style( $style );
			}

			// Load our app.js.
			wp_register_script(
				'analytics-main-app',
				$build_dir . 'index.js',
				$dependencies,
				$version,
				true
			);
			wp_enqueue_script( 'analytics-main-app' );

			// Load our style.css.
			wp_register_style(
				'analytics-main-app',
				$build_dir . 'index.css',
				array(),
				$version
			);
			wp_enqueue_style( 'analytics-main-app' );
		}

		Connection_Initial_State::render_script( 'analytics-main-app' );

		/*
		 * Manually point to the translation file.
		 *
		 * Since the JS script is loaded externally WP will not be looking for the translation file locally.
		 * The .json file generated by GlotPress is delivered with the plugin Language Pack and
		 * it is available in the standard translations directory.
		 *
		 */
		add_filter(
			'load_script_translation_file',
			function ( $file, $handle, $domain ) {
				if ( 'analytics-main-app' !== $handle ) {
					return $file;
				}

				return WP_LANG_DIR . '/plugins/' . $domain . '-' . determine_locale() . '-' . md5( 'build/index.js' ) . '.json';
			},
			10,
			3
		);

		// Set translation file.
		wp_set_script_translations( 'analytics-main-app', 'woocommerce-analytics' );
	}

	/**
	 * Enqueue the new admin layout app.
	 *
	 * @param string $hook The hook name of the page.
	 * @return void
	 */
	public function analytics_enqueue_new_admin_layout_app( $hook ): void {
		if ( ! $this->is_new_admin_layout_page( $hook ) ) {
			return;
		}

		// Adding `is-fullscreen-mode` class to the body.
		add_filter(
			'admin_body_class',
			static function ( $classes ) {
				return "$classes is-fullscreen-mode woocommerce-analytics";
			}
		);

		Assets::register_script(
			'analytics-admin-app',
			'admin-app.js',
			$this->get_local_build_path() . 'admin-app.js',
			array(
				'enqueue'    => true,
				'in_footer'  => true,
				'textdomain' => 'woocommerce-analytics',
			)
		);
		Assets::enqueue_script( 'analytics-admin-app' );
	}

	/**
	 * Get the structure of pages/menus for the WooCommerce Analytics section.
	 *
	 * @return array The filtered menu structure.
	 */
	public function get_wc_analytics_sections_structure(): array {
		global $menu, $submenu;

		$analytics_menu = array();

		/*
		 * Map with extra data for the WooCommerce Analytics menu items.
		 * - icon: The icon ID for the menu item.
		 * - addToPalette: Whether to add the icon to the Command Palette.
		 */
		$extras = array(
			'wc-analytics/dashboard'         => array(
				'icon'         => 'wordpress/navigation',
				'addToPalette' => true,
			),
			'wc-analytics/reports'           => array(
				'icon'         => 'wc-analytics/reports',
				'addToPalette' => true,
			),
			'wc-analytics/reports/all'       => array(
				'icon'         => 'wc-analytics/reports',
				'addToPalette' => true,
			),
			'wc-analytics/reports/finances'  => array(
				'icon'         => 'wordpress/payment',
				'addToPalette' => true,
			),
			'wc-analytics/reports/inventory' => array(
				'icon'         => 'wordpress/tag',
				'addToPalette' => true,
			),
			'wc-analytics/reports/orders'    => array(
				'icon'         => 'wordpress/receipt',
				'addToPalette' => true,
			),
			'wc-analytics/reports/sales'     => array(
				'icon'         => 'wc-analytics/bag',
				'addToPalette' => true,
			),

			'wc-analytics/settings'          => array(
				'icon'         => 'wordpress/cog',
				'addToPalette' => true,
			),
		);

		/*
		 * Create Reports subsections,
		 * based on the `woocommerce-analytics-reports.php` slug
		 */
		$reports_subsections = array();
		if ( isset( $submenu['woocommerce-analytics-reports.php'] ) ) {
			foreach ( $submenu['woocommerce-analytics-reports.php'] as $item ) {
				$extra                 = isset( $extras[ $item[2] ] ) ? $extras[ $item[2] ] : array();
				$reports_subsections[] = array(
					'title'        => $item[0],
					'path'         => $item[2],
					'iconId'       => $extra['icon'] ?? '',
					'addToPalette' => $extra['addToPalette'] ?? false,
				);
			}
		}

		foreach ( $menu as $item ) {
			// Only iterate over the WooCommerce Analytics menu.
			if ( 'wc-analytics' === $item[2] ) {
				$analytics_menu['home'] = array(
					'title'  => $item[0],
					'path'   => $item[2],
					'iconId' => $item[6],
				);
				break;
			}
		}

		// Get the sub-sections of the 'wc-analytics' menu.
		if ( isset( $submenu['wc-analytics'] ) ) {
			$analytics_menu['sections'] = array();

			foreach ( $submenu['wc-analytics'] as $item ) {
				$extra   = isset( $extras[ $item[2] ] ) ? $extras[ $item[2] ] : array();
				$section = array(
					'title'        => $item[0],
					'path'         => $item[2],
					'iconId'       => $extra['icon'] ?? '',
					'addToPalette' => $extra['addToPalette'] ?? false,
				);

				if ( 'wc-analytics/reports' === $item[2] ) {
					$section['subsections'] = $reports_subsections;
				}

				$analytics_menu['sections'][] = $section;

			}
		}

		return $analytics_menu;
	}

	/**
	 * Enqueue the sections structure as a client global variable.
	 *
	 * @param string $hook The hook name of the page.
	 * @return void
	 */
	public function enqueue_sections_structure_script( $hook ): void {
		if ( ! $this->is_new_admin_layout_page( $hook ) ) {
			return;
		}

		// Get the menu structure.
		$menu_structure = $this->get_wc_analytics_sections_structure();

		// Add the default section, which is the dashboard.
		$menu_json = wp_json_encode( $menu_structure );

		wp_add_inline_script(
			'analytics-admin-app', // Enqueue for the new admin layout app.
			"window.wcAnalyticsSections = {$menu_json};",
			'before'
		);
	}

	/**
	 * Admin init function.
	 *
	 * Runs on the admin init hook.
	 *
	 * @return void
	 */
	public function admin_init(): void {
		// Run post-activation actions if needed.
		$this->plugin_post_activation();
	}

	/**
	 * Run plugin post-activation actions if we need to.
	 *
	 * @return void
	 */
	private function plugin_post_activation(): void {
		if ( get_transient( 'activated_woocommerce_analytics' ) ) {
			delete_transient( 'activated_woocommerce_analytics' );
			$redirect_url = admin_url( 'admin.php?page=wc-admin&path=%2Fanalytics%2Forder-attribution' );
			wp_safe_redirect( $redirect_url );
			exit();
		}
	}
}
