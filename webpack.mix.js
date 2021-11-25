const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
    'resources/plugins/themefisher-font/style.css',
    'resources/plugins/bootstrap/css/bootstrap.min.css',
    'resources/plugins/animate/animate.css',
    'resources/plugins/slick/slick.css',
    'resources/plugins/slick/slick-theme.css',
    'resources/css/website/style.css',
    'resources/css/website/website.css',
], 'public/css/website/all.css').version();

mix.scripts([
    'resources/plugins/jquery/dist/jquery.min.js',
    'resources/plugins/bootstrap/js/bootstrap.min.js',
    'resources/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js',
    'resources/plugins/instafeed/instafeed.min.js',
    'resources/plugins/ekko-lightbox/dist/ekko-lightbox.min.js',
    'resources/plugins/slick/slick.min.js',
    'resources/plugins/slick/slick-animation.min.js',
    'resources/js/website/script.js',
    'resources/js/webfontloader.js',
], 'public/js/website/all.js').version();

mix.scripts([
    'resources/js/website/index.js',
], 'public/js/website/index.js').version();
