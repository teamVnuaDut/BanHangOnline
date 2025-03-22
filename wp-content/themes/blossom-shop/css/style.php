<?php
/**
 * Blossom Shop Dynamic Styles
 * 
 * @package Blossom_Shop
*/

function blossom_shop_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', 'Nunito Sans' );
    $primary_fonts   = blossom_shop_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Cormorant' );
    $secondary_fonts = blossom_shop_get_fonts( $secondary_font, 'regular' );
    $font_size       = get_theme_mod( 'font_size', 20);
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Cormorant', 'variant'=>'regular' ) );
    $site_title_fonts     = blossom_shop_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 20 );
    
    $primary_color    = get_theme_mod( 'primary_color', '#dde9ed' );
    $secondary_color  = get_theme_mod( 'secondary_color', '#ee7f4b' );
    $site_title_color = get_theme_mod( 'site_title_color', '#000000' );
    
    $rgb = blossom_shop_hex2rgb( blossom_shop_sanitize_hex_color( $primary_color ) );
    $rgb2 = blossom_shop_hex2rgb( blossom_shop_sanitize_hex_color( $secondary_color ) );
     
    $custom_css = '';
    $custom_css .= '

    :root {
        --primary-color: ' . blossom_shop_sanitize_hex_color($primary_color) . ';
		--primary-color-rgb: ' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ';
        --primary-font: ' . esc_html($primary_fonts['font']) . ';
        --secondary-font: ' . esc_html($secondary_fonts['font']) . ';
        --secondary-color: ' . blossom_shop_sanitize_hex_color($secondary_color) . ';
        --secondary-color-rgb: ' . $rgb2[0] . ', ' . $rgb2[1] . ', ' . $rgb2[2] . ';
    }
     
    .content-newsletter .blossomthemes-email-newsletter-wrapper.bg-img:after,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper:after{
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.8);' . '
    }
    
    /*Typography*/

    body {
        font-family : ' . esc_html( $primary_fonts['font'] ) . ';
        font-size   : ' . absint( $font_size ) . 'px;        
    }
    
    .header-main .site-branding .site-title, 
    .sticky-header .site-branding .site-title , 
    .header-four .header-t .site-branding .site-title, 
    .header-five .logo-holder .site-branding .site-title, .header-six .logo-holder .site-branding .site-title, 
    .header-eight .logo-holder .site-branding .site-title, 
    .header-eleven .logo-holder .site-branding .site-title {
        font-size   : ' . absint( $site_title_font_size ) . 'px;
        font-family : ' . wp_kses_post( $site_title_fonts['font'] ) . ';
        font-weight : ' . wp_kses_post( $site_title_fonts['weight'] ) . ';
        font-style  : ' . wp_kses_post( $site_title_fonts['style'] ) . ';
    }
    
    .site-title a, .header-main .site-branding .site-title a, 
    .sticky-header .site-branding .site-title a, 
    .header-four .header-t .site-branding .site-title a, 
    .header-five .logo-holder .site-branding .site-title a, 
    .header-six .logo-holder .site-branding .site-title a, 
    .header-eight .logo-holder .site-branding .site-title a, 
    .header-eleven .logo-holder .site-branding .site-title a {
        color: ' . blossom_shop_sanitize_hex_color( $site_title_color ) . ';
    }

    button, input, select, optgroup, textarea, blockquote p + span, 
    .site-banner .banner-caption .meta-wrap > span.byline a, 
    .top-service-section .rtc-itw-inner-holder .widget-title, 
    section.prod-deal-section .title-wrap .section-title, 
    section.about-section .widget .widget-title, 
    section.about-section.style-two .widget .text-holder p, 
    section.cta-section.style-three .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .widget-title, 
    .woocommerce-checkout #primary .woocommerce-checkout #order_review_heading, 
    .woocommerce-checkout #primary .woocommerce-checkout .col2-set .col-1 .woocommerce-billing-fields h3, 
    .cat-tab-section .header-wrap .section-title {
        font-family : ' . wp_kses_post( $primary_fonts['font'] ) . ';
    }

    q, blockquote, .section-title, section[class*="-section"] .widget-title, 
    .yith-wcqv-main .product .summary .product_title, .widget_bttk_author_bio .title-holder, 
    .widget_bttk_popular_post ul li .entry-header .entry-title, .widget_bttk_pro_recent_post ul li .entry-header .entry-title, 
    .blossomthemes-email-newsletter-wrapper .text-holder h3, 
    .widget_bttk_posts_category_slider_widget .carousel-title .title, 
    .additional-post .section-grid article .entry-title, 
    .site-banner .banner-caption .banner-title, 
    .site-banner .banner-caption .meta-wrap > span.byline, 
    section.about-section .widget .text-holder p, 
    section.about-section.style-two .widget .widget-title, 
    section.cta-section .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .widget-title, 
    .blog-section .section-grid .entry-title, 
    .instagram-section .profile-link, 
    section.newsletter-section .newsletter-inner-wrapper .text-holder h3, 
    .recent-prod-section.style-three .recent-prod-feature .product-title-wrap .rp-title, .recent-prod-section.style-four .recent-prod-feature .product-title-wrap .rp-title, .recent-prod-section.style-five .recent-prod-feature .product-title-wrap .rp-title, .recent-prod-section.style-six .recent-prod-feature .product-title-wrap .rp-title, 
    .popular-prod-section.style-three .popular-prod-feature .product-title-wrap .pp-title, .popular-prod-section.style-four .popular-prod-feature .product-title-wrap .pp-title, .popular-prod-section.style-five .popular-prod-feature .product-title-wrap .pp-title, .popular-prod-section.style-six .popular-prod-feature .product-title-wrap .pp-title, 
    .classic-layout .site-main article .entry-title, 
    .grid-layout .site-main article .entry-title, 
    .list-layout .site-main article .entry-title, .page .site-content > .page-header .page-title, 
    .page-template-about section.intro-about-section .widget-title, 
    .page-template-contact .site-main .widget .widget-title, 
    .error404 .site-content > .page-header .page-title, 
    .single .site-content > .page-header .entry-title, 
    .woocommerce-page .site-content > .page-header .page-title, 
    .single-product .site-main div.product div.summary .product_title, 
    .single-product .site-main .related > h2, 
    section[class*="-cat-section"].style-three .cat-feature .product-title-wrap .pp-title, 
    section[class*="-cat-section"].style-four .cat-feature .product-title-wrap .pp-title, 
    section[class*="-cat-section"].style-five .cat-feature .product-title-wrap .pp-title, 
    section[class*="-cat-section"].style-six .cat-feature .product-title-wrap .pp-title {
        font-family : ' . wp_kses_post( $secondary_fonts['font'] ) . ';
    }

    .widget_blossomthemes_stat_counter_widget .blossomthemes-sc-holder .icon-holder, 
    .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a:hover, 
    .widget_bttk_posts_category_slider_widget .carousel-title .title a:hover, 
    .header-six .header-t a:hover, 
    .header-eight .header-t a:hover, .header-ten .header-t a:hover, 
    .header-six .secondary-menu ul li:hover > a, .header-six .secondary-menu ul li.current-menu-item > a, .header-six .secondary-menu ul li.current_page_item > a, .header-six .secondary-menu ul li.current-menu-ancestor > a, .header-six .secondary-menu ul li.current_page_ancestor > a, .header-eight .secondary-menu ul li:hover > a, .header-eight .secondary-menu ul li.current-menu-item > a, .header-eight .secondary-menu ul li.current_page_item > a, .header-eight .secondary-menu ul li.current-menu-ancestor > a, .header-eight .secondary-menu ul li.current_page_ancestor > a, 
    .header-nine .main-navigation ul li:hover > a, .header-nine .main-navigation ul li.current-menu-item > a, .header-nine .main-navigation ul li.current_page_item > a, .header-nine .main-navigation ul li.current-menu-ancestor > a, .header-nine .main-navigation ul li.current_page_ancestor > a, 
    .header-ten .secondary-menu ul li:hover > a, .header-ten .secondary-menu ul li.current-menu-item > a, .header-ten .secondary-menu ul li.current_page_item > a, .header-ten .secondary-menu ul li.current-menu-ancestor > a, .header-ten .secondary-menu ul li.current_page_ancestor > a, .site-banner .banner-caption .banner-title a:hover, 
    .site-banner.banner-three .banner-caption .banner-title a:hover, 
    .blog .site-banner .banner-caption:not(.centered) .banner-title a:hover,
    .entry-content a:hover,
    .entry-summary a:hover,
    .page-content a:hover,
    .comment-content a:hover,
    .widget .textwidget a:hover  {
        color: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    button:hover,
    input[type="button"]:hover,
    input[type="reset"]:hover,
    input[type="submit"]:hover, 
    .edit-link .post-edit-link, 
    .item .recent-prod-image .product_type_external:hover,
    .item .recent-prod-image .product_type_simple:hover,
    .item .recent-prod-image .product_type_grouped:hover,
    .item .recent-prod-image .product_type_variable:hover,
    .item .popular-prod-image .product_type_external:hover,
    .item .popular-prod-image .product_type_simple:hover,
    .item .popular-prod-image .product_type_grouped:hover,
    .item .popular-prod-image .product_type_variable:hover, 
    .widget_bttk_contact_social_links .social-networks li a, 
    .widget_bttk_author_bio .readmore, 
    .widget_bttk_author_bio .author-socicons li a:hover, 
    .widget_bttk_social_links ul li a:hover, 
    .widget_bttk_image_text_widget ul li:hover .btn-readmore, 
    .widget_bttk_author_bio .readmore, 
    .widget_bttk_author_bio .author-socicons li a:hover, 
    .bttk-team-inner-holder ul.social-profile li a:hover, 
    .widget_bttk_icon_text_widget .rtc-itw-inner-holder .text-holder .btn-readmore:hover, 
    .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta + .btn-cta:hover, 
    .sticky-t-bar .sticky-bar-content, 
    .header-main .right span.count, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a.checkout:hover, 
    .main-navigation ul ul li:hover > a, 
    .main-navigation ul ul li.current-menu-item > a, 
    .main-navigation ul ul li.current_page_item > a, 
    .main-navigation ul ul li.current-menu-ancestor > a, 
    .main-navigation ul ul li.current_page_ancestor > a, #load-posts a, 
    .posts-navigation .nav-links a, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"], 
    .site-banner .owl-dots .owl-dot:hover span, .site-banner .owl-dots .owl-dot.active span, 
    .featured-section .section-block:not(:first-child) .block-title a:hover, 
    .featured-section.style-three .section-block:hover .btn-readmore:hover, 
    section.prod-deal-section .button-wrap .bttn:hover, section.about-section.style-two, 
    .testimonial-section .owl-stage-outer, section.cta-section.style-one .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta + .btn-cta, section.cta-section.style-one .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta:hover, .blog-section .button-wrap .bttn:hover, 
    .popular-prod-section .button-wrap .btn-readmore:hover, 
    .single .site-main article .article-meta .social-list li a:hover, 
    .single .site-main article .entry-footer .cat-tags a:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button, 
    .woocommerce-page .widget_shopping_cart .buttons .button + .button:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button + .button:focus, 
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range, 
    .woocommerce-page .widget_price_filter .price_slider_amount .button, 
    .tagcloud a:hover, .woocommerce-page .site-content ul.products li.product .product_type_external, .woocommerce-page .site-content ul.products li.product .product_type_simple, .woocommerce-page .site-content ul.products li.product .product_type_grouped, .woocommerce-page .site-content ul.products li.product .product_type_variable, 
    .item .recent-prod-image .product_type_external:hover, .item .recent-prod-image .product_type_simple:hover, .item .recent-prod-image .product_type_grouped:hover, .item .recent-prod-image .product_type_variable:hover, .item .popular-prod-image .product_type_external:hover, .item .popular-prod-image .product_type_simple:hover, .item .popular-prod-image .product_type_grouped:hover, .item .popular-prod-image .product_type_variable:hover, .item .cat-image .product_type_external:hover, .item .cat-image .product_type_simple:hover, .item .cat-image .product_type_grouped:hover, .item .cat-image .product_type_variable:hover, 
    section[class*="-cat-section"] .button-wrap .btn-readmore:hover, 
    .item .product-image .product_type_external:hover, .item .product-image .product_type_simple:hover, .item .product-image .product_type_grouped:hover, .item .product-image .product_type_variable:hover {
        background: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, 
    .item .recent-prod-image .yith-wcqv-button:hover,
    .item .popular-prod-image .yith-wcqv-button:hover, 
    .item .recent-prod-image .compare-button a:hover,
    .item .popular-prod-image .compare-button a:hover, 
    .error404 .error-404 .search-form .search-submit:hover, 
    .woocommerce-page .site-content ul.products li.product .yith-wcwl-add-button .add_to_wishlist:hover, 
    .woocommerce-page .site-content ul.products li.product .yith-wcqv-button:hover, 
    .woocommerce-page .site-content ul.products li.product .compare.button:hover, 
    .single-product .site-main div.product div.summary .yith-wcwl-add-button .add_to_wishlist:hover, 
    .single-product .site-main div.product div.summary a.compare:hover, 
    .item .recent-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, 
    .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, 
    .item .recent-prod-image .compare-button:hover a:hover, .item .recent-prod-image .compare-button:focus-within a:hover, .item .popular-prod-image .compare-button:hover a:hover, .item .popular-prod-image .compare-button:focus-within a:hover, .item .cat-image .compare-button:hover a:hover, .item .cat-image .compare-button:focus-within a:hover, 
    .item .recent-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .recent-prod-image .yith-wcwl-add-button .add_to_wishlist:focus-within, .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:focus-within, .item .cat-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .cat-image .yith-wcwl-add-button .add_to_wishlist:focus-within, 
    .item .recent-prod-image .yith-wcqv-button:hover, .item .recent-prod-image .yith-wcqv-button:focus-within, .item .popular-prod-image .yith-wcqv-button:hover, .item .popular-prod-image .yith-wcqv-button:focus-within, .item .cat-image .yith-wcqv-button:hover, .item .cat-image .yith-wcqv-button:focus-within, 
    .item .product-image .compare-button:hover a:hover, .item .product-image .compare-button:focus-within a:hover, 
    .item .product-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .product-image .yith-wcwl-add-button .add_to_wishlist:focus-within, .item .product-image .yith-wcqv-button:hover, .item .product-image .yith-wcqv-button:focus-within {
        background-color: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    .widget_bttk_author_bio .author-socicons li a:hover, 
    .widget_bttk_social_links ul li a, 
    .blossomthemes-email-newsletter-wrapper .img-holder, 
    .widget_bttk_author_bio .author-socicons li a, 
    .bttk-team-inner-holder ul.social-profile li a:hover, .pagination .page-numbers, 
    .author-section .author-content-wrap .social-list li a svg, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"], 
    .featured-section.style-three .section-block:hover .btn-readmore:hover, 
    .single .site-main article .article-meta .social-list li a, 
    .single .site-main article .entry-footer .cat-tags a, 
    .woocommerce-page .site-content .woocommerce-pagination a, .woocommerce-page .site-content .woocommerce-pagination span, 
    .single-product .site-main div.product div.summary .yith-wcwl-add-button .add_to_wishlist, 
    .single-product .site-main div.product div.summary a.compare, 
    .tagcloud a:hover {
        border-color: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    section.about-section {
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.35);' . '
    }

    section.client-section {
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.3);' . '
    }

    blockquote::before {
        background-image: url(' . ' \'data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="' . blossom_shop_hash_to_percent23( blossom_shop_sanitize_hex_color( $primary_color ) ) . '" d="M75.6,40.5a20,20,0,1,1-20.1,20,39.989,39.989,0,0,1,40-40A39.31,39.31,0,0,0,75.6,40.5Zm-30.1,20a20,20,0,0,1-40,0h0a39.989,39.989,0,0,1,40-40,39.31,39.31,0,0,0-19.9,20A19.973,19.973,0,0,1,45.5,60.5Z"></path></svg>\'' . ' );
    }

    a, .dropcap, 
    .yith-wcqv-main .product .summary .product_meta > span a:hover, 
    .woocommerce-error a,
    .woocommerce-info a,
    .woocommerce-message a, 
    .widget_calendar table tbody td a, 
    .header-main .right .cart-block .widget_shopping_cart .cart_list li a:hover, 
    .header-eleven .header-main .right > div .user-block-popup a:hover, 
    .site-banner.banner-six .banner-caption .banner-title a:hover, 
    .site-banner.banner-six .banner-caption .cat-links a:hover, 
    .page-template-contact .site-main .widget_bttk_contact_social_links ul.contact-list li svg, 
    .error404 .error-404 .error-num, 
    .single-product .site-main div.product div.summary .product_meta > span a:hover, 
    .single-product .site-main div.product #reviews #respond .comment-reply-title a:hover, 
    .woocommerce-checkout #primary .woocommerce-info a, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .payment_methods li label .about_paypal:hover, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .place-order a, 
    .woocommerce-order-received .entry-content .woocommerce-order-details .shop_table tr td a:hover, 
    .woocommerce-account .woocommerce-MyAccount-content a, 
    .woocommerce-account .lost_password a:hover, 
    .cat-tab-section .tab-btn-wrap .tab-btn:hover, .cat-tab-section .tab-btn-wrap .tab-btn.active, 
    .item h3 a:hover, .entry-title a:hover, .widget ul li a:hover, 
    .breadcrumb a:hover, .breadcrumb .current,
    .breadcrumb a:hover, .breadcrumb .current, 
    .mega-sub-menu li.mega-menu-item-type-widget li a:hover, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link:hover, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-toggle-on > a.mega-menu-link, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-menu-item > a.mega-menu-link, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-menu-ancestor > a.mega-menu-link, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-page-ancestor > a.mega-menu-link, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus, 
    .sticky-t-bar .sticky-bar-content .blossomthemes-email-newsletter-wrapper form input[type=submit]:hover, .sticky-t-bar .sticky-bar-content .blossomthemes-email-newsletter-wrapper form input[type=submit]:active, .sticky-t-bar .sticky-bar-content .blossomthemes-email-newsletter-wrapper form input[type=submit]:focus {
        color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .edit-link .post-edit-link:hover,  
    .yith-wcqv-main .product .summary table.woocommerce-grouped-product-list tbody tr td .button:hover, 
    .yith-wcqv-main .product .summary .single_add_to_cart_button:hover, 
    .widget_calendar table tbody td#today, 
    .widget_bttk_custom_categories ul li a:hover .post-count, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta:hover, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta + .btn-cta, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a:hover, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a.checkout, 
    .pagination .page-numbers.current,
    .pagination .page-numbers:not(.dots):hover, 
    #load-posts a:not(.loading):hover, #load-posts a.disabled, 
    #load-posts a .loading:hover, 
    .posts-navigation .nav-links a:hover, 
    .author-section .author-content-wrap .social-list li a:hover svg, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"]:hover, 
    .site-banner.banner-six .banner-caption .btn-readmore:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button:focus, 
    .woocommerce-page .widget_shopping_cart .buttons .button + .button, 
    .woocommerce-page .widget_price_filter .price_slider_amount .button:hover, 
    .woocommerce-page .widget_price_filter .price_slider_amount .button:focus, 
    .single-product .site-main div.product div.summary table.woocommerce-grouped-product-list tbody tr td .button:hover, 
    .single-product .site-main div.product div.summary .single_add_to_cart_button:hover, 
    .single-product .site-main div.product .woocommerce-tabs ul.tabs li a:after, 
    .single-product .site-main div.product #reviews #respond .comment-form p.form-submit input[type="submit"]:hover, 
    .woocommerce-cart .site-main .woocommerce .woocommerce-cart-form table.shop_table tbody td.actions > .button:hover, 
    .woocommerce-cart .site-main .woocommerce .cart-collaterals .cart_totals .checkout-button, 
    .woocommerce-checkout #primary .checkout_coupon p.form-row .button:hover, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .payment_methods li input[type="radio"]:checked + label::before, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .place-order .button, 
    .woocommerce-order-received .entry-content .woocommerce-order-details .shop_table thead tr, 
    .woocommerce-wishlist #content table.wishlist_table.shop_table tbody td.product-add-to-cart .button:hover, 
    .woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover, 
    .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a, 
    .featured-section.style-one .section-block .block-content .block-title a:hover, 
    .main-navigation ul li a .menu-description, 
    .woocommerce-page .site-content ul.products li.product .product_type_external:hover,
    .woocommerce-page .site-content ul.products li.product .product_type_simple:hover,
    .woocommerce-page .site-content ul.products li.product .product_type_grouped:hover,
    .woocommerce-page .site-content ul.products li.product .product_type_variable:hover, 
    .cat-tab-section .tab-btn-wrap .tab-btn::after,
    .cat-tab-section .tab-btn-wrap .tab-btn::after, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link::before, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:hover, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus
    {
        background: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover, 
    .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar, 
    .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar, 
    .mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar, 
    .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar, 
    .woocommerce-page .site-content .woocommerce-pagination .current,
    .woocommerce-page .site-content .woocommerce-pagination a:hover,
    .woocommerce-page .site-content .woocommerce-pagination a:focus, 
    .woocommerce-cart .site-main .woocommerce .woocommerce-cart-form table.shop_table tbody td.actions .coupon .button:hover, 
    .woocommerce-wishlist #content table.wishlist_table.shop_table tbody td a.yith-wcqv-button:hover {
        background-color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .pagination .page-numbers.current,
    .pagination .page-numbers:not(.dots):hover, 
    .author-section .author-content-wrap .social-list li a:hover svg, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"]:hover, 
    .site-banner.banner-six .banner-caption .btn-readmore:hover, 
    .woocommerce-page .site-content .woocommerce-pagination .current,
    .woocommerce-page .site-content .woocommerce-pagination a:hover,
    .woocommerce-page .site-content .woocommerce-pagination a:focus, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .payment_methods li input[type="radio"]:checked + label::before {
        border-color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .main-navigation ul li a .menu-description::after {
        border-top-color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .cat-tab-section .tab-content-wrap {
        ' . 'border-top-color: rgba(' . $rgb2[0] . ', ' . $rgb2[1] . ', ' . $rgb2[2] . ', 0.2);' . '
    }

    @media screen and (max-width: 1024px) {
        .main-navigation .close:hover {
            background: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
        }
    }';
           
    wp_add_inline_style( 'blossom-shop', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'blossom_shop_dynamic_css', 99 );

/**
 * Function for sanitizing Hex color 
 */
function blossom_shop_sanitize_hex_color( $color ){
    if ( '' === $color )
        return '';

    // 3 or 6 hex digits, or the empty string.
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
        return $color;
}

/**
 * convert hex to rgb
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
*/
function blossom_shop_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}

/**
 * Convert '#' to '%23'
*/
function blossom_shop_hash_to_percent23( $color_code ){
    $color_code = str_replace( "#", "%23", $color_code );
    return $color_code;
}