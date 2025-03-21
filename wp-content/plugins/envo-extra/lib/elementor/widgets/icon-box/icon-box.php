<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;

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
class Icon_Box extends Widget_Base {

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
		return 'envo-extra-icon-box';
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
		return __( 'Icon Box', 'envo-extra' );
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
		return 'eicon-icon-box';
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
		return array( 'icon', 'icon-box' );
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

		return array( 'animate', 'envo-extra-icon-box' );
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
		'section_icon', array(
			'label'	 => __( 'Content', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_CONTENT,
		)
		);

		$this->add_control(
		'media_type', array(
			'label'			 => __( 'Media Type', 'envo-extra' ),
			'type'			 => Controls_Manager::CHOOSE,
			'label_block'	 => false,
			'options'		 => array(
				'icon'	 => array(
					'title'	 => __( 'Icon', 'envo-extra' ),
					'icon'	 => 'eicon-star-o',
				),
				'image'	 => array(
					'title'	 => __( 'Image', 'envo-extra' ),
					'icon'	 => 'eicon-image',
				),
			),
			'default'		 => 'icon',
			'toggle'		 => false,
		)
		);

		$this->add_control(
		'icon', array(
			'show_label'	 => false,
			'type'			 => Controls_Manager::ICONS,
			'label_block'	 => true,
			'default'		 => array(
				'value'		 => 'fas fa-fingerprint',
				'library'	 => 'fa-solid',
			),
			'condition'		 => array(
				'media_type' => 'icon',
			),
		)
		);

		$this->add_control(
		'image', array(
			'label'		 => __( 'Image', 'envo-extra' ),
			'type'		 => Controls_Manager::MEDIA,
			'default'	 => array(
				'url' => Utils::get_placeholder_image_src(),
			),
			'condition'	 => array(
				'media_type' => 'image',
			),
			'dynamic'	 => array(
				'active' => true,
			),
		)
		);

		$this->add_group_control(
		Group_Control_Image_Size::get_type(), array(
			'name'		 => 'media_thumbnail',
			'default'	 => 'full',
			'separator'	 => 'none',
			'exclude'	 => array(
				'custom',
			),
			'condition'	 => array(
				'media_type' => 'image',
			),
		)
		);

		$this->add_control(
		'title', array(
			'label'			 => __( 'Title', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'label_block'	 => true,
			'default'		 => __( 'Icon Box', 'envo-extra' ),
			'placeholder'	 => __( 'Type Icon Box Title', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'description', array(
			'label'			 => esc_html__( 'Description', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXTAREA,
			'rows'			 => 5,
			'placeholder'	 => esc_html__( 'Type your description here', 'envo-extra' ),
			'label_block'	 => true,
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'badge_text', array(
			'label'			 => __( 'Badge Text', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'label_block'	 => true,
			'placeholder'	 => __( 'Type Icon Badge Text', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'link', array(
			'label'			 => __( 'Box Link', 'envo-extra' ),
			'separator'		 => 'before',
			'type'			 => Controls_Manager::URL,
			'placeholder'	 => 'https://example.com',
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'title_tag', array(
			'label'		 => __( 'Title HTML Tag', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'separator'	 => 'before',
			'options'	 => array(
				'h1' => array(
					'title'	 => __( 'H1', 'envo-extra' ),
					'icon'	 => 'eicon-editor-h1',
				),
				'h2' => array(
					'title'	 => __( 'H2', 'envo-extra' ),
					'icon'	 => 'eicon-editor-h2',
				),
				'h3' => array(
					'title'	 => __( 'H3', 'envo-extra' ),
					'icon'	 => 'eicon-editor-h3',
				),
				'h4' => array(
					'title'	 => __( 'H4', 'envo-extra' ),
					'icon'	 => 'eicon-editor-h4',
				),
				'h5' => array(
					'title'	 => __( 'H5', 'envo-extra' ),
					'icon'	 => 'eicon-editor-h5',
				),
				'h6' => array(
					'title'	 => __( 'H6', 'envo-extra' ),
					'icon'	 => 'eicon-editor-h6',
				),
			),
			'default'	 => 'h3',
			'toggle'	 => false,
		)
		);

		$this->add_responsive_control(
		'layout', array(
			'label'		 => __( 'Layout', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'options'	 => array(
				'inline-flex'	 => array(
					'title'	 => __( 'Inline', 'envo-extra' ),
					'icon'	 => 'eicon-ellipsis-h',
				),
				'inline-block'	 => array(
					'title'	 => __( 'Block', 'envo-extra' ),
					'icon'	 => 'eicon-menu-bar',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-wrapper-inner' => 'display: {{VALUE}};',
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
			'prefix_class'	 => 'envo-extra-content-align%s',
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-box-icon-wrapper' => 'text-align: {{VALUE}};',
			),
		)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
		'section_style_icon', array(
			'label'	 => __( 'Icon', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_responsive_control(
		'icon_size', array(
			'label'		 => __( 'Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 5,
					'max'	 => 300,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 40,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item'														 => 'font-size: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-box-icon-item > svg,{{WRAPPER}} .envo-extra-box-icon-item > img'	 => 'width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'icon_bg_size', array(
			'label'		 => __( 'Background Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 5,
					'max'	 => 500,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 50,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
			),
			'condition'	 => array(
				'media_type' => 'icon',
			),
		)
		);

		$this->add_responsive_control(
		'image_height', array(
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
			'condition'		 => array(
				'media_type' => 'image',
			),
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item > img' => 'height: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'object-fit', array(
			'label'		 => __( 'Object Fit', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'options'	 => array(
				''			 => __( 'Default', 'envo-extra' ),
				'fill'		 => __( 'Fill', 'envo-extra' ),
				'cover'		 => __( 'Cover', 'envo-extra' ),
				'contain'	 => __( 'Contain', 'envo-extra' ),
			),
			'default'	 => '',
			'condition'	 => array(
				'media_type'			 => 'image',
				'image_height[size]!'	 => '',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item > img' => 'object-fit: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'icon_vertical_align', array(
			'label'		 => __( 'Vertical Align', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'options'	 => array(
				'flex-start' => array(
					'title'	 => __( 'Start', 'envo-extra' ),
					'icon'	 => 'eicon-v-align-top',
				),
				'center'	 => array(
					'title'	 => __( 'Center', 'envo-extra' ),
					'icon'	 => 'eicon-v-align-middle',
				),
				'flex-end'	 => array(
					'title'	 => __( 'End', 'envo-extra' ),
					'icon'	 => 'eicon-v-align-bottom',
				),
			),
			'condition'	 => array(
				'layout' => 'inline-flex',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-wrapper-inner' => 'align-items: {{VALUE}};',
			),
		)
		);

		$this->add_responsive_control(
		'icon_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'icon_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-item',
		)
		);

		$this->add_responsive_control(
		'icon_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'icon_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'condition'	 => array(
				'media_type' => 'image',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'icon_shadow',
			'exclude'	 => array(
				'box_shadow_position',
			),
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-item',
		)
		);

		$this->start_controls_tabs( '_tabs_icon' );

		$this->start_controls_tab(
		'_tab_icon_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'icon_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item'			 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-box-icon-item > svg'	 => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'icon_bg_color',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-item',
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'_tab_icon_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'icon_hover_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-box-icon-item'		 => 'color: {{VALUE}};',
				'{{WRAPPER}}:hover .envo-extra-box-icon-item > svg'	 => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'icon_hover_bg_color',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}}:hover .envo-extra-box-icon-item',
		)
		);

		$this->add_control(
		'icon_hover_border_color', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-box-icon-item' => 'border-color: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_border_border!' => '',
			),
		)
		);

		$this->add_control(
		'icon_hover_animation', array(
			'label'				 => __( 'Animation', 'envo-extra' ),
			'type'				 => Controls_Manager::SELECT,
			'default'			 => '',
			'options'			 => array(
				''			 => __( 'None', 'envo-extra' ),
				'fadeIn'	 => __( 'FadeIn', 'envo-extra' ),
				'bounce'	 => __( 'Bounce', 'envo-extra' ),
				'bounceIn'	 => __( 'BounceIn', 'envo-extra' ),
				'bounceOut'	 => __( 'BounceOut', 'envo-extra' ),
				'flash'		 => __( 'Flash', 'envo-extra' ),
				'pulse'		 => __( 'Pulse', 'envo-extra' ),
				'rubberBand' => __( 'Rubber', 'envo-extra' ),
				'shake'		 => __( 'Shake', 'envo-extra' ),
				'swing'		 => __( 'Swing', 'envo-extra' ),
				'tada'		 => __( 'Tada', 'envo-extra' ),
				'wobble'	 => __( 'Wobble', 'envo-extra' ),
				'flipInX'	 => __( 'Flip X', 'envo-extra' ),
				'flipInY'	 => __( 'Flip Y', 'envo-extra' ),
			),
			'frontend_available' => true,
			'render_type'		 => 'template',
		)
		);

		$this->add_control(
		'icon_hover_transition', array(
			'label'		 => __( 'Transition Duration', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'range'		 => array(
				'px' => array(
					'max'	 => 3,
					'step'	 => 0.1,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-item' => 'animation-duration: {{SIZE}}s; transition-duration: {{SIZE}}s;',
			),
		)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style_title', array(
			'label'		 => __( 'Title', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'title!' => '',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'title',
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-title',
		)
		);

		$this->add_group_control(
		Group_Control_Text_Shadow::get_type(), array(
			'name'		 => 'title',
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-title',
		)
		);

		$this->start_controls_tabs( '_tabs_title' );

		$this->start_controls_tab(
		'_tab_title_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'title_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-title' => 'color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'_tab_title_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'title_hover_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-box-icon-title' => 'color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
		'title_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'separator'	 => 'before',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		//Description
		$this->start_controls_section(
		'section_style_description', array(
			'label'		 => __( 'Description', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'description!' => '',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'description',
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-description',
		)
		);

		$this->add_group_control(
		Group_Control_Text_Shadow::get_type(), array(
			'name'		 => 'description',
			'selector'	 => '{{WRAPPER}} .envo-extra-box-icon-description',
		)
		);

		$this->start_controls_tabs( '_tabs_description' );

		$this->start_controls_tab(
		'_tab_description_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'description_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-description' => 'color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'_tab_description_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'description_hover_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-box-icon-description' => 'color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
		'description_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'separator'	 => 'before',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-box-icon-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style_badge', array(
			'label'		 => __( 'Badge', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'badge_text!' => '',
			),
		)
		);

		$this->add_control(
		'badge_position', array(
			'label'		 => __( 'Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'options'	 => array(
				'top-left'		 => __( 'Top Left', 'envo-extra' ),
				'top-center'	 => __( 'Top Center', 'envo-extra' ),
				'top-right'		 => __( 'Top Right', 'envo-extra' ),
				'middle-left'	 => __( 'Middle Left', 'envo-extra' ),
				'middle-center'	 => __( 'Middle Center', 'envo-extra' ),
				'middle-right'	 => __( 'Middle Right', 'envo-extra' ),
				'bottom-left'	 => __( 'Bottom Left', 'envo-extra' ),
				'bottom-center'	 => __( 'Bottom Center', 'envo-extra' ),
				'bottom-right'	 => __( 'Bottom Right', 'envo-extra' ),
			),
			'default'	 => 'top-right',
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'badge_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-badge',
		)
		);

		$this->add_responsive_control(
		'badge_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'badge_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-badge' => 'color: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'badge_bg_color', array(
			'label'		 => __( 'Background Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-badge' => 'background-color: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'badge_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-badge',
		)
		);

		$this->add_responsive_control(
		'badge_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'badge_box_shadow',
			'selector'	 => '{{WRAPPER}} .envo-extra-badge',
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

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'envo-extra-box-icon-title' );
		$this->add_inline_editing_attributes( 'description', 'basic' );
		$this->add_render_attribute( 'description', 'class', 'envo-extra-box-icon-description' );

		$this->add_inline_editing_attributes( 'badge_text', 'none' );
		$this->add_render_attribute( 'badge_text', 'class', 'envo-extra-badge envo-extra-badge-' . $settings[ 'badge_position' ] );


		$html_tag	 = ( $settings[ 'link' ] ) ? 'a' : 'div';
		$attr		 = $settings[ 'link' ][ 'url' ] ? ' href="' . esc_url($settings[ 'link' ][ 'url' ]) . '"' : '';
		$attr .= $settings[ 'link' ][ 'is_external' ] ? ' target="_blank"' : '';
		$attr .= $settings[ 'link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
		?>

		<<?php echo esc_attr( $html_tag ); ?> <?php echo wp_kses_data( $attr ); ?> class="envo-extra-box-icon-wrapper">
		<div class="envo-extra-box-icon-wrapper-inner">
			<?php if ( $settings[ 'badge_text' ] ) : ?>
				<span <?php $this->print_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings[ 'badge_text' ] ); ?></span>
			<?php endif; ?>

			<?php if ( 'icon' === $settings[ 'media_type' ] || 'image' === $settings[ 'media_type' ] ) : ?>
				<span class="envo-extra-box-icon-item">
					<?php
					if ( 'icon' === $settings[ 'media_type' ] && $settings[ 'icon' ] ) {
						Icons_Manager::render_icon( $settings[ 'icon' ], array( 'aria-hidden' => 'true' ) );
					}
					if ( 'image' === $settings[ 'media_type' ] ) {
						echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
					}
					?>
				</span>
			<?php endif; ?>

			<span class="envo-extra-box-icon-content">
				<?php
				if ( $settings[ 'title' ] ) :
					printf( '<%1$s %2$s>%3$s</%1$s>', tag_escape( $settings[ 'title_tag' ] ), $this->get_render_attribute_string( 'title' ), $settings[ 'title' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endif;
				?>

				<?php if ( $settings[ 'description' ] ) : ?>
					<p <?php $this->print_render_attribute_string( 'description' ); ?>><?php echo wp_kses_post( $settings[ 'description' ] ); ?></p>
				<?php endif; ?>
			</span>

		</div>
		</<?php echo esc_attr( $html_tag ); ?>>
		<?php
	}

}
