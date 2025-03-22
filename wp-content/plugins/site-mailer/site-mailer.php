<?php
/**
 * Plugin Name: Site Mailer - SMTP Replacement, Email API Deliverability & Email Log
 * Description: Effortlessly manage transactional emails with Site Mailer. High deliverability, logs and statistics, and no SMTP plugins needed.
 * Plugin URI: https://elementor.com/
 * Version: 1.2.4
 * Author: Elementor.com
 * Author URI: https://go.elementor.com/author-url-sm/
 * Text Domain: site-mailer
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SITE_MAILER_VERSION', '1.2.4' );
define( 'SITE_MAILER_PATH', plugin_dir_path( __FILE__ ) );
define( 'SITE_MAILER_URL', plugins_url( '/', __FILE__ ) );
define( 'SITE_MAILER_ASSETS_PATH',  SITE_MAILER_PATH . 'assets/' );
define( 'SITE_MAILER_ASSETS_URL',  SITE_MAILER_URL . 'assets/' );

/**
 *  SiteMailer Class
 *
 */
final class SiteMailer {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		// Load translation
		add_action( 'init', [ $this, 'i18n' ] );

		// Init Plugin
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'site-mailer' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Do your Validations here:
	 * for example checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {
		// Once we get here, We have passed all validation checks, so we can safely include our plugin
		require_once 'plugin.php';
	}
}
// Instantiate SiteMailer..
new SiteMailer();
