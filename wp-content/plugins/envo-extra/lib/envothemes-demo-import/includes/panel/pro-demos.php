<?php

function envo_extra_pro_get_demos_data( $data ) {

	// Demos url
	$url = ENVO_URL . 'img/demos/enwoo/pro/';

	$extras = array(
		'pro-demo-1'	 => array(
			'demo_name'			 => 'Enwoo PRO #1',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro1.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-1/',
			'home_title'		 => 'Home PRO #1',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(
					array(
						'slug'	 => 'yith-woocommerce-wishlist',
						'init'	 => 'yith-woocommerce-wishlist/init.php',
						'name'	 => 'YITH WooCommerce Wishlist',
					),
					array(
						'slug'	 => 'yith-woocommerce-compare',
						'init'	 => 'yith-woocommerce-compare/init.php',
						'name'	 => 'YITH WooCommerce Compare',
					),
				),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-2'	 => array(
			'demo_name'			 => 'Enwoo PRO #2',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro2.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-2/',
			'home_title'		 => 'Home PRO #2',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(
					array(
						'slug'	 => 'yith-woocommerce-wishlist',
						'init'	 => 'yith-woocommerce-wishlist/init.php',
						'name'	 => 'YITH WooCommerce Wishlist',
					),
					array(
						'slug'	 => 'yith-woocommerce-compare',
						'init'	 => 'yith-woocommerce-compare/init.php',
						'name'	 => 'YITH WooCommerce Compare',
					),
				),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-3'	 => array(
			'demo_name'			 => 'Enwoo PRO #3',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro3.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-3/',
			'home_title'		 => 'Home PRO #3',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(
					array(
						'slug'	 => 'yith-woocommerce-wishlist',
						'init'	 => 'yith-woocommerce-wishlist/init.php',
						'name'	 => 'YITH WooCommerce Wishlist',
					),
					array(
						'slug'	 => 'yith-woocommerce-compare',
						'init'	 => 'yith-woocommerce-compare/init.php',
						'name'	 => 'YITH WooCommerce Compare',
					),
				),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-4'	 => array(
			'demo_name'			 => 'Enwoo PRO #4',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro4.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-4/',
			'home_title'		 => 'Home PRO #4',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(
					array(
						'slug'	 => 'yith-woocommerce-wishlist',
						'init'	 => 'yith-woocommerce-wishlist/init.php',
						'name'	 => 'YITH WooCommerce Wishlist',
					),
					array(
						'slug'	 => 'yith-woocommerce-compare',
						'init'	 => 'yith-woocommerce-compare/init.php',
						'name'	 => 'YITH WooCommerce Compare',
					),
				),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-5'	 => array(
			'demo_name'			 => 'Enwoo PRO #5',
			'categories'		 => array( 'Business', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro5.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-5/',
			'home_title'		 => 'Home PRO #5',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-6'	 => array(
			'demo_name'			 => 'Enwoo PRO #6',
			'categories'		 => array( 'WooCommerce', 'Gutenberg', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro6.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-6/',
			'home_title'		 => 'Home PRO #6',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-7'	 => array(
			'demo_name'			 => 'Enwoo PRO #7',
			'categories'		 => array( 'Business', 'Gutenberg', 'PRO', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro7.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-7/',
			'home_title'		 => 'Home PRO #7',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-8'	 => array(
			'demo_name'			 => 'Enwoo PRO #8',
			'categories'		 => array( 'WooCommerce', 'Gutenberg', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro8.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-8/',
			'home_title'		 => 'Home PRO #8',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-9'	 => array(
			'demo_name'			 => 'Enwoo PRO #9',
			'categories'		 => array( 'WooCommerce', 'Gutenberg', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro9.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-9/',
			'home_title'		 => 'Home PRO #9',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					), ),
			),
		),
		'pro-demo-10'	 => array(
			'demo_name'			 => 'Enwoo PRO #10',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO', 'Business', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro10.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-10/',
			'home_title'		 => 'Home PRO #10',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-11'	 => array(
			'demo_name'			 => 'Enwoo PRO #11',
			'categories'		 => array( 'Elementor', 'PRO', 'Business', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro11.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-11/',
			'home_title'		 => 'Home PRO #11',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-12'	 => array(
			'demo_name'			 => 'Enwoo PRO #12',
			'categories'		 => array( 'Elementor', 'PRO', 'WooCommerce' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro12.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-12/',
			'home_title'		 => 'Home PRO #12',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-13'	 => array(
			'demo_name'			 => 'Enwoo PRO #13',
			'categories'		 => array( 'Elementor', 'PRO', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro13.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-13/',
			'home_title'		 => 'Home PRO #13',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-14'	 => array(
			'demo_name'			 => 'Enwoo PRO #14',
			'categories'		 => array( 'Elementor', 'PRO', 'WooCommerce', 'Business' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro14.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-14/',
			'home_title'		 => 'Home PRO #14',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => true,
			'woo_image_size'	 => '600',
			'woo_thumb_size'	 => '300',
			'woo_crop_width'	 => '2',
			'woo_crop_height'	 => '3',
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
					),
					array(
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets for WooCommerce',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-15'	 => array(
			'demo_name'			 => 'Enwoo PRO #15',
			'categories'		 => array( 'Elementor', 'PRO', 'Landing Page', 'Business' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro15.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-15/',
			'home_title'		 => 'Home PRO #15',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-16'	 => array(
			'demo_name'			 => 'Enwoo PRO #16',
			'categories'		 => array( 'Elementor', 'PRO', 'Landing Page', 'Business' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro16.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-16/',
			'home_title'		 => 'Home PRO #16',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-demo-17'	 => array(
			'demo_name'			 => 'Enwoo PRO #17',
			'categories'		 => array( 'Elementor', 'PRO', 'Landing Page', 'Business', 'WooCommerce' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro17.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-17/',
			'home_title'		 => 'Home PRO #17',
			'blog_title'		 => 'Blog',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(
					array(
						'slug'	 => 'woocommerce',
						'init'	 => 'woocommerce/woocommerce.php',
						'name'	 => 'WooCommerce',
						'notice' => '(Only for shop purposes.)',
					),
				),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-headers'	 => array(
			'demo_name'			 => 'Enwoo PRO Elementor Headers',
			'categories'		 => array( 'Elementor', 'PRO'),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro-headers.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-headers/',
			'home_title'		 => '',
			'blog_title'		 => '',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
		'pro-footers'	 => array(
			'demo_name'			 => 'Enwoo PRO Elementor Footers',
			'categories'		 => array( 'Elementor', 'PRO'),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'pro-footers.webp',
			'demo_link'			 => 'https://enwoo-demos.com/pro-demo-footers/',
			'home_title'		 => '',
			'blog_title'		 => '',
			'posts_to_show'		 => '6',
			'elementor_width'	 => '1140',
			'is_shop'			 => false,
			'required_plugins'	 => array(
				'free'			 => array(
					array(
						'slug'	 => 'elementor',
						'init'	 => 'elementor/elementor.php',
						'name'	 => 'Elementor',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'enwoo-pro',
						'init'	 => 'enwoo-pro/enwoo-pro.php',
						'name'	 => 'Enwoo PRO',
					),
				),
			),
		),
	);

	// combine the two arrays
	$data = array_merge( $data, $extras );

	return $data;
}

add_filter( 'envo_demos_data', 'envo_extra_pro_get_demos_data' );

