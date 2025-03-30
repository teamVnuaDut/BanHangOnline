<?php
/**
 * SureMail core integrations file
 *
 * @since 1.0.0
 * @package SureTrigger
 */

namespace SureTriggers\Integrations\SureMail;

use SureTriggers\Controllers\IntegrationsController;
use SureTriggers\Integrations\Integrations;
use SureTriggers\Traits\SingletonLoader;
use SureMails\Loader;

/**
 * Class SureTrigger
 *
 * @package SureTriggers\Integrations\SureMail
 */
class SureMail extends Integrations {


	use SingletonLoader;

	/**
	 * ID
	 *
	 * @var string
	 */
	protected $id = 'SureMail';

	/**
	 * SureTrigger constructor.
	 */
	public function __construct() {
		$this->name        = __( 'SureMail', 'suretriggers' );
		$this->description = __( 'A simple yet powerful way to create modern forms for your website.', 'suretriggers' );
		$this->icon_url    = SURE_TRIGGERS_URL . 'assets/icons/Suremails.svg';

		parent::__construct();
	}

	/**
	 * Is Plugin depended plugin is installed or not.
	 *
	 * @return bool
	 */
	public function is_plugin_installed() {
		return class_exists( Loader::class );
	}

}

IntegrationsController::register( SureMail::class );
