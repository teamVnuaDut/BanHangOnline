<?php



use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
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
class Envo_Block_Quote extends Widget_Base {

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
		return 'envo-extra-block-quote';
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
		return __( 'Block Quote', 'envo-extra' );
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
		return 'eicon-blockquote';
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
		return array( 'block', 'quote' );
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

		return array( 'envo-extra-block-quote', 'font-awesome-5-all' );
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
			'section_blockquote',
			array(
				'label' => __( 'Block Quote', 'envo-extra' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'quote_position',
			array(
				'label'   => __( 'Position', 'envo-extra' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'layout-1',
				'options' => array(
					'layout-1'  => __( 'Layout 1', 'envo-extra' ),
					'layout-2'  => __( 'Layout 2', 'envo-extra' ),
					'layout-3'  => __( 'Layout 3', 'envo-extra' ),
					'layout-4'  => __( 'Layout 4', 'envo-extra' ),
					'layout-5'  => __( 'Layout 5', 'envo-extra' ),
					'layout-6'  => __( 'Layout 6', 'envo-extra' ),
					'layout-7'  => __( 'Layout 7', 'envo-extra' ),
					'layout-8'  => __( 'Layout 8', 'envo-extra' ),
					'layout-9'  => __( 'Layout 9', 'envo-extra' ),
					'layout-10' => __( 'Layout 10', 'envo-extra' ),
				),
			)
		);

		$this->add_control(
			'quote_icon',
			array(
				'label'     => esc_html__( 'Icons', 'envo-extra' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-quote-left',
					'library' => 'solid',
				),
				'condition' => array(
					'quote_position!' => array( 'layout-3', 'layout-6' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Image', 'envo-extra' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'quote_position' => array( 'layout-4' ),
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'media_thumbnail',
				'default'   => 'full',
				'separator' => 'none',
				'exclude'   => array(
					'custom',
				),
				'condition' => array(
					'quote_position' => array( 'layout-4' ),
				),
			)
		);

		$this->add_control(
			'quote_title',
			array(
				'label'       => __( 'Title', 'envo-extra' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => __( 'Steve Smith', 'envo-extra' ),
				'placeholder' => __( 'Type Block Quote Title', 'envo-extra' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'quote_designation',
			array(
				'label'       => __( 'Designation', 'envo-extra' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => __( 'By Developer', 'envo-extra' ),
				'placeholder' => __( 'Type Block Quote Designation', 'envo-extra' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'quote_description',
			array(
				'label'       => esc_html__( 'Description', 'envo-extra' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'placeholder' => esc_html__( 'Type your description here', 'envo-extra' ),
				'default'     => 'Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_style_general',
			array(
				'label' => __( 'General', 'envo-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'box_alignment',
			array(
				'label'          => __( 'Alignment', 'envo-extra' ),
				'type'           => Controls_Manager::CHOOSE,
				'options'        => array(
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
				'toggle'         => false,
				'default'        => 'left',
				'tablet_default' => 'left',
				'mobile_default' => 'center',
				'prefix_class'   => 'elementor%s-align-',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background',
				'label'    => __( 'Background', 'envo-extra' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-inner, {{WRAPPER}} .envo-extra-block-quote-layout-3 .envo-extra-block-quote-inner::before, {{WRAPPER}} .envo-extra-block-quote-layout-6 .envo-extra-block-quote-text::before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'style_border',
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-inner',
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Image
		$this->start_controls_section(
			'section_block_quote_image',
			array(
				'label'     => __( 'Image', 'envo-extra' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'quote_position' => 'layout-4',
				),
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'      => __( 'Size', 'envo-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-layout-4 .envo-extra-block-quote-content-img > img' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-layout-4 .envo-extra-block-quote-content-img > img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Separator
		$this->start_controls_section(
			'section_block_quote_separator',
			array(
				'label'     => __( 'Separator', 'envo-extra' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'quote_position' => 'layout-5',
				),
			)
		);

		$this->add_responsive_control(
			'separator_size',
			array(
				'label'      => __( 'Size', 'envo-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-layout-5 .envo-extra-block-quote-content-wrap::before' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-block-quote-layout-5 .envo-extra-block-quote-content-wrap::before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_margin',
			array(
				'label'      => __( 'Margin', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-layout-5 .envo-extra-block-quote-content-wrap::before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Quote
		$this->start_controls_section(
			'section_block_quote',
			array(
				'label'     => __( 'Quote', 'envo-extra' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'quote_position!' => array( 'layout-3', 'layout-6' ),
				),
			)
		);

		$this->add_responsive_control(
			'quote_size',
			array(
				'label'      => __( 'Size', 'envo-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .envo-extra-block-quote-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'quote_bg_size',
			array(
				'label'      => __( 'Background Size', 'envo-extra' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'quote_color',
			array(
				'label'     => __( 'Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .envo-extra-block-quote-icon > svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'quote_bg_color',
			array(
				'label'     => __( 'Background Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'quote_border',
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-icon',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'quote_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-icon',
			)
		);

		$this->add_responsive_control(
			'quote_border_radius',
			array(
				'label'      => __( 'Border Radius', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'quote_padding',
			array(
				'label'      => __( 'Padding', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'quote_margin',
			array(
				'label'      => __( 'Margin', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Description
		$this->start_controls_section(
			'section_block_quote_desc',
			array(
				'label' => __( 'Description', 'envo-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => __( 'Typography', 'envo-extra' ),
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => __( 'Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'desc_border',
				'selector'  => '{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text,
				{{WRAPPER}} .envo-extra-block-quote-layout-6 .envo-extra-block-quote-text::after',
				'condition' => array(
					'quote_position' => 'layout-6',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'desc_shadow',
				'exclude'   => array(
					'box_shadow_position',
				),
				'selector'  => '{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text',
				'condition' => array(
					'quote_position' => 'layout-6',
				),
			)
		);

		$this->add_responsive_control(
			'desc_border_radius',
			array(
				'label'      => __( 'Border Radius', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'quote_position' => 'layout-6',
				),
			)
		);

		$this->add_responsive_control(
			'desc_padding',
			array(
				'label'      => __( 'Padding', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'quote_position' => 'layout-6',
				),
			)
		);

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-content-wrap > .envo-extra-block-quote-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Title
		$this->start_controls_section(
			'section_block_quote_title',
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
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-title'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-title::before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Destination
		$this->start_controls_section(
			'section_block_quote_designation',
			array(
				'label' => __( 'Designation', 'envo-extra' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'designation_typography',
				'label'    => __( 'Typography', 'envo-extra' ),
				'selector' => '{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-designation',
			)
		);

		$this->add_control(
			'designation_color',
			array(
				'label'     => __( 'Color', 'envo-extra' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-designation' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'designation_margin',
			array(
				'label'      => __( 'Margin', 'envo-extra' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .envo-extra-block-quote-desc > .envo-extra-block-quote-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
<div class="envo-extra-block-quote-wrapper envo-extra-block-quote-<?php echo esc_attr( $settings['quote_position'] ); ?>">
	<div class="envo-extra-block-quote-inner">
		<?php if ( $settings['quote_icon']['value'] && ( 'layout-3' !== $settings['quote_position'] && 'layout-6' !== $settings['quote_position'] ) ) : ?>
			<span class="envo-extra-block-quote-icon">
			<?php
			if ( $settings['quote_icon'] ) {
				\Elementor\Icons_Manager::render_icon( $settings['quote_icon'], array( 'aria-hidden' => 'true' ) );
			}
			?>
			</span>
		<?php endif; ?>

		<div class="envo-extra-block-quote-content">
			<?php if ( $settings['image'] || 'layout-4' === $settings['quote_position'] ) : ?>
				<span class="envo-extra-block-quote-content-img">
				<?php
				if ( $settings['image'] ) {
					echo wp_kses_post( \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
				}
				?>
			</span>
			<?php endif; ?>

			<div class="envo-extra-block-quote-content-wrap">

				<?php if ( $settings['quote_description'] ) : ?>
					<!-- Text -->
					<p class="envo-extra-block-quote-text"><?php wp_kses_post( $settings['quote_description'] ); ?></p>
				<?php endif; ?>

				<div class="envo-extra-block-quote-desc">
					<?php if ( $settings['quote_title'] ) : ?>
						<!-- Title -->
						<span class="envo-extra-block-quote-title"><?php echo esc_html( $settings['quote_title'] ); ?></span>
					<?php endif; ?>

					<?php if ( $settings['quote_designation'] ) : ?>
						<!-- Designation -->
						<span class="envo-extra-block-quote-designation"><?php echo esc_html( $settings['quote_designation'] ); ?></span>
					<?php endif; ?>

				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	}
}
