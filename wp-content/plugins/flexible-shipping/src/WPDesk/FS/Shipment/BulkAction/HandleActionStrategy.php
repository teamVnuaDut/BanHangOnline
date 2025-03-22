<?php
/**
 * Class HandleActionStrategy
 */

namespace WPDesk\FS\Shipment\BulkAction;

use Exception;
use FSVendor\WPDesk\Session\SessionFactory;

/**
 * .
 */
class HandleActionStrategy {

	/**
	 * @var SessionFactory
	 */
	private $session_factory;

	/**
	 * @param SessionFactory $session_factory .
	 */
	public function __construct( SessionFactory $session_factory ) {
		$this->session_factory = $session_factory;
	}

	public function get( string $action ) {
		switch ( $action ) {
			case 'flexible_shipping_send':
				return new HandleActionSend( $this->session_factory );
			case 'flexible_shipping_labels':
				return new HandleActionLabels( $this->session_factory );
			case 'flexible_shipping_manifest':
				return new HandleActionManifest( $this->session_factory );
			default:
				throw new Exception( __( 'Bulk Handle action not found', 'flexible-shipping' ) );
		}
	}
}
