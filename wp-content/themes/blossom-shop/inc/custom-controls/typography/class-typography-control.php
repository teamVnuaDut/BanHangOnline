<?php
/**
 * Blossom Shop Customizer Typography Control
 *
 * @package Blossom_Shop
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Blossom_Shop_Typography_Control' ) ) {
    
    class Blossom_Shop_Typography_Control extends WP_Customize_Control{
    
    	public $tooltip = '';
    	public $js_vars = array();
    	public $output = array();
    	public $option_type = 'theme_mod';
    	public $type = 'blossom-shop-typography';
    
    	/**
    	 * Refresh the parameters passed to the JavaScript via JSON.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function to_json() {
    		parent::to_json();
    
    		if ( isset( $this->default ) ) {
    			$this->json['default'] = $this->default;
    		} else {
    			$this->json['default'] = $this->setting->default;
    		}
    		$this->json['js_vars'] = $this->js_vars;
    		$this->json['output']  = $this->output;
    		$this->json['value']   = $this->value();
    		$this->json['choices'] = $this->choices;
    		$this->json['link']    = $this->get_link();
    		$this->json['tooltip'] = $this->tooltip;
    		$this->json['id']      = $this->id;
    		$this->json['l10n']    = apply_filters( 'blossom_shop_il8n_strings', array(
    			'on'                 => esc_attr__( 'ON', 'blossom-shop' ),
    			'off'                => esc_attr__( 'OFF', 'blossom-shop' ),
    			'all'                => esc_attr__( 'All', 'blossom-shop' ),
    			'cyrillic'           => esc_attr__( 'Cyrillic', 'blossom-shop' ),
    			'cyrillic-ext'       => esc_attr__( 'Cyrillic Extended', 'blossom-shop' ),
    			'devanagari'         => esc_attr__( 'Devanagari', 'blossom-shop' ),
    			'greek'              => esc_attr__( 'Greek', 'blossom-shop' ),
    			'greek-ext'          => esc_attr__( 'Greek Extended', 'blossom-shop' ),
    			'khmer'              => esc_attr__( 'Khmer', 'blossom-shop' ),
    			'latin'              => esc_attr__( 'Latin', 'blossom-shop' ),
    			'latin-ext'          => esc_attr__( 'Latin Extended', 'blossom-shop' ),
    			'vietnamese'         => esc_attr__( 'Vietnamese', 'blossom-shop' ),
    			'hebrew'             => esc_attr__( 'Hebrew', 'blossom-shop' ),
    			'arabic'             => esc_attr__( 'Arabic', 'blossom-shop' ),
    			'bengali'            => esc_attr__( 'Bengali', 'blossom-shop' ),
    			'gujarati'           => esc_attr__( 'Gujarati', 'blossom-shop' ),
    			'tamil'              => esc_attr__( 'Tamil', 'blossom-shop' ),
    			'telugu'             => esc_attr__( 'Telugu', 'blossom-shop' ),
    			'thai'               => esc_attr__( 'Thai', 'blossom-shop' ),
    			'serif'              => _x( 'Serif', 'font style', 'blossom-shop' ),
    			'sans-serif'         => _x( 'Sans Serif', 'font style', 'blossom-shop' ),
    			'monospace'          => _x( 'Monospace', 'font style', 'blossom-shop' ),
    			'font-family'        => esc_attr__( 'Font Family', 'blossom-shop' ),
    			'font-size'          => esc_attr__( 'Font Size', 'blossom-shop' ),
    			'font-weight'        => esc_attr__( 'Font Weight', 'blossom-shop' ),
    			'line-height'        => esc_attr__( 'Line Height', 'blossom-shop' ),
    			'font-style'         => esc_attr__( 'Font Style', 'blossom-shop' ),
    			'letter-spacing'     => esc_attr__( 'Letter Spacing', 'blossom-shop' ),
    			'text-align'         => esc_attr__( 'Text Align', 'blossom-shop' ),
    			'text-transform'     => esc_attr__( 'Text Transform', 'blossom-shop' ),
    			'none'               => esc_attr__( 'None', 'blossom-shop' ),
    			'uppercase'          => esc_attr__( 'Uppercase', 'blossom-shop' ),
    			'lowercase'          => esc_attr__( 'Lowercase', 'blossom-shop' ),
    			'top'                => esc_attr__( 'Top', 'blossom-shop' ),
    			'bottom'             => esc_attr__( 'Bottom', 'blossom-shop' ),
    			'left'               => esc_attr__( 'Left', 'blossom-shop' ),
    			'right'              => esc_attr__( 'Right', 'blossom-shop' ),
    			'center'             => esc_attr__( 'Center', 'blossom-shop' ),
    			'justify'            => esc_attr__( 'Justify', 'blossom-shop' ),
    			'color'              => esc_attr__( 'Color', 'blossom-shop' ),
    			'select-font-family' => esc_attr__( 'Select a font-family', 'blossom-shop' ),
    			'variant'            => esc_attr__( 'Variant', 'blossom-shop' ),
    			'style'              => esc_attr__( 'Style', 'blossom-shop' ),
    			'size'               => esc_attr__( 'Size', 'blossom-shop' ),
    			'height'             => esc_attr__( 'Height', 'blossom-shop' ),
    			'spacing'            => esc_attr__( 'Spacing', 'blossom-shop' ),
    			'ultra-light'        => esc_attr__( 'Ultra-Light 100', 'blossom-shop' ),
    			'ultra-light-italic' => esc_attr__( 'Ultra-Light 100 Italic', 'blossom-shop' ),
    			'light'              => esc_attr__( 'Light 200', 'blossom-shop' ),
    			'light-italic'       => esc_attr__( 'Light 200 Italic', 'blossom-shop' ),
    			'book'               => esc_attr__( 'Book 300', 'blossom-shop' ),
    			'book-italic'        => esc_attr__( 'Book 300 Italic', 'blossom-shop' ),
    			'regular'            => esc_attr__( 'Normal 400', 'blossom-shop' ),
    			'italic'             => esc_attr__( 'Normal 400 Italic', 'blossom-shop' ),
    			'medium'             => esc_attr__( 'Medium 500', 'blossom-shop' ),
    			'medium-italic'      => esc_attr__( 'Medium 500 Italic', 'blossom-shop' ),
    			'semi-bold'          => esc_attr__( 'Semi-Bold 600', 'blossom-shop' ),
    			'semi-bold-italic'   => esc_attr__( 'Semi-Bold 600 Italic', 'blossom-shop' ),
    			'bold'               => esc_attr__( 'Bold 700', 'blossom-shop' ),
    			'bold-italic'        => esc_attr__( 'Bold 700 Italic', 'blossom-shop' ),
    			'extra-bold'         => esc_attr__( 'Extra-Bold 800', 'blossom-shop' ),
    			'extra-bold-italic'  => esc_attr__( 'Extra-Bold 800 Italic', 'blossom-shop' ),
    			'ultra-bold'         => esc_attr__( 'Ultra-Bold 900', 'blossom-shop' ),
    			'ultra-bold-italic'  => esc_attr__( 'Ultra-Bold 900 Italic', 'blossom-shop' ),
    			'invalid-value'      => esc_attr__( 'Invalid Value', 'blossom-shop' ),
    		) );
    
    		$defaults = array( 'font-family'=> false );
    
    		$this->json['default'] = wp_parse_args( $this->json['default'], $defaults );
    	}
    
    	/**
    	 * Enqueue scripts and styles.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function enqueue() {
    		wp_enqueue_style( 'blossom-shop-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.css', null );
            
            wp_enqueue_script( 'jquery-ui-core' );
    		wp_enqueue_script( 'jquery-ui-tooltip' );
    		wp_enqueue_script( 'jquery-stepper-min-js' );
    		wp_enqueue_script( 'blossom-shop-selectize', get_template_directory_uri() . '/inc/js/selectize.js', array( 'jquery' ), false, true );
    		wp_enqueue_script( 'blossom-shop-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.js', array( 'jquery', 'blossom-shop-selectize' ), false, true );
    
    		$google_fonts   = Blossom_Shop_Fonts::get_google_fonts();
    		$standard_fonts = Blossom_Shop_Fonts::get_standard_fonts();
    		$all_variants   = Blossom_Shop_Fonts::get_all_variants();
    
    		$standard_fonts_final = array();
    		foreach ( $standard_fonts as $key => $value ) {
    			$standard_fonts_final[] = array(
    				'family'      => $value['stack'],
    				'label'       => $value['label'],
    				'is_standard' => true,
    				'variants'    => array(
    					array(
    						'id'    => 'regular',
    						'label' => $all_variants['regular'],
    					),
    					array(
    						'id'    => 'italic',
    						'label' => $all_variants['italic'],
    					),
    					array(
    						'id'    => '700',
    						'label' => $all_variants['700'],
    					),
    					array(
    						'id'    => '700italic',
    						'label' => $all_variants['700italic'],
    					),
    				),
    			);
    		}
    
    		$google_fonts_final = array();
    
    		if ( is_array( $google_fonts ) ) {
    			foreach ( $google_fonts as $family => $args ) {
    				$label    = ( isset( $args['label'] ) ) ? $args['label'] : $family;
    				$variants = ( isset( $args['variants'] ) ) ? $args['variants'] : array( 'regular', '700' );
    
    				$available_variants = array();
    				foreach ( $variants as $variant ) {
    					if ( array_key_exists( $variant, $all_variants ) ) {
    						$available_variants[] = array( 'id' => $variant, 'label' => $all_variants[ $variant ] );
    					}
    				}
    
    				$google_fonts_final[] = array(
    					'family'   => $family,
    					'label'    => $label,
    					'variants' => $available_variants
    				);
    			}
    		}
    
    		$final = array_merge( $standard_fonts_final, $google_fonts_final );
    		wp_localize_script( 'blossom-shop-typography', 'all_fonts', $final );
    	}
    
    	/**
    	 * An Underscore (JS) template for this control's content (but not its container).
    	 *
    	 * Class variables for this control class are available in the `data` JS object;
    	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
    	 *
    	 * I put this in a separate file because PhpStorm didn't like it and it fucked with my formatting.
    	 *
    	 * @see    WP_Customize_Control::print_template()
    	 *
    	 * @access protected
    	 * @return void
    	 */
    	protected function content_template(){ ?>
    		<# if ( data.tooltip ) { #>
                <a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
            <# } #>
            
            <label class="customizer-text">
                <# if ( data.label ) { #>
                    <span class="customize-control-title">{{{ data.label }}}</span>
                <# } #>
                <# if ( data.description ) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
            </label>
            
            <div class="wrapper">
                <# if ( data.default['font-family'] ) { #>
                    <# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>
                    <# if ( data.choices['fonts'] ) { data.fonts = data.choices['fonts']; } #>
                    <div class="font-family">
                        <h5>{{ data.l10n['font-family'] }}</h5>
                        <select id="blossom-shop-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}"></select>
                    </div>
                    <div class="variant blossom-shop-variant-wrapper">
                        <h5>{{ data.l10n['style'] }}</h5>
                        <select class="variant" id="blossom-shop-typography-variant-{{{ data.id }}}"></select>
                    </div>
                <# } #>   
                
            </div>
            <?php
    	}

        protected function render_content(){
        }    
    }
}