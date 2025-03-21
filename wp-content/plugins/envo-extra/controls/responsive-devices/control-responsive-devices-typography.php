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

class Envo_Extra_Customize_Responsive_Devices_Typography extends Kirki\Control\Base {

	public $type = 'envo-responsive-devices-typography';

	public function enqueue() {
		
	}

	public function render_content() {

		$devices = array( 'desktop', 'tablet', 'mobile' );

		//$value_bucket = empty( $this->value() ) ? [] : json_decode( $this->value(), true );

		echo '<div class="envo-responsive-input-wrap">';
		?>

		<div class="customize-control-title"><?php echo esc_html( $this->label ); ?>

			<ul class="envo-responsive-options">
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
		</div>
		<?php
		$devicess = array(
	'desktop'	 => array(
		'media_query_key'	 => '',
		'media_query'		 => '',
		'description'		 => 'Desktop',
	),
	'tablet'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 991px)',
		'description'		 => 'Tablet',
	),
	'mobile'	 => array(
		'media_query_key'	 => 'media_query',
		'media_query'		 => '@media (max-width: 767px)',
		'description'		 => 'Mobile',
	),
);
		// Responsive field.
foreach ( $devicess as $key => $value ) {
	Kirki::add_field( 'envo_extra', array(
		'type'			 => 'typography',
		'settings'		 => 'sstypography_mainmenu' . $key,
		'description'	 => $value[ 'description' ],
		'section'		 => 'main_menu',
		'transport'		 => 'auto',
		'choices'		 => array(
			'fonts' => envo_extra_fonts(),
		),
		'default'		 => array(
			'font-family'		 => '',
			'font-size'			 => '15px',
			'variant'			 => '400',
			'letter-spacing'	 => '0px',
			'text-transform'	 => 'uppercase',
			'color'				 => '',
			'word-spacing'		 => '0px',
			'text-decoration'	 => 'none'
		),
		'priority'		 => 15,
		'output'		 => array(
			array(
				'element'					 => '#site-navigation, #site-navigation .navbar-nav > li > a, #site-navigation .dropdown-menu > li > a',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
			array(
				'choice'					 => 'color',
				'element'					 => '.open-panel span',
				'property'					 => 'background-color',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
			array(
				'choice'					 => 'color',
				'element'					 => '.navbar-default .navbar-brand.brand-absolute',
				'property'					 => 'color',
				$value[ 'media_query_key' ]	 => $value[ 'media_query' ],
			),
		),
	) );
}
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

	$controls[ 'responsive_devices_typography' ] = 'Envo_Extra_Customize_Responsive_Devices_Typography';

	return $controls;
}
);
