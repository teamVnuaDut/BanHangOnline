<?php

/**
 * Elementor AWS Widget.
 *
 * Elementor widget  to add AWS search form
 *
 * @since 1.0.0
 */
class Elementor_AWS_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aws';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Advanced Woo Search', 'advanced-woo-search' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'aws-elementor-icon';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return array( 'general', 'woocommerce-elements' );
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            array(
                'label' => __( 'Content', 'advanced-woo-search' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'placeholder',
            array(
                'label' => __( 'Placeholder', 'advanced-woo-search' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => '',
            )
        );

        $this->end_controls_section();

    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        if ( function_exists( 'aws_get_search_form' ) ) {
            $args = $settings['placeholder'] ? array( 'placeholder' => $settings['placeholder'] ) : array();
            $search_form = aws_get_search_form( false, $args );
            echo $search_form;
        }

    }

}