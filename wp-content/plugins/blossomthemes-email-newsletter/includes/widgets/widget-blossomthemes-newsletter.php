<?php
/**
 * Widget Featured
 *
 * @package Rttk
 */

// register widget
function blossomthemes_email_newsletter_featured_widget() {
	register_widget( 'BlossomThemes_Email_Newsletter_Widget' );
}
add_action( 'widgets_init', 'blossomthemes_email_newsletter_featured_widget' );

 /**
  * Adds BlossomThemes_Email_Newsletter_Widget widget.
  */
class BlossomThemes_Email_Newsletter_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'blossomthemes_email_newsletter_widget', // Base ID
			__( 'BlossomThemes: Email Newsletter Widget', 'blossomthemes-email-newsletter' ), // Name
			array( 'description' => __( 'A Newsletter Widget to add Email Subscription Form to your website.', 'blossomthemes-email-newsletter' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $instance['newsletter'] ) ) {
			// Display nothing if called in backend.
			return;
		}
		global $post;
		$obj        = new Blossomthemes_Email_Newsletter_Functions();
		$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$newsletter = ! empty( $instance['newsletter'] ) ? $instance['newsletter'] : '';
		$image      = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$gdpr       = ! empty( $instance['gdpr'] ) ? $instance['gdpr'] : '';
		$unique_id  = bin2hex( random_bytes( 5 ) );

		if ( isset( $image ) && $image != '' ) {
			$icon_img_size = apply_filters( 'bten_icon_header_img_size', 'full' );
		}

		echo $args['before_widget'];
		ob_start();
		if ( $title ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];
		}
		if ( $newsletter == '' ) {
			return;
		}

			$atts['id']                             = absint( $newsletter );
			$blossomthemes_email_newsletter_setting = get_post_meta( $atts['id'], 'blossomthemes_email_newsletter_setting', true );
			$settings                               = get_option( 'blossomthemes_email_newsletter_settings', true );
			$rrsb_fc                                = '';
		if ( isset( $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] ) && $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] != '' ) {
			$rrsb_fc = ! empty( $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['fontcolor'] ) : apply_filters( 'bt_newsletter_font_color_setting', '#ffffff' );
		} elseif ( isset( $settings['appearance']['fontcolor'] ) && $settings['appearance']['fontcolor'] != '' ) {
			$rrsb_fc = ! empty( $settings['appearance']['fontcolor'] ) ? sanitize_text_field( $settings['appearance']['fontcolor'] ) : apply_filters( 'bt_newsletter_font_color_setting', '#ffffff' );
		}
			$rrsb_sc     = isset( $blossomthemes_email_newsletter_setting['appearance']['submitcolor'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['appearance']['submitcolor'] ) : '';
			$rrsb_shc    = isset( $blossomthemes_email_newsletter_setting['appearance']['submithovercolor'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['appearance']['submithovercolor'] ) : '';
			$rrsb_stc    = isset( $blossomthemes_email_newsletter_setting['appearance']['submittextcolor'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['appearance']['submittextcolor'] ) : '';
			$rrsb_sthc   = isset( $blossomthemes_email_newsletter_setting['appearance']['submittexthovercolor'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['appearance']['submittexthovercolor'] ) : '';
			$rrsb_bg     = '';
			$rrsb_option = ! empty( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) : 'bg-color';
		if ( $rrsb_option == 'image' ) {
			$overlay = isset( $blossomthemes_email_newsletter_setting['appearance']['overlay'] ) && $blossomthemes_email_newsletter_setting['appearance']['overlay'] == '1' ? ' has-overlay' : ' no-overlay';
			if ( isset( $blossomthemes_email_newsletter_setting['appearance']['bg'] ) && $blossomthemes_email_newsletter_setting['appearance']['bg'] != '' ) {
				$attachment_id           = $blossomthemes_email_newsletter_setting['appearance']['bg'];
				$newsletter_bio_img_size = apply_filters( 'bt_newsletter_img_size', 'full' );
				$image_array             = wp_get_attachment_image_src( $attachment_id, $newsletter_bio_img_size );
				$rrsb_bg                 = 'url(' . $image_array[0] . ') no-repeat';
			}
		} else {
			if ( isset( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) && $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] != '' ) {
				$rrsb_bg = ! empty( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) ? sanitize_text_field( $blossomthemes_email_newsletter_setting['appearance']['bgcolor'] ) : apply_filters( 'bt_newsletter_bg_color', '#ffffff' );
			} elseif ( isset( $settings['appearance']['bgcolor'] ) && $settings['appearance']['bgcolor'] != '' ) {
				$rrsb_bg = ! empty( $settings['appearance']['bgcolor'] ) ? sanitize_text_field( $settings['appearance']['bgcolor'] ) : apply_filters( 'bt_newsletter_bg_color', '#ffffff' );
			}
		}
			// ob_start();

		?>
				<div class="blossomthemes-email-newsletter-wrapper
				<?php
				if ( isset( $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] ) && $blossomthemes_email_newsletter_setting['appearance']['newsletter-bg-option'] == 'image' ) {
					echo ' bg-img', $overlay; }
				?>
				" id="boxes-<?php echo esc_attr( $atts['id'] ); ?>" style="background: <?php echo esc_attr( $rrsb_bg ); ?>; color:<?php echo esc_attr( $rrsb_fc ); ?>;">
					
					<?php
					$inner_wrap = apply_filters( 'bt_newsletter_widget_inner_wrap_display', false );
					if ( $inner_wrap ) {
						do_action( 'bt_newsletter_widget_inner_wrap_start' );
					}
					?>

					<?php if ( isset( $image ) && $image != '' ) { ?>
						<div class="img-holder">
							<?php
							echo wp_get_attachment_image(
								$image,
								$icon_img_size,
								false,
								array( 'alt' => esc_attr( $title ) )
							);
							?>
							 
						</div>
					<?php } ?>
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
					<form id="blossomthemes-email-newsletter-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" class="blossomthemes-email-newsletter-window-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>">
						<?php
						$val = isset( $blossomthemes_email_newsletter_setting['field']['select'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['field']['select'] ) : 'email';
						if ( $val == 'email' ) {
							?>
							<input type="text" name="subscribe-email" class="subscribe-email-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" value="" placeholder="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) : 'Your Email'; ?>">
							<?php
						} else {
							?>
							<input type="text" name="subscribe-fname" required="required" class="subscribe-fname-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" value="" placeholder="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['first_name_placeholder'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['field']['first_name_placeholder'] ) : 'Your Name'; ?>">
							<input type="text" name="subscribe-email" required="required" class="subscribe-email-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" value="" placeholder="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['field']['email_placeholder'] ) : 'Your Email'; ?>">
							<?php
						}
						if ( isset( $gdpr ) && $gdpr != '' ) {
							?>
						<label for="subscribe-confirmation-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>">
							<div class="subscribe-inner-wrap">
								<input type="checkbox" class="subscribe-confirmation-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" name="subscribe-confirmation" id="subscribe-confirmation-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" required/><span class="check-mark"></span>
								<span class="text">
									<?php
									$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
									$gdprmsg                                 = isset( $blossomthemes_email_newsletter_settings['gdpr-msg'] ) ? $blossomthemes_email_newsletter_settings['gdpr-msg'] : 'By checking this, you agree to our Privacy Policy.';
									echo wp_kses_post( $gdprmsg );
									?>
								</span>
							</div>
						</label>
							<?php
						}
						?>
						<div id="loader-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" style="display: none">
							<div class="table">
								<div class="table-row">
									<div class="table-cell">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="51px" height="51px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"> <g transform="rotate(0 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.9166666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(30 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.8333333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(60 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(90 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.6666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(120 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5833333333333334s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(150 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(180 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.4166666666666667s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(210 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.3333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(240 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(270 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.16666666666666666s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(300 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.08333333333333333s" repeatCount="indefinite"></animate> </rect> </g><g transform="rotate(330 50 50)"> <rect x="47" y="24" rx="3" ry="6" width="6" height="12" fill="#453e3f"> <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animate> </rect> </g> <!-- [ldio] generated by https://loading.io/ --></svg>
									</div>
								</div>
							</div>
						</div>
						<input type="submit" name="subscribe-submit" class="subscribe-submit-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>" value="<?php echo isset( $blossomthemes_email_newsletter_setting['field']['submit_label'] ) ? esc_attr( $blossomthemes_email_newsletter_setting['field']['submit_label'] ) : 'Subscribe'; ?>">
						<?php wp_nonce_field( 'subscription_response', 'bten_subscription_nonce_' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '' ); ?>
					</form>
					<div class="bten-response" id="bten-response-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>"><span></span></div>
					<div id="mask-<?php echo esc_attr( $atts['id'] ); ?>-<?php echo esc_attr( $unique_id ); ?>"></div>

					<?php
					$inner_wrap = apply_filters( 'bt_newsletter_widget_inner_wrap_display', false );
					if ( $inner_wrap ) {
						do_action( 'bt_newsletter_widget_inner_wrap_close' );
					}
					?>
				</div>
				<?php

				global $post;
				$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
						$style = '<style>
                            #mask-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' {
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

                            #blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' input.subscribe-submit-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '{
                                color: ' . $rrsb_stc . ';
                                background: ' . $rrsb_sc . ';
                            }

                            #blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' input.subscribe-submit-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ':hover{
                                color: ' . $rrsb_sthc . ';
                                background: ' . $rrsb_shc . ';
                            }
                            #loader-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' {
                                position: absolute;
                                top: 27%;
                                left: 0;
                                width: 100%;
                                height: 80%;
                                text-align: center;
                                font-size: 50px;
                            }

                            #loader-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' .table{
                                display: table;
                                width: 100%;
                                height: 100%;
                            }

                            #loader-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' .table-row{
                                display: table-row;
                            }

                            #loader-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' .table-cell{
                                display: table-cell;
                                vertical-align: middle;
                            }
                        </style>';
						echo $obj->bten_minify_css( $style );
						// echo $style;
					$ajax                      =
					'<script>
                    jQuery(function() {
						const uniqueId = Math.random().toString(36).substr(2, 9);
                        jQuery(document).on("submit","form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '", function(e){
                        e.preventDefault();
                        jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").attr("disabled", "disabled" );
                        var email = jQuery(".subscribe-email-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").val();
                        var fname = jQuery(".subscribe-fname-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").val();
                        var sid = ' . esc_attr( $atts['id'] ) . ';
                        var nonce = jQuery("#bten_subscription_nonce_' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").val();
						jQuery.ajax({
                                type : "post",
                                dataType : "json",
                                url : bten_ajax_data.ajaxurl,
                                data : {action: "subscription_response", email : email, fname : fname, sid : sid, nonce : nonce},
                                beforeSend: function(){
                                    jQuery("#loader-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").fadeIn(500);
                                },
                                success: function(response){
                                    jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").attr("disabled", "disabled" );';
								$bten_settings = get_option( 'blossomthemes_email_newsletter_settings', true );
								$option        = isset( $bten_settings['thankyou-option'] ) ? esc_attr( $bten_settings['thankyou-option'] ) : 'text';
								$ajax         .= 'if(response.type === "success") {';
				if ( $option == 'text' ) {
					$ajax .= 'jQuery("#bten-response-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' span").html(response.message);jQuery("#bten-response-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").fadeIn("slow").delay("3000").fadeOut("3000",function(){
                                            jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").removeAttr("disabled", "disabled" );
                                            jQuery("form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").find("input[type=text]").val("");
                                        });';
				} else {
					$selected_page = isset( $bten_settings['page'] ) ? esc_attr( $bten_settings['page'] ) : '';
					$url           = get_permalink( $selected_page );
					$ajax         .= 'window.location.href = "' . esc_url( $url ) . '"';
				}

								$ajax .= '}
                                else{
                                    jQuery("#bten-response-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . ' span").html(response.message);jQuery("#bten-response-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").fadeIn("slow").delay("3000").fadeOut("3000",function(){
                                            jQuery(".subscribe-submit-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").removeAttr("disabled", "disabled" );
                                            jQuery("form#blossomthemes-email-newsletter-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").find("input[type=text]").val("");
                                        });
                                    }
                                },
                                complete: function(){
                                    jQuery("#loader-' . esc_attr( $atts['id'] ) . '-' . esc_attr( $unique_id ) . '").fadeOut(500);             
                                } 
                            });  
                        });
                    });
                    </script>';
				echo $obj->bten_minify_js( $ajax );
				$html = ob_get_clean();
				echo apply_filters( 'widget_blossomthemes_newsletter', $html, $args, $title, $image );
				echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$obj         = new Blossomthemes_Email_Newsletter_Functions();
		$title       = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$gdpr        = ! empty( $instance['gdpr'] ) ? $instance['gdpr'] : '';
		$newsletter  = ! empty( $instance['newsletter'] ) ? $instance['newsletter'] : '';
		$image       = ! empty( $instance['image'] ) ? $instance['image'] : '';
		$postlist[0] = array(
			'value' => 0,
			'label' => __( '--Choose--', 'blossomthemes-email-newsletter' ),
		);
		$arg         = array(
			'posts_per_page' => -1,
			'post_type'      => array( 'subscribe-form' ),
		);
		$posts       = get_posts( $arg );

		foreach ( $posts as $p ) {
			$postlist[ $p->ID ] = array(
				'value' => $p->ID,
				'label' => $p->post_title,
			);
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'blossomthemes-email-newsletter' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'newsletter' ) ); ?>"><?php esc_html_e( 'Newsletter', 'blossomthemes-email-newsletter' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'newsletter' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'newsletter' ) ); ?>" class="widefat">
				<?php
				foreach ( $postlist as $single_post ) {
					?>
					<option value="<?php echo $single_post['value']; ?>" id="<?php echo esc_attr( $this->get_field_id( $single_post['label'] ) ); ?>" <?php selected( $single_post['value'], $newsletter ); ?>><?php echo $single_post['label']; ?></option>
				<?php } ?>
			</select>
			<span id="footer-thankyou">
				<?php
				$bold      = '<b>';
				$boldclose = '</b>';
				echo sprintf( __( 'To create a new newsletter form, go to %1$sBlossomThemes Email Newsletters > Add New%2$s', 'blossomthemes-email-newsletter' ), $bold, $boldclose );
				?>
			</span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'gdpr' ) ); ?>"><?php esc_html_e( 'Enable GDPR', 'blossomthemes-email-newsletter' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'gdpr' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'gdpr' ) ); ?>" type="checkbox" value="1" <?php echo checked( $gdpr, 1 ); ?> />
		</p>
		<?php

		$obj->blossomthemes_email_newsletter_companion_get_image_field( $this->get_field_id( 'image' ), $this->get_field_name( 'image' ), $image, __( 'Upload Newsletter Icon', 'blossomthemes-email-newsletter' ) );

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title']      = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['newsletter'] = ! empty( $new_instance['newsletter'] ) ? absint( $new_instance['newsletter'] ) : 1;
		$instance['image']      = ! empty( $new_instance['image'] ) ? absint( $new_instance['image'] ) : '';
		$instance['gdpr']       = ! empty( $new_instance['gdpr'] ) ? $new_instance['gdpr'] : '';

		return $instance;
	}

} // class BlossomThemes_Email_Newsletter_Widget
