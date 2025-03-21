(function ($) {
    'use strict';

    // float cart

    var $myDiv = $('.woo-float-info');
    if ($myDiv.length) {
        $(window).scroll(function () {
            var distanceTop = $('.woocommerce div.product form.cart').offset().top - 60;

            if ($(window).scrollTop() > distanceTop)
                $myDiv.animate({'bottom': '0'}, 200);
            else
                $myDiv.stop(true).animate({'bottom': '-400px'}, 100);
        });

        $('.woo-float-info .close-me').bind('click', function () {
            $(this).parent().remove();
        });
    };
    
    // return to top button
    // ===== Scroll to Top ==== 
    $(window).scroll(function () {
        if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
            $('#return-to-top').fadeIn(200);    // Fade in the arrow
        } else {
            $('#return-to-top').fadeOut(200);   // Else fade out the arrow
        }
    });
    $('#return-to-top').click(function () {      // When arrow is clicked
        $('body,html').animate({
            scrollTop: 0                       // Scroll to top of body
        }, 500);
    });

})(jQuery);
