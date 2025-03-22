jQuery( document ).ready(function() {
	jQuery('.wpdesk-notice-gutenberg').each(function( index ) {
		var classList = jQuery(this).attr('class').split(/\s+/);
		var type = '';
		jQuery.each(classList, function(index, item) {
			if (item.startsWith('notice-')) {
				type = item.replace('notice-','');
			}
		});
		content = this.innerText;
		actions = [];
		jQuery.each(jQuery(this).find('a'), function(index, item) {
			text = item.innerText;
			actions.push({
				url: item.href,
				label: text.charAt(0).toUpperCase() + text.slice(1),
			});
		});
		isDismiss = jQuery(this).hasClass('is-dismissible');
		window.wp.data.dispatch( 'core/notices' ).createNotice(
			type,
			content,
			{
				isDismissible: isDismiss,
				actions: actions
			}
		);
	});
} );
