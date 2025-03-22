<?php

namespace WPDesk\FS\AdvertMetabox;

use FSVendor\Octolize\Brand\Assets\AdminAssets;
use FSVendor\Octolize\Brand\UpsellingBox\SettingsSidebar;
use FSVendor\Octolize\Brand\UpsellingBox\ShippingMethodInstanceShouldShowStrategy;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDesk\FS\TableRate\ShippingMethodSingle;


class ProPluginAdvertMetabox implements Hookable, HookableCollection {

	use HookableParent;

	private string $assets_url;

	public function __construct( string $assets_url ) {
		$this->assets_url = $assets_url;
	}

	public function hooks() {
		if ( defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' ) ) {

			return;
		}

		$should_show_strategy = new ShippingMethodInstanceShouldShowStrategy( new \WC_Shipping_Zones(), ShippingMethodSingle::SHIPPING_METHOD_ID );
		$this->add_hookable( new AdminAssets( $this->assets_url, 'fs', $should_show_strategy ) );
		add_action(
			'admin_init',
			function () use ( $should_show_strategy ) {
				( new SettingsSidebar(
					'woocommerce_settings_tabs_shipping',
					$should_show_strategy,
					__( 'Get Flexible Shipping PRO!', 'flexible-shipping' ),
					[
						__( 'Shipping Classes support', 'flexible-shipping' ),
						__( 'Products-based shipping', 'flexible-shipping' ),
						__( 'Quantity-based shipping', 'flexible-shipping' ),
						__( 'Additional Cost', 'flexible-shipping' ),
						__( 'Conditional Logic', 'flexible-shipping' ),
						__( 'Hide the shipping methods', 'flexible-shipping' ),
						__( 'Premium 1-on-1 Support', 'flexible-shipping' ),
					],
					get_locale() === 'pl_PL' ? 'https://octol.io/fs-box-upgrade-pl' : 'https://octol.io/fs-box-upgrade',
					__( 'Upgrade Now', 'flexible-shipping' ),
					1200
				) )->hooks();
			}
		);

		$this->hooks_on_hookable_objects();
	}
}
