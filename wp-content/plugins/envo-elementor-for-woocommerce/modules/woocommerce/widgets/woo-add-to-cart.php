<?php

namespace ETWWElementor\Modules\Woocommerce\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use ETWWElementor\Modules\QueryPost\Module;

class Woo_Add_To_Cart extends Widget_Base {

    public function get_name() {
        return 'etww-woo-add-to-cart';
    }

    public function get_title() {
        return __('Woo - Add To Cart', 'etww');
    }

    public function get_icon() {

        return 'etww-icon eicon-woocommerce';
    }

    public function get_categories() {
        return ['etww-elements'];
    }

    public function get_style_depends() {
        return ['etww-woo-addtocart'];
    }

    protected function register_controls() {

        $this->start_controls_section(
                'section_woo_product',
                [
                    'label' => __('Product', 'etww'),
                ]
        );

        $this->add_control(
                'product_id',
                [
                    'label' => __('Select Product', 'etww'),
                    'type' => 'etww-query-posts',
                    'post_type' => 'product',
                ]
        );

        $this->add_control(
                'quantity',
                [
                    'label' => __('Quantity', 'etww'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 1,
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_button',
                [
                    'label' => __('Button', 'etww'),
                ]
        );

        $this->add_control(
                'text',
                [
                    'label' => __('Text', 'etww'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Add To Cart', 'etww'),
                    'dynamic' => ['active' => true],
                ]
        );

        $this->add_responsive_control(
                'align',
                [
                    'label' => __('Alignment', 'etww'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
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
                        'justify' => [
                            'title' => __('Justified', 'etww'),
                            'icon' => 'fa fa-align-justify',
                        ],
                    ],
                    'default' => '',
                    'prefix_class' => 'wew%s-align-',
                ]
        );

        $this->add_control(
                'icon',
                [
                    'label' => __('Icon', 'etww'),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-shopping-basket',
                        'library' => 'solid',
                    ],
                ]
        );

        $this->add_control(
                'icon_align',
                [
                    'label' => __('Icon Position', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __('Before', 'etww'),
                        'right' => __('After', 'etww'),
                    ],
                    'condition' => [
                        'icon!' => '',
                    ],
                ]
        );

        $this->add_control(
                'icon_indent',
                [
                    'label' => __('Icon Spacing', 'etww'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 4,
                    ],
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'icon!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-addtocart .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_button_style',
                [
                    'label' => __('Button', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',

                    'selector' => '{{WRAPPER}} .etww-addtocart',
                ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => __('Normal', 'etww'),
                ]
        );

        $this->add_control(
                'button_background_color',
                [
                    'label' => __('Background Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                    'label' => __('Text Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => __('Hover', 'etww'),
                ]
        );

        $this->add_control(
                'button_hover_background_color',
                [
                    'label' => __('Background Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_color',
                [
                    'label' => __('Text Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => __('Border Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'button_border',
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-addtocart',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'button_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'selector' => '{{WRAPPER}} .etww-addtocart',
                ]
        );

        $this->add_responsive_control(
                'button_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'button_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_view_cart_style',
                [
                    'label' => __('View Cart Text', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'view_cart_typography',
                    'selector' => '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart',
                ]
        );

        $this->start_controls_tabs('tabs_view_cart_style');

        $this->start_controls_tab(
                'tab_view_cart_normal',
                [
                    'label' => __('Normal', 'etww'),
                ]
        );

        $this->add_control(
                'view_cart_background_color',
                [
                    'label' => __('Background Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'view_cart_text_color',
                [
                    'label' => __('Text Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_view_cart_hover',
                [
                    'label' => __('Hover', 'etww'),
                ]
        );

        $this->add_control(
                'view_cart_hover_background_color',
                [
                    'label' => __('Background Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'view_cart_hover_color',
                [
                    'label' => __('Text Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'view_cart_hover_border_color',
                [
                    'label' => __('Border Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart:hover' => 'border-color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'view_cart_border',
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'view_cart_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'view_cart_box_shadow',
                    'selector' => '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart',
                ]
        );

        $this->add_responsive_control(
                'view_cart_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'view_cart_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-addtocart-wrap .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $html = '';
        $product = false;

        if (!empty($settings['product_id'])) {
            $product_data = get_post($settings['product_id']);
        }

        $product = !empty($product_data) && in_array($product_data->post_type, ['product', 'product_variation']) ? wc_setup_product_data($product_data) : false;

        $this->add_render_attribute('button-wrap', 'class', 'etww-addtocart-wrap');
        $this->add_render_attribute('button-text', 'class', 'etww-addtocart-text');

        if ($product) {

            $product_id = $product->get_id();
            $product_type = $product->get_type();

            $class = [
                'etww-addtocart',
                'button',
                'product_type_' . $product_type,
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
            ];

            $this->add_render_attribute(
                    'button', [
                'href' => esc_url($product->add_to_cart_url()),
                'class' => $class,
                'data-quantity' => (isset($settings['quantity']) ? $settings['quantity'] : 1),
                'data-product_id' => $product_id,
                'rel' => 'nofollow',
                    ]
            );

            $this->add_render_attribute('icon-align', 'class', [
                'etww-button-icon',
                'elementor-align-icon-' . $settings['icon_align'],
            ]);
            ?>

            <div <?php echo $this->get_render_attribute_string('button-wrap'); ?>>
                <a <?php echo $this->get_render_attribute_string('button'); ?>>
                    <?php if (!empty($settings['icon']) && 'left' == $settings['icon_align']) { ?>
                        <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                            <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                        </span>
                        <?php }
                    ?>

                    <span <?php echo $this->get_render_attribute_string('button-text'); ?>><?php echo esc_attr($settings['text']); ?></span>

                    <?php if (!empty($settings['icon']) && 'right' == $settings['icon_align']) { ?>
                        <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                            <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                        </span>
                        <?php }
                    ?>
                </a>
            </div>

            <?php
        } elseif (current_user_can('manage_options')) {

            $this->add_render_attribute('button', 'href', '#');
            $this->add_render_attribute('button', 'class', [
                'etww-addtocart',
                'button',
            ]);
            ?>

            <div <?php echo $this->get_render_attribute_string('button-wrap'); ?>>
                <a <?php echo $this->get_render_attribute_string('button'); ?>>
                    <span <?php echo $this->get_render_attribute_string('button-text'); ?>><?php echo __('Please select a product', 'etww'); ?></span>
                </a>
            </div>

            <?php
        }
    }

}
