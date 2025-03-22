jQuery( document ).on( 'click', '.notice-dismiss', function() {
    const $notice_div= jQuery(this).closest('div.notice');
    const notice_name = $notice_div.data('notice-name');
    const source = $notice_div.data('source');
    const security = $notice_div.data('security');
    if ('' !== notice_name) {
        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                security: security,
                action: 'wpdesk_notice_dismiss',
                notice_name: notice_name,
                source: source,
            },
            success: function (response) {
            }
        });
    }
});

jQuery( document ).on( 'click', '.notice-dismiss-link', function() {
    jQuery(this).closest('div.notice').data('source',jQuery(this).data('source'));
    jQuery(this).closest('div.notice').find('.notice-dismiss').click();
});
