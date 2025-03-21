<?php
/**
 * Envo One Theme Customizer
 *
 * @package Envo One
 */

/*
 * Notifications in customizer
 */
require get_template_directory() . '/lib/customizer/notice/class-customizer-notice.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

require get_template_directory() . '/lib/customizer/install/class-plugin-install-helper.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

$theme_data = wp_get_theme();

$config_customizer = array(
	'recommended_plugins' => array( 
		'envo-extra' => array(
			'recommended' => true,
			/* translators: %s: Plugin name string */
			'description' => sprintf( esc_html__( 'To take full advantage of all the features this theme has to offer, please install and activate the %s plugin.', 'envo-one' ), '<strong>Envo Extra</strong>' ),
		),
	),
	/* translators: %s: Theme name */
    'recommended_plugins_title' => sprintf( esc_html__( 'Thank you for installing %s.', 'envo-one' ), $theme_data->Name ),
	'install_button_label'      => esc_html__( 'Install now', 'envo-one' ),
	'activate_button_label'     => esc_html__( 'Activate', 'envo-one' ),
);
Envo_One_Customizer_Notice::init( apply_filters( 'envo_one_customizer_notice_array', $config_customizer ) );