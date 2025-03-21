<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
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
class Pricing extends Widget_Base {

	private static function get_currency_symbol( $symbol_name ) {
		$symbols = array(
			'dollar'		 => '&#36;',
			'baht'			 => '&#3647;',
			'bdt'			 => '&#2547;',
			'euro'			 => '&#128;',
			'franc'			 => '&#8355;',
			'guilder'		 => '&fnof;',
			'indian_rupee'	 => '&#8377;',
			'pound'			 => '&#163;',
			'peso'			 => '&#8369;',
			'peseta'		 => '&#8359',
			'lira'			 => '&#8356;',
			'ruble'			 => '&#8381;',
			'shekel'		 => '&#8362;',
			'rupee'			 => '&#8360;',
			'real'			 => 'R$',
			'krona'			 => 'kr',
			'won'			 => '&#8361;',
			'yen'			 => '&#165;',
		);

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

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
		return 'envo-extra-pricing';
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
		return __( 'Pricing', 'envo-extra' );
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
		return 'eicon-price-table';
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
		return array( 'pricing', 'price', 'card', 'table' );
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

		return array( 'envo-extra-pricing' );
	}

	/**
	 * Register Pricing widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
		'section_header', array(
			'label' => __( 'Header', 'envo-extra' ),
		)
		);

		$this->add_control(
		'title', array(
			'label'			 => __( 'Title', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'label_block'	 => false,
			'default'		 => __( 'Basic', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'media_type', array(
			'label'			 => __( 'Media Type', 'envo-extra' ),
			'type'			 => Controls_Manager::CHOOSE,
			'label_block'	 => false,
			'options'		 => array(
				'icon'	 => array(
					'title'	 => __( 'Icon', 'envo-extra' ),
					'icon'	 => 'eicon-star-o',
				),
				'image'	 => array(
					'title'	 => __( 'Image', 'envo-extra' ),
					'icon'	 => 'eicon-image',
				),
			),
			'default'		 => 'icon',
			'toggle'		 => false,
		)
		);

		$this->add_control(
		'icon', array(
			'label'		 => __( 'Icon', 'envo-extra' ),
			'type'		 => Controls_Manager::ICONS,
			'default'	 => array(
				'value'		 => 'far fa-clone',
				'library'	 => 'regular',
			),
			'condition'	 => array(
				'media_type' => 'icon',
			),
		)
		);

		$this->add_control(
		'image', array(
			'label'		 => __( 'Image', 'envo-extra' ),
			'type'		 => Controls_Manager::MEDIA,
			'default'	 => array(
				'url' => Utils::get_placeholder_image_src(),
			),
			'condition'	 => array(
				'media_type' => 'image',
			),
			'dynamic'	 => array(
				'active' => true,
			),
		)
		);

		$this->add_group_control(
		Group_Control_Image_Size::get_type(), array(
			'name'		 => 'media_thumbnail',
			'default'	 => 'full',
			'separator'	 => 'none',
			'exclude'	 => array(
				'custom',
			),
			'condition'	 => array(
				'media_type' => 'image',
			),
		)
		);

		$this->add_control(
		'media_position', array(
			'label'		 => __( 'Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'before_header',
			'options'	 => array(
				'after_header'	 => __( 'After Title', 'envo-extra' ),
				'before_header'	 => __( 'Before Title', 'envo-extra' ),
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_price', array(
			'label' => __( 'Price', 'envo-extra' ),
		)
		);

		$this->add_control(
		'currency', array(
			'label'			 => __( 'Currency', 'envo-extra' ),
			'type'			 => Controls_Manager::SELECT,
			'label_block'	 => false,
			'options'		 => array(
				''				 => __( 'None', 'envo-extra' ),
				'dollar'		 => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'envo-extra' ),
				'baht'			 => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'envo-extra' ),
				'bdt'			 => '&#2547; ' . _x( 'BD Taka', 'Currency Symbol', 'envo-extra' ),
				'euro'			 => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'envo-extra' ),
				'franc'			 => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'envo-extra' ),
				'guilder'		 => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'envo-extra' ),
				'krona'			 => 'kr ' . _x( 'Krona', 'Currency Symbol', 'envo-extra' ),
				'lira'			 => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'envo-extra' ),
				'peseta'		 => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'envo-extra' ),
				'peso'			 => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'envo-extra' ),
				'pound'			 => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'envo-extra' ),
				'real'			 => 'R$ ' . _x( 'Real', 'Currency Symbol', 'envo-extra' ),
				'ruble'			 => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'envo-extra' ),
				'rupee'			 => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'envo-extra' ),
				'indian_rupee'	 => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'envo-extra' ),
				'shekel'		 => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'envo-extra' ),
				'won'			 => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'envo-extra' ),
				'yen'			 => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'envo-extra' ),
				'custom'		 => __( 'Custom', 'envo-extra' ),
			),
			'default'		 => 'dollar',
		)
		);

		$this->add_control(
		'currency_custom', array(
			'label'		 => __( 'Custom Symbol', 'envo-extra' ),
			'type'		 => Controls_Manager::TEXT,
			'condition'	 => array(
				'currency' => 'custom',
			),
		)
		);

		$this->add_control(
		'price', array(
			'label'		 => __( 'Price', 'envo-extra' ),
			'type'		 => Controls_Manager::TEXT,
			'default'	 => '9.99',
			'dynamic'	 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'period', array(
			'label'		 => __( 'Period', 'envo-extra' ),
			'type'		 => Controls_Manager::TEXT,
			'default'	 => __( 'Per Month', 'envo-extra' ),
			'dynamic'	 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'price_position', array(
			'label'		 => __( 'Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'before_features',
			'options'	 => array(
				'before_features'	 => __( 'Before Features', 'envo-extra' ),
				'after_features'	 => __( 'After Features', 'envo-extra' ),
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_features', array(
			'label' => __( 'Features', 'envo-extra' ),
		)
		);

		$this->add_control(
		'show_feature', array(
			'label'			 => __( 'Show', 'envo-extra' ),
			'type'			 => Controls_Manager::SWITCHER,
			'label_on'		 => __( 'Show', 'envo-extra' ),
			'label_off'		 => __( 'Hide', 'envo-extra' ),
			'return_value'	 => 'yes',
			'default'		 => 'yes',
		)
		);

		$this->add_control(
		'features_title', array(
			'label'		 => __( 'Title', 'envo-extra' ),
			'type'		 => Controls_Manager::TEXT,
			'default'	 => __( 'Features', 'envo-extra' ),
			'dynamic'	 => array(
				'active' => true,
			),
			'condition'	 => array(
				'show_feature' => 'yes',
			),
		)
		);

		$repeater = new Repeater();

		$repeater->add_control(
		'icon', array(
			'label'			 => __( 'Icon', 'envo-extra' ),
			'type'			 => Controls_Manager::ICONS,
			'default'		 => array(
				'value'		 => 'fas fa-check',
				'library'	 => 'fa-solid',
			),
			'recommended'	 => array(
				'fa-solid' => array(
					'check',
					'check-circle',
					'times',
					'times-circle',
				),
			),
		)
		);

		$repeater->add_control(
		'title_text', array(
			'label'			 => __( 'Title', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'placeholder'	 => __( 'Type list item content.', 'envo-extra' ),
			'label_block'	 => true,
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$repeater->add_control(
		'tooltip_text', array(
			'label'			 => __( 'Tooltip Text', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXTAREA,
			'rows'			 => 3,
			'placeholder'	 => __( 'Type tooltip text here.', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$repeater->add_control(
		'status', array(
			'label'		 => __( 'Status', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'active',
			'options'	 => array(
				'active'	 => __( 'Active', 'envo-extra' ),
				'inactive'	 => __( 'Inactive', 'envo-extra' ),
			),
		)
		);

		$this->add_control(
		'feature_items', array(
			'type'			 => Controls_Manager::REPEATER,
			'fields'		 => $repeater->get_controls(),
			'show_label'	 => false,
			'title_field'	 => sprintf(
			/* translators: %s: Title */
			__( 'Item: %1$s', 'envo-extra' ), '{{title_text}}'
			),
			'render_type'	 => 'template',
			'default'		 => array(
				array(
					'icon'		 => array(
						'value'		 => 'fas fa-check',
						'library'	 => 'fa-solid',
					),
					'title_text' => __( 'Feature List 1', 'envo-extra' ),
					'status'	 => 'active',
				),
				array(
					'icon'			 => array(
						'value'		 => 'fas fa-check',
						'library'	 => 'fa-solid',
					),
					'title_text'	 => __( 'Feature List 2', 'envo-extra' ),
					'tooltip_text'	 => __( 'Tooltip Text Here', 'envo-extra' ),
					'status'		 => 'active',
				),
				array(
					'icon'		 => array(
						'value'		 => 'fas fa-times',
						'library'	 => 'fa-solid',
					),
					'title_text' => __( 'Feature List 3', 'envo-extra' ),
					'status'	 => 'inactive',
				),
				array(
					'icon'		 => array(
						'value'		 => 'fas fa-times',
						'library'	 => 'fa-solid',
					),
					'title_text' => __( 'Feature List 4', 'envo-extra' ),
					'status'	 => 'inactive',
				),
			),
			'condition'		 => array(
				'show_feature' => 'yes',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_description', array(
			'label' => __( 'Description', 'envo-extra' ),
		)
		);

		$this->add_control(
		'item_description', array(
			'label'		 => '',
			'type'		 => Controls_Manager::WYSIWYG,
			'default'	 => __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'envo-extra' ),
		)
		);

		$this->add_control(
		'description_position', array(
			'label'		 => __( 'Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'before_features',
			'options'	 => array(
				'before_features'	 => __( 'Before Features', 'envo-extra' ),
				'after_features'	 => __( 'After Features', 'envo-extra' ),
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_button', array(
			'label' => __( 'Button', 'envo-extra' ),
		)
		);

		$this->add_control(
		'button_title', array(
			'label'			 => __( 'Title', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'label_block'	 => false,
			'default'		 => __( 'Get Started', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'button_link', array(
			'label'			 => __( 'Link', 'envo-extra' ),
			'type'			 => Controls_Manager::URL,
			'label_block'	 => true,
			'placeholder'	 => 'https://yoursite.com/',
			'default'		 => array(
				'url' => '#',
			),
			'dynamic'		 => array(
				'active' => true,
			),
		)
		);

		$this->add_control(
		'button_position', array(
			'label'		 => __( 'Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'after_features',
			'options'	 => array(
				'before_features'	 => __( 'Before Features', 'envo-extra' ),
				'after_features'	 => __( 'After Features', 'envo-extra' ),
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_badge', array(
			'label' => __( 'Badge', 'envo-extra' ),
		)
		);

		$this->add_control(
		'show_badge', array(
			'label'			 => __( 'Show', 'envo-extra' ),
			'type'			 => Controls_Manager::SWITCHER,
			'label_on'		 => __( 'Show', 'envo-extra' ),
			'label_off'		 => __( 'Hide', 'envo-extra' ),
			'return_value'	 => 'yes',
			'default'		 => 'yes',
		)
		);

		$this->add_control(
		'badge_text', array(
			'label'			 => __( 'Text', 'envo-extra' ),
			'type'			 => Controls_Manager::TEXT,
			'label_block'	 => false,
			'default'		 => __( 'Premium', 'envo-extra' ),
			'dynamic'		 => array(
				'active' => true,
			),
			'condition'		 => array(
				'show_badge' => 'yes',
			),
		)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
		'section_style_general', array(
			'label'	 => __( 'General', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_control(
		'align', array(
			'label'			 => __( 'Alignment', 'envo-extra' ),
			'type'			 => Controls_Manager::CHOOSE,
			'options'		 => array(
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
			'prefix_class'	 => 'envo-extra-pricing-align-',
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-pricing-item' => 'text-align: {{VALUE}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style_header', array(
			'label'	 => __( 'Header', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'header_title_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-title',
		)
		);

		$this->add_control(
		'header_title_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-title' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'header_title_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-title',
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'header_title_border',
			'label'		 => __( 'Border', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-title',
		)
		);

		$this->add_control(
		'header_title_display', array(
			'label'		 => __( 'Display', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'default'	 => 'block',
			'options'	 => array(
				'block'			 => array(
					'title'	 => __( 'Block', 'envo-extra' ),
					'icon'	 => 'eicon-menu-bar',
				),
				'inline-block'	 => array(
					'title'	 => __( 'Inline', 'envo-extra' ),
					'icon'	 => 'eicon-ellipsis-h',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-title' => 'display: {{VALUE}};',
			),
		)
		);

		$this->add_responsive_control(
		'header_title_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'header_title_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'header_media', array(
			'label'		 => __( 'Media', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
		)
		);

		$this->add_control(
		'header_media_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'condition'	 => array(
				'media_type' => 'icon',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-icon > i'	 => 'color: {{VALUE}}',
				'{{WRAPPER}} .envo-extra-pricing-icon > svg' => 'fill: {{VALUE}}',
			),
		)
		);

		$this->add_responsive_control(
		'header_media_size', array(
			'label'		 => __( 'Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 1000,
					'step'	 => 1,
				),
				'%'	 => array(
					'min'	 => 0,
					'max'	 => 100,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 40,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-icon > i'	 => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-pricing-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .envo-extra-pricing-media img'	 => 'width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'image_height', array(
			'label'			 => __( 'Height', 'envo-extra' ),
			'type'			 => Controls_Manager::SLIDER,
			'default'		 => array(
				'unit' => 'px',
			),
			'tablet_default' => array(
				'unit' => 'px',
			),
			'mobile_default' => array(
				'unit' => 'px',
			),
			'size_units'	 => array( 'px', 'vh' ),
			'range'			 => array(
				'px' => array(
					'min'	 => 1,
					'max'	 => 500,
				),
				'vh' => array(
					'min'	 => 1,
					'max'	 => 100,
				),
			),
			'condition'		 => array(
				'media_type' => 'image',
			),
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-pricing-media img' => 'height: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'object-fit', array(
			'label'		 => __( 'Object Fit', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'options'	 => array(
				''			 => __( 'Default', 'envo-extra' ),
				'fill'		 => __( 'Fill', 'envo-extra' ),
				'cover'		 => __( 'Cover', 'envo-extra' ),
				'contain'	 => __( 'Contain', 'envo-extra' ),
			),
			'default'	 => '',
			'condition'	 => array(
				'media_type'			 => 'image',
				'image_height[size]!'	 => '',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-media img' => 'object-fit: {{VALUE}};',
			),
		)
		);

		$this->add_responsive_control(
		'header_media_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-icon, {{WRAPPER}} .envo-extra-pricing-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style_price', array(
			'label'	 => __( 'Price', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_control(
		'price_style', array(
			'label'		 => __( 'Layout', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'default'	 => '2',
			'options'	 => array(
				'1'	 => array(
					'title'	 => __( 'Block', 'envo-extra' ),
					'icon'	 => 'eicon-menu-bar',
				),
				'2'	 => array(
					'title'	 => __( 'Inline', 'envo-extra' ),
					'icon'	 => 'eicon-ellipsis-h',
				),
			),
		)
		);

		$this->add_control(
		'price_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-price-tag' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'price_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-price-tag',
		)
		);

		$this->add_responsive_control(
		'price_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-price-tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'price_currency_title', array(
			'label'		 => __( 'Currency', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
		)
		);

		$this->add_control(
		'price_currency_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-currency' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'price_currency_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-currency',
		)
		);

		$this->add_responsive_control(
		'price_currency_vertical_offset', array(
			'label'		 => __( 'Vertical Offset', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'default'	 => array(
				'unit' => 'px',
			),
			'range'		 => array(
				'px' => array(
					'min'	 => - 50,
					'max'	 => 50,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-currency' => 'transform: translateY({{SIZE}}{{UNIT}});',
			),
		)
		);

		$this->add_responsive_control(
		'price_currency_space_between', array(
			'label'		 => __( 'Space Between', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'default'	 => array(
				'unit' => 'px',
			),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 50,
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-currency' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'price_period_title', array(
			'label'		 => __( 'Period', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
		)
		);

		$this->add_control(
		'price_period_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-price-period' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'price_period_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-price-period',
		)
		);

		$this->add_responsive_control(
		'price_period_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-price-period' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_style_features', array(
			'label'		 => __( 'Features', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'show_feature' => 'yes',
			),
		)
		);

		$this->add_responsive_control(
		'features_margin', array(
			'label'		 => __( 'Wrapper Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'features_title_heading', array(
			'label'		 => __( 'Title', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
		)
		);

		$this->add_control(
		'features_title_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-title' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'features_title_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-features-title',
		)
		);

		$this->add_responsive_control(
		'features_title_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'features_list_heading', array(
			'label'		 => __( 'Feature List', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
		)
		);

		$this->add_responsive_control(
		'features_icon_size', array(
			'label'		 => __( 'Icon Size', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 100,
					'step'	 => 1,
				),
				'%'	 => array(
					'min'	 => 0,
					'max'	 => 100,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 14,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'features_icon_space', array(
			'label'		 => __( 'Icon Space', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 100,
					'step'	 => 1,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 10,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-feature-icon'									 => 'margin:0 {{SIZE}}{{UNIT}} 0 0;',
				'{{WRAPPER}}.envo-extra-pricing-align-right .envo-extra-pricing-feature-icon'	 => 'margin:0 0 0 {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'features_list_align', array(
			'label'		 => __( 'Content Align', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'options'	 => array(
				'flex-start' => array(
					'title'	 => __( 'Left', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-left',
				),
				'center'	 => array(
					'title'	 => __( 'Center', 'envo-extra' ),
					'icon'	 => 'eicon-h-align-center',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-list li' => 'justify-content: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'features_list_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-features-list li',
		)
		);

		$this->add_responsive_control(
		'features_list_space_between', array(
			'label'		 => __( 'Space between', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 100,
					'step'	 => 1,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 15,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-list li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->start_controls_tabs( 'features_list_tab' );

		$this->start_controls_tab(
		'features_list_active', array(
			'label' => __( 'Active', 'envo-extra' ),
		)
		);

		$this->add_control(
		'features_list_active_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-list li.active' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_control(
		'features_list_active_icon_color', array(
			'label'		 => __( 'Icon Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-list li.active .envo-extra-pricing-feature-icon' => 'color: {{VALUE}}',
			),
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'features_list_inactive', array(
			'label' => __( 'Inactive', 'envo-extra' ),
		)
		);

		$this->add_control(
		'features_list_inactive_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-list li.inactive' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_control(
		'features_list_inactive_icon_color', array(
			'label'		 => __( 'Icon Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-features-list li.inactive .envo-extra-pricing-feature-icon' => 'color: {{VALUE}}',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
		'features_list_tooltip', array(
			'label'		 => __( 'Tooltip', 'envo-extra' ),
			'type'		 => Controls_Manager::HEADING,
			'separator'	 => 'before',
		)
		);

		$this->add_control(
		'features_list_tooltip_color', array(
			'label'		 => __( 'Icon Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip-toggle' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_control(
		'features_list_tooltip_bg', array(
			'label'		 => __( 'Icon Background', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip-toggle' => 'background-color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'features_tooltip_typography',
			'label'		 => __( 'Content Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip',
		)
		);

		$this->add_responsive_control(
		'features_list_tooltip_width', array(
			'label'		 => __( 'Width', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 1000,
					'step'	 => 1,
				),
				'%'	 => array(
					'min'	 => 0,
					'max'	 => 100,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 200,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip' => 'width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_control(
		'features_list_tooltip_content_color', array(
			'label'		 => __( 'Content Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_control(
		'features_list_tooltip_content_bg', array(
			'label'		 => __( 'Content Background', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip'			 => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip::after'	 => 'border-color: transparent {{VALUE}} transparent transparent;',
			),
		)
		);

		$this->add_responsive_control(
		'features_list_icon_tooltip_padding', array(
			'label'		 => __( 'Content Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-pricing-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_description_style', array(
			'label'	 => __( 'Description', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_control(
		'features_description_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-description,{{WRAPPER}} .envo-extra-pricing-description > *' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'description_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-description, {{WRAPPER}} .envo-extra-pricing-description > *',
		)
		);

		$this->add_responsive_control(
		'description_width', array(
			'label'		 => __( 'Max Width', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 1000,
					'step'	 => 1,
				),
				'%'	 => array(
					'min'	 => 0,
					'max'	 => 100,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 400,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-description' => 'max-width: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'description_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-description-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_separator_style', array(
			'label'	 => __( 'Separator', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_control(
		'show_separator', array(
			'label'			 => __( 'Show', 'envo-extra' ),
			'type'			 => Controls_Manager::SWITCHER,
			'label_on'		 => __( 'Show', 'envo-extra' ),
			'label_off'		 => __( 'Hide', 'envo-extra' ),
			'return_value'	 => 'yes',
		)
		);

		$this->add_control(
		'separator_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-separator:before' => 'border-color: {{VALUE}}',
			),
			'condition'	 => array(
				'show_separator' => 'yes',
			),
		)
		);

		$this->add_control(
		'separator_style', array(
			'label'		 => __( 'Style', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => 'solid',
			'options'	 => array(
				'solid'	 => __( 'Solid', 'envo-extra' ),
				'double' => __( 'Double', 'envo-extra' ),
				'dotted' => __( 'Dotted', 'envo-extra' ),
				'dashed' => __( 'Dashed', 'envo-extra' ),
				'groove' => __( 'Groove', 'envo-extra' ),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-separator:before' => 'border-top-style: {{VALUE}};',
			),
			'condition'	 => array(
				'show_separator' => 'yes',
			),
		)
		);

		$this->add_responsive_control(
		'separator_width', array(
			'label'		 => __( 'Width', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 1000,
					'step'	 => 1,
				),
				'%'	 => array(
					'min'	 => 0,
					'max'	 => 100,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 100,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-separator:before' => 'width: {{SIZE}}{{UNIT}};',
			),
			'condition'	 => array(
				'show_separator' => 'yes',
			),
		)
		);

		$this->add_responsive_control(
		'separator_height', array(
			'label'		 => __( 'Height', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => 0,
					'max'	 => 10,
					'step'	 => 1,
				),
			),
			'default'	 => array(
				'unit'	 => 'px',
				'size'	 => 1,
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-separator:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
			),
			'condition'	 => array(
				'show_separator' => 'yes',
			),
		)
		);

		$this->add_responsive_control(
		'separator_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition'	 => array(
				'show_separator' => 'yes',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_button_style', array(
			'label'	 => __( 'Button', 'envo-extra' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		)
		);

		$this->add_control(
		'button_display', array(
			'label'		 => __( 'Display', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'default'	 => 'inline-block',
			'options'	 => array(
				'block'			 => array(
					'title'	 => __( 'Block', 'envo-extra' ),
					'icon'	 => 'eicon-menu-bar',
				),
				'inline-block'	 => array(
					'title'	 => __( 'Inline', 'envo-extra' ),
					'icon'	 => 'eicon-ellipsis-h',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn' => 'display: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'button_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-btn',
		)
		);

		$this->start_controls_tabs(
		'button_style_tabs'
		);

		$this->start_controls_tab(
		'button_normal_tab', array(
			'label' => __( 'Normal', 'envo-extra' ),
		)
		);

		$this->add_control(
		'button_color', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'button_bg',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-btn',
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'button_border',
			'label'		 => __( 'Border', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-btn',
		)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		'button_hover_tab_style', array(
			'label' => __( 'Hover', 'envo-extra' ),
		)
		);

		$this->add_control(
		'button_hcolor', array(
			'label'		 => __( 'Text Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn:hover,{{WRAPPER}} .envo-extra-pricing-btn:focus' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'button_hbg',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-btn:hover,{{WRAPPER}} .envo-extra-pricing-btn:focus',
		)
		);

		$this->add_control(
		'button_hborder', array(
			'label'		 => __( 'Border Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn:hover,{{WRAPPER}} .envo-extra-pricing-btn:focus' => 'border-color: {{VALUE}}',
			),
		)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
		'button_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'button_item_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'button_margin', array(
			'label'		 => __( 'Margin', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_badge_style', array(
			'label'		 => __( 'Badge', 'envo-extra' ),
			'tab'		 => Controls_Manager::TAB_STYLE,
			'condition'	 => array(
				'show_badge' => 'yes',
			),
		)
		);

		$this->add_control(
		'badge_display', array(
			'label'		 => __( 'Display', 'envo-extra' ),
			'type'		 => Controls_Manager::CHOOSE,
			'default'	 => 'auto',
			'options'	 => array(
				'100%'	 => array(
					'title'	 => __( 'Block', 'envo-extra' ),
					'icon'	 => 'eicon-menu-bar',
				),
				'auto'	 => array(
					'title'	 => __( 'Inline', 'envo-extra' ),
					'icon'	 => 'eicon-ellipsis-h',
				),
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge'			 => 'width: {{VALUE}}; top:0; left: 0',
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge-top-left'	 => 'left:0; right:auto;',
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge-top-center'	 => 'left:50%; right:auto;',
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge-top-right'	 => 'right:0; left:auto;',
			),
		)
		);

		$this->add_control(
		'badge_position', array(
			'label'		 => __( 'Position', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'options'	 => array(
				'top-left'	 => __( 'Top Left', 'envo-extra' ),
				'top-center' => __( 'Top Center', 'envo-extra' ),
				'top-right'	 => __( 'Top Right', 'envo-extra' ),
			),
			'default'	 => 'top-right',
			'condition'	 => array(
				'badge_display' => 'auto',
			),
		)
		);

		$this->add_control(
		'badge_transform_toggle', array(
			'label'			 => __( 'Transform', 'envo-extra' ),
			'type'			 => Controls_Manager::POPOVER_TOGGLE,
			'label_off'		 => __( 'None', 'envo-extra' ),
			'label_on'		 => __( 'Custom', 'envo-extra' ),
			'return_value'	 => 'yes',
		)
		);

		$this->start_popover();

		$this->add_responsive_control(
		'badge_horizontal_offset', array(
			'label'		 => __( 'Horizontal Offset', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
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
			'condition'	 => array(
				'badge_transform_toggle' => 'yes',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => '--envo-extra-badge-translate-x: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'badge_vertical_offset', array(
			'label'		 => __( 'Vertical Offset', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px', '%' ),
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
					'max'	 => 200,
				),
			),
			'condition'	 => array(
				'badge_transform_toggle' => 'yes',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => '--envo-extra-badge-translate-y: {{SIZE}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'badge_rotate', array(
			'label'		 => __( 'Rotate', 'envo-extra' ),
			'type'		 => Controls_Manager::SLIDER,
			'size_units' => array( 'px' ),
			'range'		 => array(
				'px' => array(
					'min'	 => - 360,
					'max'	 => 360,
				),
			),
			'condition'	 => array(
				'badge_transform_toggle' => 'yes',
			),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => '--envo-extra-badge-rotate: {{SIZE}}deg;',
			),
		)
		);

		$this->add_control(
		'badge_transform_origin', array(
			'label'			 => __( 'Transform Origin', 'envo-extra' ),
			'type'			 => Controls_Manager::SELECT,
			'label_block'	 => true,
			'options'		 => array(
				'center center'	 => _x( 'Center Center', 'Background Control', 'envo-extra' ),
				'center left'	 => _x( 'Center Left', 'Background Control', 'envo-extra' ),
				'center right'	 => _x( 'Center Right', 'Background Control', 'envo-extra' ),
				'top center'	 => _x( 'Top Center', 'Background Control', 'envo-extra' ),
				'top left'		 => _x( 'Top Left', 'Background Control', 'envo-extra' ),
				'top right'		 => _x( 'Top Right', 'Background Control', 'envo-extra' ),
				'bottom center'	 => _x( 'Bottom Center', 'Background Control', 'envo-extra' ),
				'bottom left'	 => _x( 'Bottom Left', 'Background Control', 'envo-extra' ),
				'bottom right'	 => _x( 'Bottom Right', 'Background Control', 'envo-extra' ),
			),
			'default'		 => 'center center',
			'selectors'		 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => 'transform-origin: {{VALUE}};',
			),
			'condition'		 => array(
				'badge_transform_toggle' => 'yes',
			),
		)
		);

		$this->end_popover();

		$this->add_control(
		'badge_overflow', array(
			'label'		 => __( 'Overflow', 'envo-extra' ),
			'type'		 => Controls_Manager::SELECT,
			'options'	 => array(
				''		 => _x( 'Auto', 'Background Control', 'envo-extra' ),
				'hidden' => _x( 'Hidden', 'Background Control', 'envo-extra' ),
			),
			'default'	 => '',
			'selectors'	 => array(
				'{{WRAPPER}}.elementor-widget-envo-extra-pricing > .elementor-widget-container' => 'overflow: {{VALUE}};',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Typography::get_type(), array(
			'name'		 => 'badge_typography',
			'label'		 => __( 'Typography', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge',
		)
		);

		$this->add_control(
		'badge_color', array(
			'label'		 => __( 'Color', 'envo-extra' ),
			'type'		 => Controls_Manager::COLOR,
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => 'color: {{VALUE}}',
			),
		)
		);

		$this->add_group_control(
		Group_Control_Background::get_type(), array(
			'name'		 => 'badge_background',
			'label'		 => __( 'Background', 'envo-extra' ),
			'types'		 => array( 'classic', 'gradient' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge',
		)
		);

		$this->add_group_control(
		Group_Control_Border::get_type(), array(
			'name'		 => 'badge_border',
			'label'		 => __( 'Border', 'envo-extra' ),
			'selector'	 => '{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge',
		)
		);

		$this->add_responsive_control(
		'badge_border_radius', array(
			'label'		 => __( 'Border Radius', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
		);

		$this->add_responsive_control(
		'badge_padding', array(
			'label'		 => __( 'Padding', 'envo-extra' ),
			'type'		 => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%', 'em' ),
			'selectors'	 => array(
				'{{WRAPPER}} .envo-extra-pricing-item .envo-extra-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		<div class="envo-extra-pricing-item">

			<?php if ( 'yes' === $settings[ 'show_badge' ] && !empty( $settings[ 'badge_text' ] ) ) : ?>
				<span class="envo-extra-badge envo-extra-badge-<?php echo esc_attr( $settings[ 'badge_position' ] ); ?>"><?php echo esc_html( $settings[ 'badge_text' ] ); ?></span>
			<?php endif; ?>

			<?php if ( 'before_header' === $settings[ 'media_position' ] ) { ?>
				<?php if ( 'icon' === $settings[ 'media_type' ] && $settings[ 'icon' ][ 'value' ] ) : ?>
					<div class="envo-extra-pricing-icon">
						<?php Icons_Manager::render_icon( $settings[ 'icon' ], array( 'aria-hidden' => 'true' ) ); ?>
					</div>
				<?php endif; ?>
				<?php if ( 'image' === $settings[ 'media_type' ] && $settings[ 'image' ][ 'url' ] ) : ?>
					<div class="envo-extra-pricing-media">
						<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) ); ?>
					</div>
				<?php endif; ?>
			<?php } ?>

			<?php if ( !empty( $settings[ 'title' ] ) ) : ?>
				<div class="envo-extra-pricing-title-wrapper">
					<h2 class="envo-extra-pricing-title"><?php echo esc_html( $settings[ 'title' ] ); ?></h2>
				</div>
			<?php endif; ?>

			<?php if ( 'after_header' === $settings[ 'media_position' ] ) { ?>
				<?php if ( 'icon' === $settings[ 'media_type' ] && $settings[ 'icon' ][ 'value' ] ) : ?>
					<div class="envo-extra-pricing-icon">
						<?php Icons_Manager::render_icon( $settings[ 'icon' ], array( 'aria-hidden' => 'true' ) ); ?>
					</div>
				<?php endif; ?>
				<?php if ( 'image' === $settings[ 'media_type' ] && $settings[ 'image' ][ 'url' ] ) : ?>
					<div class="envo-extra-pricing-media">
						<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) ); ?>
					</div>
				<?php endif; ?>
			<?php } ?>

			<?php if ( 'before_features' === $settings[ 'price_position' ] ) { ?>
				<div class="envo-extra-pricing-price-box envo-extra-pricing-price-box-style-<?php echo esc_attr( $settings[ 'price_style' ] ); ?>">
					<div class="envo-extra-pricing-price-tag">
						<span class="envo-extra-pricing-currency">
							<?php echo( ( 'none' !== $settings[ 'currency' ] ) ? self::get_currency_symbol( $settings[ 'currency' ] ) : esc_html( $settings[ 'currency_custom' ] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?>
						</span>
						<span class="envo-extra-pricing-price">
							<?php echo esc_html( $settings[ 'price' ] ); ?>
						</span>
					</div>

					<?php if ( !empty( $settings[ 'period' ] ) ) : ?>
						<p class="envo-extra-pricing-price-period"><?php echo esc_html( $settings[ 'period' ] ); ?></p>
					<?php endif; ?>

				</div>
			<?php } ?>

			<?php if ( 'before_features' === $settings[ 'description_position' ] && $settings[ 'item_description' ] ) { ?>
				<div class="envo-extra-pricing-description-wrapper">
					<div class="envo-extra-pricing-description">
						<?php echo wp_kses_post( $settings[ 'item_description' ] ); ?>
					</div>
				</div>
			<?php } ?>

			<?php
			if ( 'before_features' === $settings[ 'button_position' ] && $settings[ 'button_title' ] ) {
				$target		 = $settings[ 'button_link' ][ 'is_external' ] ? ' target="_blank"' : '';
				$nofollow	 = $settings[ 'button_link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
				echo '<div class="envo-extra-pricing-btn-wrapper"><a class="envo-extra-pricing-btn" href="' . esc_url( $settings[ 'button_link' ][ 'url' ] ) . '"' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' . $settings[ 'button_title' ] . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>

			<?php if ( 'yes' === $settings[ 'show_feature' ] ) : ?>
				<div class="envo-extra-pricing-features">

					<?php if ( !empty( $settings[ 'features_title' ] ) ) : ?>
						<h4 class="envo-extra-pricing-features-title"><?php echo esc_html( $settings[ 'features_title' ] ); ?></h4>
					<?php endif; ?>

					<ul class="envo-extra-pricing-features-list">
						<?php foreach ( $settings[ 'feature_items' ] as $i => $item ) : ?>
							<li class="<?php echo esc_attr( $item[ 'status' ] ); ?>">

								<?php if ( $item[ 'icon' ] ) : ?>
									<span class="envo-extra-pricing-feature-icon"><?php Icons_Manager::render_icon( $item[ 'icon' ], array( 'aria-hidden' => 'true' ) ); ?></span>
								<?php endif; ?>

								<?php if ( $item[ 'title_text' ] ) : ?>
									<span class="envo-extra-pricing-feature-title">
										<?php echo esc_html( $item[ 'title_text' ] ); ?>
										<?php if ( $item[ 'tooltip_text' ] ) : ?>
											<i class="fas fa-question envo-extra-pricing-tooltip-toggle">
												<span class="envo-extra-pricing-tooltip">
													<?php echo wp_kses_post( $item[ 'tooltip_text' ] ); ?>
												</span>
											</i>
										<?php endif; ?>
									</span>
								<?php endif; ?>

							</li>
						<?php endforeach; ?>
					</ul>

				</div>
			<?php endif; ?>

			<?php if ( 'after_features' === $settings[ 'description_position' ] && $settings[ 'item_description' ] ) { ?>
				<div class="envo-extra-pricing-description-wrapper">
					<div class="envo-extra-pricing-description">
						<?php echo wp_kses_post( $settings[ 'item_description' ] ); ?>
					</div>
				</div>
			<?php } ?>

			<?php if ( 'yes' === $settings[ 'show_separator' ] ) { ?>
				<div class="envo-extra-pricing-separator"></div>
			<?php } ?>

			<?php if ( 'after_features' === $settings[ 'price_position' ] ) { ?>
				<div class="envo-extra-pricing-price-box envo-extra-pricing-price-box-style-<?php echo esc_attr( $settings[ 'price_style' ] ); ?>">
					<div class="envo-extra-pricing-price-tag">
						<span class="envo-extra-pricing-currency">
							<?php echo( ( 'none' !== $settings[ 'currency' ] ) ? self::get_currency_symbol( $settings[ 'currency' ] ) : esc_html( $settings[ 'currency_custom' ] ) );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  ?>
						</span>
						<span class="envo-extra-pricing-price">
							<?php echo esc_html( $settings[ 'price' ] ); ?>
						</span>
					</div>

					<?php if ( !empty( $settings[ 'period' ] ) ) : ?>
						<p class="envo-extra-pricing-price-period"><?php echo esc_html( $settings[ 'period' ] ); ?></p>
					<?php endif; ?>

				</div>
			<?php } ?>

			<?php
			if ( 'after_features' === $settings[ 'button_position' ] && $settings[ 'button_title' ] ) {
				$target		 = $settings[ 'button_link' ][ 'is_external' ] ? ' target="_blank"' : '';
				$nofollow	 = $settings[ 'button_link' ][ 'nofollow' ] ? ' rel="nofollow"' : '';
				echo '<div class="envo-extra-pricing-btn-wrapper"><a class="envo-extra-pricing-btn" href="' . esc_url( $settings[ 'button_link' ][ 'url' ] ) . '"' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' . esc_html( $settings[ 'button_title' ] ) . '</a></div>';
			}
			?>

		</div>
		<?php
	}

}
