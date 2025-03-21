<?php
if ( !function_exists( 'envo_one_cart_link' ) ) {

	function envo_one_cart_link() {
		?>	

			<div class="cart-counter">
				<span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
				<div class="amount-cart hidden-xs"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></div> 
				</div>
		<?php
	}

}


if ( !function_exists( 'envo_one_header_cart' ) ) {

	add_action( 'envo_one_top_header', 'envo_one_header_cart', 30 );

	function envo_one_header_cart() {
		if ( get_theme_mod( 'woo_header_cart', 1 ) == 1 ) {
			?>
			<div class="header-cart">
				<div class="header-cart-block">
					<div class="header-cart-inner">
						<div class="cart-contents" role="button" tabindex="0" data-tooltip="<?php esc_attr_e( 'Cart', 'envo-one' ); ?>" title="<?php esc_attr_e( 'Cart', 'envo-one' ); ?>">
						<i data-feather="shopping-cart" class="la la-shopping-bag"></i>
						<?php envo_one_cart_link(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

}
if ( !function_exists( 'envo_one_cart_content' ) ) {

	add_action( 'wp_footer', 'envo_one_cart_content', 30 );

	function envo_one_cart_content() {
		if ( get_theme_mod( 'woo_header_cart', 1 ) == 1 ) {
			?>
			<ul class="site-header-cart list-unstyled">
				<div class="header-cart-close">
					<i data-feather="x-circle" class="la la-times-circle"></i>
				</div>
				<li>
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
				</li>
			</ul>
			<?php
		}
	}

}
if ( !function_exists( 'envo_one_header_add_to_cart_fragment' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'envo_one_header_add_to_cart_fragment' );

	function envo_one_header_add_to_cart_fragment( $fragments ) {
		ob_start();

		envo_one_cart_link();

		$fragments[ '.cart-counter' ] = ob_get_clean();

		return $fragments;
	}

}

if ( !function_exists( 'envo_one_my_account' ) ) {

	add_action( 'envo_one_top_header', 'envo_one_my_account', 40 );

	function envo_one_my_account() {
		$login_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		?>
		<div class="header-my-account">
			<div class="header-login"> 
				<a href="<?php echo esc_url( $login_link ); ?>" data-tooltip="<?php esc_attr_e( 'My Account', 'envo-one' ); ?>" title="<?php esc_attr_e( 'My Account', 'envo-one' ); ?>">
					<i data-feather="user" class="la la-user"></i>
				</a>
			</div>
		</div>
		<?php
	}

}

if ( !function_exists( 'envo_one_head_wishlist' ) ) {

	add_action( 'envo_one_top_header', 'envo_one_head_wishlist', 50 );

	function envo_one_head_wishlist() {
		if ( function_exists( 'YITH_WCWL' ) ) {
			$wishlist_url = YITH_WCWL()->get_wishlist_url();
			?>
			<div class="header-wishlist">
				<a href="<?php echo esc_url( $wishlist_url ); ?>" data-tooltip="<?php esc_attr_e( 'Wishlist', 'envo-one' ); ?>" title="<?php esc_attr_e( 'Wishlist', 'envo-one' ); ?>">
					<i data-feather="heart" class="lar la-heart"></i>
				</a>
			</div>
			<?php
		}
	}

}

if ( !function_exists( 'envo_one_head_compare' ) ) {

	add_action( 'envo_one_top_header', 'envo_one_head_compare', 60 );

	function envo_one_head_compare() {
		if ( function_exists( 'yith_woocompare_constructor' ) ) {
			global $yith_woocompare;
			?>
			<div class="header-compare product">
				<a class="compare added" rel="nofollow" href="<?php echo esc_url( $yith_woocompare->obj->view_table_url() ); ?>" data-tooltip="<?php esc_attr_e( 'Compare', 'envo-one' ); ?>" title="<?php esc_attr_e( 'Compare', 'envo-one' ); ?>">
					<i data-feather="refresh-cw" class="la la-sync"></i>
				</a>
			</div>
			<?php
		}
	}

}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

add_action( 'woocommerce_before_main_content', 'envo_one_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'envo_one_wrapper_end', 10 );

function envo_one_wrapper_start() {
	?>
	<div class="row">
		<article class="envo-content woo-content col-md-<?php envo_one_main_content_width_columns(); ?>">
	<?php
}

function envo_one_wrapper_end() {
			?>
		</article>       
		<?php get_sidebar( 'right' ); ?>
	</div>
	<?php
}

// Load cart widget in header. Required since Woo 7.8
function envo_one_wc_cart_fragments() {
	wp_enqueue_script( 'wc-cart-fragments' );
}

add_action( 'wp_enqueue_scripts', 'envo_one_wc_cart_fragments' );
