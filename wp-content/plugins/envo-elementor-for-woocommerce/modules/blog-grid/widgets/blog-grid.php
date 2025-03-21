<?php
namespace ETWWElementor\Modules\BlogGrid\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Blog_Grid extends Widget_Base {

	public function get_name() {
		return 'etww-blog-grid';
	}

	public function get_title() {
		return __('Blog Grid', 'etww');
	}

	public function get_icon() {
		 
		return 'etww-icon eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'etww-elements' ];
	}

	public function get_script_depends() {
		return [ 'etww-blog-grid', 'isotope', 'imagesloaded' ];
	}

	public function get_style_depends() {
		return [ 'etww-blog-grid', 'font-awesome-5-all' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_blog_grid',
			[
				'label' 		=> __('Blog Grid', 'etww'),
			]
		);

		$this->add_control(
			'count',
			[
				'label' 		=> __('Posts Per Page', 'etww'),
				'description' 	=> __('You can enter "-1" to display all posts.', 'etww'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '6',
				'label_block' 	=> true,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' 		=> __('Grid Columns', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' 		=> [
					'1' 		=> '1',
					'2' 		=> '2',
					'3' 		=> '3',
					'4' 		=> '4',
					'5' 		=> '5',
					'6' 		=> '6',
				],
				'selectors' => [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-entry' => 'width: calc(100% / {{VALUE}});',
				],
			]
		);

		$this->add_control(
			'grid_style',
			[
				'label' 		=> __('Grid Style', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'fit-rows',
				'options' 		=> [
					'fit-rows' 	=> __('Fit Rows', 'etww'),
					'masonry' 	=> __('Masonry', 'etww'),
				],
			]
		);

		$this->add_control(
			'grid_equal_heights',
			[
				'label' 		=> __('Equal Heights', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'no',
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' 		=> __('Pagination', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'no',
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' 		=> __('Pagination Position', 'etww'),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options' => [
					'left' => [
						'title' => __('Left', 'etww'),
						'icon' 	=> 'eicon-h-align-left',
					],
					'center' => [
						'title' => __('Center', 'etww'),
						'icon' 	=> 'eicon-h-align-center',
					],
					'right' => [
						'title' => __('Right', 'etww'),
						'icon' 	=> 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.page-numbers' => 'text-align: {{VALUE}};',
				],
				'default' 		=> 'center',
				'condition' => [
					'pagination' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_query',
            [
                'label' => __('Query', 'etww')
            ]
       );

		$this->add_control(
			'post_type',
			[
				'label' 		=> __('Post Type', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '0',
				'options' 		=> $this->get_available_post_types(),
			]
		);

		$this->add_control(
			'order',
			[
				'label' 		=> __('Order', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options' 		=> [
					'' 			=> __('Default', 'etww'),
					'DESC' 		=> __('DESC', 'etww'),
					'ASC' 		=> __('ASC', 'etww'),
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 		=> __('Order By', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options' 		=> [
					'' 				=> __('Default', 'etww'),
					'date' 			=> __('Date', 'etww'),
					'title' 		=> __('Title', 'etww'),
					'name' 			=> __('Name', 'etww'),
					'modified' 		=> __('Modified', 'etww'),
					'author' 		=> __('Author', 'etww'),
					'rand' 			=> __('Random', 'etww'),
					'ID' 			=> __('ID', 'etww'),
					'comment_count' => __('Comment Count', 'etww'),
					'menu_order' 	=> __('Menu Order', 'etww'),
				],
			]
		);

		$this->add_control(
			'include_categories',
			[
				'label' 		=> __('Include Categories', 'etww'),
				'description' 	=> __('Enter the categories slugs seperated by a "comma"', 'etww'),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'exclude_categories',
			[
				'label' 		=> __('Exclude Categories', 'etww'),
				'description' 	=> __('Enter the categories slugs seperated by a "comma"', 'etww'),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'section_elements',
            [
                'label' => __('Elements', 'etww')
            ]
       );

		$this->add_control(
			'image_size',
			[
				'label' 		=> __('Image Size', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'medium',
				'options' 		=> $this->get_img_sizes(),
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label' 		=> __('Learn More Text', 'etww'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __('Learn More', 'etww'),
				'label_block' 	=> true,
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_control(
			'title',
			[
				'label' 		=> __('Display Title', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __('Show', 'etww'),
				'label_off' 	=> __('Hide', 'etww'),
			]
		);

		$this->add_control(
			'author',
			[
				'label' 		=> __('Display Author', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __('Show', 'etww'),
				'label_off' 	=> __('Hide', 'etww'),
			]
		);

		$this->add_control(
			'comments',
			[
				'label' 		=> __('Display Comments', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __('Show', 'etww'),
				'label_off' 	=> __('Hide', 'etww'),
			]
		);

		$this->add_control(
			'cat',
			[
				'label' 		=> __('Display Categories', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __('Show', 'etww'),
				'label_off' 	=> __('Hide', 'etww'),
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label' 		=> __('Display Excerpt', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __('Show', 'etww'),
				'label_off' 	=> __('Hide', 'etww'),
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' 		=> __('Excerpt Length', 'etww'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '15',
				'label_block' 	=> true,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_grid',
			[
				'label' 		=> __('Grid', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'grid_background_color',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_border_color',
			[
				'label' 		=> __('Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-inner' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' 		=> __('Overlay Button', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_typo',
				'selector' 		=> '{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn',
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'etww'),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' 		=> __('Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __('Hover', 'etww'),
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label' 		=> __('Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .overlay-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tab();

        $this->end_controls_section();

		$this->start_controls_section(
			'section_avatar',
			[
				'label' 		=> __('Author Avatar', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'avatar_border_color',
			[
				'label' 		=> __('Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-media .entry-author-link' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' 		=> __('Title', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-details .etww-grid-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' 		=> __('Color: Hover', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-details .etww-grid-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'title_typo',
				'selector' 		=> '{{WRAPPER}} .etww-blog-grid .etww-grid-details .etww-grid-title',
				
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_excerpt',
			[
				'label' 		=> __('Excerpt', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-details .etww-grid-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'excerpt_typo',
				'selector' 		=> '{{WRAPPER}} .etww-blog-grid .etww-grid-details .etww-grid-excerpt',
				
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_meta',
			[
				'label' 		=> __('Meta', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'meta_bg',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-meta' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-meta, {{WRAPPER}} .etww-blog-grid .etww-grid-meta li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color_hover',
			[
				'label' 		=> __('Color: Hover', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-blog-grid .etww-grid-meta li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'meta_typo',
				'selector' 		=> '{{WRAPPER}} .etww-blog-grid .etww-grid-meta',
				
			]
		);

        $this->end_controls_section();

	}

	protected function get_available_post_types() {

		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		if(! empty($args['post_type'])) {
			$post_type_args['name'] = $args['post_type'];
		}

		$post_types = get_post_types($post_type_args , 'objects');

		$result = array(__('-- Select --', 'etww'));

		foreach($post_types as $post_type => $object) {
			$result[ $post_type ] = $object->label;
		}

		return $result;
	}

	public function get_img_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();
	    $get_intermediate_image_sizes = get_intermediate_image_sizes();
	 
	    // Create the full array with sizes and crop info
	    foreach($get_intermediate_image_sizes as $_size) {
	        if(in_array($_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
	            $sizes[ $_size ]['width'] 	= get_option($_size . '_size_w');
	            $sizes[ $_size ]['height'] 	= get_option($_size . '_size_h');
	            $sizes[ $_size ]['crop'] 	= (bool) get_option($_size . '_crop');
	        } elseif(isset($_wp_additional_image_sizes[ $_size ])) {
	            $sizes[ $_size ] = array(
	                'width' 	=> $_wp_additional_image_sizes[ $_size ]['width'],
	                'height' 	=> $_wp_additional_image_sizes[ $_size ]['height'],
	                'crop' 		=> $_wp_additional_image_sizes[ $_size ]['crop'],
	           );
	        }
	    }

	    $image_sizes = array();

		foreach($sizes as $size_key => $size_attributes) {
			$image_sizes[ $size_key ] = ucwords(str_replace('_', ' ', $size_key)) . sprintf(' - %d x %d', $size_attributes['width'], $size_attributes['height']);
		}

		$image_sizes['full'] 	= _x('Full', 'Image Size Control', 'etww');

	    return $image_sizes;
	}

	protected function render() {
		$settings 		= $this->get_settings_for_display();

		// Vars
		$post_type 		= $settings['post_type'];
		$post_type 		= $post_type ? $post_type : 'post';
		$posts_per_page = $settings['count'];
		$order 			= $settings['order'];
		$orderby  		= $settings['orderby'];
	    $include 		= $settings['include_categories'];
	    $exclude 		= $settings['exclude_categories'];
		$pagination  	= $settings['pagination'];

		// Paged
		global $paged;
		if(get_query_var('paged')) {
			$paged = get_query_var('paged');
		} else if(get_query_var('page')) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}

		$args = array(
	        'post_type'         => $post_type,
	        'posts_per_page'    => $posts_per_page,
			'paged' 			=> $paged,
	        'order'             => $order,
	        'orderby'           => $orderby,
			'tax_query' 		=> array(
				'relation' 		=> 'AND',
			),
	   );

	    // Include category
		if(! empty($include)) {

			// Sanitize category and convert to array
			$include = str_replace(', ', ',', $include);
			$include = explode(',', $include);

			// Add to query arg
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $include,
				'operator' => 'IN',
			);

		}

		// Exclude category
		if(! empty($exclude)) {

			// Sanitize category and convert to array
			$exclude = str_replace(', ', ',', $exclude);
			$exclude = explode(',', $exclude);

			// Add to query arg
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $exclude,
				'operator' => 'NOT IN',
			);

		}

	    // Build the WordPress query
	    $etww_query = new \WP_Query($args);

		// Output posts
		if($etww_query->have_posts()) :

			// Vars
			$grid_style 	= $settings['grid_style'];
			$equal_heights 	= $settings['grid_equal_heights'];
			$readmore 		= $settings['readmore_text'];
			$title   		= $settings['title'];
			$excerpt 		= $settings['excerpt'];
			$author 		= $settings['author'];
			$comments 		= $settings['comments'];
			$cat 			= $settings['cat'];

			// Image size
			$img_size 		= $settings['image_size'];
			$img_size 		= $img_size ? $img_size : 'medium';

			// Wrapper classes
			$wrap_classes = array('etww-blog-grid', 'clr');

			if('masonry' == $grid_style) {
				$wrap_classes[] = 'etww-masonry';
			}

			if('yes' == $equal_heights) {
				$wrap_classes[] = 'match-height-grid';
			}

			if('yes' == $author) {
				$wrap_classes[] = 'has-avatar';
			}

			$wrap_classes = implode(' ', $wrap_classes); ?>

			<div class="<?php echo esc_attr($wrap_classes); ?>">

				<?php
				// Start loop
				while($etww_query->have_posts()) : $etww_query->the_post();

					// Inner classes
					$inner_classes 		= array('etww-grid-entry', 'clr');

					if('masonry' == $grid_style) {
						$inner_classes[] = 'isotope-entry';
					}

					$inner_classes = implode(' ', $inner_classes);

					// If equal heights
					$details_class = '';
					if('yes' == $equal_heights) {
						$details_class = ' match-height-content';
					}

					// Meta class
					$meta_class = '';
					if('false' == $comments || 'false' == $cat) {
						$meta_class = ' etww-center';
					}

					// Create new post object.
					$post = new \stdClass();

					// Get post data
					$get_post = get_post();

					// Post Data
					$post->ID           = $get_post->ID;
					$post->permalink    = get_the_permalink($post->ID);
					$post->title        = $get_post->post_title;

					// Only display carousel item if there is content to show
					if(has_post_thumbnail()
						|| 'yes' == $title
						|| 'yes' == $excerpt
					) { ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class($inner_classes); ?>>

							<?php
							// Open details if the elements are yes
							if('yes' == $title
								|| 'yes' == $excerpt
							) { ?>

								<div class="etww-grid-inner clr">
							
									<?php
									// Display thumbnail if enabled and defined
									if(has_post_thumbnail()) { ?>

										<div class="etww-grid-media clr">

											<a href="<?php echo esc_url($post->permalink); ?>" title="<?php esc_attr(the_title()); ?>" class="etww-grid-img">

												<?php
												// Display post thumbnail
												the_post_thumbnail($img_size, array(
													'alt'		=> get_the_title(),
													'itemprop' 	=> 'image',
												)); ?>

												<span class="overlay">
													<?php
													// Display read more
													if('' != $readmore) { ?>
														<span class="overlay-btn">
															<?php echo esc_html($readmore); ?>
														</span>
													<?php } ?>
												</span>

											</a>

											<?php if('yes' == $author) { ?>
												<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php esc_attr_e('Visit Author Page', 'etww'); ?>" class="entry-author-link" rel="author" >
													<?php
													// Display author avatar
													echo get_avatar(get_the_author_meta('user_email'), 100); ?>
												</a>
											<?php } ?>

										</div><!-- .etww-grid-media -->

									<?php } ?>

									<?php
									// Open details element if the title or excerpt are yes
									if('yes' == $title
										|| 'yes' == $excerpt
									) { ?>

										<div class="etww-grid-details<?php echo esc_attr($details_class); ?> clr">

											<?php
											// Display title if $title is yes and there is a post title
											if('yes' == $title) { ?>

												<h2 class="etww-grid-title entry-title">
													<a href="<?php echo esc_url($post->permalink); ?>" title="<?php esc_html(the_title()); ?>"><?php echo esc_html($post->title); ?></a>
												</h2>

											<?php } ?>

											<?php
											// Display excerpt if $excerpt is yes
											if('yes' == $excerpt) { ?>

												<div class="etww-grid-excerpt clr">
													<?php etww_excerpt($settings['excerpt_length']); ?>
												</div>
												
											<?php } ?>

										</div><!-- .etww-grid-details -->

									<?php } ?>

									<?php
									// Display meta
									if('yes' == $comments
										|| 'yes' == $cat) { ?>

										<ul class="etww-grid-meta<?php echo esc_attr($meta_class); ?> clr">

											<?php if('yes' == $comments && comments_open() && ! post_password_required()) { ?>
												<li class="meta-comments"><i class="icon-bubble"></i><?php comments_popup_link(esc_html__('0 Comments', 'etww'), esc_html__('1 Comment',  'etww'), esc_html__('% Comments', 'etww'), 'comments-link'); ?></li>
											<?php } ?>

											<?php if('yes' == $cat) { ?>
												<li class="meta-cat"><i class="icon-folder"></i><?php the_category(' / ', get_the_ID()); ?></li>
											<?php } ?>

										</ul>

									<?php } ?>

								</div>

							<?php } ?>

						</article>

					<?php } ?>

				<?php
				// End entry loop
				endwhile; ?>

			</div><!-- .etww-blog-grid -->
				
			<?php
			// Display pagination if enabled
			if('yes' == $pagination) {
				etww_pagination($etww_query);
			} ?>

			<?php
			// Reset the post data to prevent conflicts with WP globals
			wp_reset_postdata(); wp_reset_query();

		// If no posts are found display message
		else : ?>

			<p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'etww'); ?></p>

		<?php
		// End post check
		endif; ?>

	<?php
	}

}