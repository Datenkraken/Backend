const mix = require('laravel-mix');
const config = require('./webpack.config');

require('mix-tailwindcss');

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
mix.webpackConfig(config);

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .tailwind('./tailwind.config.js')
   .disableNotifications();

if (mix.inProduction()) {
    mix.version();
} else {
// Development settings
    mix.webpackConfig({
            devtool: 'inline-cheap-module-source-map'
    });
}
