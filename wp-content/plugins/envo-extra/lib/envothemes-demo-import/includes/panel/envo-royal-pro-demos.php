<?php

function envo_extra_pro_get_demos_data( $data ) {

	// Demos url
	$url = ENVO_URL . 'img/demos/';

	$extras	 = array(
		'pro-demo-1' => array(
			'demo_name'			 => 'Envo Royal PRO #1',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO', 'Business', 'Creative' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-1.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-1/',
			'home_title'		 => 'Envo Royal PRO Home #1',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-2' => array(
			'demo_name'			 => 'Envo Royal PRO #2',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO', 'Business', 'Creative' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-2.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-2/',
			'home_title'		 => 'Envo Royal PRO Home #2',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-3' => array(
			'demo_name'			 => 'Envo Royal PRO #3',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO', 'Business', 'Creative' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-3.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-3/',
			'home_title'		 => 'Envo Royal PRO Home #3',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-4' => array(
			'demo_name'			 => 'Envo Royal PRO #4',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO', 'Business', 'Creative' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-4.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-4/',
			'home_title'		 => 'Envo Royal PRO Home #4',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-5' => array(
			'demo_name'			 => 'Envo Royal PRO #5',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO', 'Business', 'Creative' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-5.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-5/',
			'home_title'		 => 'Envo Royal PRO Home #5',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-6' => array(
			'demo_name'			 => 'Envo Royal PRO #6',
			'categories'		 => array( 'Elementor', 'PRO', 'Business', 'Creative', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-6.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-6/',
			'home_title'		 => 'Envo Royal PRO Home #6',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-7' => array(
			'demo_name'			 => 'Envo Royal PRO #7',
			'categories'		 => array( 'Elementor', 'PRO', 'Business', 'Creative', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-7.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-7/',
			'home_title'		 => 'Envo Royal PRO Home #7',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-8' => array(
			'demo_name'			 => 'Envo Royal PRO #8',
			'categories'		 => array( 'Elementor', 'PRO', 'Business', 'Creative', 'Landing Page' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro/screenshot-8.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro/envo-royal-pro-home-8/',
			'home_title'		 => 'Envo Royal PRO Home #8',
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
						'slug'	 => 'envo-elementor-for-woocommerce',
						'init'	 => 'envo-elementor-for-woocommerce/elementor-templates-widgets-woocommerce.php',
						'name'	 => 'Elementor Templates and Widgets',
					),
				),
				'recommended'	 => array(),
				'premium'		 => array(
					array(
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-1' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #1',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-1.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-1/',
			'home_title'		 => 'Home PRO WooCommerce #1',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-2' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #2',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-2.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-2/',
			'home_title'		 => 'Home PRO WooCommerce #2',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-3' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #3',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-3.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-3/',
			'home_title'		 => 'Home PRO WooCommerce #3',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-4' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #4',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-4.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-4/',
			'home_title'		 => 'Home PRO WooCommerce #4',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-5' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #5',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-5.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-5/',
			'home_title'		 => 'Home PRO WooCommerce #5',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-6' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #6',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-6.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-6/',
			'home_title'		 => 'Home PRO WooCommerce #6',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
		'pro-demo-woo-7' => array(
			'demo_name'			 => 'Envo Royal PRO WooCommerce #7',
			'categories'		 => array( 'WooCommerce', 'Elementor', 'PRO' ),
			'xml_file'			 => '',
			'theme_settings'	 => '',
			'widgets_file'		 => '',
			'screenshot'		 => $url . 'envo-royal-pro-woocommerce/screenshot-woo-7.webp',
			'demo_link'			 => 'https://envo-demos.com/envo-royal-pro-woocommerce/home-pro-woocommerce-7/',
			'home_title'		 => 'Home PRO WooCommerce #7',
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
						'name'	 => 'Elementor Templates and Widgets',
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
						'slug'	 => 'envo-royal-pro',
						'init'	 => 'envo-royal-pro/envo-royal-pro.php',
						'name'	 => 'Envo Royal PRO',
					), ),
			),
		),
	);

	// combine the two arrays
	$data = array_merge( $data, $extras );

	return $data;
}

add_filter( 'envo_demos_data', 'envo_extra_pro_get_demos_data' );

