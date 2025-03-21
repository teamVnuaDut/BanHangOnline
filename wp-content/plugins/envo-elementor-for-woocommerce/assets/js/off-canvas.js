(function($) {
    var WidgetwewOffCanvassHandler = function($scope, $) {

        var $btn = $scope.find('.etww-off-canvas-button a');

        // Move the off canvas sidebar to the footer
        $('.etww-off-canvas-wrap').appendTo('body');

        $($btn).on('click', function(e) {
            e.preventDefault();
            var $target = $(this).attr('href');

            // Open the off canvas sidebar
            $($target).toggleClass('show');
        });

        $('.etww-off-canvas-close, .etww-off-canvas-overlay').on('click', function() {
            $(this).closest('.etww-off-canvas-wrap').removeClass('show');
        });

    };
    
    // Make sure we run this code under Elementor
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/etww-off-canvas.default', WidgetwewOffCanvassHandler);
    });
})(jQuery);