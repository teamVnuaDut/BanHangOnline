<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
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
class Counter extends Widget_Base {

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
		return 'envo-extra-counter';
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
		return __( 'Counter', 'envo-extra' );
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
		return 'eicon-counter-circle';
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
		return array( 'number', 'counter' );
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

		return array( 'envo-extra-counter' );
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
		'section_counter', array(
			'label'	 => __( 'General', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_CONTENT,
		)
		);

		$this->add_control(
		'value', array(
			'label'				 => esc_html__( 'Counter Value', 'envo-extra' ),
			'type'				 => Controls_Manager::NUMBER,
			'min'				 => 1,
			'max'				 => 10000,
			'step'				 => 1,
			'default'			 => 90,
			'frontend_available' => true,
			'dynamic'			 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'symbol', array(
			'label'				 => __( 'Character', 'envo-extra' ),
			'type'				 => Controls_Manager::TEXT,
			'default'			 => __( 'K', 'envo-extra' ),
			'frontend_available' => true,
			'dynamic'			 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'title', array(
			'label'			 => __( 'Title', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'label_block'	 => true,
			'default'		 => __( 'Counter Title', 'envo-extra' ),
			'placeholder'	 => __( 'Type Counter Title', 'envo-extra' ),
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
			'default'		 => __( 'Description Here', 'envo-extra' ),
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
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-wrapper' => 'text-align: {{VALUE}};',
			),
		)
		);


		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
		'section_style_counter', array(
			'label'	 => __( 'Counter', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'counter_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-item',
		)
		);

		$this->add_responsive_control(
		'counter_bg_size', array(
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
				'{{WRAPPER}} .envo-extra-counter-item' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'counter_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'counter_border',
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-item',
		)
		);

		$this->add_responsive_control(
		'counter_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), array(
			'name'		 => 'counter_shadow',
			'exclude'	 => array(
				'box_shadow_position',
			),
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-item',
		)
		);

		$this->start_controls_tabs( '_tabs_icon' );

		$this->start_controls_tab(
		'_tab_counter_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'counter_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-item' => 'color: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'counter_bg_color',
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-item',
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'_tab_button_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'counter_hover_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-counter-item' => 'color: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'counter_hover_bg_color',
			'types'		 => array( 'classic', 'gradient' ),
			'exclude'	 => array( 'image' ),
			'selector'	 => '{{WRAPPER}}:hover .envo-extra-counter-item',
		)
		);

		$this->add_control(
		'counter_hover_border_color', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-counter-item' => 'border-color: {{VALUE}};',
			),
			'condition'	 => array(
				'counter_border_border!' => '',
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
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-title',
		)
		);

		$this->add_group_control(
		Group_Control_Text_Shadow::get_type(), array(
			'name'		 => 'title',
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-title',
		)
		);

		$this->start_controls_tabs( 'tabs_title' );

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
				'{{WRAPPER}} .envo-extra-counter-title' => 'color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'tab_title_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'title_hover_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-counter-title' => 'color: {{VALUE}};',
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
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-description',
		)
		);

		$this->add_group_control(
		Group_Control_Text_Shadow::get_type(), array(
			'name'		 => 'description',
			'selector'	 => '{{WRAPPER}} .envo-extra-counter-description',
		)
		);

		$this->start_controls_tabs( 'tabs_description' );

		$this->start_controls_tab(
		'tab_description_normal', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'description_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-description' => 'color: {{VALUE}};',
			),
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'tab_description_hover', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'description_hover_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}}:hover .envo-extra-counter-description' => 'color: {{VALUE}};',
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
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-counter-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_control(
		'badge_offset_toggle', array(
			'label'			 => __( 'Offset', 'envo-extra' ),
			'type'			 => Controls_Manager::POPOVER_TOGGLE,
			'label_off'		 => __( 'None', 'envo-extra' ),
			'label_on'		 => __( 'Custom', 'envo-extra' ),
			'return_value'	 => 'yes',
		)
		);

		$this->start_popover();

		$this->add_responsive_control(
		'badge_offset_x', array(
			'label'		 => __( 'Offset Left', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'condition'	 => array(
				'badge_offset_toggle' => 'yes',
			),
			'default'	 => array(
				'unit' => 'px',
			),
			'range'		 => array(
				'px' => array(
					'min'	 => - 1000,
					'max'	 => 1000,
				),
				'%'	 => array(
					'min'	 => - 100,
					'max'	 => 100,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-badge' => '--envo-extra-badge-translate-x: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'badge_offset_y', array(
			'label'		 => __( 'Offset Top', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'condition'	 => array(
				'badge_offset_toggle' => 'yes',
			),
			'default'	 => array(
				'unit' => 'px',
			),
			'range'		 => array(
				'px' => array(
					'min'	 => - 1000,
					'max'	 => 1000,
				),
				'%'	 => array(
					'min'	 => - 100,
					'max'	 => 100,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-badge' => '--envo-extra-badge-translate-y: {{SIZE}}{{UNIT}};',
			),
		)
		);
		$this->end_popover();

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
			'exclude'	 => array(
				'box_shadow_position',
			),
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
		$this->add_render_attribute( 'title', 'class', 'envo-extra-counter-title' );
		$this->add_inline_editing_attributes( 'description', 'basic' );
		$this->add_render_attribute( 'description', 'class', 'envo-extra-counter-description' );

		$this->add_inline_editing_attributes( 'badge_text', 'none' );
		$this->add_render_attribute( 'badge_text', 'class', 'envo-extra-badge envo-extra-badge-' . $settings[ 'badge_position' ] );

		$html_tag	 = ( $settings[ 'link' ][ 'url' ] ) ? 'a' : 'div';
		$attr		 = $settings[ 'link' ][ 'url' ] ? ' href="' . esc_url($settings[ 'link' ][ 'url' ]) . '"' : '';
		$attr .= $settings[ 'link' ][ 'is_external' ] ? ' target="_blank"' : '';
		$attr .= $settings[ 'link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
		?>

		<<?php echo esc_attr( $html_tag ); ?> <?php echo wp_kses_data( $attr ); ?> class="envo-extra-counter-wrapper">
		<div class="envo-extra-counter-wrapper-inner">
			<?php if ( $settings[ 'badge_text' ] ) : ?>
				<div <?php $this->print_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings[ 'badge_text' ] ); ?></div>
			<?php endif; ?>

			<?php if ( !empty( $settings[ 'value' ] ) ) : ?>
				<div class="envo-extra-counter-item"><?php echo esc_html( $settings[ 'value' ] ); ?><?php echo esc_html( $settings[ 'symbol' ] ); ?></div>
			<?php endif; ?>

			<div class="envo-extra-counter-content">
				<?php
				if ( $settings[ 'title' ] ) :
					printf( '<%1$s %2$s>%3$s</%1$s>', tag_escape( $settings[ 'title_tag' ] ), $this->get_render_attribute_string( 'title' ), $settings[ 'title' ] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endif;
				if ( $settings[ 'description' ] ) :
					?>
					<p <?php $this->print_render_attribute_string( 'description' ); ?>><?php echo esc_html( $settings[ 'description' ] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		</<?php echo esc_attr( $html_tag ); ?>>
		<?php
	}

}
