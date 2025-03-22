jQuery(document).ready(function($) {

    var slider_auto, rtl;
    
    if( blossom_shop_data.auto == '1' ){
        slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( blossom_shop_data.rtl == '1' ){
        rtl = true;
    }else{
        rtl = false;
    }

    //sticky t bar js
    var winWidth = $(window).width();
    var containWidth = $('.sticky-bar-content .container').width();
    var result = (parseInt(winWidth) - parseInt(containWidth)) / 2;
    $('.sticky-t-bar .close').css('right', result);

    $('.sticky-t-bar .close').on( 'click', function(){
        $('.sticky-bar-content').slideToggle();
        $('.sticky-t-bar').toggleClass('active');
    });

    //header search toggle js
    $('.header-search .search-toggle').on( 'click', function(e){
        $(this).parent('.header-search').addClass('active');
        $('body').addClass('search-active');
        $('.header-search-wrap').fadeIn('slow');
        e.stopPropagation();
    });

    $('.header-search .search-form').on( 'click', function(e){
        e.stopPropagation();
    });

    $(window).on( 'click', function(){
        $('.header-search').removeClass('active');
        $('body').removeClass('search-active');
        $('.header-search-wrap').fadeOut('slow');
    });

    $(window).on( 'keyup', function(e){
        if(e.key == 'Escape') {
            $('.header-search').removeClass('active');
            $('body').removeClass('search-active');
            $('.header-search-wrap').fadeOut('slow');
            $('.secondary-menu .nav-menu').slideUp();
        }
    });

    //responsive menu toggle
    $('.menu-item-has-children').append('<button class="submenu-toggle"><i class="fas fa-chevron-down"></i></button>');
    $('.menu-item-has-children .submenu-toggle').on( 'click', function(){
        $(this).siblings('ul').slideToggle();
        $(this).toggleClass('active');
    });

    $('.secondary-menu button.toggle-btn').on( 'click', function(){
        $('.secondary-menu .nav-menu').slideDown();
    });

    $('.secondary-menu button.close-nav-toggle').on( 'click', function(){
        $('.secondary-menu .nav-menu').slideUp();
    });

    $('.main-navigation button.toggle-btn').on( 'click', function(){
        $(this).siblings('.primary-menu-list').animate({
            width: 'toggle'
        });
    });

    $('.main-navigation .close').on( 'click', function(){
        $(this).parents('.primary-menu-list').animate({
            width: 'toggle'
        });
    });

    //for accessibility
    $('.main-navigation ul li a, .secondary-menu ul li a, .mega-menu-wrap ul li a').on( 'focus', function () {
        $(this).parents('li').addClass('focused');
    }).on( 'blur', function () {
        $(this).parents('li').removeClass('focused');
    });

    $('.site-banner .item-wrap').owlCarousel({
        items      : 1,
        autoplay   : slider_auto,
        loop       : true,
        nav        : false,
        dots       : true,
        autoplaySpeed: 800,
        autoplayTimeout: 5000,
        lazyLoad   : true,
        rtl        : rtl,
        animateOut : blossom_shop_data.animation,
    });

    //banner cat border
    $('.site-banner .item-wrap .item').each(function(){
        var catWidth = $(this).find('.cat-links-inner').width();
        var containerWidth = $(this).find('.container').width();
        var widthResult = (parseInt(containerWidth) - parseInt(catWidth)) - 20;
        $(this).find('.cat-links-border').css('width', widthResult);
    });

    if($('.recent-prod-slider .item').length <= 4){
        owlLoop = false;
    }else {
        owlLoop = true;
    }
    $('.recent-prod-slider').owlCarousel({
        items: 4,
        autoplay: false,
        loop: owlLoop,
        nav: true,
        dots: true,
        autoplaySpeed: 800,
        autoplayTimeout: 3000,
        autoplayHoverPause : true,
        margin: 20,
        rtl: rtl,
        responsive : {
            0 : {
                items: 1,
                nav: false,
            }, 
            768 : {
                items: 2,
                nav: true,
            },
            1025 : {
                items: 3,
            }, 
            1200 : {
                items: 4,
            }
        }
    });

    if($('.testimonial-section.style-two .widget').length <= 3){
        owlLoop = false;
    }else {
        owlLoop = true;
    }
    $('.testimonial-section.style-two .section-grid').owlCarousel({
        items: 3,
        autoplay: false,
        loop: owlLoop,
        nav: true,
        dots: true,
        autoplaySpeed: 800,
        autoplayTimeout: 3000,
        autoplayHoverPause : true,
        rtl: rtl,
        responsive : {
            0 : {
                nav: false,
                items: 1,
            }, 
            768 : {
                nav: true,
                items: 2,
            }, 
            1025 : {
                items: 3,
            }
        }
    });

    //client section
    if($('.client-section:not(.style-two) .image-holder').length <= 6){
        owlLoop = false;
    }else {
        owlLoop = true;
    }
    $('.client-section:not(.style-two) .blossom-inner-wrap').addClass('owl-carousel');
    $('.client-section:not(.style-two) .blossom-inner-wrap').owlCarousel({
        items: 6,
        autoplay: true,
        loop: owlLoop,
        nav: true,
        dots: false,
        autoplaySpeed: 800,
        autoplayTimeout: 3000,
        autoplayHoverPause : true,
        rtl: rtl,
        responsive : {
            0 : {
                items: 1,
            }, 
            768 : {
                items: 3,
            }, 
            1025 : {
                items: 5,
            },
            1200 : {
                items: 6,
            }
        }
    });

    //back to top
    $(window).on( 'scroll', function(){
        if($(this).scrollTop() > 200) {
            $('#back-to-top').addClass('active');
        }else {
            $('#back-to-top').removeClass('active');
        }
    });

    $('#back-to-top').on( 'click', function(){
        $('html, body').animate({
            scrollTop: 0
        }, 600);
    });

    //blog page feature section js
    $('.blog-page-feature-section .bttk-itw-holder').addClass('owl-carousel');

    $('.blog-page-feature-section .bttk-itw-holder, .trending-section .section-grid').owlCarousel({
        items: 3,
        autoplay: false,
        loop: true,
        nav: true,
        dots: false,
        autoplayHoverPause : true,
        margin: 30,
        rtl: rtl,
        responsive : {
            0 : {
                items: 1,
            },
            768 : {
                items: 2,
            },
            1025 : {
                items: 3,
            }
        }
    });

    $('.bsp-style-one .site-main .flex-control-thumbs').addClass('owl-carousel');
    $('.bsp-style-one .site-main .flex-control-thumbs').owlCarousel({
        items: 4,
        autoplay: false,
        loop: false,
        nav: true,
        dots: false,
        margin: 10,
        rtl: rtl,
        lazyLoad: false,
    });

    //js for accesibility compatible in IE edge
    $(".nav-menu li a, .products li a").on( 'focus', function(){
        $(this).parents("li").addClass("hover");
     }).on( 'blur', function(){
        $(this).parents("li").removeClass("hover");
     });

     $(".recent-prod-image a, .popular-prod-image a, .cat-image a, .product-image a").on( 'focus', function(){
        $(this).parents(".item").addClass("hover");
     }).on( 'blur', function(){
        $(this).parents(".item").removeClass("hover");
     }); 

     $(".user-block a").on( 'focus', function(){
        $(this).parents(".user-block").addClass("hover");
     }).on( 'blur', function(){
        $(this).parents(".user-block").removeClass("hover");
     });

     $(".cart-block a").on( 'focus', function(){
        $(this).parents(".cart-block").addClass("hover");
     }).on( 'blur', function(){
        $(this).parents(".cart-block").removeClass("hover");
     });  

});