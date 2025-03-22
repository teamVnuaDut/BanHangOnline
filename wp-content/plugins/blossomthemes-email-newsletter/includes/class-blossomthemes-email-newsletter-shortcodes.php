<?php
/**
 * Frontend view of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */
class Blossomthemes_Email_Newsletter_Shortcodes {

	function __construct() {
		add_shortcode( 'BTEN', array( $this, 'blossomthemes_email_newsletter_shortcode_callback' ) );
		add_action( 'wp_ajax_subscription_response', array( $this, 'blossomthemes_email_newsletter_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_subscription_response', array( $this, 'blossomthemes_email_newsletter_ajax_callback' ) );
	}

	// function to generate shortcode
	function blossomthemes_email_newsletter_shortcode_callback( $atts, $content = '' ) {
		$obj = new Blossomthemes_Email_Newsletter_Functions();

		$atts                                       = shortcode_atts(
			array(
				'id'         => '',
				'html_class' => '',
			),
			$atts,
			'BTEN'
		);
		$atts['id']                                 = absint( $atts['id'] );
			$rrsb_bg                                = '';
			$rrsb_font                              = '';
			$blossomthemes_email_newsletter_setting = get_post_meta( $atts['id'], 'blossomthemes_email_newsletter_setting', true );
			$settings                               = get_option( 'blossomthemes_email_newsletter_settings', true );
			$rrsb_option                            = ! empty( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) : 'bg-color';

		if ( $rrsb_option == 'image' ) {
			$overlay = isset( $blossomthemes_email_newsletter_setting['appearance']['overlay'] ) && $blossomthemes_email_newsletter_setting['appearance']['overlay'] == '1' ? ' has-overlay' : ' no-overlay';
			if ( isset( $blossomthemes_email_newsletter_setting['appearance']['bg'] ) && $blossomthemes_email_newsletter_setting['appearance']['bg'] != '' ) {
				$attachment_id           = $blossomthemes_email_newsletter_setting['appearance']['bg'];
				$newsletter_bio_img_size = apply_filters( 'bt_newsletter_img_size', 'full' );
				$image_array             = wp_get_attachment_image_src( $attachment_id, $newsletter_bio_img_size );
				$rrsb_bg                 = 'url(' . $image_array[0] . ') no-repeat';
			}
		} elseif ( isset( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) && $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] != '' ) {
				$rrsb_bg = ! empty( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) : apply_filters( 'bt_newsletter_bg_color', '#ffffff' );
		} elseif ( isset( $settings['appearance']['bgcolor'] ) && $settings['appearance']['bgcolor'] != '' ) {
			$rrsb_bg = ! empty( $settings['appearance']['bgcolor'] ) ? sanitize_text_field( $settings['appearance']['bgcolor'] ) : apply_filters( 'bt_newsletter_bg_color', '#ffffff' );
		}
		if ( isset( $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] ) && $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] != '' ) {
			$rrsb_font = ! empty( $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] ) : apply_filters( 'bt_newsletter_font_color_setting', '#ffffff' );
		} elseif ( isset( $settings['appearance']['fontcolor'] ) && $settings['appearance']['fontcolor'] != '' ) {
			$rrsb_font = ! empty( $settings['appearance']['fontcolor'] ) ? sanitize_text_field( $settings['appearance']['fontcolor'] ) : apply_filters( 'bt_newsletter_font_color_setting', '#ffffff' );
		}

				ob_start();
		?>
				<div class="blossomthemes-email-newsletter-wrapper
				<?php
				if ( isset( $rrsb_option ) && $rrsb_option == 'image' ) {
					echo ' bg-img', $overlay;}
				?>
				<?php echo esc_attr( $atts['html_class'] ); ?>" id="boxes-<?php echo esc_attr( $atts['id'] ); ?>" style="background: <?php echo esc_attr( $rrsb_bg ); ?>; color: <?php echo esc_attr( $rrsb_font ); ?> ">

					<?php
					$inner_wrap = apply_filters( 'bt_newsletter_shortcode_inner_wrap_display', false );
					if ( $inner_wrap ) {
						do_action( 'bt_newsletter_shortcode_inner_wrap_start' );
					}
					?>

					<div class="text-holder" >
						<?php
						if ( get_the_title( $atts['id'] ) ) {
							$title = get_the_title( $atts['id'] );
							echo '<h3>' . esc_attr( $title ) . '</h3>'; }
						?>
						<?php
						if ( isset( $blossomthemes_email_newsletter_setting['appearance']['note'] ) && $blossomthemes_email_newsletter_setting['appearance']['note'] != '' ) {
							$note = $blossomthemes_email_newsletter_setting['appearance']['note'];
							echo '<span>' . esc_attr( $note ) . '</span>';
						}
						?>
					</div>
					<form id="blossomthemes-email-newsletter-<?php echo esc_attr( $atts['id'] ); ?>" class="blossomthemes-email-newsletter-window-<?php echo esc_attr( $atts['id'] ); ?>">
						<?php
						$val = isset( $blossomthemes_email_newsletter_setting['field']['select'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['field']['select'] ) : 'email';
						if ( $val == 'email' ) {
							?>
							<input type="text" name="subscribe-email" required="required" class="subscribe-email-<?php echo esc_attr( $atts['id'] ); ?>" value="" placeholder="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) && $blossomthemes_email_newsletter_setting['field']['email_placeholder'] != '' ? esc_attr( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) : __( 'Your Email', 'blossomthemes-email-newsletter' ); ?>">
							<?php
						} else {
							?>
							<input type="text" name="subscribe-fname" required="required" class="subscribe-fname-<?php echo esc_attr( $atts['id'] ); ?>" value="" placeholder="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['first_name_placeholder'] ) && $blossomthemes_email_newsletter_setting['field']['first_name_placeholder'] != '' ? esc_attr( $blossomthemes_email_newsletter_setting['field']['first_name_placeholder'] ) : __( 'Your Name', 'blossomthemes-email-newsletter' ); ?>">

							<input type="text" name="subscribe-email" required="required" class="subscribe-email-<?php echo esc_attr( $atts['id'] ); ?>" value="" placeholder="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) && $blossomthemes_email_newsletter_setting['field']['email_placeholder'] != '' ? esc_attr( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) : __( 'Your Email', 'blossomthemes-email-newsletter' ); ?>">
							<?php
						}
						if ( isset( $blossomthemes_email_newsletter_setting['appearance']['gdpr'] ) && $blossomthemes_email_newsletter_setting['appearance']['gdpr'] == '1' ) {
							?>
						<label for="subscribe-confirmation-<?php echo esc_attr( $atts['id'] ); ?>">
							<div class="subscribe-inner-wrap">
								<input type="checkbox" class="subscribe-confirmation-<?php echo esc_attr( $atts['id'] ); ?>" name="subscribe-confirmation" id="subscribe-confirmation-<?php echo esc_attr( $atts['id'] ); ?>" required/><span class="check-mark"></span>
								<span class="text">
									<?php
									$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
									$gdprmsg                                 = isset( $blossomthemes_email_newsletter_settings['gdpr-msg'] ) && $blossomthemes_email_newsletter_settings['gdpr-msg'] != '' ? $blossomthemes_email_newsletter_settings['gdpr-msg'] : __( 'By checking this, you agree to our Privacy Policy.', 'blossomthemes-email-newsletter' );
									echo wp_kses_post( $gdprmsg );
									?>
								</span>
							</div>
						</label>
							<?php
						}
						?>
						<div id="loader-<?php echo esc_attr( $atts['id'] ); ?>" style="display: none">
							<div class="table">
								<div class="table-row">
									<div class="table-cell">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="51px" height="51px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"> <g transform="rotate(0 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.9166666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(30 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.8333333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(60 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(90 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.6666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(120 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5833333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(150 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(180 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.4166666666666667s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(210 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.3333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(240 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(270 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.16666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(300 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.08333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(330 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animate> </rect> </g> <!-- [ldio] generated by https://loading.io/ --></svg>
									</div>
								</div>
							</div>
						</div>
						<input type="submit" name="subscribe-submit" class="subscribe-submit-<?php echo esc_attr( $atts['id'] ); ?>" value="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['submit_label'] ) && $blossomthemes_email_newsletter_setting['field']['submit_label'] != '' ? esc_attr( $blossomthemes_email_newsletter_setting['field']['submit_label'] ) : __( 'Subscribe', 'blossomthemes-email-newsletter' ); ?>">
						<?php wp_nonce_field( 'subscription_response', 'bten_subscription_nonce_' . esc_attr( $atts['id'] ) . '' ); ?>
					</form>

					<?php
					$inner_wrap = apply_filters( 'bt_newsletter_shortcode_inner_wrap_display', false );
					if ( $inner_wrap ) {
						do_action( 'bt_newsletter_shortcode_inner_wrap_close' );
					}
					?>

					<div class="bten-response" id="bten-response-<?php echo esc_attr( $atts['id'] ); ?>"><span></span></div>
					<div id="mask-<?php echo esc_attr( $atts['id'] ); ?>"></div>
				</div>
				<?php
				global $post;
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
						$style = '<style>
                            #mask-' . esc_attr( $atts['id'] ) . ' {
                              position: fixed;
                              width: 100%;
                              height: 100%;
                              left: 0;
                              top: 0;
                              z-index: 9000;
                              background-color: #000;
                              display: none;
                            }

                            #boxes-' . esc_attr( $atts['id'] ) . ' #dialog {
                              width: 750px;
                              height: 300px;
                              padding: 10px;
                              background-color: #ffffff;
                              font-family: "Segoe UI Light", sans-serif;
                              font-size: 15pt;
                            }

                            
                            #loader-' . esc_attr( $atts['id'] ) . ' {
                                position: absolute;
                                top: 27%;
                                left: 0;
                                width: 100%;
                                height: 80%;
                                text-align: center;
                                font-size: 50px;
                            }

                            #loader-' . esc_attr( $atts['id'] ) . ' .table{
                                display: table;
                                width: 100%;
                                height: 100%;
                            }

                            #loader-' . esc_attr( $atts['id'] ) . ' .table-row{
                                display: table-row;
                            }

                            #loader-' . esc_attr( $atts['id'] ) . ' .table-cell{
                                display: table-cell;
                                vertical-align: middle;
                            }
                        </style>';
						echo $obj->bten_minify_css( $style );
						// echo $style;

						$ajax                          =
							'<script>
                            jQuery(function() { 
                                jQuery(document).on("submit","form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '", function(e){
                                e.preventDefault();
                                jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '").attr("disabled", "disabled" );
                                var email = jQuery(".subscribe-email-' . esc_attr( $atts['id'] ) . '").val();
                                var fname = jQuery(".subscribe-fname-' . esc_attr( $atts['id'] ) . '").val();
                                var confirmation = jQuery(".subscribe-confirmation-' . esc_attr( $atts['id'] ) . '").val();
                                var sid = ' . esc_attr( $atts['id'] ) . ';
                                var nonce = jQuery("#bten_subscription_nonce_' . esc_attr( $atts['id'] ) . '").val();
                                    jQuery.ajax({
                                        type : "post",
                                        dataType : "json",
                                        url : bten_ajax_data.ajaxurl,
                                        data : {action: "subscription_response", email : email, fname : fname, sid : sid, confirmation : confirmation, nonce : nonce},
                                        beforeSend: function(){
                                            jQuery("#loader-' . esc_attr( $atts['id'] ) . '").fadeIn(500);
                                        },
                                        success: function(response){
                                            jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '").attr("disabled", "disabled" );';
										$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
										$option        = isset( $bten_settings['thankyou-option'] ) ? esc_attr( $bten_settings['thankyou-option'] ) : 'text';
										$ajax         .= 'if(response.type === "success") {';
				if ( $option == 'text' ) {
					$ajax .= 'jQuery("#bten-response-' . esc_attr( $atts['id'] ) . ' span").html(response.message);jQuery("#bten-response-' . esc_attr( $atts['id'] ) . '").fadeIn("slow").delay("3000").fadeOut("3000",function(){
                                                    jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '").removeAttr("disabled", "disabled" );
                                                    jQuery("form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '").find("input[type=text]").val("");
                                                    jQuery("form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '").find("input[type=checkbox]").prop("checked", false);
                                                });';
				} else {
					$selected_page = isset( $bten_settings['page'] ) ? esc_attr( $bten_settings['page'] ) : '';
					$url           = get_permalink( $selected_page );
					$ajax         .= 'window.location.href = "' . esc_url( $url ) . '"';
				}

										$ajax .= '}
                                        else{
                                            jQuery("#bten-response-' . esc_attr( $atts['id'] ) . ' span").html(response.message);jQuery("#bten-response-' . esc_attr( $atts['id'] ) . '").fadeIn("slow").delay("3000").fadeOut("3000",function(){
                                                    jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '").removeAttr("disabled", "disabled" );
                                                    jQuery("form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '").find("input[type=text]").val("");
                                                    jQuery("form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '").find("input[type=checkbox]").prop("checked", false); 

                                                });
                                            }
                                        },
                                        complete: function(){
                                            jQuery("#loader-' . esc_attr( $atts['id'] ) . '").fadeOut(500);             
                                        } 
                                    });  
                                });
                            });
                            </script>';
				echo $obj->bten_minify_js( $ajax );
				$output = ob_get_contents();
				ob_end_clean();
				return apply_filters( 'blossomthemes_newsletter_shortcode_filter', $output, $atts );
	}

	// function to generate ajax actions
	function blossomthemes_email_newsletter_ajax_callback() {
		if ( ! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( $_POST['nonce'], 'subscription_response' )
		) {
			$result['type']    = 'error';
			$result['message'] = __( 'Sorry, your nonce did not verify.', 'blossomthemes-email-newsletter' );
			echo json_encode( $result );
			exit;
		}

		$email = sanitize_email( $_POST['email'] );
		$fname = isset( $_POST['fname'] ) ? esc_attr( $_POST['fname'] ) : '';
		$lname = ' ';

		$arr['subscriber']['email'] = $email;
		$sid                        = intval( $_POST['sid'] );
		$to                         = $email;

		/*
		if ( !preg_match('/^[a-z A-Z]+$/', $fname ) ) {
			$result['type'] = 'error';
			$result['message'] = __( 'Please enter a valid name.', 'blossomthemes-email-newsletter' );
			echo json_encode( $result );
			exit;
		}*/

		if ( ! is_email( $to ) ) {
			$result['type']    = 'error';
			$result['message'] = __( 'Please enter a valid email.', 'blossomthemes-email-newsletter' );
			echo json_encode( $result );
			exit;
		}

		$subject     = 'Subscribe To Newsletter';
		$admin_email = get_option( 'admin_email' );
		if ( $admin_email != '' ) {
			$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
			$platform                                = $blossomthemes_email_newsletter_settings['platform'];

			if ( $platform == 'mailerlite' ) {
				$obj      = new Blossomthemes_Email_Newsletter_Mailerlite();
				$response = $obj->bten_mailerlite_action( $email, $sid, $fname );
				if ( $response == 200 ) {
					$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );
					$result['type']    = 'success';
					$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : __( 'Successfully subscribed.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				} else {

					$result['type']    = 'error';
					$result['message'] = isset( $response ) ? $response : __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				}
			} elseif ( $platform == 'mailchimp' ) {
				$obj      = new Blossomthemes_Email_Newsletter_Mailchimp();
				$response = $obj->bten_mailchimp_action( $email, $sid, $fname );
				if ( $response == 200 ) {
					$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
					if ( isset( $bten_settings['mailchimp']['enable_notif'] ) && $blossomthemes_email_newsletter_settings['mailchimp']['enable_notif'] == '1' ) {
						$result['type']    = 'success';
						$result['message'] = __( 'Please check your email for confirmation.', 'blossomthemes-email-newsletter' );

						echo json_encode( $result );
						exit;
					} else {
						$result['type']    = 'success';
						$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Successfully subscribed.';
						echo json_encode( $result );
						exit;
					}
				} else {
					$result['type']    = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				}
			} elseif ( $platform == 'convertkit' ) {
				$obj           = new Blossomthemes_Email_Newsletter_Convertkit();
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
				$response      = $obj->bten_convertkit_action( $email, $sid, $fname, $lname );
				if ( $response == 200 ) {
					$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );
					$result['type']    = 'success';
					$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Successfully subscribed.';
					echo json_encode( $result );
					exit;
				} else {
					$result['type']    = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				}
			} elseif ( $platform == 'getresponse' ) {
				$obj           = new Blossomthemes_Email_Newsletter_GetResponse();
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
				$response      = $obj->bten_getresponse_action( $email, $sid, $fname );
				if ( isset( $response['response'] ) && $response['response'] == 200 ) {
					$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );
					$result['type']    = 'success';
					$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Successfully subscribed.';

					echo json_encode( $result );
					exit;
				} else {
					$result['type']         = 'error';
					$result['message']      = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					$result['errorMessage'] = isset( $response['log']['errorMessage'] ) ? $response['log']['errorMessage'] : '';
					echo json_encode( $result );
					exit;
				}
			} elseif ( $platform == 'activecampaign' ) {
				$obj           = new Blossomthemes_Email_Newsletter_ActiveCampaign();
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
				$response      = $obj->bten_activecampaign_action( $email, $sid, $fname );
				if ( $response['response'] == 200 ) {
					$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );
					$result['type']    = 'success';
					$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Successfully subscribed.';

					echo json_encode( $result );
					exit;
				} else {
					$result['type']    = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				}
			} elseif ( $platform == 'aweber' ) {
				$obj           = new Blossomthemes_Email_Newsletter_AWeber();
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
				$response      = $obj->bten_aweber_action( $email, $sid, $fname );
				if ( $response['status'] == true ) {
					$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );
					$result['type']    = 'success';
					$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Successfully subscribed.';

					echo json_encode( $result );
					exit;
				} else {
					$result['type']    = 'error';
					$result['message'] = $response['log']['errorMessage'] ?? __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				}
			} elseif ( $platform === 'sendinblue' ) {
				$sendinblue_object = new Blossomthemes_Email_Newsletter_Sendinblue();
				$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );

				$response = $sendinblue_object->action_form_submission( $email, $sid, $fname );

				if ( 'success' === $response ) {
					$bten_settings     = get_option( 'blossomthemes_email_newsletter_settings', true );
					$result['type']    = 'success';
					$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Successfully subscribed.';

					echo json_encode( $result );
					exit;
				} else {
					$result['type']    = 'error';
					$result['message'] = __( 'Error in subscription.', 'blossomthemes-email-newsletter' );
					echo json_encode( $result );
					exit;
				}
			} else {
				$result['type']    = 'error';
				$result['message'] = isset( $bten_settings['msg'] ) ? $bten_settings['msg'] : 'Error in subscription. Please check the platform and API key used in the Settings.';
				echo json_encode( $result );
				exit;
			}
		}
	}
}
new Blossomthemes_Email_Newsletter_Shortcodes();
