<?php
/**
 * Class HandleActionLabels
 */

namespace WPDesk\FS\Shipment\BulkAction;

use Exception;
use FSVendor\WPDesk\FS\Shipment\Exception\UnableToCreateTmpFileException;
use FSVendor\WPDesk\FS\Shipment\Exception\UnableToCreateTmpZipFileException;
use FSVendor\WPDesk\FS\Shipment\Label\LabelsBulkActionHandler;
use FSVendor\WPDesk\FS\Shipment\Label\LabelsFileCreator;
use FSVendor\WPDesk\Session\SessionFactory;

/**
 * .
 */
class HandleActionLabels implements HandleActionStrategyInterface {

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

	/**
	 * @param string $redirect_to .
	 * @param array  $post_ids    .
	 *
	 * @return string
	 */
	public function handle( string $redirect_to, array $post_ids ): string {
		try {
			$labels_bulk_actions_handler = LabelsBulkActionHandler::get_labels_bulk_actions_handler();
			$labels_bulk_actions_handler->bulk_process_orders( $post_ids );

			$labels = $labels_bulk_actions_handler->get_labels_for_shipments();
			if ( 0 === count( $labels ) ) {
				$redirect_to = add_query_arg( 'bulk_flexible_shipping_labels', count( $post_ids ), $redirect_to );

				return add_query_arg( 'bulk_flexible_shipping_no_labels_created', 1, $redirect_to );
			}

			$labels_file_creator = new LabelsFileCreator( $labels );
			$labels_file_creator->create_labels_file();
			$labels['tmp_file']    = $labels_file_creator->get_tmp_file_name();
			$labels['client_file'] = $labels_file_creator->get_file_name();
		} catch ( UnableToCreateTmpZipFileException $zip_file_exception ) {
			$labels['error'] = __( 'Unable to create temporary zip archive for labels. Check temporary folder configuration on server.', 'flexible-shipping' );
		} catch ( UnableToCreateTmpFileException $tmp_file_exception ) {
			$labels['error'] = __( 'Unable to create temporary file for labels. Check temporary folder configuration on server.', 'flexible-shipping' );
		} catch ( \Exception $e ) {
			$labels['error'] = $e->getMessage();
		} catch ( \Throwable $e ) {
			$labels['error'] = $e->getMessage();
		}

		foreach ( $labels as $key => $label ) {
			if ( is_array( $labels[ $key ] ) && isset( $labels[ $key ]['content'] ) ) {
				unset( $labels[ $key ]['content'] );
			}
		}

		$this->session_factory->get_woocommerce_session_adapter()->set( 'flexible_shipping_bulk_labels', $labels );

		return add_query_arg( 'bulk_flexible_shipping_labels', count( $post_ids ), $redirect_to );
	}

}
