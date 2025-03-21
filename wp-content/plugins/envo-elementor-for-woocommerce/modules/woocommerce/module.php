<?php
namespace ETWWElementor\Modules\Woocommerce;

use ETWWElementor\Base\Module_Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Woo_Add_To_Cart',
			'Woo_Products',
			'Woo_Categories',
			'Woo_Slider',
		];
	}

	public function get_name() {
		return 'etww-woocommerce';
	}

	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	public function fix_query_offset(&$query) {
		if(! empty($query->query_vars['offset_to_fix'])) {
			if($query->is_paged) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + (($query->query_vars['paged'] - 1) * $query->query_vars['posts_per_page']);
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	public function fix_query_found_posts($found_posts, $query) {
		$offset_to_fix = $query->get('offset_to_fix');

		if($offset_to_fix) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}

	function add_to_cart_product_ajax() {
		$product_id   = isset($_POST['product_id']) ? sanitize_text_field( wp_unslash($_POST['product_id'])) : 0;
		$variation_id = isset($_POST['variation_id']) ? sanitize_text_field( wp_unslash($_POST['variation_id'])) : 0;
		$quantity     = isset($_POST['quantity']) ? sanitize_text_field( wp_unslash($_POST['quantity'])) : 0;

		if($variation_id) {
			WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
		} else {
			WC()->cart->add_to_cart($product_id, $quantity);
		}
		die();
	}

	public function __construct() {
		parent::__construct();

		// In Editor Woocommerce frontend hooks before the Editor init.
		add_action('admin_action_elementor', [ $this, 'register_wc_hooks' ], 9);

		add_action('pre_get_posts', [ $this, 'fix_query_offset' ], 1);
		add_filter('found_posts', [ $this, 'fix_query_found_posts' ], 1, 2);

	}
	
	
}
