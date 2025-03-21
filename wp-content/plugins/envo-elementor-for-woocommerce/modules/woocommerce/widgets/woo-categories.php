<?php
namespace ETWWElementor\Modules\Woocommerce\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

class Woo_Categories extends Widget_Base {

	public function get_name() {
		return 'etww-woo-categories';
	}

	public function get_title() {
		return __('Woo - Categories', 'etww');
	}

	public function get_icon() {
		 
		return 'etww-icon eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'etww-elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_woo_categories',
			[
				'label' 		=> __('Products', 'etww'),
			]
		);
		
		$this->add_control(
			'columns',
			[
				'label' 		=> __('Columns', 'etww'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '4',
				'options' 		=> [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label' 		=> __('Categories Count', 'etww'),
				'type' 			=> Controls_Manager::NUMBER,
				'default' 		=> '4',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			[
				'label' 		=> __('Query', 'etww'),
				'tab' 			=> Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'source',
			[
				'label'   		=> _x('Source', 'Posts Query Control', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'options' 		=> [
					''          => __('Show All', 'etww'),
					'by_id'     => __('Manual Selection', 'etww'),
					'by_parent' => __('By Parent', 'etww'),
				],
			]
		);

		$categories = get_terms('product_cat');

		$options = [];
		foreach($categories as $category) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'categories',
			[
				'label' 		=> __('Categories', 'etww'),
				'type'        	=> Controls_Manager::SELECT2,
				'options'     	=> $options,
				'default'     	=> [],
				'label_block' 	=> true,
				'multiple'    	=> true,
				'condition'   	=> [
					'source' => 'by_id',
				],
			]
		);

		$parent_options = [ '0' => __('Only Top Level', 'etww') ] + $options;
		$this->add_control(
			'parent',
			[
				'label'     	=> __('Parent', 'etww'),
				'type'      	=> Controls_Manager::SELECT,
				'default'   	=> '0',
				'options'   	=> $parent_options,
				'condition' 	=> [
					'source' => 'by_parent',
				],
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        	=> __('Hide Empty', 'etww'),
				'type'         	=> Controls_Manager::SWITCHER,
				'return_value' 	=> 'yes',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   		=> __('Order by', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'default' 		=> 'name',
				'options' 		=> [
					'name'        => __('Name', 'etww'),
					'slug'        => __('Slug', 'etww'),
					'description' => __('Description', 'etww'),
					'count'       => __('Count', 'etww'),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   		=> __('Order', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'default' 		=> 'desc',
				'options' 		=> [
					'asc'  => __('ASC', 'etww'),
					'desc' => __('DESC', 'etww'),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' 		=> __('Item', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_background_color',
			[
				'label' 		=> __('Background Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 			=> 'item_border',
				'placeholder' 	=> '1px',
				'selector' 		=> '{{WRAPPER}} .woocommerce ul.products li.product',
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label' 		=> __('Border Radius', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 			=> 'item_box_shadow',
				'selector' 		=> '{{WRAPPER}} .woocommerce ul.products li.product',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' 		=> __('Padding', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' 		=> __('Margin', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' 		=> __('Image', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 			=> 'image_border',
				'placeholder' 	=> '1px',
				'selector' 		=> '{{WRAPPER}} .woocommerce ul.products li.product img',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' 		=> __('Border Radius', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 			=> 'image_box_shadow',
				'selector' 		=> '{{WRAPPER}} .woocommerce ul.products li.product img',
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label' 		=> __('Padding', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' 		=> __('Title', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'title_typography',

				'selector' 		=> '{{WRAPPER}} .woocommerce ul.products li.product a .woocommerce-loop-category__title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     	=> esc_html__('Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product a .woocommerce-loop-category__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     	=> esc_html__('Hover Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product a:hover .woocommerce-loop-category__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' 		=> __('Margin', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product a .woocommerce-loop-category__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_style',
			[
				'label' 		=> __('Description', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'desc_typography',

				'selector' 		=> '{{WRAPPER}} .woocommerce ul.products li.product .description',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     	=> esc_html__('Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product .description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'desc_margin',
			[
				'label' 		=> __('Margin', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woocommerce ul.products li.product .description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	private function get_shortcode() {
		$settings = $this->get_settings();

		$attributes = [
			'number'     => $settings['number'],
			'columns'    => $settings['columns'],
			'hide_empty' => ('yes' === $settings['hide_empty']) ? 1 : 0,
			'orderby'    => $settings['orderby'],
			'order'      => $settings['order'],
		];

		if('by_id' === $settings['source']) {
			$attributes['ids'] = implode(',', $settings['categories']);
		} elseif('by_parent' === $settings['source']) {
			$attributes['parent'] = $settings['parent'];
		}

		$this->add_render_attribute('shortcode', $attributes);

		$shortcode = sprintf('[product_categories %s]', $this->get_render_attribute_string('shortcode'));

		return $shortcode;
	}

	public function render() {
		echo do_shortcode($this->get_shortcode());
	}

	public function render_plain_content() {
		echo $this->get_shortcode();
	}

}