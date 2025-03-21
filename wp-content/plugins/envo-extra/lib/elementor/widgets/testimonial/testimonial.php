<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Testimonial extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'envo-extra-testimonial';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Testimonial', 'envo-extra' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-testimonial';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'envo-extra-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'testimonial', 'rating', 'review', 'feedback' );
	}
	
	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_style_depends() {

		return array( 'envo-extra-testimonial', 'font-awesome-5-all' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
		'section_general', array(
			'label' => __( 'General', 'envo-extra' ),
		)
		);

		$this->add_control(
		'layout', array(
			'label'			 => esc_html__( 'Layout', 'envo-extra' ),
			'type'			 => Controls_Manager::SELECT,
			'default'		 => '1',
			'options'		 => array(
				'1'	 => esc_html__( 'Style 1', 'envo-extra' ),
				'2'	 => esc_html__( 'Style 2', 'envo-extra' ),
				'3'	 => esc_html__( 'Style 3', 'envo-extra' ),
				'4'	 => esc_html__( 'Style 4', 'envo-extra' ),
				'5'	 => esc_html__( 'Style 5', 'envo-extra' ),
				'6'	 => esc_html__( 'Style 6', 'envo-extra' ),
				'7'	 => esc_html__( 'Style 7', 'envo-extra' ),
				'8'	 => esc_html__( 'Style 8', 'envo-extra' ),
				'9'	 => esc_html__( 'Style 9', 'envo-extra' ),
				'10' => esc_html__( 'Style 10', 'envo-extra' ),
			),
			'prefix_class'	 => 'envo-extra-testimonial-layout-',
			'render_type'	 => 'template',
			'style_transfer' => true,
		)
		);

		$this->add_control(
		'image', array(
			'label'		 => __( 'Choose Image', 'envo-extra' ),
			'type'		 => Controls_Manager::MEDIA,
			'dynamic'	 => array(
				'active' => true,
			),
			'default'	 => array(
				'url' => Utils::get_placeholder_image_src(),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Image_Size::get_type(), array(
			'name'		 => 'thumbnail',
			'default'	 => 'large',
			'separator'	 => 'none',
			'exclude'	 => array( 'image' ),
		)
		);

		$this->add_control(
		'name', array(
			'label'			 => __( 'Name', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'default'		 => __( 'Jhon Walker', 'envo-extra' ),
			'label_block'	 => true,
			'separator'		 => 'before',
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'name_link', array(
			'label'			 => __( 'Link', 'envo-extra' ),
			'type'			 => Controls_Manager::URL,
			'placeholder'	 => 'https://example.com',
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'designation', array(
			'label'			 => __( 'Designation', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'default'		 => __( 'Managing Director', 'envo-extra' ),
			'label_block'	 => true,
			'separator'		 => 'before',
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'description', array(
			'label'			 => __( 'Description', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXTAREA,
			'default'		 => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'envo-extra' ),
			'placeholder'	 => __( 'Type your description here', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'show_quote', array(
			'label'			 => __( 'Show Quote', 'envo-extra' ),
			'type'			 => Controls_Manager::SWITCHER,
			'label_on'		 => __( 'Show', 'envo-extra' ),
			'label_off'		 => __( 'Hide', 'envo-extra' ),
			'default'		 => 'yes',
			'condition'		 => array(
				'layout!' => array( '6', '9', '10' ),
			),
			'render_type'	 => 'template',
		)
		);

		$this->add_control(
		'quote_icon', array(
			'label'		 => esc_html__( 'Icons', 'envo-extra-pro' ),
			'type'		 => \Elementor\Controls_Manager::ICONS,
			'default'	 => array(
				'value'		 => 'fas fa-quote-left',
				'library'	 => 'solid',
			),
			'condition'	 => array(
				'show_quote' => 'yes',
				'layout!'	 => array( '6', '9', '10' ),
			),
		)
		);

		$this->add_responsive_control(
		'align', array(
			'label'			 => __( 'Alignment', 'envo-extra' ),
			'type'			 => Controls_Manager::CHOOSE,
			'options'		 => array(
				'left'	 => array(
					'title'	 => __( 'Left', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-left',
				),
				'center' => array(
					'title'	 => __( 'Center', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-center',
				),
				'right'	 => array(
					'title'	 => __( 'Right', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-right',
				),
			),
			'separator'		 => 'before',
			'mobile_default' => 'center',
			'prefix_class'	 => 'envo-extra-testimonial-align-%s',
			'selectors'		 => array(
				'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_rating', array(
			'label' => __( 'Rating', 'envo-extra' ),
		)
		);

		$this->add_control(
		'ratting_style', array(
			'label'			 => __( 'Type', 'envo-extra' ),
			'type'			 => Controls_Manager::SELECT,
			'options'		 => array(
				'none'	 => __( 'None', 'envo-extra' ),
				'star'	 => __( 'Star', 'envo-extra' ),
				'num'	 => __( 'Number', 'envo-extra' ),
			),
			'default'		 => 'star',
			'style_transfer' => true,
		)
		);

		$this->add_control(
		'ratting', array(
			'label'		 => __( 'Rating', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 4,
			),
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 1,
					'max'	 => 5,
					'step'	 => 1,
				),
			),
			'dynamic'	 => array(
				'active' => true,
			),
		)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
		'section_image_style', array(
			'label'	 => __( 'Image', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_responsive_control(
		'width', array(
			'label'			 => __( 'Width', 'envo-extra' ),
			'type'			 => Controls_Manager::SLIDER,
			'default'		 => array(
				'unit' => '%',
			),
			'tablet_default' => array(
				'unit' => '%',
			),
			'mobile_default' => array(
				'unit' => '%',
			),
			'size_units'	 => array( '%', 'px', 'vw' ),
			'range'			 => array(
				'%'	 => array(
					'min'	 => 1,
					'max'	 => 100,
				),
				'px' => array(
					'min'	 => 1,
					'max'	 => 1000,
				),
				'vw' => array(
					'min'	 => 1,
					'max'	 => 100,
				),
			),
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-testimonial-image > img' => 'width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'height', array(
			'label'			 => __( 'Height', 'envo-extra' ),
			'type'			 => Controls_Manager::SLIDER,
			'default'		 => array(
				'unit' => 'px',
			),
			'tablet_default' => array(
				'unit' => 'px',
			),
			'mobile_default' => array(
				'unit' => 'px',
			),
			'size_units'	 => array( 'px', 'vh' ),
			'range'			 => array(
				'px' => array(
					'min'	 => 1,
					'max'	 => 500,
				),
				'vh' => array(
					'min'	 => 1,
					'max'	 => 100,
				),
			),
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-testimonial-image > img' => 'height: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'object-fit', array(
			'label'		 => __( 'Object Fit', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'condition'	 => array(
				'height[size]!' => '',
			),
			'options'	 => array(
				''			 => __( 'Default', 'envo-extra' ),
				'fill'		 => __( 'Fill', 'envo-extra' ),
				'cover'		 => __( 'Cover', 'envo-extra' ),
				'contain'	 => __( 'Contain', 'envo-extra' ),
			),
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-image > img' => 'object-fit: {{VALUE}};',
			),
		)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
		'normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_group_control(
		Group_Control_Css_Filter::get_type(), array(
			'name'		 => 'css_filters',
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-image > img',
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_group_control(
		Group_Control_Css_Filter::get_type(), array(
			'name'		 => 'css_filters_hover',
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-image > img',
		)
		);

		$this->add_control(
		'background_hover_transition', array(
			'label'		 => __( 'Transition Duration', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'range'		 => array(
				'px' => array(
					'max'	 => 3,
					'step'	 => 0.1,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-image > img' => 'transition-duration: {{SIZE}}s',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'image_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-image > img',
			'separator'	 => 'before',
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'image_box_shadow',
			'exclude'	 => array(
				'box_shadow_position',
			),
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-image > img',
		)
		);

		$this->add_responsive_control(
		'image_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'image_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-image > img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		//Content
		$this->start_controls_section(
		'section_content_style', array(
			'label'	 => __( 'Content', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'content_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}}.envo-extra-testimonial-layout-4 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-5 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-6 .envo-extra-testimonial-content,{{WRAPPER}}.envo-extra-testimonial-layout-8 .envo-extra-testimonial-content',
			'condition'	 => array(
				'layout' => array( '4', '5', '6', '8' ),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'content_border',
			'selector'	 => '{{WRAPPER}}.envo-extra-testimonial-layout-4 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-5 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-6 .envo-extra-testimonial-content,{{WRAPPER}}.envo-extra-testimonial-layout-8 .envo-extra-testimonial-content',
			'condition'	 => array(
				'layout' => array( '4', '5', '6', '8' ),
			),
		)
		);

		$this->add_responsive_control(
		'content_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}}.envo-extra-testimonial-layout-4 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-5 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-6 .envo-extra-testimonial-content,{{WRAPPER}}.envo-extra-testimonial-layout-8 .envo-extra-testimonial-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout' => array( '4', '5', '6', '8' ),
			),
		)
		);

		$this->add_responsive_control(
		'content_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}}.envo-extra-testimonial-layout-4 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-5 .envo-extra-testimonial-inner-wrapper,{{WRAPPER}}.envo-extra-testimonial-layout-6 .envo-extra-testimonial-content,{{WRAPPER}}.envo-extra-testimonial-layout-8 .envo-extra-testimonial-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'separator'	 => 'after',
			'condition'	 => array(
				'layout' => array( '4', '5', '6', '8' ),
			),
		)
		);

		$this->add_control(
		'heading_name', array(
			'label'		 => __( 'Name', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'condition'	 => array(
				'name!' => '',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'name_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-title',
			'condition'	 => array(
				'name!' => '',
			),
		)
		);

		$this->add_responsive_control(
		'name_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'name!' => '',
			),
		)
		);

		$this->add_control(
		'heading_designation', array(
			'label'		 => __( 'Designation', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
			'condition'	 => array(
				'designation!' => '',
			),
		)
		);

		$this->add_control(
		'designation_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-designation' => 'color: {{VALUE}}',
			),
			'condition'	 => array(
				'designation!' => '',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'designation_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-designation',
			'condition'	 => array(
				'designation!' => '',
			),
		)
		);

		$this->add_responsive_control(
		'designation_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'designation!' => '',
			),
		)
		);

		$this->add_control(
		'heading_description', array(
			'label'		 => __( 'Description', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
			'condition'	 => array(
				'description!' => '',
			),
		)
		);

		$this->add_control(
		'description_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-description' => 'color: {{VALUE}}',
			),
			'condition'	 => array(
				'description!' => '',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'description_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-testimonial-description',
			'condition'	 => array(
				'description!' => '',
			),
		)
		);

		$this->add_responsive_control(
		'description_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'description!' => '',
			),
		)
		);

		$this->end_controls_section();

		//Rating
		$this->start_controls_section(
		'section_rating_style', array(
			'label'		 => __( 'Rating', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'ratting_style!' => 'none',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'rating_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-rating-layout-num',
			'condition'	 => array(
				'ratting_style' => 'num',
			),
		)
		);

		$this->add_responsive_control(
		'ratting_size', array(
			'label'		 => __( 'Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'condition'	 => array(
				'ratting_style' => 'star',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-rating' => 'font-size: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'rating_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-rating, {{WRAPPER}} .envo-extra-rating-layout-star > i' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_control(
		'rating_fill', array(
			'label'		 => __( 'Filled', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-rating-layout-star > .envo-extra-rating-filled' => 'color: {{VALUE}}',
			),
			'condition'	 => array(
				'ratting_style' => 'star',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'rating_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-rating-layout-num',
			'condition'	 => array(
				'ratting_style' => 'num',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'rating_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-rating-layout-num',
			'separator'	 => 'before',
			'condition'	 => array(
				'ratting_style' => 'num',
			),
		)
		);

		$this->add_responsive_control(
		'rating_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-rating-layout-num' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'ratting_style' => 'num',
			),
		)
		);

		$this->add_responsive_control(
		'rating_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-rating-layout-num' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'ratting_style' => 'num',
			),
		)
		);

		$this->add_responsive_control(
		'rating_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		//Quote
		$this->start_controls_section(
		'section_quote_style', array(
			'label'		 => __( 'Quote', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'show_quote' => 'yes',
				'layout!'	 => array( '6', '9', '10' ),
			),
		)
		);

		$this->add_control(
		'quote_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-quote > i'		 => 'color: {{VALUE}}',
				'{{WRAPPER}} .envo-extra-testimonial-quote > svg'	 => 'fill: {{VALUE}}',
			),
		)
		);

		$this->add_responsive_control(
		'quote_sizes', array(
			'label'		 => __( 'Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-quote > i'		 => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-testimonial-quote > svg'	 => 'width: {{SIZE}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout!' => array( '6', '9', '10' ),
			),
		)
		);

		$this->add_responsive_control(
		'quote_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-testimonial-quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout!' => array( '6', '9', '10' ),
			),
		)
		);

		$this->end_controls_section();
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$title_tag	 = ( $settings[ 'name_link' ][ 'url' ] ) ? 'a' : 'h3';
		$title_attr	 = $settings[ 'name_link' ][ 'is_external' ] ? ' target="_blank"' : '';
		$title_attr .= $settings[ 'name_link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
		$title_attr .= $settings[ 'name_link' ][ 'url' ] ? ' href="' . esc_url($settings[ 'name_link' ][ 'url' ]) . '"' : '';
		?>

		<?php if ( '4' === $settings[ 'layout' ] || '5' === $settings[ 'layout' ] || '10' === $settings[ 'layout' ] ) { ?>
			<?php if ( $settings[ 'image' ][ 'id' ] || $settings[ 'image' ][ 'url' ] ) : ?>
				<div class="envo-extra-testimonial-image">
					<?php echo (!empty( $settings[ 'image' ][ 'id' ] ) ) ? wp_get_attachment_image( $settings[ 'image' ][ 'id' ], $settings[ 'thumbnail_size' ] ) : '<img src="' . esc_url( $settings[ 'image' ][ 'url' ] ) . '">'; ?>
				</div>
			<?php endif; ?>
		<?php } ?>

		<?php echo ( '4' === $settings[ 'layout' ] || '5' === $settings[ 'layout' ] || '6' === $settings[ 'layout' ] ) ? '<div class="envo-extra-testimonial-inner-wrapper">' : ''; ?>
		<div class="envo-extra-testimonial-content">

			<?php if ( 'yes' === $settings[ 'show_quote' ] && $settings[ 'quote_icon' ][ 'value' ] && '6' !== $settings[ 'layout' ] && '9' !== $settings[ 'layout' ] && '10' !== $settings[ 'layout' ] ) : ?>
				<span class="envo-extra-testimonial-quote">
					<?php \Elementor\Icons_Manager::render_icon( $settings[ 'quote_icon' ], array( 'aria-hidden' => 'true' ) ); ?>
				</span>
			<?php endif; ?>

			<?php if ( 'none' !== $settings[ 'ratting_style' ] && ( '2' === $settings[ 'layout' ] || '6' === $settings[ 'layout' ] || '10' === $settings[ 'layout' ] ) ) { ?>
				<div class="envo-extra-testimonial-rating envo-extra-rating-layout-<?php echo esc_attr( $settings[ 'ratting_style' ] ); ?>">
					<?php
					if ( 'num' === $settings[ 'ratting_style' ] ) {
						echo esc_html( $settings[ 'ratting' ][ 'size' ] ) . '<i class="fas fa-star" aria-hidden="true"></i>';
					} else {
						for ( $x = 1; $x <= 5; $x ++ ) {
							if ( $x <= $settings[ 'ratting' ][ 'size' ] ) {
								echo '<i class="fas fa-star envo-extra-rating-filled" aria-hidden="true"></i>';
							} else {
								echo '<i class="fas fa-star" aria-hidden="true"></i>';
							}
						}
					}
					?>

				</div>
			<?php } ?>

			<?php if ( $settings[ 'description' ] ) : ?>
				<div class="envo-extra-testimonial-description">
					<?php echo wp_kses_data( $settings[ 'description' ] ); ?>
				</div>
			<?php endif; ?>

			<?php if ( 'none' !== $settings[ 'ratting_style' ] && ( '1' === $settings[ 'layout' ] || '3' === $settings[ 'layout' ] || '7' === $settings[ 'layout' ] || '8' === $settings[ 'layout' ] || '9' === $settings[ 'layout' ] ) ) { ?>
				<div class="envo-extra-testimonial-rating envo-extra-rating-layout-<?php echo esc_attr( $settings[ 'ratting_style' ] ); ?>">
					<?php
					if ( 'num' === $settings[ 'ratting_style' ] ) {
						echo esc_html( $settings[ 'ratting' ][ 'size' ] ) . '<i class="fas fa-star" aria-hidden="true"></i>';
					} else {
						for ( $x = 1; $x <= 5; $x ++ ) {
							if ( $x <= $settings[ 'ratting' ][ 'size' ] ) {
								echo '<i class="fas fa-star envo-extra-rating-filled" aria-hidden="true"></i>';
							} else {
								echo '<i class="fas fa-star" aria-hidden="true"></i>';
							}
						}
					}
					?>

				</div>
			<?php } ?>
		</div>
		<div class="envo-extra-testimonial-author">
			<?php if ( '4' !== $settings[ 'layout' ] && '5' !== $settings[ 'layout' ] && '10' !== $settings[ 'layout' ] ) { ?>
				<?php if ( $settings[ 'image' ][ 'id' ] || $settings[ 'image' ][ 'url' ] ) : ?>
					<div class="envo-extra-testimonial-image">
						<?php echo (!empty( $settings[ 'image' ][ 'id' ] ) ) ? wp_get_attachment_image( $settings[ 'image' ][ 'id' ], $settings[ 'thumbnail_size' ] ) : '<img src="' . esc_url( $settings[ 'image' ][ 'url' ] ) . '">'; ?>
					</div>
				<?php endif; ?>
			<?php } ?>
			<?php if ( $settings[ 'name' ] || $settings[ 'designation' ] ) { ?>
				<div class="envo-extra-testimonial-author-bio">
					<?php if ( $settings[ 'name' ] ) : ?>
						<<?php echo esc_attr( $title_tag ); ?><?php echo wp_kses_data( $title_attr ); ?> class="envo-extra-testimonial-title"><?php echo esc_attr( $settings[ 'name' ] ); ?></<?php echo esc_attr( $title_tag ); ?>>
					<?php endif; ?>
					<?php if ( $settings[ 'designation' ] ) : ?>
						<h4 class="envo-extra-testimonial-designation"><?php echo esc_attr( $settings[ 'designation' ] ); ?></h4>
					<?php endif; ?>
				</div>
			<?php } ?>
			<?php if ( '4' === $settings[ 'layout' ] || '5' === $settings[ 'layout' ] ) { ?>
				<div class="envo-extra-testimonial-rating envo-extra-rating-layout-<?php echo esc_attr( $settings[ 'ratting_style' ] ); ?>">
					<?php
					if ( 'num' === $settings[ 'ratting_style' ] ) {
						echo esc_html( $settings[ 'ratting' ][ 'size' ] ) . '<i class="fas fa-star" aria-hidden="true"></i>';
					} else {
						for ( $x = 1; $x <= 5; $x ++ ) {
							if ( $x <= $settings[ 'ratting' ][ 'size' ] ) {
								echo '<i class="fas fa-star envo-extra-rating-filled" aria-hidden="true"></i>';
							} else {
								echo '<i class="fas fa-star" aria-hidden="true"></i>';
							}
						}
					}
					?>

				</div>
			<?php } ?>
		</div>
		<?php
		echo ( '4' === $settings[ 'layout' ] || '5' === $settings[ 'layout' ] || '6' === $settings[ 'layout' ] ) ? '</div>' : '';
	}

}
