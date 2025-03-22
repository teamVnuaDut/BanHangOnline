<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://blossomthemes.com
 * @since             2.0.0
 * @package           Blossomthemes_Email_Newsletter
 *
 * @wordpress-plugin
 * Plugin Name:       BlossomThemes Email Newsletter
 * Plugin URI:
 * Description:       Easily add email subscription form to your website using shortcode and widget.
 * Version:           2.2.10
 * Author:            blossomthemes
 * Author URI:        https://blossomthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blossomthemes-email-newsletter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BLOSSOMTHEMES_EMAIL_NEWSLETTER_FILE_PATH', __FILE__ );
define( 'BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH', dirname( __FILE__ ) );
define( 'BLOSSOMTHEMES_EMAIL_NEWSLETTER_FILE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'BLOSSOMTHEMES_EMAIL_NEWSLETTER_VERSION', '2.2.10' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blossomthemes-email-newsletter-activator.php
 */
function activate_blossomthemes_email_newsletter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-email-newsletter-activator.php';
	Blossomthemes_Email_Newsletter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blossomthemes-email-newsletter-deactivator.php
 */
function deactivate_blossomthemes_email_newsletter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-email-newsletter-deactivator.php';
	Blossomthemes_Email_Newsletter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blossomthemes_email_newsletter' );
register_deactivation_hook( __FILE__, 'deactivate_blossomthemes_email_newsletter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-email-newsletter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_blossomthemes_email_newsletter() {

	$plugin = new Blossomthemes_Email_Newsletter();
	$plugin->run();

}
run_blossomthemes_email_newsletter();
