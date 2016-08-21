var elixir = require('laravel-elixir');

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

elixir(function(mix) {
    mix.sass('../main.sass', 'public/assets/css/main.min.css')
    	.scripts([
	        '../../../bower_components/jquery/dist/jquery.min.js',
	        '../../../bower_components/jquery-validation/dist/jquery.validate.js',
	        '../../../bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
	        '../../../bower_components/jstzdetect/jstz.js',
	        '../main.js'
	    ], 'public/assets/js/main.min.js');
});