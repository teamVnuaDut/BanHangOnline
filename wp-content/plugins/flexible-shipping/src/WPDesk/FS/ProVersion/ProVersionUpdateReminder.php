<?php

namespace WPDesk\FS\ProVersion;

use FSVendor\WPDesk\Notice\Notice;
use FSVendor\WPDesk\Notice\PermanentDismissibleNotice;
use FSVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can display PRO version compatibility reminder.
 */
class ProVersionUpdateReminder implements Hookable {

	/**
	 * @var bool
	 */
	private $is_pl;

	/**
	 * @param bool $is_pl
	 */
	public function __construct( bool $is_pl ) {
		$this->is_pl = $is_pl;
	}


	public function hooks() {
		add_action( 'admin_notices', [ $this, 'add_notice_when_old_pro_version_detected' ] );
	}

	/**
	 * @return void
	 */
	public function add_notice_when_old_pro_version_detected() {
		if ( defined( 'FLEXIBLE_SHIPPING_PRO_VERSION' ) && version_compare( FLEXIBLE_SHIPPING_PRO_VERSION, '2.5', '<' ) ) {
			$notice_name = 'fs-pro-version-warning-' . date( 'YW' );
			new PermanentDismissibleNotice(
				$this->prepare_notice_content(),
				$notice_name,
				Notice::NOTICE_TYPE_WARNING
			);
		}
	}

	/**
	 * @return string
	 */
	private function prepare_notice_content() {
		$my_account_link = $this->is_pl ? 'https://www.wpdesk.pl/moje-konto/api-keys/?utm_source=api-keys&utm_medium=plugin-list&utm_campaign=subscriptions' : 'https://octolize.com/my-account/api-keys/?utm_source=api-keys&utm_medium=plugin-list&utm_campaign=subscriptions';
		$renew_link      = $this->is_pl ? 'https://www.wpdesk.pl/moje-konto/subscriptions/?utm_source=fs-update&utm_medium=plugin-list&utm_campaign=subscriptions' : 'https://octolize.com/my-account/subscriptions/?utm_source=fs-update&utm_medium=plugin-list&utm_campaign=subscriptions';
		return sprintf(
			// Translators: strong, version, /strong, strong, /strong, link, link.
			__(
				'The %1$sFlexible Shipping PRO %2$s%3$s version you are currently using is severely %4$soutdated%5$s. Its further use may result in onward %4$scompatibility issues%5$s. In order to perform the update, please copy your plugin API key from %6$sMy Account / API keys%7$s tab and activate it in your store. If your subscription expired and you don’t own an active one at the moment, please %8$srenew the subscription →%9$s',
				'flexible-shipping'
			),
			'<strong>',
			FLEXIBLE_SHIPPING_PRO_VERSION,
			'</strong>',
			'<strong>',
			'</strong>',
			'<a href="' . $my_account_link . '" target="_blank">',
			'</a>',
			'<a href="' . $renew_link . '" target="_blank">',
			'</a>'
		);
	}

}
