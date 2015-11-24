var gulp 		= require('gulp'),
	gutil 		= require('gulp-util')
	notify 		= require('gulp-notify'),
	sass 		= require('gulp-ruby-sass'),
	autoprefix 	= require('gulp-autoprefixer'),
	minifyCSS 	= require('gulp-minify-css'),
	rename		= require('gulp-rename'),
	include		= require('gulp-include'),
	uglify		= require('gulp-uglify')
	livereload	= require('gulp-livereload');

var inputDir	= 'resources/assets';
var outputDir	= 'public/assets';

gulp.task('main-css', function(){
	return sass(inputDir + '/main.sass', {
			style: 'compressed',
		})
		.on('error', handleError)
		.pipe(autoprefix('last 3 version'))
		.pipe(minifyCSS({keepSpecialComments:0}))
        .pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(outputDir + '/css'))
		.pipe(livereload());
});

gulp.task('main-js', function(){
	return gulp.src(inputDir + '/main.js')
		.pipe(include())
		.pipe(uglify())
        .pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(outputDir + '/js'))
		.pipe(livereload());
});

gulp.task('watch', function(){
	//livereload.listen();
	gulp.watch(inputDir + '/**/*.sass', ['main-css']);
	gulp.watch(inputDir + '/**/*.js', ['main-js']);
	//livereload({start: true});
	//var livereloadPage = function () {
		//livereload.reload();
	//};
	//gulp.watch('../**/*.php', livereloadPage);
});

gulp.task('default', ['main-css', 'main-js', 'watch']);

function handleError(err) {
	gulp.src(inputDir + '/main.sass').pipe(notify(err));
	this.emit('end');
}