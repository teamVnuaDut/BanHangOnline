<?php

/**
 * AWS plugin gutenberg integrations init
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AWS_Gutenberg_Init')) :

    /**
     * Class for main plugin functions
     */
    class AWS_Gutenberg_Init {

        /**
         * @var AWS_Gutenberg_Init The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main AWS_Gutenberg_Init Instance
         *
         * Ensures only one instance of AWS_Gutenberg_Init is loaded or can be loaded.
         *
         * @static
         * @return AWS_Gutenberg_Init - Main instance
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            add_action( 'init', array( $this, 'register_block' ) );

            if ( version_compare( get_bloginfo('version'),'5.8', '>=' ) ) {
                add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
            } else {
                add_filter( 'block_categories', array( $this, 'add_block_category' ) );
            }

        }

        /*
         * Register gutenberg blocks
         */
        public function register_block() {

            global $pagenow;

            $scripts = array( 'wp-blocks', 'wp-editor' );
            if ( $pagenow && $pagenow === 'widgets.php' && version_compare( get_bloginfo('version'),'5.8', '>=' ) ) {
                $scripts = array( 'wp-blocks', 'wp-edit-widgets' );
            }

            wp_register_script(
                'aws-gutenberg-search-block',
                AWS_URL . 'includes/modules/gutenberg/aws-gutenberg-search-block.js',
                $scripts,
                AWS_VERSION
            );

            wp_register_script(
                'aws-gutenberg-nav-search-block',
                AWS_URL . 'includes/modules/gutenberg/aws-gutenberg-nav-search-block.js',
                $scripts,
                AWS_VERSION
            );

            wp_register_style(
                'aws-gutenberg-styles-editor',
                AWS_URL . 'assets/css/common.css',
                array( 'wp-edit-blocks' ),
                AWS_VERSION
            );

            register_block_type( 'advanced-woo-search/search-block', array(
                'apiVersion' => 2,
                'editor_script' => 'aws-gutenberg-search-block',
                'editor_style' => 'aws-gutenberg-styles-editor',
                'render_callback' => array( $this, 'search_block_dynamic_render_callback' ),
                'attributes'      =>  array(
                    'placeholder'   =>  array(
                        'type'    => 'string',
                        'default' => AWS_Helpers::translate( 'search_field_text', AWS()->get_settings( 'search_field_text' ) ),
                    ),
                ),
            ) );

            register_block_type( 'advanced-woo-search/nav-search-block', array(
                'apiVersion' => 2,
                'editor_script' => 'aws-gutenberg-nav-search-block',
                'editor_style' => 'aws-gutenberg-styles-editor',
                'render_callback' => array( $this, 'search_block_dynamic_render_callback' ),
                'attributes'      =>  array(
                    'placeholder'   =>  array(
                        'type'    => 'string',
                        'default' => AWS_Helpers::translate( 'search_field_text', AWS()->get_settings( 'search_field_text' ) ),
                    ),
                ),
            ) );

        }

        /*
         * Render dynamic content
         */
        public function search_block_dynamic_render_callback( $block_attributes, $content ) {

            $placeholder = $block_attributes['placeholder'];
            $args = $placeholder ? array( 'placeholder' => $placeholder ) : array();
            $search_form = aws_get_search_form( false, $args );

            return $search_form;

        }

        /*
         * Add new blocks category
         */
        public function add_block_category( $categories ) {
            if ( is_array( $categories ) ) {
                $categories = array_merge(
                    $categories,
                    array(
                        array(
                            'slug'  => 'aws',
                            'title' => 'Advanced Woo Search',
                            'icon'  => 'search',
                        ),
                    )
                );
            }
            return $categories;
        }

    }


endif;

AWS_Gutenberg_Init::instance();