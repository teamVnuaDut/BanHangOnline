<?php

namespace WPDesk\FS\TableRate\ShippingMethodsIntegration;

use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FSVendor\WPDesk\PluginBuilder\Plugin\HookableParent;

/**
 * Integration class.
 * Add all required hooks.
 */
class Integration implements Hookable, HookableCollection {

	use HookableParent;

	public function hooks() {
		$this->add_hookable( new SettingsFields() );
		$this->add_hookable( new ShippingRate() );
		$this->add_hookable( new OrderMetaData() );
		$this->add_hookable( new Tracker() );

		$this->hooks_on_hookable_objects();
	}

}
