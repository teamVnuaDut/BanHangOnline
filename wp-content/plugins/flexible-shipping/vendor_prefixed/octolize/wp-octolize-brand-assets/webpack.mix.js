let mix = require( 'laravel-mix' );

mix.options( {
	processCssUrls: false,
	cssNano: {
		discardComments: {
			removeAll: true,
		},
	},
	manifest: false
} );

mix.setPublicPath( 'assets/dist' );

mix.sass( 'assets/src/scss/admin.scss', 'assets/dist/css' );
