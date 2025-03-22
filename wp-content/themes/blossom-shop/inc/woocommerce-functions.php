<?php
/**
 * Blossom Shop Woocommerce hooks and functions.
 *
 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 *
 * @package Blossom_Shop
 */

/**
 * Woocommerce related hooks
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );

/**
 * Declare Woocommerce Support
*/
function blossom_shop_woocommerce_support() {
    global $woocommerce;
    
    add_theme_support( 'woocommerce' );
    
    if( version_compare( $woocommerce->version, '3.0', ">=" ) ) {
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
}
add_action( 'after_setup_theme', 'blossom_shop_woocommerce_support');

/**
 * Woocommerce Sidebar
*/
function blossom_shop_wc_widgets_init(){
    register_sidebar( array(
      'name'          => esc_html__( 'Shop Sidebar', 'blossom-shop' ),
      'id'            => 'shop-sidebar',
      'description'   => esc_html__( 'Sidebar displaying only in woocommerce pages.', 'blossom-shop' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
  ) );    
}
add_action( 'widgets_init', 'blossom_shop_wc_widgets_init' );

/**
 * Before Content
 * Wraps all WooCommerce content in wrappers which match the theme markup
*/
function blossom_shop_wc_wrapper(){    
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php
        }
        add_action( 'woocommerce_before_main_content', 'blossom_shop_wc_wrapper' );

/**
 * After Content
 * Closes the wrapping divs
*/
function blossom_shop_wc_wrapper_end(){
    ?>
</main>
</div>
<?php
do_action( 'blossom_shop_wo_sidebar' );
}
add_action( 'woocommerce_after_main_content', 'blossom_shop_wc_wrapper_end' );

/**
 * Callback function for Shop sidebar
*/
function blossom_shop_wc_sidebar_cb(){
    $sidebar = blossom_shop_sidebar();
    if( $sidebar ){
        echo '<aside id="secondary" class="widget-area" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">';
        dynamic_sidebar( 'shop-sidebar' );
        echo '</aside>'; 
    }
}
add_action( 'blossom_shop_wo_sidebar', 'blossom_shop_wc_sidebar_cb' );

/**
 * Removes the "shop" title on the main shop page
*/
add_filter( 'woocommerce_show_page_title' , '__return_false' );

if( ! function_exists( 'blossom_shop_wc_cart_count' ) ) :
/**
 * Woocommerce Cart Count
 * 
 * @link https://isabelcastillo.com/woocommerce-cart-icon-count-theme-header 
*/
function blossom_shop_wc_cart_count(){
    $count = WC()->cart->get_cart_contents_count(); ?>
    <div class="cart-block">
        <div class="bsp-cart-block-wrap">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart" title="<?php esc_attr_e( 'View your shopping cart', 'blossom-shop' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="13.87" height="16" viewBox="0 0 13.87 16"><path d="M15.8,5.219a.533.533,0,0,0-.533-.485H13.132V4.44A3.333,3.333,0,0,0,9.932,1a3.333,3.333,0,0,0-3.2,3.44v.293H4.6a.533.533,0,0,0-.533.485L3,16.419A.539.539,0,0,0,3.532,17h12.8a.539.539,0,0,0,.533-.581Zm-8-.779A2.267,2.267,0,0,1,9.932,2.067,2.267,2.267,0,0,1,12.065,4.44v.293H7.8ZM4.118,15.933,5.084,5.8H6.732v.683a1.067,1.067,0,1,0,1.067,0V5.8h4.267v.683a1.067,1.067,0,1,0,1.067,0V5.8H14.78l.965,10.133Z" transform="translate(-2.997 -1)"/></svg>
                <span class="count"><?php echo absint( $count ); ?></span>
            </a>
            <span class="cart-amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
        </div>
    </div>
    <?php
}
endif;

/**
 * Ensure cart contents update when products are added to the cart via AJAX
 * 
 * @link https://isabelcastillo.com/woocommerce-cart-icon-count-theme-header
 */
function blossom_shop_add_to_cart_fragment( $fragments ){
    ob_start();
    $count = WC()->cart->get_cart_contents_count(); ?>
    <div class="bsp-cart-block-wrap">
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart" title="<?php esc_attr_e( 'View your shopping cart', 'blossom-shop' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="13.87" height="16" viewBox="0 0 13.87 16"><path d="M15.8,5.219a.533.533,0,0,0-.533-.485H13.132V4.44A3.333,3.333,0,0,0,9.932,1a3.333,3.333,0,0,0-3.2,3.44v.293H4.6a.533.533,0,0,0-.533.485L3,16.419A.539.539,0,0,0,3.532,17h12.8a.539.539,0,0,0,.533-.581Zm-8-.779A2.267,2.267,0,0,1,9.932,2.067,2.267,2.267,0,0,1,12.065,4.44v.293H7.8ZM4.118,15.933,5.084,5.8H6.732v.683a1.067,1.067,0,1,0,1.067,0V5.8h4.267v.683a1.067,1.067,0,1,0,1.067,0V5.8H14.78l.965,10.133Z" transform="translate(-2.997 -1)"/></svg>
            <span class="count"><?php echo absint( $count ); ?></span>
        </a>
        <span class="cart-amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
    </div>
    <?php
    
    $fragments['.bsp-cart-block-wrap'] = ob_get_clean();
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'blossom_shop_add_to_cart_fragment' );