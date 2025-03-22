<?php
/**
 * Blossom Shop Pro Customizer Radio-Buttonset Control.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blossom_Shop_Radio_Buttonset_Control' ) ) {

	/**
	 * Radio Buttonset control (modified radio)
	 */
	class Blossom_Shop_Radio_Buttonset_Control extends WP_Customize_Control {

		public $type = 'radio-buttonset';

		public $tooltip = '';
        
        public function to_json() {
			parent::to_json();
			
            if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			} else {
				$this->json['default'] = $this->setting->default;
			}
			
            $this->json['value']   = $this->value();
            $this->json['choices'] = $this->choices;
			$this->json['link']    = $this->get_link();
            $this->json['id']      = $this->id;
            $this->json['tooltip'] = $this->tooltip;
						
            $this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
			}
		}
        
        public function enqueue() {            
            wp_enqueue_style( 'blossom-shop-pro-radio-buttonset', get_template_directory_uri() . '/inc/custom-controls/radiobtn/radio-buttonset.css', null );
            wp_enqueue_script( 'blossom-shop-pro-radio-buttonset', get_template_directory_uri() . '/inc/custom-controls/radiobtn/radio-buttonset.js', array( 'jquery' ), false, true ); //for radio buttonset        
        }
        
        protected function content_template() {
			?>
			<# if ( ! data.choices ) { return; } #>
            <# if ( data.tooltip ) { #>
				<a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
			<# } #>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			<div id="input_{{ data.id }}" class="buttonset">
				<# for ( key in data.choices ) { #>
					<input {{{ data.inputAttrs }}} class="switch-input" type="radio" value="{{ key }}" name="_customize-radio-{{{ data.id }}}" id="{{ data.id }}{{ key }}" {{{ data.link }}}<# if ( key === data.value ) { #> checked="checked" <# } #>>
						<label class="switch-label switch-label-<# if ( key === data.value ) { #>on <# } else { #>off<# } #>" for="{{ data.id }}{{ key }}">
							{{ data.choices[ key ] }}
						</label>
					</input>
				<# } #>
			</div>
			<?php
		}

		protected function render_content(){
		}
	}
}