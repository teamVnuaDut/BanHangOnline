<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blossomthemes.com
 * @since      2.0.0
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/admin
 * @author     blossomthemes <blossomthemes.com>
 */
class Blossomthemes_Email_Newsletter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = BLOSSOMTHEMES_EMAIL_NEWSLETTER_VERSION;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Email_Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Email_Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( $screen->post_type == 'subscribe-form' || isset( $_GET['page'] ) && $_GET['page'] == 'class-blossomthemes-email-newsletter-admin.php' || $screen->id === 'widgets' || is_customize_preview() ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossomthemes-email-newsletter-admin.css', array(), $this->version, 'all' );
		}
		wp_register_style( 'dashicons-blossom-newsletter', plugin_dir_url( __FILE__ ) . 'images/newsletter-icon/style.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'dashicons-blossom-newsletter' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Email_Newsletter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Email_Newsletter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( $screen->post_type == 'subscribe-form' || isset( $_GET['page'] ) && $_GET['page'] == 'class-blossomthemes-email-newsletter-admin.php' || $screen->id === 'widgets' || is_customize_preview() ) {
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossomthemes-email-newsletter-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
			wp_localize_script(
				$this->plugin_name,
				'bten_uploader',
				array(
					'upload'     => __( 'Upload', 'blossomthemes-email-newsletter' ),
					'change'     => __( 'Change', 'blossomthemes-email-newsletter' ),
					'msg'        => __( 'Please upload valid image file.', 'blossomthemes-email-newsletter' ),
					'bten_nonce' => wp_create_nonce( 'bten_admin_settings' ),
				)
			);

			wp_enqueue_script( 'bten-aweber', plugin_dir_url( __FILE__ ) . 'js/bten-aweber.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'bten-mailing-platform-lists', plugin_dir_url( __FILE__ ) . 'js/bten-mailing-platform-lists.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Register a subscribe-form post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function blossomthemes_register_form() {
		$labels = array(
			'name'               => _x( 'BlossomThemes Email Newsletter', 'post type general name', 'blossomthemes-email-newsletter' ),
			'singular_name'      => _x( 'BlossomThemes Email Newsletter', 'post type singular name', 'blossomthemes-email-newsletter' ),
			'menu_name'          => _x( 'BlossomThemes Email Newsletter', 'admin menu', 'blossomthemes-email-newsletter' ),
			'name_admin_bar'     => _x( 'BlossomThemes Email Newsletter', 'add new on admin bar', 'blossomthemes-email-newsletter' ),
			'add_new'            => _x( 'Add New', 'BlossomThemes Email Newsletter', 'blossomthemes-email-newsletter' ),
			'add_new_item'       => __( 'Add New Newsletter', 'blossomthemes-email-newsletter' ),
			'new_item'           => __( 'New Newsletter', 'blossomthemes-email-newsletter' ),
			'edit_item'          => __( 'Edit Newsletter', 'blossomthemes-email-newsletter' ),
			'view_item'          => __( '', 'blossomthemes-email-newsletter' ),
			'all_items'          => __( 'All Newsletters', 'blossomthemes-email-newsletter' ),
			'search_items'       => __( 'Search Newsletters', 'blossomthemes-email-newsletter' ),
			'parent_item_colon'  => __( 'Parent Newsletters:', 'blossomthemes-email-newsletter' ),
			'not_found'          => __( 'No Newsletters found.', 'blossomthemes-email-newsletter' ),
			'not_found_in_trash' => __( 'No Newsletters found in Trash.', 'blossomthemes-email-newsletter' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'blossomthemes-email-newsletter' ),
			'public'             => false,
			'menu_icon'          => 'dashicons-blossom-newsletter',
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'subscribe-form' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 80,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'subscribe-form', $args );
	}

	// ADD SHORTCODE COLUMN
	function set_subscribe_form_columns( $columns ) {

		$columns['shortcode'] = __( 'Shortcode', 'blossomthemes-email-newsletter' );

		return $columns;
	}

	// Set Content in Shortcode Column
	function set_subscribe_form_columns_content( $column, $post_id ) {

		if ( $column == 'shortcode' ) {

			if ( get_post_status( $post_id ) == 'publish' ) {

				$shortcode = '[BTEN id="' . $post_id . '"]';

				?>
				<input type="text" name="shortcode" value="<?php echo esc_html( $shortcode ); ?>" readonly onClick="this.setSelectionRange(0, this.value.length)"/>
				<?php

			} else {
				echo '—';
			}
		}
	}

	/**
	 * Registers settings page for Subscribe Form.
	 *
	 * @since 2.0.0
	 */
	public function blossomthemes_email_newsletter_settings_page() {
		add_submenu_page( 'edit.php?post_type=subscribe-form', 'Blossomthemes Email Newsletter Settings', 'Settings', 'manage_options', basename( __FILE__ ), array( $this, 'blossomthemes_email_newsletter_callback_function' ) );
	}

	/**
	 * Registers settings.
	 *
	 * @since 2.0.0
	 */
	public function blossomthemes_email_newsletter_register_settings() {
		// The third parameter is a function that will validate input values.
		register_setting( 'blossomthemes_email_newsletter_settings', 'blossomthemes_email_newsletter_settings', array( $this, 'bten_platform_field_settings_validate' ) );
	}

	public function bten_platform_field_settings_validate( $option = '' ) {

		$bten_settings                           = new Blossomthemes_Email_Newsletter_Functions();
		$blossomthemes_email_newsletter_settings = isset( $_POST['blossomthemes_email_newsletter_settings'] ) ? $bten_settings::bten_clean( wp_unslash( $_POST['blossomthemes_email_newsletter_settings'] ) ) : array();

		if ( isset( $blossomthemes_email_newsletter_settings ) ) {
			$new_data     = $blossomthemes_email_newsletter_settings;
			$old_settings = get_option( 'blossomthemes_email_newsletter_settings', true );

			if ( is_array( $old_settings ) ) {

				$option = array_merge( $old_settings, $new_data );
			}

			return $option;
		}
	}



	/**
	 *
	 * Retrives saved settings from the database if settings are saved. Else, displays fresh forms for settings.
	 *
	 * @since 2.0.0
	 */
	function blossomthemes_email_newsletter_callback_function() {
		$blossom_themes_settings = new BlossomThemes_Email_Newsletter_Settings();
		$blossom_themes_settings->blossomthemes_email_newsletter_backend_settings();
		$option = get_option( 'blossomthemes_email_newsletter_settings' );
	}

	/*
	* Display GetResponse API update notice.
	*/
	function bten_api_update_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		$meta    = get_user_meta( $user_id, 'bten_api_update_admin_notice' );

		$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
		$notice        = ! empty( $bten_settings['platform'] ) && $bten_settings['platform'] == 'getresponse' && ! empty( $bten_settings['getresponse']['api-key'] ) ? true : false;

		// Check that the user hasn't already clicked to ignore the message
		if ( empty( $meta ) && $notice ) {
			?>

			<div class="error notice is-dismissible" style="position:relative">
				<p>
					<strong><?php _e( 'Getresponse API has been updated to support the latest version ( v3 ).', 'blossomthemes-email-newsletter' ); ?></strong>
					<br /><br/>
					<?php _e( 'Please fetch your lists again from the', 'blossomthemes-email-newsletter' ); ?>
					<strong>
						<a href="edit.php?post_type=subscribe-form&page=class-blossomthemes-email-newsletter-admin.php"><?php _e( 'Settings Page', 'blossomthemes-email-newsletter' ); ?></a>
					</strong> 
					<?php _e( 'and save the changes to make lists API work. If you have individual lists for newsletters, please make sure to update them as well.', 'blossomthemes-email-newsletter' ); ?>
					<?php _e( 'You can ignore this message if you have already fetched the GetResponse lists.', 'blossomthemes-email-newsletter' ); ?> | <strong>
						<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'bten-dismiss', 'dismiss_admin_notices' ), 'confirm=0' ) ); ?>" class="dismiss-notice" target="_parent"> <?php _e( 'Dismiss this notice', 'blossomthemes-email-newsletter' ); ?> </a>
					</strong>
				</p>
			</div>
			
			<?php
		}
	}

	function bten_ignore_admin_notice() {
		global $current_user;
		$user_id = $current_user->ID;

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET['bten-dismiss'] ) && check_admin_referer( 'confirm=0' ) ) {

			update_user_meta( $user_id, 'bten_api_update_admin_notice', 1 );
		}
	}
}
