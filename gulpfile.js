const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
       .webpack('app.js');

    if (process.env.NODE_ENV == 'production') {
        mix.copy('node_modules/font-awesome/fonts', 'public/fonts')
        mix.copy('node_modules/admin-lte/bootstrap/fonts', 'public/fonts')
    }
});
