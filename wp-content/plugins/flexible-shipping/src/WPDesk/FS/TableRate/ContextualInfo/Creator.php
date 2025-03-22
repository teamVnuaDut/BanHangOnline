<?php
/**
 * Contextual info creator.
 *
 * @package Contextual Info
 */

namespace WPDesk\FS\TableRate\ContextualInfo;

use Flexible_Shipping_Contextual_Info;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableParent;

/**
 * Can create contextual info.
 */
class Creator implements HookableCollection {

	use HookableParent;

	const METHOD_TITLE_ELEMENT                         = 'woocommerce_flexible_shipping_method_title';
	const METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS = 'woocommerce_flexible_shipping_method_title,woocommerce_flexible_shipping_method_description';

	/**
	 * @var string
	 */
	private $base_location_country;

	/**
	 * Flexible_Shipping_Contextual_Info_Creator constructor.
	 *
	 * @param string $base_location_country .
	 */
	public function __construct( $base_location_country ) {
		$this->base_location_country = $base_location_country;
	}

	/**
	 * Hooks.
	 */
	public function hooks() {
		$this->hooks_on_hookable_objects();
	}

	/**
	 * Create contextual info.
	 */
	public function create_contextual_info() {
		$other_phrases_not_in = [];

		$phrases_in = $this->get_dhl_express_phrases();
		$this->create_dhl_express_contextual_info( $phrases_in );
		$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

		$phrases_in = [ 'fedex' ];
		$this->create_fedex_contextual_info( $phrases_in );
		$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

		$phrases_in = [ 'ups' ];
		$this->create_ups_contextual_info( $phrases_in );
		$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

		if ( $this->is_base_location_country_pl() ) {
			$phrases_in = [ 'dpd' ];
			$this->create_dpd_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

			$phrases_in = [ 'List', 'poczta polska', 'pocztex', 'polecony', 'poczt', 'enadawca' ];
			$this->create_enadawca_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

			$phrases_in = [ 'dhl', 'parcel' ];
			$this->create_dhl_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

			$phrases_in = [ 'ruch', 'kiosk', 'orlen' ];
			$this->create_pwr_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );

			$phrases_in = [ 'paczkomat', 'paczka w weekend', 'inpost' ];
			$this->create_inpost_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );
		} elseif ( $this->is_base_location_country_gb() ) {
			$phrases_in = [ 'air', 'dpd' ];
			$this->create_dpd_uk_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );
		} elseif ( $this->is_base_location_country_us() ) {
			$phrases_in = [ 'usps' ];
			$this->create_usps_contextual_info( $phrases_in );
			$other_phrases_not_in = $this->merge_phrases( $other_phrases_not_in, $phrases_in );
		}

		$this->create_default_contextual_info( $other_phrases_not_in );
	}

	/**
	 * @return array
	 */
	private function get_dhl_express_phrases() {
		if ( $this->is_base_location_country_pl() ) {
			return [ 'dhl express' ];
		} else {
			return [ 'dhl', 'dhl express' ];
		}
	}

	/**
	 * @return bool
	 */
	private function is_base_location_country_pl() {
		return 'PL' === $this->base_location_country;
	}

	/**
	 * @return bool
	 */
	private function is_base_location_country_us() {
		return stripos( $this->base_location_country, 'US' ) !== false;
	}

	/**
	 * @return bool
	 */
	private function is_base_location_country_gb() {
		return 'GB' === $this->base_location_country;
	}

	/**
	 * @param array $phrases1 .
	 * @param array $phrases2 .
	 *
	 * @return array
	 */
	private function merge_phrases( array $phrases1, array $phrases2 ) {
		return array_merge( $phrases1, $phrases2 );
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_dhl_express_contextual_info( array $phrases_in ) {
		if ( ! defined( 'FLEXIBLE_SHIPPING_DHL_EXPRESS_VERSION' ) && ! defined( 'FLEXIBLE_SHIPPING_DHL_EXPRESS_PRO_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'dhl_express',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Want to show your customers the DHL Express live rates? %1$sCheck our DHL Express plugin%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-cross-dhl-express" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_fedex_contextual_info( array $phrases_in ) {
		if ( ! defined( 'FLEXIBLE_SHIPPING_FEDEX_VERSION' ) && ! defined( 'FLEXIBLE_SHIPPING_FEDEX_PRO_VERSION' ) ) {
			$target_url = $this->is_base_location_country_pl()
				? 'https://octol.io/fs-cross-fedex-pl'
				: 'https://octol.io/fs-cross-fedex';
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'fedex',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Want to show your customers the FedEx live rates? %1$sCheck our FedEx plugin%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="' . $target_url . '" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_ups_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_UPS_LABELS_VERSION' ) ) {
			$target_url = get_user_locale() === 'pl_PL'
				? 'https://octol.io/fs-cross-ups-labels-pl'
				: 'https://octol.io/fs-cross-ups-labels';
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'ups',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products with UPS? Create the shipments and generate shipping labels directly from your shop using our %1$sUPS Labels%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="' . $target_url . '" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_usps_contextual_info( array $phrases_in ) {
		if ( ! defined( 'FLEXIBLE_SHIPPING_USPS_PRO_VERSION' ) ) {
			$target_url = 'https://octol.io/fs-cross-usps';

			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'usps',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Want to show your customers the USPS live rates? %1$sCheck our USPS plugin%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="' . esc_url( $target_url ) . '" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_dpd_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_DPD_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'dpd',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products via DPD? Create the shipments and generate shipping labels directly from your shop using our %1$sDPD integration%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-context-cross-dpd-pl" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_enadawca_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_ENADAWCA_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'enadawca',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products via Poczta Polska? Create the shipments and generate shipping labels directly from your shop using our %1$sPoczta Polska eNadawca integration%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-context-cross-enadawca-pl" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_dhl_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_DHL_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'dhl',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products via DHL? Create the shipments and generate shipping labels directly from your shop using our %1$sDHL integration%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-context-cross-dhl-pl" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_pwr_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_PACZKA_W_RUCHU_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'pwr',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products via Orlen Paczka? Create the shipments and generate shipping labels directly from your shop using our %1$sOrlen Paczka integration%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-context-cross-op-pl" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_inpost_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_PACZKOMATY_INPOST_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'inpost',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products via InPost? Create the shipments and generate shipping labels directly from your shop using our %1$sInPost integration%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-context-cross-inpost-pl" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * @param array $phrases_in .
	 */
	private function create_dpd_uk_contextual_info( array $phrases_in ) {
		if ( ! defined( 'WOOCOMMERCE_DPD_UK_VERSION' ) ) {
			$this->add_hookable(
				new Flexible_Shipping_Contextual_Info(
					self::METHOD_TITLE_AND_METHOD_DESCRIPTION_ELEMENTS,
					'dpd_uk',
					$phrases_in,
					sprintf(
					// Translators: link.
						__( 'Sending your products via DPD UK? Create the shipments and generate shipping labels directly from your shop using our %1$sDPD UK integration%2$s', 'flexible-shipping' ),
						'<a class="oct-btn-more" href="https://octol.io/fs-cross-dpd-uk" target="_blank">',
						'</a>'
					)
				)
			);
		}
	}

	/**
	 * Crate default contextual info.
	 *
	 * @param array $phrases_not_in .
	 */
	private function create_default_contextual_info( array $phrases_not_in ) {
		$this->add_hookable(
			new Flexible_Shipping_Contextual_Info(
				self::METHOD_TITLE_ELEMENT,
				'other',
				[],
				$this->create_html_for_default_contextual_info(),
				$phrases_not_in
			)
		);
	}

	/**
	 * @return string
	 */
	private function create_html_for_default_contextual_info() {
		if ( $this->is_base_location_country_pl() ) {
			return __( 'Check our further shipping integrations with DPD, DHL, InPost, eNadawca and Orlen Paczka.', 'flexible-shipping' ) . '&nbsp;&nbsp;' .
				sprintf(
					// Translators: link.
					__( '%1$sAdd integrations%2$s', 'flexible-shipping' ),
					'<a class="oct-btn-more" href="https://octol.io/fs-context-cross-shipping-integrations-pl" target="_blank">',
					'</a>'
				);
		} elseif ( $this->is_base_location_country_gb() ) {
			return __( 'Check our further shipping integration with DPD UK and FedEx / UPS live rates plugins.', 'flexible-shipping' ) . '&nbsp;&nbsp;' .
				sprintf(
					// Translators: link.
					__( '%1$sAdd integration%2$s', 'flexible-shipping' ),
					'<a class="oct-btn-more" href="https://octol.io/fs-integrations" target="_blank">',
					'</a>'
				);
		} elseif ( ! defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' ) ) {
			return __( 'Upgrade from the free version to the Flexible Shipping PRO and create even the most complex shipping scenarios with ease.', 'flexible-shipping' ) . '&nbsp;&nbsp;' .
				sprintf(
					// Translators: link.
					__( '%1$sUpgrade Now%2$s', 'flexible-shipping' ),
					'<a class="oct-btn-more" href="https://octol.io/fs-context-fs-pro" target="_blank">',
					'</a>'
				);
		} else {
			return '';
		}
	}

}
