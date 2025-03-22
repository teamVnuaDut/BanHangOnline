<?php
/**
 * Free shipping notice.
 * This template can be overridden by copying it to yourtheme/flexible-shipping/free-shipping/notice.php
 *
 * @author  Octolize
 * @var string $notice_text
 * @var string $zero_value
 * @var bool   $show_progress_bar
 * @var float  $percentage
 * @var string $free_shipping_threshold
 * @var string $button_url
 * @var string $button_label
 */

?>
<div class="fs-free-shipping-notice-and-button-wrapper">
	<div class="fs-free-shipping-notice-text-and-progress-bar-wrapper">
		<div class="fs-free-shipping-notice-contents">
			<div class="fs-free-shipping-notice-text">
				<?php echo wp_kses_post( $notice_text ); ?>
			</div>
			<?php if ( $show_progress_bar ) : ?>
				<div class="fs-free-shipping-notice-progress-bar-wrapper">
					<div class="fs-free-shipping-notice-opening-value"><?php echo wp_kses_post( $zero_value ); ?></div>
					<div class="fs-free-shipping-notice-progress-bar"><span
							style="width:<?php echo esc_attr( $percentage ); ?>%;"></span></div>
					<div
						class="fs-free-shipping-notice-closing-value"><?php echo wp_kses_post( $free_shipping_threshold ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( ! empty( $button_label ) && ! empty( $button_url ) ) : ?>
		<div class="fs-free-shipping-notice-continue-shopping-button-wrapper">
			<a class="button flexible-shipping-free-shipping-button"
			   href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_label ); ?></a>
		</div>
	<?php endif; ?>
</div>
