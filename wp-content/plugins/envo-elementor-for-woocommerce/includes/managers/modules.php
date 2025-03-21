<?php

namespace ETWWElementor;

use ETWWElementor\Base\Module_Base;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Modules_Manager {

    /**
     * @var Module_Base[]
     */
    private $modules = [];

    /**
     * @since 1.0.0
     */
    public function register_modules() {

        $modules = [
            'animated-heading',
            'query-post',
            'blog-grid',
            'flip-box',
            'off-canvas',
            'pricing',
            'search',
            'tabs',
            'contact-form',
        ];


        // If WooCommerce
        if (etww_is_woocommerce_active()) {
            $modules[] = 'woocommerce';
        }

        foreach ($modules as $module_name) {
            $class_name = str_replace('-', ' ', $module_name);
            $class_name = str_replace(' ', '', ucwords($class_name));
            $class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

            $this->modules[$module_name] = $class_name::instance();
        }
    }

    private function require_files() {
        require(ETWW_ELEMENTOR_PATH . 'base/module.php');
        require(ETWW_ELEMENTOR_PATH . 'base/widget.php');
    }

    public function __construct() {
        $this->require_files();
        $this->register_modules();
    }

}
