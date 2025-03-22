jQuery(document).ready(function(){
	
	jQuery('body').on('click', '.bten_aweber_auth', function(e){
		var nonce = jQuery(this).data("nonce");
		jQuery.ajax({
			url: ajaxurl,
			data:{
				'action': 'bten_get_mailing_list',
				'calling_action': 'bten_aweber_auth',
				'bten_aw_auth_code': jQuery('#bten_aweber_auth_code').val(),
				nonce: nonce
			},
			dataType: 'JSON',
			type: 'POST',
			success:function(response){
				if(response.Ok==true)
				{
					jQuery('#bten_aweber_disconnect_div').show();
					jQuery('#bten_aweber_connect_div').hide();	
					jQuery('.bten_get_aweber_lists').click();
				}
				else
				{
					alert(response.Error);
				}
			},
			error: function(errorThrown){
			   alert('Error...');
			}
		});
	});

	jQuery('body').on('click', '.bten_aweber_remove_auth', function(e){
		var nonce = jQuery(this).data("nonce");
		jQuery.ajax({
			url: ajaxurl,
			data:{
				'action': 'bten_get_mailing_list',
				'calling_action': 'bten_aweber_remove_auth',
				nonce: nonce
			},			
			dataType: 'JSON',
			type: 'POST',
			success:function(response){
				if(response.Ok==true)
				{
					jQuery('#bten_aweber_disconnect_div').hide();
					jQuery('#bten_aweber_connect_div').show();
					jQuery('.bten_get_aweber_lists').click();
				}
				else
				{
					alert(response.Error);
				}
			},
			error: function(errorThrown){
			   alert('Error...');
			}
		});
	});

	jQuery('body').on('click', '.bten_get_aweber_lists', function(e){
		ListsSelect=jQuery('#'+jQuery(this).attr('rel-id'));
		ListsSelect.find('option').remove();
		var nonce = jQuery(this).data("nonce");
		jQuery("<option/>").val(0).text('Loading...').appendTo(ListsSelect);
		jQuery.ajax({
			url: ajaxurl,
			data:{
				'action': 'bten_get_mailing_list',
				'calling_action': 'bten_aweber_list',
				nonce: nonce
			},
			dataType: 'JSON',
			type: 'POST',
			success:function(response){
				ListsSelect.find('option').remove();
				jQuery.each(response, function(i, option)
				{
					jQuery("<option/>").val(i).text(option.name).appendTo(ListsSelect);
				});
			},
			error: function(errorThrown){
			   alert('Error...');
			}
		});
	});

});