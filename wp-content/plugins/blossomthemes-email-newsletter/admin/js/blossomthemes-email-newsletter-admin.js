jQuery(document).ready(function ($) {
    var frame;
    // ADD IMAGE LINK
    $('body').on('click', '.bten-upload-button', function (e) {
        e.preventDefault();
        var clicked = $(this).closest('div');
        var custom_uploader = wp.media({
            title: 'BlossomThemes Email Newsletter Pro Uploader',
            multiple: false  // Set this to true to allow multiple files to be selected
        })
            .on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                var str = attachment.url.split('.').pop();
                var strarray = ['jpg', 'gif', 'png', 'jpeg'];
                if ($.inArray(str, strarray) != -1) {
                    clicked.find('.bten-screenshot').empty().hide().append('<img src=' + attachment.url + '><a class="bten-remove-image"></a>').slideDown('fast');
                } else {
                    clicked.find('.bten-screenshot').empty().hide().append('<small>' + wp_travel_engine_companion_uploader.msg + '</small>').slideDown('fast');
                }

                clicked.find('.bten-upload').val(attachment.id).trigger('change');
                clicked.find('.bten-upload-button').val(bten_uploader.change);
            })
            .open();
    });

    $('body').on('click', '.bten-remove-image', function (e) {
        var selector = $(this).parent('div').parent('div');
        selector.find('.bten-upload').val('').trigger('change');
        selector.find('.bten-remove-image').hide();
        selector.find('.bten-screenshot').slideUp();
        selector.find('.bten-upload-button').val(bten_uploader.upload);

        return false;
    });

    $(".newsletter-bg-option:checked").each(function () {
        if ($(this).val() == "bg-color") {
            $(this).parent().parent().siblings(".bg-image-uploader").hide();
            $(this).parent().parent().siblings(".form-bg-color").show();
            $(this).parent().parent().siblings(".enable-overlay-option").hide();
        }
        if ($(this).val() == "image") {
            $(this).parent().parent().siblings(".form-bg-color").hide();
            $(this).parent().parent().siblings(".bg-image-uploader").show();
            $(this).parent().parent().siblings(".enable-overlay-option").show();
        }
    });

    $(".newsletter-bg-option").on("change", function () {
        if ($(this).val() == "bg-color") {
            $(this).parent().parent().siblings(".bg-image-uploader").hide();
            $(this).parent().parent().siblings(".form-bg-color").show();
            $(this).parent().parent().siblings(".enable-overlay-option").hide();
        }
        if ($(this).val() == "image") {
            $(this).parent().parent().siblings(".form-bg-color").hide();
            $(this).parent().parent().siblings(".bg-image-uploader").show();
            $(this).parent().parent().siblings(".enable-overlay-option").show();
        }
    });
    $('body').on('change', '#platform-select', function (e) {
        var val = $(this).val();
        //Remove the error message if the user selects a different platform other than activecampaign. @since 2.2.7
        if ($(this).val() !== 'activecampaign') {
            if ($('div').hasClass('activecampaign settings-error notice is-dismissible')) {
                $('.activecampaign.settings-error.notice.is-dismissible').remove();
            }
        }
        $.ajax({
            url: ajaxurl,
            data: {
                'action': 'bten_get_platform',
                'calling_action': 'bten_platform_settings',
                'platform': val,
                'nonce': bten_uploader?.bten_nonce
            },
            type: 'POST',
            beforeSend: function () {
                $("#ajax-loader").fadeIn(500);
            },
            success: function (response) {
                $('.newsletter-settings').removeClass('current');
                $('#platform-switch-holder').html(response);
            },
            complete: function () {
                $("#ajax-loader").fadeOut(500);
            }
        });

    });
    $('body').on('click', '.mailerlite-lists', function (e) {
        if ($(this).is(':checked')) {
            $(this).attr('value', '1');
        }
        else {
            $(this).attr('value', '0');
        }
    });
    $('body').on('click', '.enable_notif_opt', function (e) {
        if ($(this).is(':checked')) {
            $(this).attr('value', '1');
        }
        else {
            $(this).attr('value', '0');
        }
    });

    $('body').on('click', '.blossomthemes-email-newsletter-gdpr', function (e) {
        if ($(this).is(':checked')) {
            $(this).attr('value', '1');
        }
        else {
            $(this).attr('value', '0');
        }
    });

    $('.blossomthemes-email-newsletter-color-form').wpColorPicker();

    $('body').on('click', '.bten_upload_image_button', function (e) {
        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library: {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id, 
                    type: 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function () { // it also has "open" and "close" events 
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
                /* if you sen multiple to true, here is some code for getting the image IDs
                var attachments = frame.state().get('selection'),
                    attachment_ids = new Array(),
                    i = 0;
                attachments.each(function(attachment) {
                    attachment_ids[i] = attachment['id'];
                    console.log( attachment );
                    i++;
                });
                */
            })
                .open();
    });
    $('body').on('click', '.bten_remove_image_button', function () {
        $(this).hide().prev().val('').prev().addClass('button').html('Upload image');
        return false;
    });

    $(".newsletter-success-option:checked").each(function () {
        if ($(this).val() == "text") {
            $(".blossomthemes-email-newsletter-settings-wrap.page").hide();
            $('.blossomthemes-email-newsletter-settings-wrap.message').show();
        }
        if ($(this).val() == "page") {
            $(".blossomthemes-email-newsletter-settings-wrap.message").hide();
            $('.blossomthemes-email-newsletter-settings-wrap.page').show();
        }
    });

    $(".newsletter-success-option").on("change", function () {
        if ($(this).val() == "text") {
            $(".blossomthemes-email-newsletter-settings-wrap.page").hide();
            $('.blossomthemes-email-newsletter-settings-wrap.message').show();
        }
        if ($(this).val() == "page") {
            $(".blossomthemes-email-newsletter-settings-wrap.message").hide();
            $('.blossomthemes-email-newsletter-settings-wrap.page').show();
        }
    });

    $('.tabs-menu a').on('click', function (event) {
        event.preventDefault();
        $(this).parent().addClass('current');
        $(this).parent().siblings().removeClass('current');
        var tab = $(this).attr('href');
        $('.tab-content').not(tab).css('display', 'none');
        $('#' + tab).show();
    });

});
