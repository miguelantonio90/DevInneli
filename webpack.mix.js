const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.webpackConfig({
	module: {
		rules: [
			{
				enforce: 'pre',
				test: /\.(js|vue)$/,
				loader: 'eslint-loader',
				exclude: /node_modules/
			}
		]
	}
});

mix.js('resources/js/main.js', 'public/js')
	.sass('resources/sass/app.sass', 'public/css')
	.copyDirectory('resources/assets', 'public/assets')
	.version();
