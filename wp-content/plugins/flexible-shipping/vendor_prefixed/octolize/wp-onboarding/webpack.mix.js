/* ---
  Docs: https://www.npmjs.com/package/mati-mix/
--- */
const mix = require('mati-mix');

// OnBoarding
mix.js( [ 'assets-src/onboarding/js/index.jsx' ], 'assets/js/onboarding.js' );
mix.sass( 'assets-src/onboarding/scss/style.scss', 'assets/css/onboarding.css' );

mix.mix.babelConfig({
	"presets": [
		"@babel/preset-env",
		"@babel/preset-react"
	],
});

mix.mix.webpackConfig({
	externals: {
		"@wordpress/i18n": ["wp", "i18n"]
	}
});
