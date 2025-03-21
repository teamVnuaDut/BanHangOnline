<?php
namespace ETWWElementor\Modules\FlipBox\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if(! defined('ABSPATH')) exit; // Exit if accessed directly

class FlipBox extends Widget_Base {

	public function get_name() {
		return 'etww-flip-box';
	}

	public function get_title() {
		return __('Flip Box', 'etww');
	}

	public function get_icon() {
		 
		return 'etww-icon eicon-flip-box';
	}

	public function get_categories() {
		return [ 'etww-elements' ];
	}

	public function get_style_depends() {
		return [ 'etww-flip-box' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_front',
			[
				'label' 		=> __('Front', 'etww'),
			]
		);

		$this->add_control(
			'graphic_element',
			[
				'label'   		=> __('Graphic Element', 'etww'),
				'type'    		=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options' 		=> [
					'none' => [
						'title' => __('None', 'etww'),
						'icon'  => 'fa fa-ban',
					],
					'image' => [
						'title' => __('Image', 'etww'),
						'icon'  => 'fas fa-image',
					],
					'icon' => [
						'title' => __('Icon', 'etww'),
						'icon'  => 'fa fa-star',
					],
				],
				'default' 		=> 'icon',
			]
		);

		$this->add_control(
			'image',
			[
				'label'   		=> __('Choose Image', 'etww'),
				'type'    		=> Controls_Manager::MEDIA,
				'default' 		=> [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' 	=> [
					'graphic_element' => 'image',
				],
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      	=> 'image', // Actually its `image_size`
				'label'     	=> __('Image Size', 'etww'),
				'default'   	=> 'thumbnail',
				'condition' 	=> [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     	=> __('Icon', 'etww'),
				'type'      	=> Controls_Manager::ICONS,
				'default' 		=> [
						'value' => 'fas fa-heart-o',
						'library' => 'solid',
					],
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label'   		=> __('View', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'default' 		=> 'default',
				'options' 		=> [
					'default' => __('Default', 'etww'),
					'stacked' => __('Stacked', 'etww'),
					'framed'  => __('Framed', 'etww'),
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label'   		=> __('Shape', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'default' 		=> 'circle',
				'options' 		=> [
					'circle' => __('Circle', 'etww'),
					'square' => __('Square', 'etww'),
				],
				'condition' 	=> [
					'icon_view!'      => 'default',
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'front_title_text',
			[
				'label'       	=> __('Title & Description', 'etww'),
				'type'        	=> Controls_Manager::TEXT,
				'default'     	=> __('This is the heading', 'etww'),
				'placeholder' 	=> __('Your Title', 'etww'),
				'separator'   	=> 'before',
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_control(
			'front_description_text',
			[
				'label'       	=> __('Description', 'etww'),
				'type'        	=> Controls_Manager::TEXTAREA,
				'default'     	=> __('Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'etww'),
				'placeholder' 	=> __('Your Description', 'etww'),
				'title'       	=> __('Input image text here', 'etww'),
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_back',
			[
				'label' 		=> __('Back', 'etww'),
			]
		);

		$this->add_control(
			'back_title_text',
			[
				'label'       	=> __('Title & Description', 'etww'),
				'type'        	=> Controls_Manager::TEXT,
				'default'     	=> __('This is the heading', 'etww'),
				'placeholder' 	=> __('Your Title', 'etww'),
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_control(
			'back_description_text',
			[
				'label'       	=> __('Description', 'etww'),
				'type'        	=> Controls_Manager::TEXTAREA,
				'default'     	=> __('Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'etww'),
				'placeholder' 	=> __('Your Description', 'etww'),
				'title'       	=> __('Input image text here', 'etww'),
				'separator'   	=> 'none',
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'     	=> __('Button Text', 'etww'),
				'type'      	=> Controls_Manager::TEXT,
				'default'   	=> __('Click Here', 'etww'),
				'separator' 	=> 'before',
				'dynamic' 		=> [ 'active' => true ],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       	=> __('Link', 'etww'),
				'type'        	=> Controls_Manager::URL,
				'placeholder' 	=> __('http://your-link.com', 'etww'),
			]
		);

		$this->add_control(
			'link_click',
			[
				'label'   		=> __('Apply Link On', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'options' 		=> [
					'box'    => __('Whole Box', 'etww'),
					'button' => __('Button Only', 'etww'),
				],
				'default'   	=> 'button',
				'condition' 	=> [
					'link[url]!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' 		=> __('Settings', 'etww'),
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' 		=> __('Height', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', 'vh' ],
				'range' 		=> [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      	=> __('Border Radius', 'etww'),
				'type'       	=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range'      	=> [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-layer, {{WRAPPER}} .etww-flip-box-layer-overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'flip_effect',
			[
				'label'   		=> __('Flip Effect', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'default' 		=> 'flip',
				'options' 		=> [
					'flip'     => __('Flip', 'etww'),
					'slide'    => __('Slide', 'etww'),
					'push'     => __('Push', 'etww'),
					'zoom-in'  => __('Zoom In', 'etww'),
					'zoom-out' => __('Zoom Out', 'etww'),
					'fade'     => __('Fade', 'etww'),
				],
				'prefix_class' 	=> 'etww-flip-box-effect-',
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'flip_direction',
			[
				'label'   		=> __('Flip Direction', 'etww'),
				'type'    		=> Controls_Manager::SELECT,
				'default' 		=> 'left',
				'options' 		=> [
					'left'  => __('Left', 'etww'),
					'right' => __('Right', 'etww'),
					'up'    => __('Up', 'etww'),
					'down'  => __('Down', 'etww'),
				],
				'condition' 	=> [
					'flip_effect!' => [
							'fade',
							'zoom-in',
							'zoom-out',
						],
				],
				'prefix_class' 	=> 'etww-flip-box-direction-',
			]
		);

		$this->add_control(
			'flip_3d',
			[
				'label' 		=> __('3D Depth', 'etww'),
				'type'         	=> Controls_Manager::SWITCHER,
				'default'      	=> 'yes',
				'condition' 	=> [
					'flip_effect' => 'flip',
				],
				'prefix_class' 	=> 'etww-flip-box-3d-',
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'section_style_front',
			[
				'label' 		=> __('Front', 'etww'),
				'tab'   		=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     		=> 'front_background',
				'types'    		=> [ 'classic', 'gradient' ],
				'selector' 		=> '{{WRAPPER}} .etww-flip-box-front',
			]
		);

		$this->add_control(
			'front_background_overlay',
			[
				'label'     	=> __('Background Overlay', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'condition' 	=> [
					'front_background_image[id]!' => '',
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_responsive_control(
			'front_padding',
			[
				'label' 		=> __('Padding', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'front_alignment',
			[
				'label' 		=> __('Alignment', 'etww'),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options' 		=> [
					'left' => [
						'title' => __('Left', 'etww'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'etww'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'etww'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' 		=> 'center',
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'front_vertical_position',
			[
				'label' 		=> __('Vertical Position', 'etww'),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options' 		=> [
					'top' => [
						'title' => __('Top', 'etww'),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __('Middle', 'etww'),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'etww'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-overlay' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      	=> 'front_border',
				'selector'  	=> '{{WRAPPER}} .etww-flip-box-front',
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Image', 'etww'),
				'condition' 	=> [
					'graphic_element' => 'image',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' 		=> __('Spacing', 'etww'),
				'type'  		=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_width',
			[
				'label'      	=> __('Size (%)', 'etww'),
				'type'       	=> Controls_Manager::SLIDER,
				'size_units' 	=> [ '%' ],
				'default'    	=> [
					'unit' => '%',
				],
				'range' 		=> [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' 	=> [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'   		=> __('Opacity (%)', 'etww'),
				'type'    		=> Controls_Manager::SLIDER,
				'default' 		=> [
					'size' => 1,
				],
				'range' 		=> [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-image' => 'opacity: {{SIZE}};',
				],
				'condition' 	=> [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      	=> 'image_border',
				'label'     	=> __('Image Border', 'etww'),
				'selector'  	=> '{{WRAPPER}} .etww-flip-box-image img',
				'condition' 	=> [
					'graphic_element' => 'image',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' 		=> __('Border Radius', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition' 	=> [
					'graphic_element' => 'image',
				],
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Icon', 'etww'),
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label' 		=> __('Spacing', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_primary_color',
			[
				'label' 		=> __('Icon Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_secondary_color',
			[
				'label' 		=> __('Secondary Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' 		=> __('Icon Size', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' 		=> __('Icon Padding', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'range' 		=> [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_rotate',
			[
				'label' 		=> __('Icon Rotate', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'default' 		=> [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_border_width',
			[
				'label' 		=> __('Border Width', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
					'icon_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label' 		=> __('Border Radius', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' 	=> [
					'graphic_element' => 'icon',
					'icon_view!' => 'default',
				],
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Title', 'etww'),
				'condition' 	=> [
					'front_title_text!' => '',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'front_title_spacing',
			[
				'label' 		=> __('Spacing', 'etww'),
				'type' 			=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'front_description_text!' => '',
				],
			]
		);

		$this->add_control(
			'front_title_color',
			[
				'label' 		=> __('Text Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-title' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     		=> 'front_title_typography',
				'label'    		=> __('Typography', 'etww'),
				'selector' 		=> '{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-title',
			]
		);

		$this->add_control(
			'heading_description_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Description', 'etww'),
				'condition' 	=> [
					'front_description_text!' => '',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'front_description_color',
			[
				'label'     	=> __('Text Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-desc' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     		=> 'front_description_typography',
				'label'    		=> __('Typography', 'etww'),

				'selector' 		=> '{{WRAPPER}} .etww-flip-box-front .etww-flip-box-layer-desc',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_back',
			[
				'label' 		=> __('Back', 'etww'),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     		=> 'back_background',
				'types'    		=> [ 'classic', 'gradient' ],
				'selector' 		=> '{{WRAPPER}} .etww-flip-box-back',
			]
		);

		$this->add_control(
			'back_background_overlay',
			[
				'label' 		=> __('Background Overlay', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'default' 		=> '',
				'condition' 	=> [
					'back_background_image[id]!' => '',
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-overlay' => 'background-color: {{VALUE}};',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_responsive_control(
			'back_padding',
			[
				'label' 		=> __('Padding', 'etww'),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'back_alignment',
			[
				'label' 		=> __('Alignment', 'etww'),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options' 		=> [
					'left' => [
						'title' => __('Left', 'etww'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'etww'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'etww'),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' 		=> 'center',
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .etww-flip-box-button' => 'margin-{{VALUE}}: 0',
				],
			]
		);

		$this->add_control(
			'back_vertical_position',
			[
				'label' 		=> __('Vertical Position', 'etww'),
				'type' 			=> Controls_Manager::CHOOSE,
				'label_block' 	=> false,
				'options'     	=> [
					'top' => [
						'title' => __('Top', 'etww'),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __('Middle', 'etww'),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __('Bottom', 'etww'),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-overlay' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      	=> 'back_border',
				'selector'  	=> '{{WRAPPER}} .etww-flip-box-back',
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'heading_back_title_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Title', 'etww'),
				'condition' 	=> [
					'back_title_text!' => '',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'back_title_spacing',
			[
				'label' 		=> __('Spacing', 'etww'),
				'type'  		=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'back_title_text!' => '',
				],
			]
		);

		$this->add_control(
			'back_title_color',
			[
				'label'     	=> __('Text Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-title' => 'color: {{VALUE}}',

				],
				'condition' 	=> [
					'back_title_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      	=> 'back_title_typography',
				'label'     	=> __('Typography', 'etww'),

				'selector'  	=> '{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-title',
				'condition' 	=> [
					'back_title_text!' => '',
				],
			]
		);

		$this->add_control(
			'heading_back_description_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Description', 'etww'),
				'condition' 	=> [
					'back_description_text!' => '',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_control(
			'back_description_spacing',
			[
				'label' 		=> __('Spacing', 'etww'),
				'type'  		=> Controls_Manager::SLIDER,
				'range' 		=> [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'back_description_color',
			[
				'label' 		=> __('Text Color', 'etww'),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-desc' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      	=> 'description_typography_b',
				'label'     	=> __('Typography', 'etww'),

				'selector'  	=> '{{WRAPPER}} .etww-flip-box-back .etww-flip-box-layer-desc',
			]
		);

		$this->add_control(
			'heading_back_button_style',
			[
				'type' 			=> Controls_Manager::HEADING,
				'label' 		=> __('Button', 'etww'),
				'condition' 	=> [
					'button_text!' => '',
				],
				'separator' 	=> 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     		=> 'button_typography',
				'label'    		=> esc_html__('Typography', 'etww'),

				'selector' 		=> '{{WRAPPER}} .etww-flip-box-button',
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' 		=> esc_html__('Normal', 'etww'),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     	=> esc_html__('Background Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     	=> esc_html__('Text Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        	=> 'button_border',
				'label'       	=> esc_html__('Border', 'etww'),
				'placeholder' 	=> '1px',
				'default'     	=> '1px',
				'selector'    	=> '{{WRAPPER}} .etww-flip-box-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      	=> esc_html__('Border Radius', 'etww'),
				'type'       	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors'  	=> [
					'{{WRAPPER}} .etww-flip-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     		=> 'button_box_shadow',
				'selector' 		=> '{{WRAPPER}} .etww-flip-box-button',
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label'      	=> esc_html__('Padding', 'etww'),
				'type'       	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors'  	=> [
					'{{WRAPPER}} .etww-flip-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' 		=> esc_html__('Hover', 'etww'),
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     	=> esc_html__('Background Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     	=> esc_html__('Text Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     	=> esc_html__('Border Color', 'etww'),
				'type'      	=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .etww-flip-box-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     		=> 'button_hover_box_shadow',
				'selector' 		=> '{{WRAPPER}} .etww-flip-box-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		$settings 		= $this->get_settings_for_display();
		$wrap_tag 		= 'div';
		$button_tag 	= 'a';
		$link_url 		= empty($settings['link']['url']) ? '#' : esc_url($settings['link']['url']);

		$this->add_render_attribute('wrap', 'class', 'etww-flip-box-layer etww-flip-box-back');
		
		$this->add_render_attribute('button', 'class', [
			'etww-flip-box-button',
			'button',
		]);

		if('box' === $settings['link_click']) {
			$wrap_tag = 'a';
			$button_tag = 'button';
			$this->add_render_attribute('wrap', 'href', $link_url);

			if($settings['link']['is_external']) {
				$this->add_render_attribute('wrap', 'target', '_blank');
			}
		} else {
			$this->add_render_attribute('button', 'href', $link_url);

			if($settings['link']['is_external']) {
				$this->add_render_attribute('button', 'target', '_blank');
			}
		}

		if('icon' === $settings['graphic_element']) {
			$this->add_render_attribute('icon-wrap', 'class', 'elementor-icon-wrap');
			$this->add_render_attribute('icon-wrap', 'class', 'elementor-view-' . esc_html($settings['icon_view']));

			if('default' != $settings['icon_view']) {
				$this->add_render_attribute('icon-wrap', 'class', 'elementor-shape-' . esc_html($settings['icon_shape']));
			}

			if(! empty($settings['icon'])) {
				$this->add_render_attribute('icon', 'class', $settings['icon']);
			}
		} ?>

		<div class="etww-flip-box">

			<div class="etww-flip-box-layer etww-flip-box-front">
				<div class="etww-flip-box-layer-overlay">
					<div class="etww-flip-box-layer-inner">
						<?php
						if('image' === $settings['graphic_element']
							&& ! empty($settings['image']['url'])) { ?>
							<div class="etww-flip-box-image">
								<?php echo Group_Control_Image_Size::get_attachment_image_html($settings); ?>
							</div>
						<?php
						} else if('icon' === $settings['graphic_element']
							&& ! empty($settings['icon'])) { ?>
							<div <?php echo $this->get_render_attribute_string('icon-wrap'); ?>>
								<div class="elementor-icon">
									<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</div>
							</div>
						<?php
						}

						if(! empty($settings['front_title_text'])) { ?>
							<h3 class="etww-flip-box-layer-title">
								<?php echo $this->parse_text_editor($settings['front_title_text']); ?>
							</h3>
						<?php
						}

						if(! empty($settings['front_description_text'])) { ?>
							<div class="etww-flip-box-layer-desc">
								<?php echo $this->parse_text_editor($settings['front_description_text']); ?>
							</div>
						<?php
						} ?>
					</div>
				</div>
			</div>

			<<?php echo esc_attr($wrap_tag); ?> <?php echo $this->get_render_attribute_string('wrap'); ?>>
				<div class="etww-flip-box-layer-overlay">
					<div class="etww-flip-box-layer-inner">
						<?php
						if(! empty($settings['back_title_text'])) { ?>
							<h3 class="etww-flip-box-layer-title">
								<?php echo $this->parse_text_editor($settings['back_title_text']); ?>
							</h3>
						<?php
						}

						if(! empty($settings['back_description_text'])) { ?>
							<div class="etww-flip-box-layer-desc">
								<?php echo $this->parse_text_editor($settings['back_description_text']); ?>
							</div>
						<?php
						}

						if(! empty($settings['button_text'])) { ?>
							<<?php echo esc_attr($button_tag); ?> <?php echo $this->get_render_attribute_string('button'); ?>>
								<?php echo $this->parse_text_editor($settings['button_text']); ?>
							</<?php echo esc_attr($button_tag); ?>>
						<?php
						} ?>
					</div>
				</div>
			</<?php echo esc_attr($wrap_tag); ?>>

		</div>

	<?php
	}

	protected function content_template() { ?>
		<#
			if('image' === settings.graphic_element && '' !== settings.image.url) {
				var image = {
					id: settings.image.id,
					url: settings.image.url,
					size: settings.image_size,
					dimension: settings.image_custom_dimension,
					model: editModel
				};

				var imageUrl = elementor.imagesManager.getImageUrl(image);
			}

			var wrapperTag = 'div',
				buttonTag = 'a';

			if('box' === settings.link_click) {
				wrapperTag = 'a';
				buttonTag = 'button';
			}

			if('icon' === settings.graphic_element) {
				var iconWrapperClasses = 'elementor-icon-wrap';
					iconWrapperClasses += ' elementor-view-' + settings.icon_view;
				if('default' !== settings.icon_view) {
					iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
				}
			}
		#>

		<div class="etww-flip-box">
			<div class="etww-flip-box-layer etww-flip-box-front">
				<div class="etww-flip-box-layer-overlay">
					<div class="etww-flip-box-layer-inner">
						<# if('image' === settings.graphic_element
							&& '' !== settings.image.url) { #>
							<div class="etww-flip-box-image">
								<img src="{{ imageUrl }}">
							</div>
						<# } else if('icon' === settings.graphic_element
							&& settings.icon) { #>
							<div class="{{ iconWrapperClasses }}" >
								<div class="elementor-icon">
									<i class="{{ settings.icon }}"></i>
								</div>
							</div>
						<# } #>

						<# if(settings.front_title_text) { #>
							<h3 class="etww-flip-box-layer-title">{{{ settings.front_title_text }}}</h3>
						<# } #>

						<# if(settings.front_description_text) { #>
							<div class="etww-flip-box-layer-desc">{{{ settings.front_description_text }}}</div>
						<# } #>
					</div>
				</div>
			</div>
			<{{ wrapperTag }} class="etww-flip-box-layer etww-flip-box-back">
				<div class="etww-flip-box-layer-overlay">
					<div class="etww-flip-box-layer-inner">
						<# if(settings.back_title_text) { #>
							<h3 class="etww-flip-box-layer-title">{{{ settings.back_title_text }}}</h3>
						<# } #>

						<# if(settings.back_description_text) { #>
							<div class="etww-flip-box-layer-desc">{{{ settings.back_description_text }}}</div>
						<# } #>

						<# if(settings.button_text) { #>
							<{{ buttonTag }} href="#" class="etww-flip-box-button button">{{{ settings.button_text }}}</{{ buttonTag }}>
						<# } #>
					</div>
				</div>
			</{{ wrapperTag }}>
		</div>

	<?php
	}
}