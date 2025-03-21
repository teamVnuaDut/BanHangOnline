<?php
/**
 * Envo One admin notify
 *
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Envo_One_Notify_Admin' ) ) :

	/**
	 * The Envo One admin notify
	 */
	class Envo_One_Notify_Admin {

		/**
		 * Setup class.
		 *
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 99 );
			add_action( 'wp_ajax_envo_one_dismiss_notice', array( $this, 'dismiss_nux' ) );
			add_action( 'admin_menu', array( $this, 'add_menu' ), 5 );
		}

		/**
		 * Enqueue scripts.
		 *
		 */
		public function enqueue_scripts() {
			global $wp_customize;

			if ( isset( $wp_customize ) || envo_one_envo_extra_is_activated() ) {
				return;
			}

			wp_enqueue_style( 'envo-one-admin', get_template_directory_uri() . '/assets/css/admin/admin.css', '', '1' );

			wp_enqueue_script( 'envo-one-admin', get_template_directory_uri() . '/assets/js/admin/admin.js', array( 'jquery', 'updates' ), '1', 'all' );

			$envo_one_notify = array(
				'nonce' => wp_create_nonce( 'envo_one_notice_dismiss' )
			);

			wp_localize_script( 'envo-one-admin', 'envo_one_ux', $envo_one_notify );
		}

		/**
		 * Output admin notices.
		 *
		 */
		public function admin_notices() {
			global $pagenow;
			$theme_data = wp_get_theme();
			if ( ( 'themes.php' === $pagenow ) && isset( $_GET[ 'page' ] ) && ( 'envo-one' === $_GET[ 'page' ] ) || true === (bool) get_option( 'envo_one_notify_dismissed' ) || envo_one_envo_extra_is_activated() ) {
				return;
			}
			$theme_data = wp_get_theme();
			$theme_name = $theme_data->Name;
			?>

			<div class="notice notice-info envo-one-notice is-dismissible">
				<div class="envo-one-row">
					<div class="envo-one-col">
						<div class="notice-content">
							<?php if ( !envo_one_envo_extra_is_activated() && current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) : ?>
								<h2>
									<?php
									/* translators: %s: Theme name */
									printf( esc_html__( 'Thank you for installing %s.', 'envo-one' ), '<strong>' . $theme_name . '</strong>' );
									?>
								</h2>
								<p class="envo-one-description">
									<?php
									/* translators: %s: Plugin name string */
									printf( esc_html__( 'To take full advantage of all the features this theme has to offer, please install and activate the %s plugin.', 'envo-one' ), '<strong>Envo Extra</strong>' );
									?>
								</p>
								<p>
									<?php Envo_One_Plugin_Install::install_plugin_button( 'envo-extra', 'envo-extra.php', 'Envo Extra', array( 'envo-one-nux-button' ), __( 'Activated', 'envo-one' ), __( 'Activate', 'envo-one' ), __( 'Install', 'envo-one' ) ); ?>
									<a href="<?php echo esc_url( admin_url( 'themes.php?page=envo-one' ) ); ?>" class="button button-primary button-hero">
										<?php
										/* translators: %s: Theme name */
										printf( esc_html__( 'Get started with %s', 'envo-one' ), $theme_data->Name );
										?>
									</a>
								</p>

							<?php endif; ?>
						</div>
					</div>
					<div class="envo-one-col envo-one-col-right">
						<div class="image-container">
							<?php echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/img/' . strtolower( str_replace( ' ', '-', $theme_name ) ) . '-banner.png"/>'; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function add_menu() {
			if ( isset( $wp_customize ) || envo_one_envo_extra_is_activated() ) {
				return;
			}
			$theme_data = wp_get_theme();

			add_theme_page(
			$theme_data->Name, $theme_data->Name, 'edit_theme_options', 'envo-one', array( $this, 'admin_page' )
			);
		}

		public function admin_page() {
			if ( envo_one_envo_extra_is_activated() ) {
				return;
			}
			$theme_data = wp_get_theme();
			?>

			<div class="notice notice-info envo-one-notice-nux">

				<div class="notice-content">
					<?php if ( !envo_one_envo_extra_is_activated() && current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) : ?>
						<h2>
							<?php
							/* translators: %s: Theme name */
							printf( esc_html__( 'Thank you for installing %s.', 'envo-one' ), '<strong>' . $theme_data->Name . '</strong>' );
							?>
						</h2>
						<p>
							<?php
							/* translators: %s: Plugin name string */
							printf( esc_html__( 'To take full advantage of all the features this theme has to offer, please install and activate the %s plugin.', 'envo-one' ), '<strong>Envo Extra</strong>' );
							?>
						</p>
						<p><?php Envo_One_Plugin_Install::install_plugin_button( 'envo-extra', 'envo-extra.php', 'Envo Extra', array( 'envo-one-nux-button' ), __( 'Activated', 'envo-one' ), __( 'Activate', 'envo-one' ), __( 'Install', 'envo-one' ) ); ?></p>
					<?php endif; ?>


				</div>
			</div>
			<?php
		}

		/**
		 * AJAX dismiss notice.
		 *
		 * @since 2.2.0
		 */
		public function dismiss_nux() {
			$nonce = !empty( $_POST[ 'nonce' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ) : false;

			if ( !$nonce || !wp_verify_nonce( $nonce, 'envo_one_notice_dismiss' ) || !current_user_can( 'manage_options' ) ) {
				die();
			}

			update_option( 'envo_one_notify_dismissed', true );
		}

	}

	endif;

return new Envo_One_Notify_Admin();
