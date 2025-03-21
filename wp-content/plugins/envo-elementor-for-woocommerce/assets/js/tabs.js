(function($) {
	var WidgetwewTabsHandler = function($scope, $) {

		var $tabs 	= $scope.find('.etww-tabs'),
			$data 	= $tabs.data('settings');

		if($tabs.hasClass('etww-has-active-item')) {
			$tabs.find('.etww-tab-title[data-tab="'+ $data['active_item'] +'"]').addClass('etww-active');
			$tabs.find('#etww-tab-content-'+ $data['active_item']).addClass('etww-active');
		} else {
			$tabs.find('.etww-tab-title[data-tab="1"]').addClass('etww-active');
			$tabs.find('#etww-tab-content-1').addClass('etww-active');
		}

	    $tabs.find('.etww-tab-title').on('click', function() {
			var $this 	= $(this),
				$tab_id = $this.data('tab');

			// Remove the active classes
			$scope.find('.etww-tab-title').removeClass('etww-active');
			$scope.find('.etww-tab-content').removeClass('etww-active');

			// Add the class in the normal and mobile title
			$scope.find('.etww-tab-title[data-tab="'+ $tab_id + '"]').addClass('etww-active');

			//$this.addClass('etww-active');
			// Display the content
		    $this.parent().parent().find('#etww-tab-content-' + $tab_id).addClass('etww-active');

		});
	};
	
	// Make sure we run this code under Elementor
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/etww-tabs.default', WidgetwewTabsHandler);
	});
})(jQuery);