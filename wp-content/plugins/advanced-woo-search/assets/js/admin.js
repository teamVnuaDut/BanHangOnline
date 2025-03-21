jQuery(document).ready(function ($) {
    'use strict';

    var $reindexBlock = $('#aws-reindex');
    var $reindexBtn = $('#aws-reindex .button');
    var $reindexProgress = $('#aws-reindex .reindex-progress');
    var $reindexCount = $('#aws-reindex-count strong');
    var syncStatus;
    var processed;
    var toProcess;
    var syncData = false;

    var $clearCacheBtn = $('#aws-clear-cache .button');


    // Reindex table
    $reindexBtn.on( 'click', function(e) {

        e.preventDefault();

        syncStatus = 'sync';
        toProcess  = 0;
        processed = 0;

        $reindexBlock.addClass('loading');
        $reindexProgress.html ( processed + '%' );

        sync('start');

    });


    function sync( data ) {

        $.ajax({
            type: 'POST',
            url: aws_vars.ajaxurl,
            data: {
                action: 'aws-reindex',
                data: data,
                _ajax_nonce: aws_vars.ajax_nonce
            },
            dataType: "json",
            timeout:0,
            success: function (response) {
                if ( 'sync' !== syncStatus ) {
                    return;
                }

                toProcess = response.data.found_posts;
                processed = response.data.offset;

                processed = Math.floor( processed / toProcess * 100 );
                if ( processed > 100 ) {
                    processed = 100;
                }

                syncData = response.data;

                if ( 0 === response.data.offset && ! response.data.start ) {

                    // Sync finished
                    syncStatus = 'finished';

                    console.log( response.data );
                    console.log( "Reindex finished!" );

                    $reindexBlock.removeClass('loading');

                    $reindexCount.text( response.data.found_posts );

                } else {

                    console.log( response.data );

                    $reindexProgress.html( processed + '%' );

                    // We are starting a sync
                    syncStatus = 'sync';

                    sync( response.data );
                }

            },
            error : function( jqXHR, textStatus, errorThrown ) {
                console.log( "Request failed: " + textStatus );

                if ( textStatus == 'timeout' || jqXHR.status == 504 ) {
                    console.log( 'timeout' );
                    if ( syncData ) {
                        setTimeout(function() { sync( syncData ); }, 1000);
                    }
                } else if ( textStatus == 'error') {
                    if ( syncData ) {

                        if ( 0 !== syncData.offset && ! syncData.start ) {
                            setTimeout(function() { sync( syncData ); }, 3000);
                        }

                    }
                }

            },
            complete: function ( jqXHR, textStatus ) {
            }
        });

    }

    // Clear cache
    $clearCacheBtn.on( 'click', function(e) {

        e.preventDefault();

        var $clearCacheBlock = $(this).closest('#aws-clear-cache');

        $clearCacheBlock.addClass('loading');

        $.ajax({
            type: 'POST',
            url: aws_vars.ajaxurl,
            data: {
                action: 'aws-clear-cache',
                _ajax_nonce: aws_vars.ajax_nonce
            },
            dataType: "json",
            success: function (data) {
                $clearCacheBlock.removeClass('loading');
                alert('Cache cleared!');
            }
        });

    });


    // Change option state

    var changingState = false;

    $('[data-change-state]').on( 'click', function(e) {

        e.preventDefault();

        if ( changingState ) {
            return;
        } else {
            changingState = true;
        }

        var self = $(this);
        var $parent = self.closest('td');
        var setting = self.data('setting');
        var option = self.data('name');
        var state = self.data('change-state');

        $parent.addClass('loading');

        $.ajax({
            type: 'POST',
            url: aws_vars.ajaxurl,
            data: {
                action: 'aws-changeState',
                setting: setting,
                option: option,
                state: state,
                _ajax_nonce: aws_vars.ajax_nonce
            },
            dataType: "json",
            success: function (data) {
                $parent.removeClass('loading');
                $parent.toggleClass('active');
                changingState = false;
            }
        });

    });


    // Dismiss welcome notice

    $( '.aws-welcome-notice.is-dismissible' ).on('click', '.notice-dismiss', function ( event ) {

        $.ajax({
            type: 'POST',
            url: aws_vars.ajaxurl,
            data: {
                action: 'aws-hideWelcomeNotice',
                _ajax_nonce: aws_vars.ajax_nonce
            },
            dataType: "json",
            success: function (data) {
            }
        });

    });

});