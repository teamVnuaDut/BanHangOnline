<?php
/**
 * Class TrackingData
 */

namespace WPDesk\FS\ProFeatures\Tracker;

/**
 * Can provide pro features tracking data.
 */
class TrackingData {

	const OPTION_NAME = 'fs-pro-features-tracking-data';
	const SHOW_COUNT  = 'show_count';
	const HIDE_COUNT  = 'hide_count';

	/**
	 * @return void
	 */
	public function increase_show_count(): void {
		$this->increase_value( self::SHOW_COUNT );
	}

	/**
	 * @return void
	 */
	public function increase_hide_count(): void {
		$this->increase_value( self::HIDE_COUNT );
	}

	/**
	 * @return int[]
	 */
	public function get_tracking_data(): array {
		$tracking_data = get_option( self::OPTION_NAME, [] );

		$tracking_data[ self::SHOW_COUNT ] = (int) ( $tracking_data[ self::SHOW_COUNT ] ?? 0 );
		$tracking_data[ self::HIDE_COUNT ] = (int) ( $tracking_data[ self::HIDE_COUNT ] ?? 0 );

		return $tracking_data;
	}

	/**
	 * @param string $option .
	 *
	 * @return void
	 */
	private function increase_value( string $option ): void {
		$tracking_data = $this->get_tracking_data();
		$tracking_data[ $option ]++;
		$this->save_tracking_data( $tracking_data );
	}

	/**
	 * @param int[] $tracking_data .
	 */
	private function save_tracking_data( array $tracking_data ): void {
		update_option( self::OPTION_NAME, $tracking_data, false );
	}
}
