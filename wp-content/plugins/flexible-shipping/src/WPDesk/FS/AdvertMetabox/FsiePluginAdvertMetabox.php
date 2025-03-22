<?php

namespace WPDesk\FS\AdvertMetabox;

use FSVendor\Octolize\Brand\Assets\AdminAssets;
use FSVendor\Octolize\Brand\UpsellingBox\SettingsSidebar;
use FSVendor\Octolize\Brand\UpsellingBox\ShippingMethodInstanceShouldShowStrategy;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use WPDesk\FS\TableRate\ShippingMethodSingle;


class FsiePluginAdvertMetabox implements Hookable, HookableCollection {

	use HookableParent;

	private string $assets_url;

	public function __construct( string $assets_url ) {
		$this->assets_url = $assets_url;
	}

	public function hooks() {
		if ( ! defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' ) || defined( 'FLEXIBLE_SHIPPING_IMPORT_EXPORT_VERSION' ) ) {

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
					__( 'Extend the Flexible Shipping capabilities with functional add-ons', 'flexible-shipping' ),
					[
						__( 'Calculate the shipping cost based on your custom locations or the WooCommerce defaults', 'flexible-shipping' ),
						__( 'Define shipping cost for each Vendor / Product Author in your marketplace', 'flexible-shipping' ),
						__( 'Move, replace, update or backup multiple shipping methods with Import / Export feature', 'flexible-shipping' ),
					],
					get_locale() === 'pl_PL' ? 'https://octol.io/fs-info-addons-pl' : 'https://octol.io/addons-box-fs',
					__( 'Buy Flexible Shipping Add-ons', 'flexible-shipping' ),
					1200
				) )->hooks();
			}
		);

		$this->hooks_on_hookable_objects();
	}
}
