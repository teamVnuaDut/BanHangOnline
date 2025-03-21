<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
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
class Team extends Widget_Base {

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
		return 'envo-extra-team';
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
		return __( 'Team', 'envo-extra' );
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
		return 'eicon-person';
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
		return array( 'team', 'grid' );
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

		return array( 'envo-extra-team' );
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
			'label'				 => esc_html__( 'Layout', 'envo-extra' ),
			'type'				 => Controls_Manager::SELECT,
			'default'			 => '1',
			'options'			 => array(
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
				'11' => esc_html__( 'Style 11', 'envo-extra' ),
				'12' => esc_html__( 'Style 12', 'envo-extra' ),
				'13' => esc_html__( 'Style 13', 'envo-extra' ),
				'14' => esc_html__( 'Style 14', 'envo-extra' ),
				'15' => esc_html__( 'Style 15', 'envo-extra' ),
			),
			'frontend_available' => true,
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
		)
		);

		$this->add_control(
		'title', array(
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
		'title_link', array(
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
			'default'		 => __( 'It is a long established fact that a reader will be distracted by the content.', 'envo-extra' ),
			'placeholder'	 => __( 'Type your description here', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'align', array(
			'label'		 => __( 'Alignment', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'options'	 => array(
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
			'separator'	 => 'before',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper' => 'text-align: {{VALUE}};',
			),
			'condition'	 => array(
				'layout!' => array( '8', '9' ),
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_social', array(
			'label' => __( 'Social', 'envo-extra' ),
		)
		);

		$this->add_control(
		'social_enable', array(
			'label'			 => __( 'Enable', 'envo-extra' ),
			'type'			 => Controls_Manager::SWITCHER,
			'label_on'		 => __( 'Show', 'envo-extra' ),
			'label_off'		 => __( 'Hide', 'envo-extra' ),
			'return_value'	 => 'yes',
			'default'		 => 'yes',
		)
		);

		$repeater = new Repeater();

		$repeater->add_control(
		'social_icon', array(
			'label'		 => __( 'Icon', 'envo-extra' ),
			'type'		 => Controls_Manager::ICONS,
			'default'	 => array(
				'value'		 => 'fab fa-wordpress',
				'library'	 => 'fa-brands',
			),
		)
		);

		$repeater->add_control(
		'icon_link', array(
			'label'			 => __( 'Link', 'envo-extra' ),
			'type'			 => Controls_Manager::URL,
			'default'		 => array(
				'is_external' => 'true',
			),
			'dynamic'		 => array(
				'active' => true,
			),
			'placeholder'	 => __( 'https://your-link.com', 'envo-extra' ),
		)
		);

		$repeater->add_control(
		'icon_inline_style', array(
			'label'			 => __( 'Inline Style', 'envo-extra' ),
			'type'			 => Controls_Manager::SWITCHER,
			'label_on'		 => __( 'Show', 'envo-extra' ),
			'label_off'		 => __( 'Hide', 'envo-extra' ),
			'return_value'	 => 'yes',
		)
		);

		$repeater->start_controls_tabs( 'icon_inline_style_tab' );

		$repeater->start_controls_tab(
		'icon_inline_normal', array(
			'label'		 => __( 'Normal', 'envo-extra' ),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->add_control(
		'icon_inline_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon > i'	 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon > svg'	 => 'fill: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->add_control(
		'icon_inline_bg', array(
			'label'		 => __( 'Background', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon' => 'background: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->add_control(
		'icon_inline_border', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon' => 'border-color: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
		'icon_inline_hover', array(
			'label'		 => __( 'Hover', 'envo-extra' ),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->add_control(
		'icon_inline_hover_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:hover > i, {{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:focus > i'	 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:hover > svg, {{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:focus > svg' => 'fill: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->add_control(
		'icon_inline_hover_bg', array(
			'label'		 => __( 'Background', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:hover, {{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:focus' => 'background: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->add_control(
		'icon_inline_border_hcolor', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:hover, {{WRAPPER}} .envo-extra-team-social-list {{CURRENT_ITEM}} .envo-extra-team-social-icon:focus' => 'border-color: {{VALUE}};',
			),
			'condition'	 => array(
				'icon_inline_style' => 'yes',
			),
		)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
		'social_icon_list', array(
			'type'			 => Controls_Manager::REPEATER,
			'fields'		 => $repeater->get_controls(),
			'default'		 => array(
				array(
					'social_icon' => array(
						'value'		 => 'fab fa-facebook-f',
						'library'	 => 'fa-brands',
					),
				),
				array(
					'social_icon' => array(
						'value'		 => 'fab fa-twitter',
						'library'	 => 'fa-brands',
					),
				),
				array(
					'social_icon' => array(
						'value'		 => 'fab fa-instagram',
						'library'	 => 'fa-brands',
					),
				),
			),
			'title_field'	 => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
			'condition'		 => array(
				'social_enable' => 'yes',
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
			'label'		 => __( 'Width', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'default'	 => array(
				'unit' => 'px',
			),
			'size_units' => array( 'px', '%', 'vw' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 1000,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image > img' => 'width: {{SIZE}}{{UNIT}};',
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
					'min'	 => 0,
					'max'	 => 1000,
				),
			),
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image > img' => 'height: {{SIZE}}{{UNIT}};',
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
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image > img' => 'object-fit: {{VALUE}};',
			),
		)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
		'normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'shape_color', array(
			'label'		 => __( 'Shape Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-13::after' => 'background-color: {{VALUE}};',
			),
			'condition'	 => array(
				'layout' => array( '13' ),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Css_Filter::get_type(), array(
			'name'		 => 'css_filters',
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image img',
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'image_overlay', array(
			'label'		 => __( 'Overlay Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-5 .envo-extra-team-image::before, {{WRAPPER}} .envo-extra-team-layout-12 .envo-extra-team-image::after' => 'background-color: {{VALUE}};',
			),
			'condition'	 => array(
				'layout' => array( '5', '12' ),
			),
		)
		);

		$this->add_control(
		'shape_hcolor', array(
			'label'		 => __( 'Shape Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-13:hover::after' => 'background-color: {{VALUE}};',
			),
			'condition'	 => array(
				'layout' => array( '13' ),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Css_Filter::get_type(), array(
			'name'		 => 'css_filters_hover',
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper:hover .envo-extra-team-image img',
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
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image img' => 'transition-duration: {{SIZE}}s',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'image_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image > img',
			'separator'	 => 'before',
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'image_box_shadow',
			'exclude'	 => array(
				'box_shadow_position',
			),
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image > img',
		)
		);

		$this->add_responsive_control(
		'image_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'image_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout' => '9',
			),
		)
		);

		$this->add_responsive_control(
		'image_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_responsive_control(
		'content_height', array(
			'label'		 => esc_html__( 'Height', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 1000,
					'step'	 => 5,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-6 .envo-extra-team-content' => 'height: {{SIZE}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout' => '6',
			),
		)
		);

		$this->add_control(
		'content_backdrop_blur', array(
			'label'		 => esc_html__( 'Backdrop Blur', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 10,
					'step'	 => 1,
				),
			),
			'default'	 => array(
				'size' => 3,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-6 .envo-extra-team-content:before' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
			),
			'condition'	 => array(
				'layout' => '6',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'content_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-content,{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-inner-content',
			'condition'	 => array(
				'layout!' => array( '15' ),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'content_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-content',
		)
		);

		$this->add_responsive_control(
		'content_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'separator_color', array(
			'label'		 => __( 'Separator Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-description::before' => 'background-color: {{VALUE}}',
			),
			'condition'	 => array(
				'layout' => '9',
			),
		)
		);

		$this->add_responsive_control(
		'content_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-content,{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'heading_title', array(
			'label'		 => __( 'Title', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
			'condition'	 => array(
				'title!' => '',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'title_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-title',
			'condition'	 => array(
				'title!' => '',
			),
		)
		);

		$this->add_responsive_control(
		'title_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'title!' => '',
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
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-designation' => 'color: {{VALUE}}',
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
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-designation',
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
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-description' => 'color: {{VALUE}}',
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
			'selector'	 => '{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-description',
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
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'description!' => '',
			),
		)
		);

		$this->end_controls_section();

		// Social Icon
		$this->start_controls_section(
		'section_social_icon_style', array(
			'label'		 => __( 'Social', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'social_enable' => 'yes',
			),
		)
		);

		$this->add_responsive_control(
		'icon_size', array(
			'label'		 => __( 'Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'devices' => [ 'desktop', 'tablet', 'mobile' ],
			'default' => [
				'size' => 14,
				'unit' => 'px',
			],
			'tablet_default' => [
				'size' => 14,
				'unit' => 'px',
			],
			'mobile_default' => [
				'size' => 14,
				'unit' => 'px',
			],
			'range'		 => array(
				'px' => array(
					'min'	 => 1,
					'max'	 => 50,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon > i'		 => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon > svg'	 => 'width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'icon_bg_size', array(
			'label'		 => __( 'Background Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'devices' => [ 'desktop', 'tablet', 'mobile' ],
			'default' => [
				'size' => 42,
				'unit' => 'px',
			],
			'tablet_default' => [
				'size' => 42,
				'unit' => 'px',
			],
			'mobile_default' => [
				'size' => 42,
				'unit' => 'px',
			],
			'range'		 => array(
				'px' => array(
					'min'	 => 1,
					'max'	 => 100,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'icon_space', array(
			'label'		 => __( 'Space Between', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'devices' => [ 'desktop', 'tablet', 'mobile' ],
			'default' => [
				'size' => 15,
				'unit' => 'px',
			],
			'tablet_default' => [
				'size' => 15,
				'unit' => 'px',
			],
			'mobile_default' => [
				'size' => 15,
				'unit' => 'px',
			],
			'range'		 => array(
				'px' => array(
					'min'	 => - 100,
					'max'	 => 100,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-wrapper .envo-extra-team-social-list > li'																																									 => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-social-list > li,
					 {{WRAPPER}} .envo-extra-team-layout-13 .envo-extra-team-social-list > li,
					 {{WRAPPER}} .envo-extra-team-layout-15 .envo-extra-team-social-list > li'	 => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->start_controls_tabs( 'social_icon_style' );

		$this->start_controls_tab(
		'icon_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'icon_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon > i'		 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon > svg'	 => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'icon_bg', array(
			'label'		 => __( 'Background Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon' => 'background-color: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'icon_wrapper_bg', array(
			'label'		 => __( 'Wrapper Background', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-15 .envo-extra-team-social-list' => 'background-color: {{VALUE}};',
			),
			'condition'	 => array(
				'layout' => array( '15' ),
			),
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'icon_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'icon_hover_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:hover > i, {{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:focus > i'		 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:hover > svg, {{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:focus  svg'	 => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'icon_hbg', array(
			'label'		 => __( 'Background Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:hover,{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:focus' => 'background-color: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'icon_border_hover_color', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:hover, {{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon:focus' => 'border-color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'icon_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon',
			'separator'	 => 'before',
		)
		);

		$this->add_responsive_control(
		'icon_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-social-list .envo-extra-team-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout!' => array( '13' ),
			),
		)
		);

		$this->add_control(
		'heading_social_wrapper', array(
			'label'		 => __( 'Wrapper', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
			'condition'	 => array(
				'layout' => array( '8', '9', '15' ),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'icon_wrapper_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-team-layout-8 .envo-extra-team-social-list,{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-social-list,{{WRAPPER}} .envo-extra-team-layout-15 .envo-extra-team-social-list',
			'condition'	 => array(
				'layout' => array( '8', '9', '15' ),
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'icon_wrapper_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-team-layout-8 .envo-extra-team-social-list,{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-social-list,{{WRAPPER}} .envo-extra-team-layout-15 .envo-extra-team-social-list',
			'condition'	 => array(
				'layout' => array( '8', '9', '15' ),
			),
		)
		);

		$this->add_responsive_control(
		'icon_wrapper_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-8 .envo-extra-team-social-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout' => array( '8' ),
			),
		)
		);

		$this->add_responsive_control(
		'icon_wrapper_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-8 .envo-extra-team-social-list'	 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-team-layout-15 .envo-extra-team-social-list'	 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-team-layout-9 .envo-extra-team-social-list'	 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout' => array( '8', '15', '9' ),
			),
		)
		);

		$this->add_responsive_control(
		'icon_wrapper_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-team-layout-8 .envo-extra-team-social-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'layout' => array( '8' ),
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


		$title_tag	 = ( $settings[ 'title_link' ][ 'url' ] ) ? 'a' : 'h2';
		$title_attr	 = $settings[ 'title_link' ][ 'is_external' ] ? ' target="_blank"' : '';
		$title_attr .= $settings[ 'title_link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
		$title_attr .= $settings[ 'title_link' ][ 'url' ] ? ' href="' . esc_url($settings[ 'title_link' ][ 'url' ]) . '"' : '';
		?>

		<div class="envo-extra-team-wrapper envo-extra-team-layout-<?php echo esc_attr( $settings[ 'layout' ] ); ?>">
			<?php if ( $settings[ 'designation' ] && '2' === $settings[ 'layout' ] ) : ?>
				<h4 class="envo-extra-team-designation"><?php echo esc_attr( $settings[ 'designation' ] ); ?></h4>
			<?php endif; ?>

			<?php if ( $settings[ 'image' ][ 'id' ] || $settings[ 'image' ][ 'url' ] ) : ?>
				<div class="envo-extra-team-image">
					<?php echo (!empty( $settings[ 'image' ][ 'id' ] ) ) ? wp_get_attachment_image( $settings[ 'image' ][ 'id' ], $settings[ 'thumbnail_size' ] ) : '<img src="' . esc_url( $settings[ 'image' ][ 'url' ] ) . '">'; ?>
					<?php if ( '8' === $settings[ 'layout' ] || '9' === $settings[ 'layout' ] ) : ?>
						<div class="envo-extra-team-inner-content">
							<?php if ( $settings[ 'title' ] ) : ?>
								<<?php echo esc_attr( $title_tag ); ?><?php echo wp_kses_data( $title_attr ); ?>
								class="envo-extra-team-title"><?php echo esc_attr( $settings[ 'title' ] ); ?></<?php echo esc_attr( $title_tag ); ?>>
							<?php endif; ?>
							<?php if ( $settings[ 'designation' ] ) : ?>
								<h4 class="envo-extra-team-designation"><?php echo esc_attr( $settings[ 'designation' ] ); ?></h4>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $settings[ 'social_enable' ] && $settings[ 'social_icon_list' ] && ( '2' === $settings[ 'layout' ] || '3' === $settings[ 'layout' ] || '5' === $settings[ 'layout' ] || '8' === $settings[ 'layout' ] || '12' === $settings[ 'layout' ] || '13' === $settings[ 'layout' ] || '15' === $settings[ 'layout' ] ) ) : ?>
						<ul class="envo-extra-team-social-list">
							<?php
							foreach ( $settings[ 'social_icon_list' ] as $i => $icon ) {
								$html_tag	 = $icon[ 'icon_link' ][ 'url' ] ? 'a' : 'span';
								$attr		 = $icon[ 'icon_link' ][ 'is_external' ] ? ' target="_blank"' : '';
								$attr .= $icon[ 'icon_link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
								$attr .= $icon[ 'icon_link' ][ 'url' ] ? ' href="' . esc_url($icon[ 'icon_link' ][ 'url' ]) . '"' : '';
								?>
								<li class="elementor-repeater-item-<?php echo esc_attr( $icon[ '_id' ] ); ?>">
									<<?php echo esc_attr( $html_tag ); ?> <?php echo wp_kses_data( $attr ); ?> class="envo-extra-team-social-icon">
									<?php Icons_Manager::render_icon( $icon[ 'social_icon' ], array( 'aria-hidden' => 'true' ) ); ?>
									</<?php echo esc_attr( $html_tag ); ?>>
								</li>
							<?php } ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="envo-extra-team-content">
				<?php if ( '8' !== $settings[ 'layout' ] && '9' !== $settings[ 'layout' ] ) : ?>
					<?php if ( $settings[ 'title' ] ) : ?>
						<<?php echo esc_attr( $title_tag ); ?><?php echo wp_kses_data( $title_attr ); ?>
						class="envo-extra-team-title"><?php echo esc_attr( $settings[ 'title' ] ); ?></<?php echo esc_attr( $title_tag ); ?>>
					<?php endif; ?>
					<?php if ( $settings[ 'designation' ] && '2' !== $settings[ 'layout' ] ) : ?>
						<h4 class="envo-extra-team-designation"><?php echo esc_attr( $settings[ 'designation' ] ); ?></h4>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( $settings[ 'description' ] ) : ?>
					<p class="envo-extra-team-description"><?php echo esc_attr( $settings[ 'description' ] ); ?></p>
				<?php endif; ?>

				<?php if ( $settings[ 'social_enable' ] && $settings[ 'social_icon_list' ] && ( '2' !== $settings[ 'layout' ] && '3' !== $settings[ 'layout' ] && '5' !== $settings[ 'layout' ] && '8' !== $settings[ 'layout' ] && '12' !== $settings[ 'layout' ] && '13' !== $settings[ 'layout' ] && '15' !== $settings[ 'layout' ] ) ) : ?>
					<ul class="envo-extra-team-social-list">
						<?php
						foreach ( $settings[ 'social_icon_list' ] as $i => $icon ) {
							$html_tag	 = $icon[ 'icon_link' ][ 'url' ] ? 'a' : 'span';
							$attr		 = $icon[ 'icon_link' ][ 'is_external' ] ? ' target="_blank"' : '';
							$attr .= $icon[ 'icon_link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
							$attr .= $icon[ 'icon_link' ][ 'url' ] ? ' href="' . esc_url($icon[ 'icon_link' ][ 'url' ]) . '"' : '';
							?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $icon[ '_id' ] ); ?>">
								<<?php echo esc_attr( $html_tag ); ?> <?php echo wp_kses_data( $attr ); ?> class="envo-extra-team-social-icon">
								<?php Icons_Manager::render_icon( $icon[ 'social_icon' ], array( 'aria-hidden' => 'true' ) ); ?>
								</<?php echo esc_attr( $html_tag ); ?>>
							</li>
						<?php } ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

}
