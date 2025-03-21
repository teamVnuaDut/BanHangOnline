<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom widgets for Elementor
 *
 * This class handles custom widgets for Elementor
 *
 * @since 1.0.0
 */
#[AllowDynamicProperties]
final class Enwoo_Extra_Elementor_Extension {

	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Registers widgets in Elementor
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {

		
		foreach ( $this->modules as $key => $value ) {
			require_once ENVO_EXTRA_PATH . 'lib/elementor/widgets/' . $key . '/' . $key . '.php';
			\Elementor\Plugin::instance()->widgets_manager->register( new $value[ 'class' ]() );
		}
	}

	/**
	 * Registers widgets scripts
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {
		
	}
	public function editor_scripts() {
		wp_register_script( 'preview-script-elmn', ENVO_EXTRA_PLUGIN_URL . 'lib/elementor/assets/js/elementor.js', [ 'elementor-editor' ], ELEMENTOR_VERSION, true );

		wp_enqueue_script( 'preview-script-elmn' );
	}

	/**
	 * Enqueue widgets scripts in preview mode, as later calls in widgets render will not work,
	 * as it happens in admin env
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts_preview() {

	}

	/**
	 * Registers widgets styles
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_styles() {
		
		foreach ($this->modules as $key => $value) {
            wp_register_style('envo-extra-' . $key . '', ENVO_EXTRA_PLUGIN_URL . 'lib/elementor/assets/css/' . $key . '/' . $key . '.css');
        }
		wp_register_style( 'font-awesome-5-all', ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css', false );
	}

	public function widget_styles_preview() {
		foreach ($this->modules as $key => $value) {
            wp_register_style('envo-extra-' . $key . '', ENVO_EXTRA_PLUGIN_URL . 'lib/elementor/assets/css/' . $key . '/' . $key . '.css');
        }
		
	}

	public function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
		'envo-extra-widgets', [
			'title'	 => __( 'Envo Extra', 'envo-extra' ),
			'icon'	 => 'fa fa-plug',
		]
		);
	}

	/**
	 * Widget constructor.
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		
		add_action( 'elementor/widgets/register', [$this, 'register_widgets' ] );
		// Register Widget Styles
		add_action( 'elementor/frontend/after_register_styles', [$this, 'widget_styles' ] );
		//add_action('elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ]);
		add_action( 'elementor/editor/after_enqueue_scripts', [$this, 'editor_scripts' ] );
		// Register Widget Scripts
		add_action( 'elementor/frontend/after_register_scripts', [$this, 'widget_scripts' ] );
		// Enqueue ALL Widgets Scripts for preview
		add_action( 'elementor/preview/enqueue_scripts', [$this, 'widget_scripts_preview' ] );

		add_action( 'elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories' ] );

		add_action( 'elementor/preview/enqueue_styles', [$this, 'widget_styles_preview' ] );
		
		$this->modules = array(
			'block-quote'	 => array(
				'class' => 'Envo_Block_Quote',
			),
			'button'		 => array(
				'class' => 'Button',
			),
			'counter'		 => array(
				'class' => 'Counter',
			),
			'heading'		 => array(
				'class' => 'Heading',
			),
			'team'		 => array(
				'class' => 'Team',
			),
			'icon-box'		 => array(
				'class' => 'Icon_Box',
			),
			'pricing'		 => array(
				'class' => 'Pricing',
			),
			'testimonial'	 => array(
				'class' => 'Testimonial',
			),
		);
	}

}

Enwoo_Extra_Elementor_Extension::instance();

