<?php

class AwsSearchModule extends FLBuilderModule {

    public function __construct() {

        parent::__construct(array(
            'name'            => __( 'Advanced Woo Search', 'advanced-woo-search' ),
            'description'     => __( 'WooCommerce search form', 'advanced-woo-search' ),
            'category'        => __( 'WooCommerce', 'fl-builder' ),
            'dir'             => AWS_DIR . '/includes/modules/bb-aws-search/',
            'url'             => AWS_URL . 'includes/modules/bb-aws-search/',
            'icon'            => 'search.svg',
            'partial_refresh' => true,
        ));

    }

}

$placeholder   = AWS_Helpers::translate( 'search_field_text', AWS()->get_settings( 'search_field_text' ) );

FLBuilder::register_module( 'AwsSearchModule', array(
    'general' => array(
        'title'    => __( 'General', 'advanced-woo-search' ),
        'sections' => array(
            'general' => array(
                'title'  => '',
                'fields' => array(
                    'placeholder' => array(
                        'type'        => 'text',
                        'label'       => __( 'Placeholder', 'advanced-woo-search' ),
                        'default'     => $placeholder,
                        'preview'     => array(
                            'type'     => 'text',
                            'selector' => '.fl-heading-text',
                        ),
                        'connections' => array( 'string' ),
                    ),
                ),
            ),
        ),
        'description' => sprintf( esc_html__( 'To configure your Advanced Woo Search form please visit %s.', 'advanced-woo-search' ), '<a target="_blank" href="'.esc_url( admin_url('admin.php?page=aws-options') ).'">'.esc_html__( 'plugin settings page', 'advanced-woo-search' ).'</a>'  ),
    ),
) );