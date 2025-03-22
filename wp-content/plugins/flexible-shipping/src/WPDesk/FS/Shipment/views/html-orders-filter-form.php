<?php
/**
 * @var string[] $integrations .
 * @var string[] $statuses     .
 * @var string   $integration  .
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="alignleft actions">
	<select name="flexible_shipping_integration_filter">
		<option value=""><?php esc_html_e( 'All shippings', 'flexible-shipping' ); ?></option>
		<optgroup label="<?php esc_attr_e( 'Integration', 'flexible-shipping' ); ?>">

			<?php foreach ( $integrations as $key => $val ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $integration ); ?>><?php echo wp_kses_post( $val ); ?></option>
			<?php endforeach; ?>
		</optgroup>
	</select>

	<select name="flexible_shipping_status_filter">
		<option value=""><?php esc_html_e( 'All shippings', 'flexible-shipping' ); ?></option>
		<optgroup label="<?php esc_attr_e( 'Shipment status', 'flexible-shipping' ); ?>">
			<?php foreach ( $statuses as $key => $val ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $integration ); ?>><?php echo wp_kses_post( $val ); ?></option>
			<?php endforeach; ?>
		</optgroup>
	</select>
</div>
