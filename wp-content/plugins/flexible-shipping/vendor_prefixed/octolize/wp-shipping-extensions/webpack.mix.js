let mix = require( 'laravel-mix' );

mix.options( {
    processCssUrls: false,
    cssNano: {
        discardComments: {
            removeAll: true,
        },
    },
    manifest: false,
    terser: {
        extractComments: false,
        terserOptions: {
            compress: {
                drop_console: true
            },
            output: {
                comments: false,
            },
        }
    }
} );

// Shipping Extensions
mix.js('assets/src/js/shipping-extensions.jsx', 'assets/dist/js/shipping-extensions.js').react();
mix.sass('assets/src/scss/shipping-extensions.scss', 'assets/dist/css/shipping-extensions.css');
