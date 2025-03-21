<?php
namespace ETWWElementor\Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Module
 *
 * Base 
 *
 * @since 1.1.0
 */
abstract class Module_Base {

	/**
	 * @var \ReflectionClass
	 */
	private $reflection;

	/**
	 * @var Module_Base
	 */
	protected static $_instances = [];

	/**
	 * Abstract method for retrieveing the module name
	 *
	 * @access public
	 * @since 1.1.0
	 */
	abstract public function get_name();

	/**
	 * Return the current module class name
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @eturn string
	 */
	public static function class_name() {
		return get_called_class();
	}

	/**
	 * @return static
	 */
	public static function instance() {
		if(empty(static::$_instances[ static::class_name() ])) {
			static::$_instances[ static::class_name() ] = new static();
		}

		return static::$_instances[ static::class_name() ];
	}

	/**
	 * Constructor
	 *
	 * Hook into Elementor to register the widgets
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->reflection = new \ReflectionClass($this);

		add_action('elementor/widgets/register', [ $this, 'init_widgets' ]);
	}

	/**
	 * Initializes all widget for the current module
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function init_widgets() {
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;

		foreach($this->get_widgets() as $widget) {

			$class_name = $this->reflection->getNamespaceName() . '\Widgets\\' . $widget;

			$widget_manager->register(new $class_name());
		}
	}

	/**
	 * Method for retrieveing this module's widgets
	 *
	 * @access public
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function get_widgets() {
		return [];
	}
}
