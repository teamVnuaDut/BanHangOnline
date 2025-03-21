<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Heading extends Widget_Base {

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
		return 'envo-extra-heading';
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
		return __( 'Heading', 'envo-extra' );
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
		return 'eicon-heading';
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
		return array( 'heading', 'masking' );
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

		return array( 'envo-extra-heading' );
	}

	public function render_title() {

		$settings = $this->get_settings_for_display();

		$target   = $settings['title_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['title_link']['nofollow'] ? ' rel="nofollow"' : '';

		$title = $settings['title_before'] . ( ( ! empty( $settings['title_center'] ) ) ? ' <span class="envo-extra-title-focus">' . $settings['title_center'] . '</span>' : '' ) . ' ' . $settings['title_after'];

		$html = '';

		if ( ! empty( $settings['title_link']['url'] ) ) {
			$html .= '<a href="' . esc_url( $settings['title_link']['url'] ) . '"' . esc_attr( $target ) . esc_attr( $nofollow ) . '>';
		}

		$html .= '<' . esc_attr( $settings['title_tag'] ) . ' class="envo-extra-heading-title">';
		$html .= wp_kses_post( $title );
		$html .= '</' . esc_attr( $settings['title_tag'] ) . '>';

		if ( ! empty( $settings['title_link']['url'] ) ) {
			$html .= '</a>';
		}

		return $html;
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
			'section_title',
			array(
				'label' => __( 'Title', 'envo-extra' ),
			)
		);

		$this->add_control(
			'title_before',
			array(
				'label'       => __( 'Title Before', 'envo-extra' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Your', 'envo-extra' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title_center',
			array(
				'label'       => __( 'Title Center', 'envo-extra' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Simple', 'envo-extra' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title_after',
			array(
				'label'       => __( 'Title After', 'envo-extra' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Heading', 'envo-extra' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title_link',
			array(
				'label'       => __( 'Link', 'envo-extra' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'HTML Tag', 'envo-extra' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'h1' => array(
						'title' => __( 'H1', 'envo-extra' ),
						'icon'  => 'eicon-editor-h1',
					),
					'h2' => array(
						'title' => __( 'H2', 'envo-extra' ),
						'icon'  => 'eicon-editor-h2',
					),
					'h3' => array(
						'title' => __( 'H3', 'envo-extra' ),
						'icon'  => 'eicon-editor-h3',
					),
					'h4' => array(
						'title' => __( 'H4', 'envo-extra' ),
						'icon'  => 'eicon-editor-h4',
					),
					'h5' => array(
						'title' => __( 'H5', 'envo-extra' ),
						'icon'  => 'eicon-editor-h5',
					),
					'h6' => array(
						'title' => __( 'H6', 'envo-extra' ),
						'icon'  => 'eicon-editor-h6',
					),
				),
				'default' => 'h2',
				'toggle'  => false,
			)
		);

		$this->add_responsive_control(
			'box_align',
			array(
				'label'     => __( 'Alignment', 'envo-extra' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'envo-extra' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'envo-extra' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'envo-extra' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-simple-heading-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		//Title Style
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'envo-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'envo-extra' ),
				'selector' => '{{WRAPPER}} .envo-extra-heading-title',
			)
		);
		
		$this->add_control(
			'title_text_color', array(
				'label'		 => __( 'Text Color', 'envo-extra' ),
				'type'		 => Controls_Manager::COLOR,
				'selectors'	 => array(
					'{{WRAPPER}} .envo-extra-heading-title'		 => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_stroke',
			array(
				'label'        => __( 'Text Stroke', 'envo-extra' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'envo-extra' ),
				'label_on'     => __( 'Custom', 'envo-extra' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_control(
			'stroke_width',
			array(
				'label'      => __( 'Stroke Width', 'envo-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-heading-title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'title_stroke' => 'yes',
				),
			)
		);

		$this->add_control(
			'stroke_color',
			array(
				'label'     => __( 'Stroke Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-heading-title' => '-webkit-text-stroke-color: {{VALUE}};',
				),
				'condition' => array(
					'title_stroke' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .envo-extra-heading-title',
			)
		);

		$this->add_control(
			'blend_mode',
			array(
				'label'     => esc_html__( 'Blend Mode', 'envo-extra' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => esc_html__( 'Normal', 'envo-extra' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				),
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-heading-title' => 'mix-blend-mode: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_section();

		//Center Title Style
		$this->start_controls_section(
			'section_style_center_title',
			array(
				'label' => __( 'Center Title', 'envo-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'center_title_typography',
				'label'    => __( 'Typography', 'envo-extra' ),
				'selector' => '{{WRAPPER}} .envo-extra-title-focus',
			)
		);
		
		$this->add_control(
			'center_text_color', array(
				'label'		 => __( 'Text Color', 'envo-extra' ),
				'type'		 => Controls_Manager::COLOR,
				'selectors'	 => array(
					'{{WRAPPER}} .envo-extra-title-focus'		 => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'center_title_stroke',
			array(
				'label'        => __( 'Text Stroke', 'envo-extra' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'envo-extra' ),
				'label_on'     => __( 'Custom', 'envo-extra' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_control(
			'center_stroke_width',
			array(
				'label'      => __( 'Stroke Width', 'envo-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-title-focus' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'center_title_stroke' => 'yes',
				),
			)
		);

		$this->add_control(
			'center_stroke_color',
			array(
				'label'     => __( 'Stroke Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-title-focus' => '-webkit-text-stroke-color: {{VALUE}};',
				),
				'condition' => array(
					'center_title_stroke' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'center_title_text_shadow',
				'selector' => '{{WRAPPER}} .envo-extra-title-focus',
			)
		);

		$this->add_control(
			'center_title_blend_mode',
			array(
				'label'     => esc_html__( 'Blend Mode', 'envo-extra' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => esc_html__( 'Normal', 'envo-extra' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				),
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-title-focus' => 'mix-blend-mode: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'center_title_border',
				'label'    => __( 'Border', 'envo-extra' ),
				'selector' => '{{WRAPPER}} .envo-extra-title-focus',
			)
		);

		$this->add_responsive_control(
			'center_title_border_radius',
			array(
				'label'      => __( 'Border Radius', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-title-focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'center_title_padding',
			array(
				'label'      => __( 'Padding', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-title-focus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		?>

		<div class="envo-extra-heading-wrapper envo-extra-simple-heading-wrapper">
			<?php echo wp_kses_post($this->render_title()); ?>
		</div>
		<?php
	}
}
