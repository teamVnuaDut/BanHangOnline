<?php
function bten_register_privacy_policy_template() {

	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = wp_kses_post( apply_filters( 'rttk_privacy_policy_content', __( '
	We collect information about your name and email during subscription. This information may include, but is not limited to, your name, email address and any other details that might be requested from you for the purpose of subscribing your email.
	Handling this data also allows us to:
	- Send you important account/order/service information.
	- Respond to your queries or complaints.
	- Set up and administer your account, provide technical and/or customer support, and to verify your identity.
	', 'blossomthemes-email-newsletter' ) ) );

	$content .= "\n\n";

	wp_add_privacy_policy_content( 'WP Travel Engine', wpautop( $content ) );
}
add_action( 'admin_init', 'bten_register_privacy_policy_template' );
