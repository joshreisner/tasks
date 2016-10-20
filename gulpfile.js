var elixir = require('laravel-elixir');

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