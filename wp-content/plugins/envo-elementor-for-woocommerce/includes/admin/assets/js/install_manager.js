;(function($) {
"use strict";

    /*
    * Plugin Installation Manager
    */
    var ETWWtemplataPluginManager = {

        init: function(){
            $( document ).on('click','.install-now', ETWWtemplataPluginManager.installNow );
            $( document ).on('click','.activate-now', ETWWtemplataPluginManager.activatePlugin);
            $( document ).on('wp-plugin-install-success', ETWWtemplataPluginManager.installingSuccess);
            $( document ).on('wp-plugin-install-error', ETWWtemplataPluginManager.installingError);
            $( document ).on('wp-plugin-installing', ETWWtemplataPluginManager.installingProcess);
        },

        /**
         * Installation Error.
         */
        installingError: function( e, response ) {
            e.preventDefault();
            var $card = $( '.etwwptemplata-plugin-' + response.slug );
            $button = $card.find( '.button' );
            $button.removeClass( 'button-primary' ).addClass( 'disabled' ).html( wp.updates.l10n.installFailedShort );
        },

        /**
         * Installing Process
         */
        installingProcess: function(e, args){
            e.preventDefault();
            var $card = $( '.etwwptemplata-plugin-' + args.slug ),
                $button = $card.find( '.button' );
                $button.text( WLTM.buttontxt.installing ).addClass( 'updating-message' );
        },

        /**
        * Plugin Install Now
        */
        installNow: function(e){
            e.preventDefault();

            var $button = $( e.target ),
                $plugindata = $button.data('pluginopt');

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }
            if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
                wp.updates.requestFilesystemCredentials( e );
                $( document ).on( 'credential-modal-cancel', function() {
                    var $message = $( '.install-now.updating-message' );
                    $message.removeClass( 'updating-message' ).text( wp.updates.l10n.installNow );
                    wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
                });
            }
            wp.updates.installPlugin( {
                slug: $plugindata['slug']
            });

        },

        /**
         * After Plugin Install success
         */
        installingSuccess: function( e, response ) {
            var $message = $( '.etwwptemplata-plugin-' + response.slug ).find( '.button' );

            var $plugindata = $message.data('pluginopt');

            $message.removeClass( 'install-now installed button-disabled updated-message' )
                .addClass( 'updating-message' )
                .html( WLTM.buttontxt.activating );

            setTimeout( function() {
                $.ajax( {
                    url: WLTM.ajaxurl,
                    type: 'POST',
                    data: {
                        action   : 'etww_ajax_plugin_activation',
                        location : $plugindata['location'],
                    },
                } ).done( function( result ) {
                    if ( result.success ) {
                        $message.removeClass( 'button-primary install-now activate-now updating-message' )
                            .attr( 'disabled', 'disabled' )
                            .addClass( 'disabled' )
                            .text( WLTM.buttontxt.active );

                    } else {
                        $message.removeClass( 'updating-message' );
                    }

                });

            }, 1200 );

        },

        /**
         * Plugin Activate
         */
        activatePlugin: function( e, response ) {
            e.preventDefault();

            var $button = $( e.target ),
                $plugindata = $button.data('pluginopt');

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }

            $button.addClass( 'updating-message button-primary' ).html( WLTM.buttontxt.activating );

            $.ajax( {
                url: WLTM.ajaxurl,
                type: 'POST',
                data: {
                    action   : 'etww_ajax_plugin_activation',
                    security: WLTM.etww_data_nonce,
                    location : $plugindata['location'],
                },
            }).done( function( response ) {
                if ( response.success ) {
                    $button.removeClass( 'button-primary install-now activate-now updating-message' )
                        .attr( 'disabled', 'disabled' )
                        .addClass( 'disabled' )
                        .text( WLTM.buttontxt.active );
                }
            });

        },

        
    };

    /*
    * Theme Installation Manager
    */
    var ETWWtemplataThemeManager = {

        init: function(){
            $( document ).on('click','.themeinstall-now', ETWWtemplataThemeManager.installNow );
            $( document ).on('click','.themeactivate-now', ETWWtemplataThemeManager.activateTheme);
            $( document ).on('wp-theme-install-success', ETWWtemplataThemeManager.installingSuccess);
            $( document ).on('wp-theme-install-error', ETWWtemplataThemeManager.installingError);
            $( document ).on('wp-theme-installing', ETWWtemplataThemeManager.installingProcess);
        },

        /**
         * Installation Error.
         */
        installingError: function( e, response ) {
            e.preventDefault();
            var $card = $( '.etwwptemplata-theme-' + response.slug );
            $button = $card.find( '.button' );
            $button.removeClass( 'button-primary' ).addClass( 'disabled' ).html( wp.updates.l10n.installFailedShort );
        },

        /**
         * Installing Process
         */
        installingProcess: function(e, args){
            e.preventDefault();
            var $card = $( '.etwwptemplata-theme-' + args.slug ),
                $button = $card.find( '.button' );
                $button.text( WLTM.buttontxt.installing ).addClass( 'updating-message' );
        },

        /**
        * Theme Install Now
        */
        installNow: function(e){
            e.preventDefault();

            var $button = $( e.target ),
                $themedata = $button.data('themeopt');

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }
            if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
                wp.updates.requestFilesystemCredentials( e );
                $( document ).on( 'credential-modal-cancel', function() {
                    var $message = $( '.themeinstall-now.updating-message' );
                    $message.removeClass( 'updating-message' ).text( wp.updates.l10n.installNow );
                    wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
                });
            }
            wp.updates.installTheme( {
                slug: $themedata['slug']
            });

        },

        /**
         * After Theme Install success
         */
        installingSuccess: function( e, response ) {
            var $message = $( '.etwwptemplata-theme-' + response.slug ).find( '.button' );

            var $themedata = $message.data('themeopt');

            $message.removeClass( 'install-now installed button-disabled updated-message' )
                .addClass( 'updating-message' )
                .html( WLTM.buttontxt.activating );

            setTimeout( function() {
                $.ajax( {
                    url: WLTM.ajaxurl,
                    type: 'POST',
                    data: {
                        action   : 'etww_ajax_theme_activation',
                        themeslug : $themedata['slug'],
                    },
                } ).done( function( result ) {
                    if ( result.success ) {
                        $message.removeClass( 'button-primary install-now activate-now updating-message' )
                            .attr( 'disabled', 'disabled' )
                            .addClass( 'disabled' )
                            .text( WLTM.buttontxt.active );

                    } else {
                        $message.removeClass( 'updating-message' );
                    }

                });

            }, 1200 );

        },

        /**
         * Theme Activate
         */
        activateTheme: function( e, response ) {
            e.preventDefault();

            var $button = $( e.target ),
                $themedata = $button.data('themeopt');

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }

            $button.addClass( 'updating-message button-primary' ).html( WLTM.buttontxt.activating );

            $.ajax( {
                url: WLTM.ajaxurl,
                type: 'POST',
                data: {
                    action   : 'etww_ajax_theme_activation',
                    themeslug : $themedata['slug'],
                    security: WLTM.etww_data_nonce,
                },
            }).done( function( response ) {
                if ( response.success ) {
                    $button.removeClass( 'button-primary install-now activate-now updating-message' )
                        .attr( 'disabled', 'disabled' )
                        .addClass( 'disabled' )
                        .text( WLTM.buttontxt.active );
                }
            });

        },

        
    };

    /**
     * Initialize ETWWtemplataPluginManager
     */
    $( document ).ready( function() {
        ETWWtemplataPluginManager.init();
        ETWWtemplataThemeManager.init();
        var $myDiv = $( '.etwwptemplate-theme' );
        if ( $myDiv.length ) {
            $( ".etww-import" ).addClass( 'disabled' );
        } else {
            $( ".etww-import" ).removeClass( 'disabled' );
        }
    });

})(jQuery);