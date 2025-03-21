(function($) {
	var WidgetwewWooSliderHandler = function($scope, $) {
	    if($('body').hasClass('no-carousel')) {
			return;
		}

		var $slider = $scope.find('.etww-woo-slider');

		if($slider.length > 0) {

			var $selector 	= $slider.find('ul.products'),
				$options 	= JSON.parse($slider.attr('data-settings'));
			
			$selector.find('li.col').each(function(index) {
				$(this).removeClass('col');
			});
			
			$selector.slick($options);

		}
	};
	
	// Make sure we run this code under Elementor
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/etww-woo-slider.default', WidgetwewWooSliderHandler);
	});
})(jQuery);