<?php
declare( strict_types=1 );

namespace Automattic\WooCommerce\Analytics\Internal\FullSyncCheck;

use Automattic\WooCommerce\Admin\Notes\Note;
use Automattic\WooCommerce\Admin\Notes\NoteTraits;
use Automattic\WooCommerce\Admin\Notes\Notes;
use Automattic\WooCommerce\Internal\Admin\Notes\MerchantEmailNotifications;

defined( 'ABSPATH' ) || exit;

/**
 * Class AdminFullSyncEmailNotification
 *
 * @package Automattic\WooCommerce\Admin\Internal\FullSyncCheck
 */
class AdminFullSyncEmailNotification {
	/**
	 * Note traits.
	 */
	use NoteTraits;

	/**
	 * Name of the note for use in the database.
	 */
	const NOTE_NAME = 'woocommerce-analytics-sync-complete';

	/**
	 * Get the note to send to the merchant when the full sync is complete.
	 */
	public static function get_note(): Note {

		// Prevent duplicate notes from being created (always returns lowest ID note).
		$existing_note = Notes::get_note_by_name( self::NOTE_NAME );
		if ( $existing_note ) {
			return $existing_note;
		}

		$note = new Note();
		$note->set_title( __( 'Your order attribution report is ready!', 'woocommerce-analytics' ) );

		// If you're updating the following please use sprintf to separate HTML tags.
		// https://github.com/woocommerce/woocommerce-admin/pull/6617#discussion_r596889685.
		$content_lines = array(
			'{greetings}<br/><br/>',
			/* translators: %1$s: open strong tag, %2$s: close strong tag, %3$s: line break */
			sprintf( __( 'Great news! We\'ve processed your data, and your %1$sorder attribution report%2$s is now ready to view.%3$s', 'woocommerce-analytics' ), '<strong>', '</strong>', '<br/><br/>' ),
			/* translators: %1$s is an open anchor tag (<a>) and %2$s is a close link tag and line breaks (</a><br/><br/>). */
			sprintf( __( '%1$sView my report%2$s', 'woocommerce-analytics' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-admin&path=/analytics/order-attribution' ) ) . '">', '</a><br/><br/>' ),
			/* translators: %s: line break */
			sprintf( __( 'Use this data to:%s', 'woocommerce-analytics' ), '<br/><br/>' ),
			/* translators: %s: line break */
			sprintf( __( '- See which marketing channels drive your best sales.%s', 'woocommerce-analytics' ), '<br/>' ),
			/* translators: %s: line break */
			sprintf( __( '- Track your customer journey from first click to purchase.%s', 'woocommerce-analytics' ), '<br/>' ),
			/* translators: %s: line break */
			sprintf( __( '- Gain a deeper understanding of your store’s performance.%s', 'woocommerce-analytics' ), '<br/><br/>' ),
			/* translators: %s: line break */
			sprintf( __( 'We hope this info helps you continue to grow your business.%s', 'woocommerce-analytics' ), '<br/><br/>' ),
			/* translators: %s: line break */
			sprintf( __( 'Happy selling, %s', 'woocommerce-analytics' ), '<br/>' ),
			/* translators: %1$s: open strong tag, %2$s: close strong tag and line breaks */
			sprintf( __( '%1$sThe Woo Team%2$s', 'woocommerce-analytics' ), '<strong>', '</strong><br/><br/>' ),
		);

		$additional_data = array(
			'role' => 'administrator',
		);

		$note->set_content( implode( '', $content_lines ) );
		$note->set_content_data( (object) $additional_data );
		$note->set_type( Note::E_WC_ADMIN_NOTE_EMAIL );
		$note->set_name( self::NOTE_NAME );
		$note->set_date_reminder( null );
		$note->set_image( null );
		$note->set_source( 'woocommerce-analytics' );
		return $note;
	}

	/**
	 * Send the email notification to the merchant with the note.
	 */
	public static function send_email_notification(): void {
		$note = self::get_note();
		$note->save();

		// If the note is already sent, don't send it again.
		if ( $note->get_status() === Note::E_WC_ADMIN_NOTE_SENT ) {
			return;
		}

		MerchantEmailNotifications::send_merchant_notification( $note );
		$note->set_status( Note::E_WC_ADMIN_NOTE_SENT );
		$note->save();
	}
}
