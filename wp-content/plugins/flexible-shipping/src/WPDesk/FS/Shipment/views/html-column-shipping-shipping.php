<?php
/**
 * @var array    $shipping .
 * @var string[] $statuses .
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="shipping">
	<div class="shipping-status">
		<a class="icon-status icon-status-<?php echo esc_attr( $shipping['status'] ); ?> tips" href="<?php echo esc_url( $shipping['url'] ); ?>" data-tip="<?php echo esc_attr( $statuses[ $shipping['status'] ] ); ?>">
			<?php echo esc_html( $statuses[ $shipping['status'] ] ); ?>
		</a>

		<?php do_action( 'flexible_shipping_shipping_status_html', $shipping ); ?>
	</div>
	<div class="shipping-actions order_actions">
		<?php if ( ! empty( $shipping['label_url'] ) ) : ?>
			<a class="button tips get-label" target="_blank" href="<?php echo esc_url( $shipping['label_url'] ); ?>" data-tip="<?php esc_attr_e( 'Get label for: ', 'flexible-shipping' ); ?><?php echo esc_attr( $shipping['tracking_number'] ); ?>">
				<?php esc_html_e( 'Get label for: ', 'flexible-shipping' ); ?><?php echo wp_kses_post( $shipping['tracking_number'] ); ?>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $shipping['tracking_url'] ) ) : ?>
			<a class="button tips track" target="_blank" href="<?php echo esc_url( $shipping['tracking_url'] ); ?>" data-tip="<?php esc_attr_e( 'Track shipment for: ', 'flexible-shipping' ); ?><?php echo esc_attr( $shipping['tracking_number'] ); ?>">
				<?php esc_html_e( 'Track shipment for: ', 'flexible-shipping' ); ?><?php echo wp_kses_post( $shipping['tracking_number'] ); ?>
			</a>
		<?php endif; ?>

		<?php do_action( 'flexible_shipping_shipping_actions_html', $shipping ); ?>
	</div>
	<div style="clear: both;"></div>
	<?php do_action( 'flexible_shipping_shipping_html', $shipping ); ?>
</div>
