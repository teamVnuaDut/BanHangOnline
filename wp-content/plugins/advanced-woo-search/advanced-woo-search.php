<?php

/*
Plugin Name: Advanced Woo Search
Description: Advance ajax WooCommerce product search.
Version: 3.29
Author: ILLID
Author URI: https://advanced-woo-search.com/
Text Domain: advanced-woo-search
Requires Plugins: woocommerce
WC requires at least: 3.0.0
WC tested up to: 9.7.0
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'AWS_FILE' ) ) {
    define( 'AWS_FILE', __FILE__ );
}

if ( ! class_exists( 'AWS_Main' ) ) :

/**
 * Main plugin class
 *
 * @class AWS_Main
 */
final class AWS_Main {

	/**
	 * @var AWS_Main The single instance of the class
	 */
	protected static $_instance = null;

    /**
     * @var AWS_Main Array of all plugin data $data
     */
    private $data = array();

    /**
     * @var AWS_Main Cache instance
     */
    public $cache = null;

    /**
     * @var AWS_Main Table updates instance
     */
    public $table_updates = null;

    /**
     * @var AWS_Main Candition vars
     */
    public $option_vars = null;

	/**
	 * Main AWS_Main Instance
	 *
	 * Ensures only one instance of AWS_Main is loaded or can be loaded.
	 *
	 * @static
	 * @return AWS_Main - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {

        $this->define_constants();

        $this->data['settings'] = get_option( 'aws_settings' );

		add_filter( 'widget_text', 'do_shortcode' );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );

		add_filter( 'plugin_action_links', array( $this, 'add_action_link' ), 10, 2 );

		load_plugin_textdomain( 'advanced-woo-search', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

        $this->includes();
                
        add_action( 'init', array( $this, 'init' ), 1 );

        add_filter( 'wcml_multi_currency_ajax_actions', array( $this, 'add_wpml_ajax_actions' ) );

        add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

        if ( $this->get_settings('seamless') === 'true' ) {
            add_filter( 'get_search_form', array( $this, 'markup_filter' ), 999999 );
            add_filter( 'get_product_search_form', array( $this, 'markup_filter' ), 999999 );
        }

    }

    /**
     * Define constants
     */
    private function define_constants() {

        $this->define( 'AWS_VERSION', '3.29' );

        $this->define( 'AWS_DIR', plugin_dir_path( AWS_FILE ) );
        $this->define( 'AWS_URL', plugin_dir_url( AWS_FILE ) );

        $this->define( 'AWS_INDEX_TABLE_NAME', 'aws_index' );
        $this->define( 'AWS_CACHE_TABLE_NAME', 'aws_cache' );

    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {

        include_once( 'includes/class-aws-option-vars.php' );
        include_once( 'includes/class-aws-helpers.php' );
        include_once( 'includes/class-aws-versions.php' );
        include_once( 'includes/class-aws-cache.php' );
        include_once( 'includes/class-aws-plurals.php' );
        include_once( 'includes/class-aws-similar-terms.php' );
        include_once( 'includes/class-aws-table.php' );
        include_once( 'includes/class-aws-table-data.php' );
        include_once( 'includes/class-aws-table-updates.php' );
        include_once( 'includes/class-aws-markup.php' );
        include_once( 'includes/class-aws-search.php' );
        include_once( 'includes/class-aws-tax-search.php' );
        include_once( 'includes/class-aws-search-page.php' );
        include_once( 'includes/class-aws-order.php' );
        include_once( 'includes/class-aws-integrations.php' );
        include_once( 'includes/class-aws-langs.php' );
        include_once( 'includes/class-aws-hooks.php' );
        include_once( 'includes/class-aws-shortcodes.php' );
        include_once( 'includes/widget.php' );

        // Admin
        include_once( 'includes/admin/class-aws-admin-notices.php' );
        include_once( 'includes/admin/class-aws-admin.php' );
        include_once( 'includes/admin/class-aws-admin-ajax.php' );
        include_once( 'includes/admin/class-aws-admin-fields.php' );
        include_once( 'includes/admin/class-aws-admin-options.php' );
        include_once( 'includes/admin/class-aws-admin-meta-boxes.php' );
        include_once( 'includes/admin/class-aws-admin-page-premium.php' );

    }

	/*
	 * Generate search box markup
	 */
	 public function markup( $args = array() ) {

         if ( ! $args || ! is_array( $args ) ) {
             $args = array();
         }

         $markup = new AWS_Markup( $args );

         return $markup->markup();

	}

    /*
	 * Filter search box markup
	 */
    public function markup_filter( $search_form = '' ) {

        $markup = $this->markup();

        /**
         * Filter search form markup for seamless integration
         * @since 2.48
         * @param string $markup New search form markup
         * @param string $search_form Old search form markup
         */
        return apply_filters( 'aws_seamless_search_form_filter', $markup, $search_form );

    }

    /*
	 * Sort products
	 */
    public function order( $products, $order_by ) {

        $order = new AWS_Order( $products, $order_by );

        return $order->result();

    }

    /*
     * Init plugin classes
     */
    public function init() {
        $this->option_vars = new AWS_Option_Vars();
        $this->cache = AWS_Cache::factory();
        $this->table_updates = new AWS_Table_Updates();
        AWS_Integrations::instance();
        AWS_Hooks::instance();
        AWS_Shortcodes::instance();
        AWS_Langs::instance();
    }

	/*
	 * Load assets for search form
	 */
	public function load_scripts() {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'aws-style', AWS_URL . 'assets/css/common' . $suffix . '.css', array(), AWS_VERSION );
        if ( is_rtl() ) {
            wp_enqueue_style( 'aws-style-rtl', AWS_URL . 'assets/css/common-rtl' . $suffix . '.css', array(), AWS_VERSION );
        }
        wp_enqueue_script('aws-script', AWS_URL . 'assets/js/common' . $suffix . '.js', array('jquery'), AWS_VERSION, true);
        wp_localize_script('aws-script', 'aws_vars', array(
            'sale'       => __('Sale!', 'advanced-woo-search'),
            'sku'        => __('SKU', 'advanced-woo-search') . ': ',
            'showmore'   => $this->get_settings('show_more_text') ? AWS_Helpers::translate( 'show_more_text', stripslashes( strip_tags( html_entity_decode( $this->get_settings('show_more_text') ) ) ) ) : __('View all results', 'advanced-woo-search'),
            'noresults'  => $this->get_settings('not_found_text') ? AWS_Helpers::translate( 'not_found_text', stripslashes( wp_kses( html_entity_decode( $this->get_settings('not_found_text') ), AWS_Helpers::get_kses( AWS_Helpers::kses_textarea_allowed_tags() ) ) ) ) : __('Nothing found', 'advanced-woo-search'),
        ));
	}

	/*
	 * Add settings link to plugins
	 */
	public function add_action_link( $links, $file ) {
		$plugin_base = plugin_basename( __FILE__ );

		if ( $file == $plugin_base ) {
			$setting_link = '<a href="' . admin_url('admin.php?page=aws-options') . '">'.esc_html__( 'Settings', 'advanced-woo-search' ).'</a>';
			array_unshift( $links, $setting_link );

            $premium_link = '<a href="' . admin_url( 'admin.php?page=aws-options&tab=premium' ) . '">'.esc_html__( 'Premium Version', 'advanced-woo-search' ).'</a>';
            array_unshift( $links, $premium_link );
		}

		return $links;
	}

    /*
     * Get plugin settings
     */
    public function get_settings( $name ) {
        $plugin_options = $this->data['settings'];
		$return_value = isset( $plugin_options[ $name ] ) ? $plugin_options[ $name ] : '';
        return $return_value;
    }

    /*
     * Define constant if not already set
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /*
     * Add ajax action to WPML plugin
     */
    function add_wpml_ajax_actions( $actions ){
        $actions[] = 'aws_action';
        return $actions;
    }

    /*
     * Declare support for WooCommerce features
     */
    public function declare_wc_features_support() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }

}

endif;


/**
 * Returns the main instance of AWS_Main
 *
 * @return AWS_Main
 */
function AWS() {
    return AWS_Main::instance();
}


/*
 * Check if WooCommerce is active
 */
if ( ! aws_is_plugin_active( 'advanced-woo-search-pro/advanced-woo-search-pro.php' ) ) {
    if ( aws_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        add_action( 'woocommerce_loaded', 'aws_init' );
    } else {
        add_action( 'admin_notices', 'aws_install_woocommerce_admin_notice' );
    }
}

/*
 * Check whether the plugin is active by checking the active_plugins list.
 */
function aws_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || aws_is_plugin_active_for_network( $plugin );
}


/*
 * Check whether the plugin is active for the entire network
 */
function aws_is_plugin_active_for_network( $plugin ) {
    if ( !is_multisite() )
        return false;

    $plugins = get_site_option( 'active_sitewide_plugins' );
    if ( isset($plugins[$plugin]) )
        return true;

    return false;
}


/*
 * Error notice if WooCommerce plugin is not active
 */
function aws_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'Advanced Woo Search plugin is enabled but not effective. It requires WooCommerce in order to work.', 'advanced-woo-search' ); ?></p>
	</div>
	<?php
}


/*
 * Activation hook
 */
register_activation_hook( __FILE__, 'aws_on_activation' );
function aws_on_activation() {
    $hide_notice = get_option( 'aws_hide_welcome_notice' );
    if ( ! $hide_notice ) {
        $free_plugin_version = get_option( 'aws_plugin_ver' );
        $pro_plugin_version = get_option( 'aws_pro_plugin_ver' );
        $hide = 'false';
        if ( $free_plugin_version || $pro_plugin_version ) {
            $hide = 'true';
        }
        update_option( 'aws_hide_welcome_notice', $hide, false );
    }
}


/*
 * Init AWS plugin
 */
function aws_init() {
    AWS();
}


if ( ! function_exists( 'aws_get_search_form' ) ) {

    /**
     * Returns search form html
     *
     * @since 1.47
     * @return string
     */
    function aws_get_search_form( $echo = true, $args = array() ) {

        $form = '';

        if ( ! aws_is_plugin_active( 'advanced-woo-search-pro/advanced-woo-search-pro.php' ) ) {
            if ( aws_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                $form = AWS()->markup( $args );
            }
        }

        if ( $echo ) {
            echo $form;
        } else {
            return $form;
        }

    }

}