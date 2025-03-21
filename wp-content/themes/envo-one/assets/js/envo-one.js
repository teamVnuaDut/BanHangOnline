jQuery.noConflict();
( function ( $ ) {
    // Menu fixes
    function onResizeMenuLayout() {
        
        if ( $( window ).width() > 767 ) {
            $( ".main-menu" ).on( 'hover', '.dropdown', function () {
                $( this ).addClass( 'open' )
            },
                function () {
                    $( this ).removeClass( 'open' )
                }
            );
            $( ".dropdown" ).on( 'focusin',
                function () {
                    $( this ).addClass( 'open' )
                }
            );
            $( ".dropdown" ).on( 'focusout',
                function () {
                    $( this ).removeClass( 'open' )
                }
            );
            $( window ).on( 'resize',
                function () {
                    $(  '.dropdown'  ).removeClass( 'open' )
                }
            );
            $( "#site-navigation" ).appendTo( ".menu-heading" );
        } else {
            $( ".dropdown" ).on( 'hover',
                function () {
                    $( this ).removeClass( 'open' );
                }
            );
        }
    };
    // initial state
    onResizeMenuLayout();
    // on resize
    $( window ).on( 'resize', onResizeMenuLayout );

    $( '.navbar .dropdown-toggle' ).on( 'focus', function () {
        $( this ).addClass( 'disabled' );
    } );

    $( document ).ready( function () {
        var $myDiv = $( '#theme-menu' );
        $(".toggle").click(function(e){
            setTimeout(function(){ $('.nav-close-button').filter(':visible').focus(); }, 200);
            e.preventDefault();
        });
         
        if ( $myDiv.length ) {
            $('#theme-menu').hcOffcanvasNav({
                disableAt: 768,
                customToggle: $('.toggle'),
                levelTitles: false,
                levelTitleAsBack: false,
                pushContent: $('.page-wrap')
              });
        }
    } );

    $( document ).ready( function () {
        $( 'body.cart-open' ).on( 'click', function () {
            $( "body" ).removeClass( "cart-open" );
        } );
        $( '.site-header-cart .header-cart-close' ).on( 'click', function () {
            $( "body" ).removeClass( "cart-open" );
        } );
        $( '.header-cart' ).on( 'click', function (e) {
            e.preventDefault();
            $( "body" ).toggleClass( "cart-open" );
        } );
    } );
    $( document ).ready( function () {
        $( "body" ).addClass( "js-loaded" );
        $( ".header-cart, .header-compare, .header-my-account, .header-search, .header-wishlist" ).css("visibility", "visible");
        feather.replace({ class: 'envo-icon', 'stroke-width': 1.5 });
    } );
} )( jQuery );