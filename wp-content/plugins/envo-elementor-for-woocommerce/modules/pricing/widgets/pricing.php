<?php

namespace ETWWElementor\Modules\Pricing\Widgets;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Pricing extends Widget_Base {

    public function get_name() {
        return 'etww-pricing';
    }

    public function get_title() {
        return __('Price Table', 'etww');
    }

    public function get_icon() {

        return 'etww-icon eicon-price-table';
    }

    public function get_categories() {
        return ['etww-elements'];
    }

    public function get_style_depends() {
        return ['etww-pricing'];
    }

    protected function register_controls() {

        $this->start_controls_section(
                'section_pricing',
                [
                    'label' => __('Price Table', 'etww'),
                ]
        );

        $this->add_control(
                'featured',
                [
                    'label' => __('Featured', 'etww'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'no',
                    'options' => [
                        'no' => __('No', 'etww'),
                        'yes' => __('Yes', 'etww'),
                    ],
                ]
        );

        $this->add_control(
                'plan',
                [
                    'label' => __('Plan', 'etww'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Standard', 'etww'),
                    'label_block' => true,
                    'dynamic' => ['active' => true],
                ]
        );

        $this->add_control(
                'cost',
                [
                    'label' => __('Cost', 'etww'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '$29',
                    'label_block' => true,
                    'dynamic' => ['active' => true],
                ]
        );

        $this->add_control(
                'per',
                [
                    'label' => __('Per', 'etww'),
                    'type' => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic' => ['active' => true],
                ]
        );

        $this->add_control(
                'content',
                [
                    'label' => __('Features', 'etww'),
                    'type' => Controls_Manager::TEXTAREA,
                    'default' => '<ul>
							<li>1 Website</li>
							<li class="etww-even">20GB Disk Space</li>
							<li>SSD Included FREE</li>
							<li class="etww-even">E-Commerce Ready</li>
							<li>Unlimited Bandwidth</li>
						</ul>',
                    'dynamic' => ['active' => true],
                ]
        );

        $this->add_control(
                'button_url',
                [
                    'label' => __('Button URL', 'etww'),
                    'type' => Controls_Manager::URL,
                    'placeholder' => 'http://your-link.com',
                    'default' => [
                        'url' => '#',
                    ],
                ]
        );

        $this->add_control(
                'button_text',
                [
                    'label' => __('Button Text', 'etww'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Subscribe Now', 'etww'),
                    'label_block' => true,
                    'dynamic' => ['active' => true],
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_plan',
                [
                    'label' => __('Plan', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'plan_background',
                [
                    'label' => __('Background', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-header' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'plan_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-header' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'plan_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'plan_border',
                    'label' => __('Border', 'etww'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-pricing-header',
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'plan_typo',
                    'selector' => '{{WRAPPER}} .etww-pricing-header',

                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_cost',
                [
                    'label' => __('Cost', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'cost_background',
                [
                    'label' => __('Background', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-cost' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'cost_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-cost .etww-pricing-amount' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'cost_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-cost' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cost_border',
                    'label' => __('Border', 'etww'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-pricing-cost',
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cost_typo',
                    'selector' => '{{WRAPPER}} .etww-pricing-cost .etww-pricing-amount',
                    
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_per',
                [
                    'label' => __('Per', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'per_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-per' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'per_typo',
                    'selector' => '{{WRAPPER}} .etww-pricing-per',
                    
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_features',
                [
                    'label' => __('Features', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'features_bg',
                [
                    'label' => __('Background', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-content' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'features_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-content' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'features_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'features_border',
                    'label' => __('Border', 'etww'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-pricing-content',
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'features_typo',
                    'selector' => '{{WRAPPER}} .etww-pricing-content',
                    
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_button',
                [
                    'label' => __('Button', 'etww'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'wrap_button_bg',
                [
                    'label' => __('Wrap Background', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'wrap_button_padding',
                [
                    'label' => __('Wrap Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'wrap_button_border',
                    'label' => __('Wrap Border', 'etww'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .etww-pricing-button',
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
                'button_bg',
                [
                    'label' => __('Background', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button .button' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'button_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button .button' => 'color: {{VALUE}};',
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
                'button_hover_bg',
                [
                    'label' => __('Background', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button .button:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_color',
                [
                    'label' => __('Color', 'etww'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button .button:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
                'button_padding',
                [
                    'label' => __('Padding', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'button_border_radius',
                [
                    'label' => __('Border Radius', 'etww'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .etww-pricing-button .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typo',
                    'selector' => '{{WRAPPER}} .etww-pricing-button .button',
                    
                ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Vars
        $plan = $settings['plan'];
        $cost = $settings['cost'];
        $per = $settings['per'];
        $content = $settings['content'];
        $btn_url = $settings['button_url']['url'];
        $btn_text = $settings['button_text'];

        // Wrapper classes
        $featured_class = '';
        if ('yes' == $settings['featured']) {
            $featured_class = ' featured';
        }

        // Button target
        if (!empty($settings['button_url']['is_external'])) {
            $btn_target = 'blank';
        } else {
            $btn_target = 'self';
        }
        ?>

        <div class="etww-pricing clr<?php echo esc_attr($featured_class); ?>">

            <?php
            // Display plan
            if ($plan) {
                ?>

                <div class="etww-pricing-header clr"><?php echo do_shortcode($plan); ?></div>

            <?php } ?>

            <?php
            // Display cost
            if ($cost) {
                ?>

                <div class="etww-pricing-cost clr">

                    <div class="etww-pricing-amount"><?php echo esc_attr($cost); ?></div>

                    <?php if ($per) { ?>
                        <div class="etww-pricing-per"><?php echo esc_attr($per); ?></div>
                    <?php } ?>

                </div>

            <?php } ?>

            <?php
            // Display content
            if ($content) {
                ?>

                <div class="etww-pricing-content clr"><?php echo $this->parse_text_editor($content); ?></div>

            <?php } ?>

            <?php
            // Display button
            if ($btn_url) {
                ?>

                <div class="etww-pricing-button clr">

                    <a href="<?php echo esc_url($btn_url); ?>" title="<?php echo esc_attr($btn_text); ?>" class="button" target="_<?php echo esc_attr($btn_target); ?>"><?php echo esc_attr($btn_text); ?></a>

                </div>

            <?php } ?>

        </div><!-- .etww-pricing -->

        <?php
    }

    protected function content_template() {
        ?>
        <#
        var featured_class = '',
        btn_target = '';

        if('yes' === settings.featured) {
        featured_class = ' featured';
        }
        #>

        <div class="etww-pricing clr{{ featured_class }}">

            <# if(settings.plan) { #>

            <div class="etww-pricing-header clr">{{{ settings.plan }}}</div>

            <# } #>

            <# if(settings.cost) { #>

            <div class="etww-pricing-cost clr">

                <div class="etww-pricing-amount">{{{ settings.cost }}}</div>

                <# if(settings.per) { #>
                <div class="etww-pricing-per">{{{ settings.per }}}</div>
                <# } #>

            </div>

            <# } #>

            <# if(settings.content) { #>

            <div class="etww-pricing-content clr">{{{ settings.content }}}</div>

            <# } #>

            <# if(settings.button_url.url) { #>

            <div class="etww-pricing-button clr">

                <a href="{{ settings.button_url.url }}" title="{{ settings.button_text }}" class="button">{{{ settings.button_text }}}</a>

            </div>

            <# } #>

        </div><!-- .etww-pricing -->
        <?php
    }

}
