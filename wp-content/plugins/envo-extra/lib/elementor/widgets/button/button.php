<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
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
class Button extends Widget_Base {

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
		return 'envo-extra-button';
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
		return __( 'Button', 'envo-extra' );
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
		return 'eicon-button';
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
		return array( 'button', 'link', 'cta' );
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

		return array( 'envo-extra-button' );
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
		'text', array(
			'label'			 => __( 'Text', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'dynamic'		 => array(
				'active' => true,
			),
			'default'		 => __( 'Click here', 'envo-extra' ),
			'placeholder'	 => __( 'Click here', 'envo-extra' ),
		)
		);

		$this->add_control(
		'link', array(
			'label'			 => __( 'Link', 'envo-extra' ),
			'type'			 => Controls_Manager::URL,
			'dynamic'		 => array(
				'active' => true,
			),
			'placeholder'	 => __( 'https://your-link.com', 'envo-extra' ),
			'default'		 => array(
				'url' => '#',
			),
		)
		);

		$this->add_responsive_control(
		'align', array(
			'label'			 => __( 'Alignment', 'envo-extra' ),
			'type'			 => Controls_Manager::CHOOSE,
			'options'		 => array(
				'left'		 => array(
					'title'	 => __( 'Left', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-left',
				),
				'center'	 => array(
					'title'	 => __( 'Center', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-center',
				),
				'right'		 => array(
					'title'	 => __( 'Right', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-right',
				),
				'justify'	 => array(
					'title'	 => __( 'Justified', 'envo-extra' ),
					'icon'	 => 'eicon-text-align-justify',
				),
			),
			'prefix_class'	 => 'elementor%s-align-',
			'default'		 => '',
		)
		);

		$this->add_control(
		'icon', array(
			'label'			 => __( 'Icon', 'envo-extra' ),
			'type'			 => Controls_Manager::ICONS,
			'skin'			 => 'inline',
			'label_block'	 => false,
		)
		);

		$this->add_control(
		'icon_align', array(
			'label'		 => __( 'Icon Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'left',
			'options'	 => array(
				'left'	 => __( 'Before', 'envo-extra' ),
				'right'	 => __( 'After', 'envo-extra' ),
			),
			'condition'	 => array(
				'icon[value]!' => '',
			),
		)
		);

		$this->add_control(
		'icon_indent', array(
			'label'		 => __( 'Icon Spacing', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'range'		 => array(
				'px' => array(
					'max' => 50,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-align-icon-right .envo-extra-elementor-button-media'	 => 'margin-left: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-align-icon-left .envo-extra-elementor-button-media'	 => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'condition'	 => array(
				'icon[value]!' => '',
			),
		)
		);

		$this->add_control(
		'button_css_id', array(
			'label'			 => __( 'Button ID', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'dynamic'		 => array(
				'active' => true,
			),
			'default'		 => '',
			'title'			 => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'envo-extra' ),
			'description'	 => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'envo-extra' ),
			'separator'		 => 'before',
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style', array(
			'label'	 => __( 'General', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'typography',
			'global'	 => array(
				'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			),
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button',
		)
		);

		$this->add_group_control(
		Group_Control_Text_Shadow::get_type(), array(
			'name'		 => 'text_shadow',
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button',
		)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
		'tab_button_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'button_text_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button'		 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-elementor-button svg'	 => 'color: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button,{{WRAPPER}} .envo-extra-elementor-button-hover-style-skewFill:before,
								{{WRAPPER}} .envo-extra-elementor-button-hover-style-flipSlide::before',
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'button_box_shadow',
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button',
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'tab_button_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'hover_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button:hover, {{WRAPPER}} .envo-extra-elementor-button:focus'		 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-elementor-button:hover svg, {{WRAPPER}} .envo-extra-elementor-button:focus svg' => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'button_background_hover',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button-animation-none:hover,{{WRAPPER}} .envo-extra-button-2d-animation:hover,
								{{WRAPPER}} .envo-extra-button-bg-animation::before,{{WRAPPER}} .envo-extra-elementor-button-hover-style-bubbleFromDown::before,
								{{WRAPPER}} .envo-extra-elementor-button-hover-style-bubbleFromDown::after,{{WRAPPER}} .envo-extra-elementor-button-hover-style-bubbleFromCenter::before,
								{{WRAPPER}} .envo-extra-elementor-button-hover-style-bubbleFromCenter::after,{{WRAPPER}} .envo-extra-elementor-button-hover-style-flipSlide,
								{{WRAPPER}} [class*=envo-extra-elementor-button-hover-style-underline]:hover,{{WRAPPER}} .envo-extra-elementor-button-hover-style-skewFill,
								
								{{WRAPPER}} .envo-extra-elementor-button-animation-none:focus,{{WRAPPER}} .envo-extra-button-2d-animation:focus,
								{{WRAPPER}} [class*=envo-extra-elementor-button-focus-style-underline]:focus',
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'button_box_hshadow',
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button:hover',
		)
		);

		$this->add_control(
		'button_hover_border_color', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'condition'	 => array(
				'border_border!'			 => '',
				'hover_unique_animation!'	 => array(
					'underlineFromLeft',
					'underlineFromRight',
					'underlineFromCenter',
					'underlineReveal',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button:hover, {{WRAPPER}} .envo-extra-elementor-button:focus' => 'border-color: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'button_hover_underline', array(
			'label'		 => __( 'Line Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'condition'	 => array(
				'hover_animation'		 => 'unique',
				'hover_unique_animation' => array(
					'underlineFromLeft',
					'underlineFromRight',
					'underlineFromCenter',
					'underlineReveal',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} [class*=envo-extra-elementor-button-hover-style-underline]:before' => 'background-color: {{VALUE}};',
			),
		)
		);

		$this->add_control(
		'hover_animation', array(
			'label'		 => __( 'Hover Animation', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'none',
			'options'	 => array(
				'none'					 => __( 'None', 'envo-extra' ),
				'2d-transition'			 => __( '2D', 'envo-extra' ),
				'background-transition'	 => __( 'Background', 'envo-extra' ),
				'unique'				 => __( 'Unique', 'envo-extra' ),
			),
		)
		);

		$this->add_control(
		'hover_2d_css_animation', array(
			'label'		 => __( 'Animation Type', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'hvr-grow',
			'options'	 => array(
				'hvr-grow'					 => __( 'Grow', 'envo-extra' ),
				'hvr-shrink'				 => __( 'Shrink', 'envo-extra' ),
				'hvr-pulse'					 => __( 'Pulse', 'envo-extra' ),
				'hvr-pulse-grow'			 => __( 'Pulse Grow', 'envo-extra' ),
				'hvr-pulse-shrink'			 => __( 'Pulse Shrink', 'envo-extra' ),
				'hvr-push'					 => __( 'Push', 'envo-extra' ),
				'hvr-pop'					 => __( 'Pop', 'envo-extra' ),
				'hvr-bounce-in'				 => __( 'Bounce In', 'envo-extra' ),
				'hvr-bounce-out'			 => __( 'Bounce Out', 'envo-extra' ),
				'hvr-rotate'				 => __( 'Rotate', 'envo-extra' ),
				'hvr-grow-rotate'			 => __( 'Grow Rotate', 'envo-extra' ),
				'hvr-float'					 => __( 'Float', 'envo-extra' ),
				'hvr-sink'					 => __( 'Sink', 'envo-extra' ),
				'hvr-bob'					 => __( 'Bob', 'envo-extra' ),
				'hvr-hang'					 => __( 'Hang', 'envo-extra' ),
				'hvr-wobble-vertical'		 => __( 'Wobble Vertical', 'envo-extra' ),
				'hvr-wobble-horizontal'		 => __( 'Wobble Horizontal', 'envo-extra' ),
				'hvr-wobble-to-bottom-right' => __( 'Wobble To Bottom Right', 'envo-extra' ),
				'hvr-wobble-to-top-right'	 => __( 'Wobble To Top Right', 'envo-extra' ),
				'hvr-buzz'					 => __( 'Buzz', 'envo-extra' ),
				'hvr-buzz-out'				 => __( 'Buzz Out', 'envo-extra' ),
			),
			'condition'	 => array(
				'hover_animation' => '2d-transition',
			),
		)
		);

		$this->add_control(
		'hover_background_css_animation', array(
			'label'		 => __( 'Animation', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'hvr-sweep-to-right',
			'options'	 => array(
				'hvr-sweep-to-right'		 => __( 'Sweep To Right', 'envo-extra' ),
				'hvr-sweep-to-left'			 => __( 'Sweep To Left', 'envo-extra' ),
				'hvr-sweep-to-bottom'		 => __( 'Sweep To Bottom', 'envo-extra' ),
				'hvr-sweep-to-top'			 => __( 'Sweep To Top', 'envo-extra' ),
				'hvr-bounce-to-right'		 => __( 'Bounce To Right', 'envo-extra' ),
				'hvr-bounce-to-left'		 => __( 'Bounce To Left', 'envo-extra' ),
				'hvr-bounce-to-bottom'		 => __( 'Bounce To Bottom', 'envo-extra' ),
				'hvr-bounce-to-top'			 => __( 'Bounce To Top', 'envo-extra' ),
				'hvr-radial-out'			 => __( 'Radial Out', 'envo-extra' ),
				'hvr-radial-in'				 => __( 'Radial In', 'envo-extra' ),
				'hvr-rectangle-in'			 => __( 'Rectangle In', 'envo-extra' ),
				'hvr-rectangle-out'			 => __( 'Rectangle Out', 'envo-extra' ),
				'hvr-shutter-in-horizontal'	 => __( 'Shutter In Horizontal', 'envo-extra' ),
				'hvr-shutter-out-horizontal' => __( 'Shutter Out Horizontal', 'envo-extra' ),
				'hvr-shutter-in-vertical'	 => __( 'Shutter In Vertical', 'envo-extra' ),
				'hvr-shutter-out-vertical'	 => __( 'Shutter Out Vertical', 'envo-extra' ),
			),
			'condition'	 => array(
				'hover_animation' => 'background-transition',
			),
		)
		);

		$this->add_control(
		'hover_unique_animation', array(
			'label'		 => __( 'Animation', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'skewFill',
			'options'	 => array(
				'underlineFromLeft'		 => __( 'Underline From Left', 'envo-extra' ),
				'underlineFromRight'	 => __( 'Underline From Right', 'envo-extra' ),
				'underlineFromCenter'	 => __( 'Underline From Center', 'envo-extra' ),
				'skewFill'				 => __( 'Skew Fill', 'envo-extra' ),
				'flipSlide'				 => __( 'Flip Slide', 'envo-extra' ),
				'bubbleFromDown'		 => __( 'Bubble From Down', 'envo-extra' ),
				'bubbleFromCenter'		 => __( 'Bubble From Center', 'envo-extra' ),
			),
			'condition'	 => array(
				'hover_animation' => 'unique',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'border',
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button',
			'separator'	 => 'before',
		)
		);

		$this->add_responsive_control(
		'border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'button_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style_icon', array(
			'label'		 => __( 'Icon', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'icon[value]!' => '',
			),
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
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button-media > i'	 => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-elementor-button-media > svg'	 => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-elementor-button-media'		 => 'min-width: {{SIZE}}{{UNIT}};',
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
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button-media' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->start_controls_tabs( 'button_icon_style' );

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
				'{{WRAPPER}} .envo-extra-elementor-button-media > i'	 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-elementor-button-media > svg'	 => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'icon_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button-media',
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
				'{{WRAPPER}} .envo-extra-elementor-button:hover .envo-extra-elementor-button-media > i, {{WRAPPER}} .envo-extra-elementor-button:focus .envo-extra-elementor-button-media > i'		 => 'color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-elementor-button:hover .envo-extra-elementor-button-media > svg, {{WRAPPER}} .envo-extra-elementor-button:focus .envo-extra-elementor-button-media > svg'	 => 'fill: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'icon_background_hover',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button:hover .envo-extra-elementor-button-media, {{WRAPPER}} .envo-extra-elementor-button:focus .envo-extra-elementor-button-media',
		)
		);

		$this->add_control(
		'icon_border_hover_color', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'condition'	 => array(
				'icon_border!' => '',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button:hover .envo-extra-elementor-button-media, {{WRAPPER}} .envo-extra-elementor-button:focus .envo-extra-elementor-button-media' => 'border-color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'icon_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-elementor-button-media',
			'separator'	 => 'before',
		)
		);

		$this->add_responsive_control(
		'icon_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-elementor-button-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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



		$html_tag	 = ( $settings[ 'link' ][ 'url' ] ) ? 'a' : 'span';
		$attr		 = ( $settings[ 'button_css_id' ] ) ? ' id="' . esc_html($settings[ 'button_css_id' ]) . '"' : '';
		$attr .= $settings[ 'link' ][ 'is_external' ] ? ' target="_blank"' : '';
		$attr .= $settings[ 'link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
		$attr .= $settings[ 'link' ][ 'url' ] ? ' href="' . esc_url($settings[ 'link' ][ 'url' ]) . '"' : '';

		$hover_animation = ( '2d-transition' === $settings[ 'hover_animation' ] ) ? 'envo-extra-button-2d-animation ' . $settings[ 'hover_2d_css_animation' ] : ( ( 'background-transition' === $settings[ 'hover_animation' ] ) ? 'envo-extra-button-bg-animation ' . $settings[ 'hover_background_css_animation' ] : ( ( 'unique' === $settings[ 'hover_animation' ] ) ? 'envo-extra-elementor-button-hover-style-' . $settings[ 'hover_unique_animation' ] : 'envo-extra-elementor-button-animation-none' ) );
		?>

		<<?php echo esc_attr( $html_tag ); ?> <?php echo wp_kses_data( $attr ); ?> class="envo-extra-elementor-button <?php echo esc_attr( $hover_animation ); ?> envo-extra-align-icon-<?php echo ( 'left' === $settings[ 'icon_align' ] ) ? 'left' : 'right'; ?>">
		<span class="envo-extra-elementor-button-inner">
			<?php if ( $settings[ 'icon' ][ 'value' ] ) { ?>
				<span class="envo-extra-elementor-button-media"><?php Icons_Manager::render_icon( $settings[ 'icon' ], array( 'aria-hidden' => 'true' ) ); ?></span>
			<?php } ?>
			<span class="envo-extra-button-text"><?php echo esc_html( $settings[ 'text' ] ); ?></span>
		</span>
		</<?php echo esc_attr( $html_tag ); ?>>
		<?php
	}

}
