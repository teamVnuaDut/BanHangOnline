<?php
namespace ETWWElementor\Modules\Search\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class Search extends Widget_Base {

    public function get_name() {
		return 'etww-search';
	}

	public function get_title() {
		return __('Ajax Search', 'etww');
	}

	public function get_icon() {
		 
		return 'etww-icon eicon-search';
	}

	public function get_categories() {
		return [ 'etww-elements' ];
	}

	public function get_script_depends() {
		return [ 'etww-search' ];
	}

	public function get_style_depends() {
		return [ 'etww-search', 'font-awesome-5-all' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_search',
			[
				'label' 		=> __('Search', 'etww'),
			]
		);
		
		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'etww' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'product',
				'options' => [
					'any' => __( 'All Post Types', 'etww' ),
					'post' => __( 'Posts', 'etww' ),
					'page' => __( 'Pages', 'etww' ),
					'product' => __( 'Products', 'etww' ),
				],
				'prefix_class' => 'etww-search-pro--source-',
				'frontend_available' => true,
			]
		);
		
		$this->add_responsive_control(
			'width',
			[
				'label' 		=> __('Width', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-search-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' 		=> __('Height', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-searchform, {{WRAPPER}} .etww-searchform input.field' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label' 		=> __('Placeholder', 'etww'),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __('Search', 'etww'),
				'placeholder' 	=> __('Search', 'etww'),
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		/*$this->add_control(
			'source',
			[
				'label' 		=> _x('Source', 'Posts Type', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'options' 		=> $this->get_post_types(),
				'default' 		=> 'any',
				'label_block' 	=> true,
			]
		);*/

		$this->add_control(
			'enable_ajax',
			[
				'label' 		=> __('Enable Ajax', 'etww'),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __('Show', 'etww'),
				'label_off' 	=> __('Hide', 'etww'),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' 		=> __('Alignment', 'etww'),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left'    => [
						'title' => __('Left', 'etww'),
						'icon' 	=> 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'etww'),
						'icon' 	=> 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'etww'),
						'icon' 	=> 'fa fa-align-right',
					],
				],
				'default' 		=> '',
				'prefix_class' => 'wew%s-align-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input',
			[
				'label' 		=> __('Input', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_input_style');

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => __('Normal', 'etww'),
			]
		);

		$this->add_control(
			'input_bg',
			[
				'label' 		=> __('Background', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_hover',
			[
				'label' => __('Hover', 'etww'),
			]
		);

		$this->add_control(
			'input_bg_hover',
			[
				'label' 		=> __('Background', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_color_hover',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color_hover',
			[
				'label' 		=> __('Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => __('Focus', 'etww'),
			]
		);

		$this->add_control(
			'input_bg_focus',
			[
				'label' 		=> __('Background', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_color_focus',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label' 		=> __('Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 			=> 'input_focus_box_shadow',
				'selector' 		=> '{{WRAPPER}} .etww-searchform input.field:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 			=> 'input_border',
				'label' 		=> __('Border', 'etww'),
				'placeholder' 	=> '1px',
				'default' 		=> '1px',
				'selector' 		=> '{{WRAPPER}} .etww-searchform input.field',
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'input_border_radius',
			[
				'label' 		=> __('Border Radius', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'input_padding',
			[
				'label' 		=> __('Padding', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform input.field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'newsletter_input',
				'selector' 		=> '{{WRAPPER}} .etww-searchform input.field',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label' 		=> __('Icon Button', 'etww'),
				'type' 			=> Controls_Manager::SECTION,
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' 		=> __('Font Size', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'default' => [
					'size' => 12,
				],
				'range' => [
					'min' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .etww-searchform button' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label' 		=> __('Position', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'min' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .etww-searchform button' => is_rtl() ? 'left: {{SIZE}}px;' : 'right: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs('tabs_icon_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'etww'),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_bg_color',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform button' => 'background-color: {{VALUE}};',
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
			'btn_color_hover',
			[
				'label' 		=> __('Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_bg_color_hover',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-searchform button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search_results',
			[
				'label' 		=> __('Search Results', 'etww'),
				'type' 			=> Controls_Manager::SECTION,
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'results_bg',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_border_radius',
			[
				'label' 		=> __('Border Radius', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' 	=> 'after',
			]
		);

		$this->start_controls_tabs('tabs_results_links_style');

		$this->start_controls_tab(
			'tab_results_links_normal',
			[
				'label' => __('Normal', 'etww'),
			]
		);

		$this->add_control(
			'results_links_bg',
			[
				'label' 		=> __('Links Background', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a.search-result-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_links_color',
			[
				'label' 		=> __('Links Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a.search-result-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_links_border_color',
			[
				'label' 		=> __('Links Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_icons_color',
			[
				'label' 		=> __('Icons Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a i.icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_all_links_color',
			[
				'label' 		=> __('All Results Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a.all-results' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'results_links_hover',
			[
				'label' => __('Hover', 'etww'),
			]
		);

		$this->add_control(
			'results_links_hover_bg',
			[
				'label' 		=> __('Links Background', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a.search-result-link:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_links_hover_color',
			[
				'label' 		=> __('Links Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a.search-result-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_links_hover_border_color',
			[
				'label' 		=> __('Links Border Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_icons_hover_color',
			[
				'label' 		=> __('Icons Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a:hover i.icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'results_all_links_hover_color',
			[
				'label' 		=> __('All Results Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a.all-results:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'results_links_padding',
			[
				'label' => __('Links Padding', 'etww'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'results_search_typo',
				'selector' 		=> '{{WRAPPER}} .etww-search-wrap .etww-search-results ul li a',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_no_search_results',
			[
				'label' 		=> __('No Results Found', 'etww'),
				'type' 			=> Controls_Manager::SECTION,
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'no_results_heading_color',
			[
				'label' 		=> __('Heading Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-no-search-results h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'no_results_heading_typo',
				'selector' 		=> '{{WRAPPER}} .etww-search-wrap .etww-no-search-results h6',

			]
		);

		$this->add_control(
			'no_results_text_color',
			[
				'label' 		=> __('Text Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-search-wrap .etww-no-search-results p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'no_results_text_typo',
				'selector' 		=> '{{WRAPPER}} .etww-search-wrap .etww-no-search-results p',

			]
		);

	}

	private static function get_post_types($args = []) {
		$post_type_args = [
			'show_in_nav_menus' => true,
		];

		if(! empty($args['post_type'])) {
			$post_type_args['name'] = $args['post_type'];
		}

		$_post_types = get_post_types($post_type_args , 'objects');

		$post_types  = [];
		$post_types[ 'any' ]  = esc_html__('Any', 'etww');

		foreach($_post_types as $post_type => $object) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Admin ajax, put it in data so that it works in the edit mode of Elementor
		$ajax = admin_url('admin-ajax.php');

		// If ajax
		$classes = 'etww-searchform';
		if('yes' == $settings['enable_ajax']) {
			$classes .= ' etww-ajax-search';
		}

		// Placeholder
		$placeholder = '';
		if(! empty($settings['placeholder'])) {
			$placeholder = $settings['placeholder'];
		} ?>

		<div class="etww-search-wrap" data-ajaxurl="<?php echo esc_url($ajax); ?>">
			<form method="get" class="<?php echo esc_attr($classes); ?>" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
				<input type="text" class="field" name="s" id="s" placeholder="<?php echo esc_attr($placeholder); ?>">
				<button type="submit" class="search-submit" value=""><i class="fas fa-search"></i></button>
				<input type="hidden" class="post-type" name="post_type" value="<?php echo esc_attr($settings['source']); ?>">
			</form>
			<?php
			if('yes' == $settings['enable_ajax']) { ?>
				<div class="etww-ajax-loading"></div>
				<div class="etww-search-results"></div>
			<?php
			} ?>
		</div>

	<?php
	}

	// No template because it cause a js error in the edit mode


}