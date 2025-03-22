<?php

namespace WPDesk\FS\Plugin;

use FSVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeMessage;
use FSVendor\Octolize\Onboarding\PluginUpgrade\PluginUpgradeOnboardingFactory;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk_Plugin_Info;

class UpgradeOnboarding implements Hookable {

	private WPDesk_Plugin_Info $plugin_info;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}

	public function hooks() {
		add_action( 'init', [ $this, 'init_upgrade_onboarding' ] );
	}

	public function init_upgrade_onboarding(): void {
		$upgrade_onboarding = new PluginUpgradeOnboardingFactory(
			$this->plugin_info->get_plugin_name(),
			$this->plugin_info->get_version(),
			$this->plugin_info->get_plugin_file_name()
		);
		$upgrade_onboarding->add_upgrade_message(
			new PluginUpgradeMessage(
				'5.0.0',
				trailingslashit( $this->plugin_info->get_plugin_url() ) . 'vendor_prefixed/octolize/wp-onboarding/assets/images/icon-complex-solution.svg',
				__( 'We\'ve added integration with Live Rates', 'flexible-shipping' ),
				sprintf(
					// Translators: %1$s - <br/><br/>, %2$s - <a href="https://octol.io/fs-tr-adv-live-rates-popup-lr" target="_blank">, %3$s - </a>.
					__( 'You can now take advantage of automatically calculated Live Rates and adjust shipping costs using the shipping cost calculation rules. Precisely define additional charges or discounts based on specific conditions.%1$sCheck out the integration by installing one of our %2$sLive Rates%3$s plugins.', 'flexible-shipping' ),
					'<br/><br/>',
					'<a href="https://octol.io/fs-tr-adv-live-rates-popup-lr" target="_blank">',
					'</a>'
				),
				'',
				''
			)
		);
		$upgrade_onboarding->add_upgrade_message(
			new PluginUpgradeMessage(
				'5.0.0',
				trailingslashit( $this->plugin_info->get_plugin_url() ) . 'vendor_prefixed/octolize/wp-onboarding/assets/images/icon-update-cost.svg',
				__( 'We’ve added integration with any shipping method in WooCommerce', 'flexible-shipping' ),
				__( 'The new feature allows the use of shipping cost calculation rules for all shipping methods available in WooCommerce, including Flat Rate and those added by other plugins. This provides greater control over delivery costs and allows you to take advantage of the existing features of other methods.', 'flexible-shipping' ),
				'',
				''
			)
		);
		$upgrade_onboarding->create_onboarding();
	}
}
