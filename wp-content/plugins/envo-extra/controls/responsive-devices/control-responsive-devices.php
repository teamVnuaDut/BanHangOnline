<?php
/**
 * Custom input control.
 *
 * @package Envo Extra
 * @subpackage Customizer
 */
defined( 'ABSPATH' ) || die( "Can't access directly" );

if (!class_exists('Kirki')) {
    return;
}

class Envo_Extra_Customize_Responsive_Devices extends Kirki\Control\Base {

	public $type = 'envo-responsive-devices';

	public function enqueue() {
		
	}

	public function render_content() {

		$devices = array( 'desktop', 'tablet', 'mobile' );

		//$value_bucket = empty( $this->value() ) ? [] : json_decode( $this->value(), true );

		echo '<div class="envo-responsive-input-wrap">';
		?>

		<div class="customize-control-title"><?php echo esc_html( $this->label ); ?>

			<ul class="envo-responsive-options" style="display: none;">
				<li class="desktop">
					<button type="button" class="preview-desktop active" data-device="desktop">
						<i class="dashicons dashicons-desktop"></i>
					</button>
				</li>
				<li class="tablet">
					<button type="button" class="preview-tablet" data-device="tablet">
						<i class="dashicons dashicons-tablet"></i>
					</button>
				</li>
				<li class="mobile">
					<button type="button" class="preview-mobile" data-device="mobile">
						<i class="dashicons dashicons-smartphone"></i>
					</button>
				</li>
			</ul>
			<span class="show-kirki-control dashicons dashicons-edit"></span>
		</div>
		<?php
		echo '</div>';
	}

}

/**
 * Register input slider control with Kirki.
 *
 * @param array $controls The controls.
 *
 * @return array The updated controls.
 */
add_filter( 'kirki_control_types', function ( $controls ) {

	$controls[ 'responsive_devices' ] = 'Envo_Extra_Customize_Responsive_Devices';

	return $controls;
}
);
