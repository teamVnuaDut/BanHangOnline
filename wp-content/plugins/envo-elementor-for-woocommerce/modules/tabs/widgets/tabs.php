<?php

namespace ETWWElementor\Modules\Tabs\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Plugin;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Tabs extends Widget_Base {

    public function get_name() {
        return 'etww-tabs';
    }

    public function get_title() {
        return __('Tabs', 'etww');
    }

    public function get_icon() {

        return 'etww-icon eicon-tabs';
    }

    public function get_categories() {
        return ['etww-elements'];
    }

    public function get_script_depends() {
        return ['etww-tabs'];
    }

    public function get_style_depends() {
        return ['etww-tabs'];
    }

    protected function register_controls() {

        $this->start_controls_section(
                'section_tabs',
                [
                    'label' => __('Tabs', 'etww'),
                ]
        );

        $this->add_control(
                'tabs',
                [
                    'label' => __('Items', 'etww'),
                    'type' => Controls_Manager::REPEATER,
                    'default' => [
                        [
                            'tab_title' => __('Tab #1', 'etww'),
                            'tab_content' => __('I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'etww'),
                        ],
                        [
                            'tab_title' => __('Tab #2', 'etww'),
                            'tab_content' => __('I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'etww'),
                        ],
                        [
                            'tab_title' => __('Tab #3', 'etww'),
                            'tab_content' => __('I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'etww'),
                        ],
                    ],
                    'fields' => [
                        [
                            'name' => 'tab_title',
                            'label' => __('Title & Content', 'etww'),
                            'type' => Controls_Manager::TEXT,
                            'default' => __('Tab Title', 'etww'),
                            'label_block' => true,
                            'dynamic' => ['active' => true],
                        ],
                        [
                            'name' => 'source',
                            'label' => __('Select Source', 'etww'),
                            'type' => Controls_Manager::SELECT,
                            'default' => 'custom',
                            'options' => [
                                'custom' => __('Custom', 'etww'),
                                'template' => __('Template', 'etww'),
                            ],
                        ],
                        [
                            'name' => 'tab_content',
                            'label' => __('Content', 'etww'),
                            'type' => Controls_Manager::WYSIWYG,
                            'default' => __('I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'etww'),
                            'show_label' => false,
                            'condition' => [
                                'source' => 'custom',
                            ],
                            'dynamic' => ['active' => true],
                        ],
                        [
                            'name' => 'templates',
                            'label' => __('Content', 'etww'),
                            'type' => Controls_Manager::SELECT,
                            'default' => '0',
                            'options' => etww_get_available_templates(),
                            'condition' => [
                                'source' => 'template',
                            ],
                        ],
                    ],
                    'title_field' => '{{{ tab_title }}}',
                ]
        );

        $this->add_control(
                'tab_layout',
                [
                    'label' => __('Layout', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'top',
                    'options' => [
                        'top' => __('Top', 'etww'),
                        'bottom' => __('Bottom', 'etww'),
                        'left' => __('Left', 'etww'),
                        'right' => __('Right', 'etww'),
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
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
                    'condition' => [
                        'tab_layout' => ['top', 'bottom']
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_additional',
                [
                    'label' => __('Additional Options', 'etww'),
                ]
        );

        $this->add_control(
                'active_item',
                [
                    'label' => __('Active Item No', 'etww'),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 20,
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_style',
                [
                    'label' => __('Tab', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'tab_spacing',
                [
                    'label' => __('Tab Spacing', 'etww'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs-wrap' => 'margin-left: -{{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-tabs-wrap .etww-tab-title' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-tabs-left .etww-tabs-wrap .etww-tab-title, {{WRAPPER}} .etww-tabs-right .etww-tabs-wrap .etww-tab-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'tab_typography',
                    
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title',
                ]
        );

        $this->start_controls_tabs('tabs_tab_style');

        $this->start_controls_tab(
                'tab_tab_normal',
                [
                    'label' => __('Normal', 'etww'),
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name' => 'tab_background_color',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title',
                )
        );

        $this->add_control(
                'tab_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'tab_box_shadow',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title',
                    'separator' => 'before',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tab_border',
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title',
                ]
        );

        $this->add_control(
                'tab_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'tab_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_tab_active',
                [
                    'label' => __('Active', 'etww'),
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name' => 'tab_active_background_color',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title.etww-active',
                )
        );

        $this->add_control(
                'tab_active_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title.etww-active' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'tab_active_box_shadow',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title.etww-active',
                    'separator' => 'before',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'tab_active_border',
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-title.etww-active',
                ]
        );

        $this->add_control(
                'tab_active_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title.etww-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
                'section_content_style',
                [
                    'label' => __('Tab Content', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'content_typography',
                    
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tab-content',
                ]
        );

        $this->add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name' => 'content_background_color',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tabs-content-wrap',
                )
        );

        $this->add_control(
                'content_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-content' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'content_spacing',
                [
                    'label' => __('Content Spacing', 'etww'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs.etww-tabs-top .etww-tab-content' => 'margin-top: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-tabs.etww-tabs-bottom .etww-tab-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-tabs.etww-tabs-left .etww-tab-content' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-tabs.etww-tabs-right .etww-tab-content' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_box_shadow',
                    'selector' => '{{WRAPPER}} .etww-tabs .etww-tabs-content-wrap',
                ]
        );

        $this->add_responsive_control(
                'content_border_width',
                [
                    'label' => __('Border Width', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 10,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-content, {{WRAPPER}} .etww-tabs .etww-tab-mobile-title' => 'border-width: {{SIZE}}{{UNIT}}; border-top: 0;',
                        '{{WRAPPER}} .etww-tabs .etww-tabs-content-wrap' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'content_border_style',
                [
                    'label' => __('Border Style', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'solid',
                    'options' => [
                        'none' => __('None', 'etww'),
                        'solid' => __('Solid', 'etww'),
                        'double' => __('Double', 'etww'),
                        'dotted' => __('Dotted', 'etww'),
                        'dashed' => __('Dashed', 'etww'),
                        'groove' => __('Groove', 'etww'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-content, {{WRAPPER}} .etww-tabs .etww-tab-mobile-title' => 'border-style: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'content_border_color',
                [
                    'label' => __('Border Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-content, {{WRAPPER}} .etww-tabs .etww-tab-mobile-title, {{WRAPPER}} .etww-tabs .etww-tabs-content-wrap' => 'border-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'content_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tabs-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'content_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_icon_style',
                [
                    'label' => __('Tab Icon', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'icon_align',
                [
                    'label' => __('Alignment', 'etww'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Start', 'etww'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'right' => [
                            'title' => __('End', 'etww'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => is_rtl() ? 'right' : 'left',
                ]
        );

        $this->start_controls_tabs('tabs_icon_style');

        $this->start_controls_tab(
                'tab_icon_normal',
                [
                    'label' => __('Normal', 'etww'),
                ]
        );

        $this->add_control(
                'icon_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title i' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_icon_active',
                [
                    'label' => __('Active', 'etww'),
                ]
        );

        $this->add_control(
                'icon_active_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title.etww-active i' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
                'icon_spacing',
                [
                    'label' => __('Spacing', 'etww'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .etww-tabs .etww-tab-title .etww-icon-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .etww-tabs .etww-tab-title .etww-icon-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_item_style',
                [
                    'label' => __('Product Item', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'item_background_color',
                [
                    'label' => __('Background Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'item_border',
                    'placeholder' => '1px',
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'item_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_box_shadow',
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product',
                ]
        );

        $this->add_responsive_control(
                'item_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );
        $this->add_responsive_control(
                'item_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
                'section_image_style',
                [
                    'label' => __('Product Image', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'image_border',
                    'placeholder' => '1px',
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) img:not(.secondary-image)',
                ]
        );

        $this->add_control(
                'image_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) img:not(.secondary-image), {{WRAPPER}} .woocommerce ul.products li.product .woo-entry-inner li.image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; position: relative; overflow: hidden;',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'image_box_shadow',
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) img:not(.secondary-image)',
                ]
        );

        $this->add_responsive_control(
                'image_margin',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product:not(.arhive-product-gallery) img:not(.secondary-image)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'product_section_content_style',
                [
                    'label' => __('Product Content', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'category_heading',
                [
                    'label' => __('Category', 'etww'),
                    'type' => Controls_Manager::HEADING,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'category_typography',
                    
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product li.category a, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a',
                ]
        );

        $this->add_control(
                'category_color',
                [
                    'label' => esc_html__('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product li.category a, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'category_hover_color',
                [
                    'label' => esc_html__('Hover Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product li.category a:hover, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'category_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product li.category, {{WRAPPER}} .woocommerce ul.products li.product .archive-product-categories a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'title_heading',
                [
                    'label' => __('Title', 'etww'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title',
                ]
        );

        $this->add_control(
                'title_color',
                [
                    'label' => esc_html__('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'title_hover_color',
                [
                    'label' => esc_html__('Hover Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'price_heading',
                [
                    'label' => __('Price', 'etww'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'price_color',
                [
                    'label' => esc_html__('Price Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .price, {{WRAPPER}} .woocommerce ul.products li.product .price .amount' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'price_typography',
                    
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .price, {{WRAPPER}} .woocommerce ul.products li.product .price .amount',
                ]
        );

        $this->add_control(
                'del_price_color',
                [
                    'label' => esc_html__('Del Price Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .price del .amount' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'del_price_typography',
                    
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .price del .amount',
                ]
        );

        $this->add_responsive_control(
                'price_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'rating_heading',
                [
                    'label' => __('Rating', 'etww'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'rating_color',
                [
                    'label' => esc_html__('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .star-rating span::before' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'rating_fill_color',
                [
                    'label' => esc_html__('Fill Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .star-rating::before' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_button_style',
                [
                    'label' => __('Product Button', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .button',
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
                        '{{WRAPPER}} .woocommerce ul.products li.product .button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                    'label' => __('Text Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .button' => 'color: {{VALUE}};',
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
                        '{{WRAPPER}} .woocommerce ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_color',
                [
                    'label' => __('Text Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .button:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => __('Border Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .button:hover' => 'border-color: {{VALUE}};',
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
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .button',
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
                        '{{WRAPPER}} .woocommerce ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .button',
                ]
        );

        $this->add_responsive_control(
                'button_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .woocommerce ul.products li.product .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_badge_style',
                [
                    'label' => __('Product Badge', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'badge_typography',
                    
                    'selector' => '{{WRAPPER}} .woocommerce span.onsale',
                ]
        );

        $this->add_control(
                'badge_background_color',
                [
                    'label' => __('Background Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce span.onsale' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'badge_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce span.onsale' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'badge_border',
                    'placeholder' => '1px',
                    'selector' => '{{WRAPPER}} .woocommerce span.onsale',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'badge_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'badge_box_shadow',
                    'selector' => '{{WRAPPER}} .woocommerce span.onsale',
                ]
        );

        $this->add_responsive_control(
                'badge_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_responsive_control(
                'badge_margin',
                [
                    'label' => __('Margin', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce span.onsale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);
        $layout = $settings['tab_layout'];

        $this->add_render_attribute('wrap', 'class', [
            'etww-tabs',
            'etww-tabs-' . $layout
        ]);

        if (!empty($settings['active_item'])) {
            $data = [$settings['active_item']];
            $this->add_render_attribute('wrap', 'class', 'etww-has-active-item');
            $this->add_render_attribute('wrap', 'data-settings', wp_json_encode($data));
        }

        $this->add_render_attribute('tabs-wrap', 'class', 'etww-tabs-wrap');

        if ('top' == $layout || 'bottom' == $layout) {
            $this->add_render_attribute('tabs-wrap', 'class', [
                'etww-tabs-normal',
                'etww-tabs-' . $settings['align']
            ]);
        }
        ?>

        <div <?php echo $this->get_render_attribute_string('wrap'); ?>>

            <?php if ('bottom' != $layout) { ?>
                <div <?php echo $this->get_render_attribute_string('tabs-wrap'); ?>>
                    <?php
                    foreach ($settings['tabs'] as $index => $item) :
                        $tab_count = $index + 1;
                        $active_item = ($tab_count === $settings['active_item']) ? ' etww-active' : '';
                        $tab_title_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

                        $this->add_render_attribute($tab_title_key, [
                            'id' => 'etww-tab-title-' . $id_int . $tab_count,
                            'class' => ['etww-tab-title', $active_item],
                            'data-tab' => $tab_count,
                            'tabindex' => $id_int . $tab_count,
                            'role' => 'tab',
                            'aria-controls' => 'etww-tab-content-' . $id_int . $tab_count,
                        ]);
                        ?>

                        <div <?php echo $this->get_render_attribute_string($tab_title_key); ?>>
                            <?php

                            if ($item['tab_title']) {
                                echo esc_html($item['tab_title']);
                            }

                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php }
            ?>

            <div class="etww-tabs-content-wrap">
                <?php
                foreach ($settings['tabs'] as $index => $item) :
                    $tab_count = $index + 1;
                    $active_item = ($tab_count === $settings['active_item']) ? ' etww-active' : '';
                    $tab_content_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);
                    $tab_title_mobile_key = $this->get_repeater_setting_key('tab_title_mobile', 'tabs', $tab_count);

                    $this->add_render_attribute($tab_content_key, [
                        'id' => 'etww-tab-content-' . $tab_count,
                        'class' => ['etww-tab-content', $active_item],
                        'role' => 'tabpanel',
                        'aria-labelledby' => 'etww-tab-title-' . $id_int . $tab_count,
                    ]);

                    $this->add_render_attribute($tab_title_mobile_key, [
                        'class' => ['etww-tab-title', 'etww-tab-mobile-title', $active_item],
                        'tabindex' => $id_int . $tab_count,
                        'data-tab' => $tab_count,
                        'role' => 'tab',
                    ]);
                    ?>

                    <div <?php echo $this->get_render_attribute_string($tab_title_mobile_key); ?>>
                        <?php

                        if ($item['tab_title']) {
                            echo esc_html($item['tab_title']);
                        }

                        ?>
                    </div>

                    <div <?php echo $this->get_render_attribute_string($tab_content_key); ?>>
                        <?php
                        if ('custom' == $item['source'] && !empty($item['tab_content'])) {
                            echo $this->parse_text_editor($item['tab_content']);
                        } else if ('template' == $item['source'] && ('0' != $item['templates'] && !empty($item['templates']))) {
                            echo Plugin::instance()->frontend->get_builder_content_for_display($item['templates']);
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ('bottom' == $layout) { ?>
                <div <?php echo $this->get_render_attribute_string('tabs-wrap'); ?>>
                    <?php
                    foreach ($settings['tabs'] as $index => $item) :
                        $tab_count = $index + 1;
                        $active_item = ($tab_count === $settings['active_item']) ? ' etww-active' : '';
                        $tab_title_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

                        $this->add_render_attribute($tab_title_key, [
                            'id' => 'etww-tab-title-' . $id_int . $tab_count,
                            'class' => ['etww-tab-title', $active_item],
                            'data-tab' => $tab_count,
                            'tabindex' => $id_int . $tab_count,
                            'role' => 'tab',
                            'aria-controls' => 'etww-tab-content-' . $id_int . $tab_count,
                        ]);
                        ?>

                        <div <?php echo $this->get_render_attribute_string($tab_title_key); ?>>
                            <?php
                            if ($item['tab_title']) {
                                echo esc_html($item['tab_title']);
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php }
            ?>

        </div>

        <?php
    }

}
