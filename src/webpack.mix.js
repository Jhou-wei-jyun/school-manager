const mix = require('laravel-mix');
// require('laravel-mix-svg-sprite');
mix.setPublicPath('./')
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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');
mix.browserSync({
   proxy: 'localhost:8000',
   browser: "google chromium"
})

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   // .svgSprite(
   //    'resources/assets/icon', // The directory containing your SVG files
   //    'pubilc/sprite.svg', // The output path for the sprite
   //    [loaderOptions], // Optional, see https://github.com/kisenka/svg-sprite-loader#configuration
   //    [pluginOptions] // Optional, see https://github.com/kisenka/svg-sprite-loader#configuration
   // )
   .version()
   .sourceMaps();

