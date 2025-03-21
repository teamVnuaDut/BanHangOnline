<?php
namespace ETWWElementor\Modules\BlogGrid;

use ETWWElementor\Base\Module_Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Blog_Grid',
		];
	}

	public function get_name() {
		return 'etww-blog-grid';
	}
}
