jQuery( document ).ready( function ( $ ) {

    // Let's use the API.
    var api = wp.customize;

    syncPreviewButtons();

    /**
     * Sync device preview button from WordPress to WPBF and vice versa.
     */
    function syncPreviewButtons() {
        // Bind device changes from WordPress default.
        api.previewedDevice.bind( function ( newDevice ) {
            envoResponsivePreview( newDevice );
        } );
    }

    /**
     * Setup WPBF device preview.
     * 
     * @param string device The device (mobile, tablet, or desktop).
     * @param bool modifyOverlay Whether or not to modify the wp-full-overlay.
     */
    function envoResponsivePreview( device ) {
        $( '.envo-responsive-options button' ).removeClass( 'active' );
        $( '.envo-responsive-options .preview-' + device ).addClass( 'active' );
        $( '.envo-control-device' ).removeClass( 'active' );
        $( '.envo-control-' + device ).addClass( 'active' );
        //$(".control-section li[id*='"+device+"']").addClass('active');
    }

    // Display desktop control by default.
    $( ".control-section li[id*='desktop']" ).addClass( 'envo-control-device' ).addClass( 'envo-control-desktop' );
    $( ".control-section li[id*='tablet']" ).addClass( 'envo-control-device' ).addClass( 'envo-control-tablet' );
    $( ".control-section li[id*='mobile']" ).addClass( 'envo-control-device' ).addClass( 'envo-control-mobile' );
    //$( '.envo-control-desktop' ).addClass( 'active' );


    // Loop through envo device buttons and assign the event.
    $( '.envo-responsive-options button' ).on( 'click', function ( e ) {
        var device = this.getAttribute( 'data-device' );

        envoResponsivePreview( device );
        // Trigger WordPress device event.
        api.previewedDevice.set( device );
    } );

} );

jQuery( document ).ready( function ( $ ) {

    // Let's use the API.
    var api = wp.customize;

    syncPreviewButtons();

    /**
     * Sync device preview button from WordPress to WPBF and vice versa.
     */
    function syncPreviewButtons() {
        // Bind device changes from WordPress default.
        api.previewedDevice.bind( function ( newDevice ) {
            envoResponsivePreview( newDevice );
        } );
    }

    /**
     * Setup WPBF device preview.
     * 
     * @param string device The device (mobile, tablet, or desktop).
     * @param bool modifyOverlay Whether or not to modify the wp-full-overlay.
     */
    function envoResponsivePreview( device ) {
        $( '.envo-responsive-options' ).removeClass( 'active' );
        $( '.envo-responsive-options.preview-' + device ).addClass( 'active' );
        $( '.customize-control-kirki-radio-buttonset' ).find( "input[value='" + device + "']" ).prop( "checked", true );
    }

    // Display desktop control by default.
    //$( '.preview-desktop' ).addClass( 'active' );

    // Loop through envo device buttons and assign the event.
    $( '.switch-label' ).on( 'click', function ( e ) {
        var device = $( '.envo-responsive-options' ).getAttribute( 'data-device' );

        envoResponsivePreview( device );
        // Trigger WordPress device event.
        api.previewedDevice.set( device );
    } );

} );

jQuery( document ).ready( function ( $ ) {
    
    // Let's use the API.
    var api = wp.customize;

    // on each click of the new element, we toggle the wrapper element to show or hide
    $( '.show-kirki-control.dashicons-edit' ).click( function ( e ) {
        $( '.envo-control-desktop' ).removeClass( 'active' );
        // we go 2 parents up, to find the '.customize-control-kirki-custom' element, and toggle it
        $( this ).parents().eq( 2 ).nextUntil( '.customize-control-kirki-custom' ).toggleClass( 'active' ).toggleClass('activated');
        $( '.envo-control-tablet' ).removeClass( 'active' );
        $( '.envo-control-mobile' ).removeClass( 'active' );
        // toggle the devices buttons
        $( this ).prev().toggle();
        // set desktop as default
        api.previewedDevice.set( 'desktop' );

        // switch classes to display '+' and '-' dashicons
        $( e.target ).toggleClass( 'dashicons-edit dashicons-minus' );

        // prevent default behaviour if element is clicked (page jump)
        e.preventDefault();
    } );
    






// add collapse feature to 'typography' and 'spacing' controls
    var controlClasses = '.customize-control-kirki-typography, .customize-control-kirki-spacing';


    // hide all '.wrapper' instances inside the above defined controls
    $( controlClasses ).find( '.wrapper' ).hide();

    // prepend a new '<span>' element to the control-label
    $( controlClasses ).find( '.customize-control-title' ).prepend( '<span class="show-kirki-control dashicons dashicons-plus"></span> ' );



    // on each click of the new element, we toggle the wrapper element to show or hide
    $( controlClasses ).find( '.customize-control-title' ).click( function ( e ) {

        // we go 2 parents up, to find the '.wrapper' element, and toggle it
        $( this ).parents().eq( 1 ).find( '.wrapper' ).slideToggle( 'fast' );

        // switch classes to display '+' and '-' dashicons
        $( this ).find( '.show-kirki-control' ).toggleClass( 'dashicons-plus dashicons-minus' );

        // prevent default behaviour if element is clicked (page jump)
        e.preventDefault();
    } );
} );

