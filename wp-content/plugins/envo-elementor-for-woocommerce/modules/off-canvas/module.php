<?php
namespace ETWWElementor\Modules\OffCanvas;

use ETWWElementor\Base\Module_Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Off_Canvas',
		];
	}

	public function get_name() {
		return 'etww-off-canvas';
	}
}
