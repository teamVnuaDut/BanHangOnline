<?php
namespace ETWWElementor\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use ETWWElementor\Modules\Woocommerce\Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_MenuCart extends Widget_Base {

	public function get_name() {
		return 'etww-woo-menucart';
	}

	public function get_title() {
		return __( 'Woo - Menu Cart', 'etww' );
	}

	public function get_icon() {
		return 'etww-icon eicon-cart';
	}

	public function get_categories() {
		return [ 'etww-elements' ];
	}
	
	public function get_style_depends() {
		return [ 'etww-eicons-bc', 'etww-woo-menucart' ];
	}
	
	protected function register_controls() {

		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => __( 'Menu Icon', 'etww' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'etww' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cart-light' => __( 'Cart', 'etww' ) . ' ' . __( 'Light', 'etww' ),
					'cart-medium' => __( 'Cart', 'etww' ) . ' ' . __( 'Medium', 'etww' ),
					'cart-solid' => __( 'Cart', 'etww' ) . ' ' . __( 'Solid', 'etww' ),
					'basket-light' => __( 'Basket', 'etww' ) . ' ' . __( 'Light', 'etww' ),
					'basket-medium' => __( 'Basket', 'etww' ) . ' ' . __( 'Medium', 'etww' ),
					'basket-solid' => __( 'Basket', 'etww' ) . ' ' . __( 'Solid', 'etww' ),
					'bag-light' => __( 'Bag', 'etww' ) . ' ' . __( 'Light', 'etww' ),
					'bag-medium' => __( 'Bag', 'etww' ) . ' ' . __( 'Medium', 'etww' ),
					'bag-solid' => __( 'Bag', 'etww' ) . ' ' . __( 'Solid', 'etww' ),
				],
				'default' => 'cart-medium',
				'prefix_class' => 'toggle-icon--',
			]
		);

		$this->add_control(
			'items_indicator',
			[
				'label' => __( 'Items Indicator', 'etww' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'etww' ),
					'bubble' => __( 'Bubble', 'etww' ),
					'plain' => __( 'Plain', 'etww' ),
				],
				'prefix_class' => 'etww-menucart--items-indicator-',
				'default' => 'bubble',
			]
		);

		$this->add_control(
			'hide_empty_indicator',
			[
				'label' => __( 'Hide Empty', 'etww' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'etww' ),
				'label_off' => __( 'No', 'etww' ),
				'return_value' => 'hide',
				'prefix_class' => 'etww-menucart--empty-indicator-',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'show_subtotal',
			[
				'label' => __( 'Subtotal', 'etww' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'etww' ),
				'label_off' => __( 'Hide', 'etww' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'etww-menucart--show-subtotal-',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'etww' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'etww' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'etww' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'etww' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => __( 'Menu Icon', 'etww' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', [ 'label' => __( 'Normal', 'etww' ) ] );

		$this->add_control(
			'toggle_button_text_color',
			[
				'label' => __( 'Text Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_icon_color',
			[
				'label' => __( 'Icon Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_background_color',
			[
				'label' => __( 'Background Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_color',
			[
				'label' => __( 'Border Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', [ 'label' => __( 'Hover', 'etww' ) ] );

		$this->add_control(
			'toggle_button_hover_text_color',
			[
				'label' => __( 'Text Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_icon_color',
			[
				'label' => __( 'Icon Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button:hover .elementor-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_background_color',
			[
				'label' => __( 'Background Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_button_border_width',
			[
				'label' => __( 'Border Width', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_radius',
			[
				'label' => __( 'Border Radius', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'toggle_button_typography',
				
				'selector' => '{{WRAPPER}} .etww-menucart__toggle .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_size',
			[
				'label' => __( 'Size', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_spacing',
			[
				'label' => __( 'Spacing', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size-units' => [ 'px', 'em' ],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .etww-menucart__toggle .elementor-button-text' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .etww-menucart__toggle .elementor-button-text' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_padding',
			[
				'label' => __( 'Padding', 'etww' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'items_indicator_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Items Indicator', 'etww' ),
				'separator' => 'before',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);
		$this->add_control(
			'items_indicator_text_color',
			[
				'label' => __( 'Text Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button-icon[data-counter]:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'items_indicator_background_color',
			[
				'label' => __( 'Background Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button-icon[data-counter]:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->add_control(
			'items_indicator_distance',
			[
				'label' => __( 'Distance', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__toggle .elementor-button-icon[data-counter]:before' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => __( 'Cart', 'etww' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Divider', 'etww' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'etww' ),
				'label_off' => __( 'Hide', 'etww' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'etww-menucart--show-divider-',
			]
		);

		$this->add_control(
			'show_remove_icon',
			[
				'label' => __( 'Remove Item Icon', 'etww' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'etww' ),
				'label_off' => __( 'Hide', 'etww' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'etww-menucart--show-remove-button-',
			]
		);

		$this->add_control(
			'heading_subtotal_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Subtotal', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtotal_color',
			[
				'label' => __( 'Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__subtotal' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .etww-menucart__subtotal',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => __( 'Products', 'etww' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_product_title_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Title', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label' => __( 'Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__product-name, {{WRAPPER}} .etww-menucart__product-name a' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				
				'selector' => '{{WRAPPER}} .etww-menucart__product-name, {{WRAPPER}} .etww-menucart__product-name a',
			]
		);

		$this->add_control(
			'heading_product_price_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Price', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => __( 'Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__product-price' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				
				'selector' => '{{WRAPPER}} .etww-menucart__product-price',
			]
		);

		$this->add_control(
			'heading_product_divider_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Divider', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'etww' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'etww' ),
					'solid' => __( 'Solid', 'etww' ),
					'double' => __( 'Double', 'etww' ),
					'dotted' => __( 'Dotted', 'etww' ),
					'dashed' => __( 'Dashed', 'etww' ),
					'groove' => __( 'Groove', 'etww' ),
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__product, {{WRAPPER}} .etww-menucart__subtotal' => 'border-bottom-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__product, {{WRAPPER}} .etww-menucart__subtotal' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Weight', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__product, {{WRAPPER}} .etww-menucart__products, {{WRAPPER}} .etww-menucart__subtotal' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_gap',
			[
				'label' => __( 'Spacing', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__product, {{WRAPPER}} .etww-menucart__subtotal' => 'padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .etww-menucart__product:not(:first-of-type), {{WRAPPER}} .etww-menucart__footer-buttons, {{WRAPPER}} .etww-menucart__subtotal' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => __( 'Buttons', 'etww' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'buttons_layout',
			[
				'label' => __( 'Layout', 'etww' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'inline' => __( 'Inline', 'etww' ),
					'stacked' => __( 'Stacked', 'etww' ),
				],
				'default' => 'inline',
				'prefix_class' => 'etww-menucart--buttons-',
			]
		);

		$this->add_control(
			'space_between_buttons',
			[
				'label' => __( 'Space Between', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__footer-buttons' => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_buttons_typography',
				
				'selector' => '{{WRAPPER}} .etww-menucart__footer-buttons .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'etww' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .etww-menucart__footer-buttons .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_view_cart_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'View Cart', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_button_text_color',
			[
				'label' => __( 'Text Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--view-cart' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_button_background_color',
			[
				'label' => __( 'Background Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--view-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_cart_border',
				'selector' => '{{WRAPPER}} .elementor-button--view-cart',
			]
		);

		$this->add_control(
			'heading_checkout_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Checkout', 'etww' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_button_text_color',
			[
				'label' => __( 'Text Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--checkout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_button_background_color',
			[
				'label' => __( 'Background Color', 'etww' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--checkout' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'selector' => '{{WRAPPER}} .elementor-button--checkout',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		Module::render_menu_cart();
	}

	public function render_plain_content() {}
}
