<?php
/**
 * SupportPortalForSureCart core integrations file
 *
 * @since 1.0.0
 * @package SureTrigger
 */

namespace SureTriggers\Integrations\SupportPortalForSureCart;

use SureTriggers\Controllers\IntegrationsController;
use SureTriggers\Integrations\Integrations;
use SureTriggers\Traits\SingletonLoader;

/**
 * Class SureTrigger
 *
 * @package SureTriggers\Integrations\SupportPortalForSureCart
 */
class SupportPortalForSureCart extends Integrations {


	use SingletonLoader;

	/**
	 * ID
	 *
	 * @var string
	 */
	protected $id = 'SupportPortalForSureCart';

	/**
	 * SureTrigger constructor.
	 */
	public function __construct() {
		$this->name        = __( 'Support Portal For SureCart', 'suretriggers' );
		$this->description = __( 'Support Portal For SureCart plugin allows customers to request support directly from their SureCart Customer Dashboard, seamlessly integrating a ticketing system with automated notifications and streamlined management within the SureCart interface.', 'suretriggers' );
		$this->icon_url    = SURE_TRIGGERS_URL . 'assets/icons/servicesforsurecart.svg';

		parent::__construct();
	}

	/**
	 * Is Plugin depended plugin is installed or not.
	 *
	 * @return bool
	 */
	public function is_plugin_installed() {
		return class_exists( 'SureCart' ) && defined( 'SURELYWP_SUPPORT_PORTAL' );
	}

}

IntegrationsController::register( SupportPortalForSureCart::class );
