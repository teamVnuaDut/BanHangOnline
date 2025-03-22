function fs_removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

function fs_trimChar(string, charToRemove) {
    while(string.charAt(0)==charToRemove) {
        string = string.substring(1);
    }

    while(string.charAt(string.length-1)==charToRemove) {
        string = string.substring(0,string.length-1);
    }

    return string;
}

/* Notice */
jQuery(function($) {
    $( document ).on( 'click', '.flexible-shipping-taxes-notice .notice-dismiss', function () {
        $.ajax( ajaxurl,
            {
                type: 'POST',
                data: {
                    action: 'flexible_shipping_taxes_notice',
                }
            } );
    } );

	$( document ).on( 'click', '#enable-fs-connect-box', function () {
		var fs_connect_checkbox = $('.enable-fs-connect-box');
		var fs_box_state;

		if ( fs_connect_checkbox.prop('checked') ){
			$('.fs-connect-integration-box').slideDown();
			fs_box_state = 1;
		} else{
			$('.fs-connect-integration-box').slideUp();
			fs_box_state = 0;
		}

		$.ajax( ajaxurl,
			{
				type: 'POST',
				data: {
					action: 'update_fs_connect_integration_setting',
					fs_box_state: fs_box_state
				}
			} );
	} );

	$( document ).on( 'click', '#flexible_shipping_rate_plugin .close-fs-rate-notice', function () {
		$( '#flexible_shipping_rate_plugin .notice-dismiss' ).click();
	} );

	$( document ).on( 'click', '#flexible_shipping_rate_plugin .fs-not-good', function () {
		$('#flexible_shipping_rate_plugin p').html( fs_admin.notice_not_good_enought );
	} );

	$( document ).on( 'click', '.js--button-hints', function () {
		var label = $( this ).text();
		var second_label = $( this ).attr( 'data-second_label' );
		$( this ).toggleClass( 'open' ).text( second_label ).attr( 'data-second_label', label );
		$( '.js--hints' ).slideToggle();
	} );

});

/* Free shipping */
jQuery(function($) {

	const $free_shipping = $('#woocommerce_flexible_shipping_method_free_shipping');
	const $free_shipping_notice = $('#woocommerce_flexible_shipping_method_free_shipping_cart_notice');
	const $free_shipping_notice_text = $('#woocommerce_flexible_shipping_method_free_shipping_notice_text');
	const $free_shipping_progress_bar = $('#woocommerce_flexible_shipping_method_free_shipping_progress_bar');

	function fs_toggle_free_shipping_notice() {
		const free_shipping_val = $free_shipping.val();
		$free_shipping_notice.closest('tr').toggle(free_shipping_val !== '');
		$free_shipping_notice_text.closest('tr').toggle(free_shipping_val !== '' && $free_shipping_notice.is(':checked'));
		$free_shipping_progress_bar.closest('tr').toggle(free_shipping_val !== '' && $free_shipping_notice.is(':checked'));
	}

	$free_shipping.on('change',  function(){
		fs_toggle_free_shipping_notice();
	});

	$free_shipping_notice.on('change',  function(){
		fs_toggle_free_shipping_notice();
	});

	fs_toggle_free_shipping_notice();

	function disable_free_shipping_requires_upselling_options() {
		let $select = jQuery('#woocommerce_flexible_shipping_method_free_shipping_requires_upselling');
		let enabled_option = 'order_amount';
		$select.find('option').each(function(){
			jQuery(this).prop('disabled',this.value !== enabled_option);
		});
	}

	disable_free_shipping_requires_upselling_options();
});

/* Tax included in shipping costs */
jQuery(function($) {
	let tax_status_field = $('#woocommerce_flexible_shipping_tax_status');

	function fs_toggle_prices_include_tax() {
		$('#woocommerce_flexible_shipping_prices_include_tax').closest('tr').toggle(tax_status_field.val()!=='none');
	}

	tax_status_field.on('change',  function(){
		fs_toggle_prices_include_tax();
	});

	fs_toggle_prices_include_tax();
});

jQuery(function($) {
	function fs_toggle_costs_calculation() {
		$('.fs-costs-calculation-enabled').each(function(){
			$('tr.flexible_shipping_method_rules').toggle($(this).prop('checked'));
			$('tr.flexible-shipping-method-rules-settings').toggle($(this).prop('checked'));
		});
		$('div.flexible-shipping-method-rules-settings-modal').hide();
	}

	function fs_toggle_costs_calculation_modal() {
		$('.wc-modal-shipping-method-settings .fs-costs-calculation-enabled').each(function(){
			$('.wc-modal-shipping-method-settings div.flexible-shipping-method-rules-settings-modal').toggle($(this).prop('checked'));
		});
	}

	$( document ).on( 'change', '.fs-costs-calculation-enabled', function () {
		fs_toggle_costs_calculation();
	} );

	$( document ).on( 'change', '.wc-modal-shipping-method-settings .fs-costs-calculation-enabled', function () {
		fs_toggle_costs_calculation_modal();
	} );

	$( document.body ).on( 'init_tooltips', function() {
		fs_toggle_costs_calculation_modal();
		$( 'a.fs-non-modal-method-edit' ).each( function () {
			$( this ).attr( 'href', $( this ).attr( 'href' ) + '&instance_id=' + $( this ).closest( 'article.wc-modal-shipping-method-settings' ).data( 'id' ) );
		} );
	} );

	fs_toggle_costs_calculation();
});
