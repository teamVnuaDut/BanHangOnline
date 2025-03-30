<?php

namespace Automattic\WooCommerce\Analytics\Utilities;

/**
 * Class Tracking
 *
 * @package Automattic\WooCommerce\Analytics\Utilities
 */
class Tracking {

	const TRACKING_NAMESPACE = 'woocommerceanalytics_';

	/**
	 * Record a tracks event.
	 *
	 * @param string $event_name The event name to record.
	 * @param array  $properties Array of properties to include with the event.
	 */
	public static function tracks_event( $event_name, $properties = array() ) {

		$name = self::TRACKING_NAMESPACE . $event_name;

		if ( ! class_exists( 'WC_Tracks' ) ) {
			if ( ! defined( 'WC_ABSPATH' ) || ! file_exists( WC_ABSPATH . 'includes/tracks/class-wc-tracks.php' ) ) {
				return;
			}

			include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks.php';
			include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks-event.php';
			include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks-client.php';
			include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks-footer-pixel.php';
			include_once WC_ABSPATH . 'includes/tracks/class-wc-site-tracking.php';
		}

		\WC_Tracks::record_event( $name, $properties );
	}

	/**
	 * Prepare and send a full sync started event.
	 *
	 * @param array $full_status Full sync status.
	 */
	public static function track_full_sync_completed( $full_status ) {
		$started      = $full_status['started'] ?? 0;
		$finished     = $full_status['finished'] ?? 0;
		$duration     = $finished - $started;
		$orders_count = $full_status['progress']['woocommerce_analytics']['total'] ?? 0;

		$full_sync_properties = array(
			'sync_started'      => $started,
			'sync_finished'     => $finished,
			'sync_duration'     => $duration,
			'sync_orders_count' => $orders_count,
		);

		self::tracks_event( 'full_sync_completed', $full_sync_properties );
	}

	/**
	 * Track when a manual sync action occurs.
	 *
	 * @param string $action The sync action - either "started" or "stopped".
	 */
	public static function track_manual_sync_action( $action ) {
		if ( ! in_array( $action, array( 'started', 'stopped' ), true ) ) {
			return;
		}

		$properties = array(
			'action'    => $action,
			'timestamp' => time(),
		);

		self::tracks_event( 'manual_sync', $properties );
	}
}
