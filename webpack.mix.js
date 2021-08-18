let mix = require('laravel-mix')
require('laravel-mix-eslint')
require('laravel-vue-lang/mix')

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

mix.js('resources/js/app.js', 'public/js').vue({ version: 2 })
mix.sass('resources/sass/app.scss', 'public/css').options({ processCssUrls: false })
mix.webpackConfig({ output: { filename: '[name].js', publicPath: 'public/' }})
mix.babelConfig({ plugins: ['@babel/plugin-syntax-dynamic-import'] })
mix.eslint({ fix: true, extensions: ['vue', 'js'] })
mix.lang()
mix.sourceMaps()
mix.version()
