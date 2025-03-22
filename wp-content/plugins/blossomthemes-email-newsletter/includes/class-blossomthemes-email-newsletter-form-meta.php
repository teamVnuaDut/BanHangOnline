<?php
/**
 * BlossomThemes Email Newsletters metas.
 *
 * Responsible for creating metaboxes for form.
 *
 * @package    BlossomThemes_Email_Newsletters
 * @subpackage BlossomThemes_Email_Newsletters/includes
 * @author
 */
class BlossomThemes_Email_Newsletter_Form_Meta {

	function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'blossomthemes_email_newsletter_add_field_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'blossomthemes_email_newsletter_save_field_data' ) );
		add_action( 'add_meta_boxes', array( $this, 'blossomthemes_email_newsletter_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'blossomthemes_email_newsletter_save_meta_box_data' ) );
		add_action( 'add_meta_boxes', array( $this, 'blossomthemes_email_newsletter_add_usage_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'blossomthemes_email_newsletter_save_shortcode_data' ) );
		add_action( 'add_meta_boxes', array( $this, 'blossomthemes_email_newsletter_add_list_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'blossomthemes_email_newsletter_save_list_data' ) );
	}

	/**
	 * Form metabox.
	 *
	 * @since 1.0
	 */
	function blossomthemes_email_newsletter_add_meta_boxes() {
		$screens = array( 'subscribe-form' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'form_id',
				__( 'Appearance Settings', 'blossomthemes-email-newsletter' ),
				array( $this, 'blossomthemes_email_newsletter_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Form settings
	public function blossomthemes_email_newsletter_metabox_callback() {
		include BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/setting/form-meta.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function blossomthemes_email_newsletter_save_meta_box_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Sanitize user input.
		$bten_settings = new Blossomthemes_Email_Newsletter_Functions();
		$blossomthemes_email_newsletter_settings = isset( $_POST['blossomthemes_email_newsletter_setting'] ) ? $bten_settings::bten_clean( wp_unslash( $_POST['blossomthemes_email_newsletter_setting'] ) ) : array();

		if ( isset( $blossomthemes_email_newsletter_settings ) ) {
			$settings = $blossomthemes_email_newsletter_settings;
			update_post_meta( $post_id, 'blossomthemes_email_newsletter_setting', $settings );
		}
	}

	/**
	 * Form metabox.
	 *
	 * @since 1.0
	 */
	function blossomthemes_email_newsletter_add_field_meta_boxes() {
		$screens = array( 'subscribe-form' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'form_field_id',
				__( 'Field Settings', 'blossomthemes-email-newsletter' ),
				array( $this, 'blossomthemes_email_newsletter_field_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Form settings
	public function blossomthemes_email_newsletter_field_metabox_callback() {
		include BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/setting/form-field-meta.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function blossomthemes_email_newsletter_save_field_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Sanitize user input.
		$bten_settings = new Blossomthemes_Email_Newsletter_Functions();
		$blossomthemes_email_newsletter_settings = isset( $_POST['blossomthemes_email_newsletter_setting'] ) ? $bten_settings::bten_clean( wp_unslash( $_POST['blossomthemes_email_newsletter_setting'] ) ) : array();

		if ( isset( $blossomthemes_email_newsletter_settings ) ) {
			$settings = $blossomthemes_email_newsletter_settings;
			update_post_meta( $post_id, 'blossomthemes_email_newsletter_setting', $settings );
		}
	}

	/**
	 * Form metabox.
	 *
	 * @since 1.0
	 */
	function blossomthemes_email_newsletter_add_usage_meta_boxes() {
		$screens = array( 'subscribe-form' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'shortcode_field_id',
				__( 'Usage', 'blossomthemes-email-newsletter' ),
				array( $this, 'blossomthemes_email_newsletter_shortcode_metabox_callback' ),
				$screen,
				'side',
				'high'
			);
		}
	}

	// Form settings
	public function blossomthemes_email_newsletter_shortcode_metabox_callback() {
		include BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/setting/shortcode-meta.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function blossomthemes_email_newsletter_save_shortcode_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Sanitize user input.
		$bten_settings = new Blossomthemes_Email_Newsletter_Functions();
		$blossomthemes_email_newsletter_settings = isset( $_POST['blossomthemes_email_newsletter_setting'] ) ? $bten_settings::bten_clean( wp_unslash( $_POST['blossomthemes_email_newsletter_setting'] ) ) : array();

		if ( isset( $blossomthemes_email_newsletter_settings ) ) {
			$settings = $blossomthemes_email_newsletter_settings;
			update_post_meta( $post_id, 'blossomthemes_email_newsletter_setting', $settings );
		}
	}

	/**
	 * List metabox.
	 *
	 * @since 1.0
	 */
	function blossomthemes_email_newsletter_add_list_meta_boxes() {
		$screens = array( 'subscribe-form' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'list_id',
				__( 'Group/List', 'blossomthemes-email-newsletter' ),
				array( $this, 'blossomthemes_email_newsletter_list_metabox_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}

	// Form settings
	public function blossomthemes_email_newsletter_list_metabox_callback() {
		include BLOSSOMTHEMES_EMAIL_NEWSLETTER_BASE_PATH . '/includes/setting/form-list-meta.php';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function blossomthemes_email_newsletter_save_list_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Sanitize user input.
		$bten_settings = new Blossomthemes_Email_Newsletter_Functions();
		$blossomthemes_email_newsletter_settings = isset( $_POST['blossomthemes_email_newsletter_setting'] ) ? $bten_settings::bten_clean( wp_unslash( $_POST['blossomthemes_email_newsletter_setting'] ) ) : array();

		if ( isset( $blossomthemes_email_newsletter_settings ) ) {
			$settings = $blossomthemes_email_newsletter_settings;
			update_post_meta( $post_id, 'blossomthemes_email_newsletter_setting', $settings );
		}
	}
}
new BlossomThemes_Email_Newsletter_Form_Meta();
