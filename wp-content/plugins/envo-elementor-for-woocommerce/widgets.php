<?php
/**
 * Elementor Widgets functions
 *
 * @package ETWW WordPress plugin
 */

// Exit if accessed directly
if(! defined('ABSPATH')) {
	exit;
}

/**
 * Returns the main instance of ETWW_Elementor_Widgets to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object ETWW_Elementor_Widgets
 */
function ETWW_Elementor_Widgets() {
	return ETWW_Elementor_Widgets::instance();
} // End ETWW_Elementor_Widgets()

ETWW_Elementor_Widgets();

/**
 * Main ETWW_Elementor_Widgets Class
 *
 * @class ETWW_Elementor_Widgets
 * @version	1.0.0
 * @since 1.0.0
 * @package	ETWW_Elementor_Widgets
 */
#[AllowDynamicProperties]
final class ETWW_Elementor_Widgets {
	/**
	 * ETWW_Elementor_Widgets The single instance of ETWW_Elementor_Widgets.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token 			= 'etww';
		$this->plugin_url 		= plugin_dir_url(__FILE__);
		$this->plugin_path 		= plugin_dir_path(__FILE__);
		$this->version 			= '2.7.4';

		define('ETWW_ELEMENTOR__FILE__', __FILE__);
		define('ETWW_ELEMENTOR_PATH', $this->plugin_path);
		define('ETWW_ELEMENTOR_VERSION', $this->version);

		add_action('plugins_loaded', array($this, 'setup'));
		
	//	add_action('elementor/frontend/before_enqueue_scripts', array($this, 'enqueue_site_scripts'));
		add_action('elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ]);
		add_action( 'elementor/editor/after_enqueue_scripts', [$this, 'editor_scripts' ] );
	}
	
	/**
	 * Loading site related script that needs all time such as uikit.
	 * @return [type] [description]
	 */
	public function enqueue_site_scripts() {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	}
	
	public function enqueue_styles() {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style('etww-frontend', plugins_url('/assets/css/etww-frontend' . $suffix . '.css', ETWW_ELEMENTOR__FILE__));
		wp_register_style( 'font-awesome-5-all', ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css', false );
	}
	
	public function editor_scripts() {
		wp_register_script( 'preview-script-elmn', plugins_url('/assets/js/elementor.js', ETWW_ELEMENTOR__FILE__), [ 'elementor-editor' ], ELEMENTOR_VERSION, true );
		wp_enqueue_script( 'preview-script-elmn' );
	}

	/**
	 * Main ETWW_Elementor_Widgets Instance
	 *
	 * Ensures only one instance of ETWW_Elementor_Widgets is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see ETWW_Elementor_Widgets()
	 * @return Main ETWW_Elementor_Widgets instance
	 */
	public static function instance() {
		if(is_null(self::$_instance))
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), '1.0.0');
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), '1.0.0');
	}
	/**
	 * Setup all the things.
	 * Only executes if ETWW or a child theme using ETWW as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function setup() {
	
			require(ETWW_ELEMENTOR_PATH .'includes/plugin.php');
			require_once(ETWW_ELEMENTOR_PATH .'includes/helpers.php');
	}

} // End Class