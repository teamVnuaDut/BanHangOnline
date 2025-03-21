<?php

namespace ETWWElementor\Modules\Woocommerce\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

class Woo_Products extends Widget_Base {

    private $query = null;

    public function get_name() {
        return 'etww-woo-products';
    }

    public function get_title() {
        return __('Woo - Products', 'etww');
    }

    public function get_icon() {

        return 'etww-icon eicon-woocommerce';
    }

    public function get_categories() {
        return ['etww-elements'];
    }

    public function get_query() {
        return $this->query;
    }

    protected function register_controls() {

        $this->start_controls_section(
                'section_woo_products',
                [
                    'label' => __('Products', 'etww'),
                ]
        );

        $this->add_control(
                'columns',
                [
                    'label' => __('Columns', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                    ],
                ]
        );

        $this->add_control(
                'posts_per_page',
                [
                    'label' => __('Products Count', 'etww'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '4',
                ]
        );

        $this->add_control(
                'pagination',
                [
                    'label' => __('Pagination', 'etww'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                ]
        );

        $this->add_control(
                'pagination_position',
                [
                    'label' => __('Pagination Position', 'etww'),
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
                    ],
                    'selectors' => [
                        '{{WRAPPER}} ul.page-numbers' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                    'condition' => [
                        'pagination' => 'yes',
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_filter',
                [
                    'label' => __('Query', 'etww'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
                'query_type',
                [
                    'label' => __('Source', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'all',
                    'options' => [
                        'all' => __('All Products', 'etww'),
                        'custom' => __('Custom Query', 'etww'),
                        'manual' => __('Manual Selection', 'etww'),
                    ],
                ]
        );

        $this->add_control(
                'category_filter_rule',
                [
                    'label' => __('Cat Filter Rule', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'IN',
                    'options' => [
                        'IN' => __('Match Categories', 'etww'),
                        'NOT IN' => __('Exclude Categories', 'etww'),
                    ],
                    'condition' => [
                        'query_type' => 'custom',
                    ],
                ]
        );

        $this->add_control(
                'category_filter',
                [
                    'label' => __('Select Categories', 'etww'),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'default' => '',
                    'options' => $this->get_product_categories(),
                    'condition' => [
                        'query_type' => 'custom',
                    ],
                ]
        );

        $this->add_control(
                'tag_filter_rule',
                [
                    'label' => __('Tag Filter Rule', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'IN',
                    'options' => [
                        'IN' => __('Match Tags', 'etww'),
                        'NOT IN' => __('Exclude Tags', 'etww'),
                    ],
                    'condition' => [
                        'query_type' => 'custom',
                    ],
                ]
        );

        $this->add_control(
                'tag_filter',
                [
                    'label' => __('Select Tags', 'etww'),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'default' => '',
                    'options' => $this->get_product_tags(),
                    'condition' => [
                        'query_type' => 'custom',
                    ],
                ]
        );

        $this->add_control(
                'offset',
                [
                    'label' => __('Offset', 'etww'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0,
                    'description' => __('Number of post to displace or pass over.', 'etww'),
                    'condition' => [
                        'query_type' => 'custom',
                    ],
                ]
        );

        $this->add_control(
                'query_manual_ids',
                [
                    'label' => __('Select Products', 'etww'),
                    'type' => 'etww-query-posts',
                    'post_type' => 'product',
                    'multiple' => true,
                    'condition' => [
                        'query_type' => 'manual',
                    ],
                ]
        );

        $this->add_control(
                'query_exclude',
                [
                    'label' => __('Exclude', 'etww'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => [
                        'query_type!' => 'manual',
                    ],
                ]
        );

        $this->add_control(
                'query_exclude_ids',
                [
                    'label' => __('Select Products', 'etww'),
                    'type' => 'etww-query-posts',
                    'post_type' => 'product',
                    'multiple' => true,
                    'description' => __('Select products to exclude from the query.', 'etww'),
                    'condition' => [
                        'query_type!' => 'manual',
                    ],
                ]
        );

        $this->add_control(
                'query_exclude_current',
                [
                    'label' => __('Exclude Current Product', 'etww'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'etww'),
                    'label_off' => __('No', 'etww'),
                    'return_value' => 'yes',
                    'default' => '',
                    'description' => __('Enable this option to remove current product from the query.', 'etww'),
                    'condition' => [
                        'query_type!' => 'manual',
                    ],
                ]
        );

        $this->add_control(
                'advanced',
                [
                    'label' => __('Advanced', 'etww'),
                    'type' => Controls_Manager::HEADING,
                ]
        );

        $this->add_control(
                'filter_by',
                [
                    'label' => __('Filter By', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __('None', 'etww'),
                        'featured' => __('Featured', 'etww'),
                        'sale' => __('Sale', 'etww'),
                    ],
                ]
        );

        $this->add_control(
                'orderby',
                [
                    'label' => __('Order by', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => [
                        'date' => __('Date', 'etww'),
                        'title' => __('Title', 'etww'),
                        'price' => __('Price', 'etww'),
                        'popularity' => __('Popularity', 'etww'),
                        'rating' => __('Rating', 'etww'),
                        'rand' => __('Random', 'etww'),
                        'menu_order' => __('Menu Order', 'etww'),
                    ],
                ]
        );

        $this->add_control(
                'order',
                [
                    'label' => __('Order', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                        'asc' => __('ASC', 'etww'),
                        'desc' => __('DESC', 'etww'),
                    ],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_item_style',
                [
                    'label' => __('Item', 'etww'),
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
                    'label' => __('Image', 'etww'),
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
                'section_content_style',
                [
                    'label' => __('Content', 'etww'),
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
                    'label' => __('Button', 'etww'),
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
                    'label' => __('Badge', 'etww'),
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

    protected function get_product_categories() {

        $product_cat = array();

        $cat_args = array(
            'orderby' => 'name',
            'order' => 'asc',
            'hide_empty' => false,
        );

        $product_categories = get_terms('product_cat', $cat_args);

        if (!empty($product_categories)) {
            foreach ($product_categories as $key => $category) {
                $product_cat[$category->slug] = $category->name;
            }
        }

        return $product_cat;
    }

    protected function get_product_tags() {

        $product_tag = array();

        $tag_args = array(
            'orderby' => 'name',
            'order' => 'asc',
            'hide_empty' => false,
        );

        $product_tag = get_terms('product_tag', $tag_args);

        if (!empty($product_tag)) {
            foreach ($product_tag as $key => $tag) {
                $product_tag[$tag->slug] = $tag->name;
            }
        }

        return $product_tag;
    }

    public function query_posts() {
        $settings = $this->get_settings();

        global $post;

        $query_args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'post__not_in' => array(),
        ];

        // Default ordering args.
        $ordering_args = WC()->query->get_catalog_ordering_args($settings['orderby'], $settings['order']);

        $query_args['orderby'] = $ordering_args['orderby'];
        $query_args['order'] = $ordering_args['order'];

        if ('sale' === $settings['filter_by']) {
            $query_args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
        } elseif ('featured' === $settings['filter_by']) {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();

            $query_args['tax_query'][] = [
                'taxonomy' => 'product_visibility',
                'field' => 'term_taxonomy_id',
                'terms' => $product_visibility_term_ids['featured'],
            ];
        }

        if ('custom' === $settings['query_type']) {
            if (!empty($settings['category_filter'])) {
                $cat_operator = $settings['category_filter_rule'];

                $query_args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $settings['category_filter'],
                    'operator' => $cat_operator,
                ];
            }

            if (!empty($settings['tag_filter'])) {
                $tag_operator = $settings['tag_filter_rule'];

                $query_args['tax_query'][] = [
                    'taxonomy' => 'product_tag',
                    'field' => 'slug',
                    'terms' => $settings['tag_filter'],
                    'operator' => $tag_operator,
                ];
            }

            if (0 < $settings['offset']) {
                $query_args['offset_to_fix'] = $settings['offset'];
            }
        }

        if ('manual' === $settings['query_type']) {
            $manual_ids = $settings['query_manual_ids'];
            $query_args['post__in'] = $manual_ids;
        }

        if ('manual' !== $settings['query_type']) {
            if ('' !== $settings['query_exclude_ids']) {
                $exclude_ids = $settings['query_exclude_ids'];
                $query_args['post__not_in'] = $exclude_ids;
            }

            if ('yes' === $settings['query_exclude_current']) {
                $query_args['post__not_in'][] = $post->ID;
            }
        }

        if ('yes' === $settings['pagination']) {

            // Paged
            global $paged;
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } else if (get_query_var('page')) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }

            $query_args['posts_per_page'] = $settings['posts_per_page'];

            if (1 < $paged) {
                $query_args['paged'] = $paged;
            }
        }

        $this->query = new \WP_Query($query_args);
    }

    public function render() {
        $settings = $this->get_settings();

        $this->query_posts();

        $query = $this->get_query();

        if (!$query->have_posts()) {
            return;
        }

        global $woocommerce_loop;

        $woocommerce_loop['columns'] = (int) $settings['columns'];

        echo '<div class="woocommerce columns-' . esc_attr($woocommerce_loop['columns']) . '">';

        woocommerce_product_loop_start();

        while ($query->have_posts()) : $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;

        woocommerce_product_loop_end();

        // Display pagination if enabled
        if ('yes' == $settings['pagination']) {
            etww_pagination($query);
        }

        woocommerce_reset_loop();

        wp_reset_postdata();

        echo '</div>';
    }

}
