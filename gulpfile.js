var gulp 		= require('gulp');
var gutil 		= require('gulp-util');
var notify 		= require('gulp-notify');
var sass 		= require('gulp-ruby-sass');
var autoprefix 	= require('gulp-autoprefixer');
var minifyCSS 	= require('gulp-minify-css');
var rename		= require('gulp-rename');
var include		= require('gulp-include');
var uglify		= require('gulp-uglify');

var inputDir	= 'resources/assets';
var outputDir	= 'public/assets';

gulp.task('main-css', function(){
	return gulp.src(inputDir + '/main.sass')
		.pipe(sass())
		.on('error', handleError)
		.pipe(autoprefix('last 3 version'))
		.pipe(minifyCSS({keepSpecialComments:0}))
        .pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(outputDir + '/css'));
});

gulp.task('main-js', function(){
	return gulp.src(inputDir + '/main.js')
		.pipe(include())
		.pipe(uglify())
        .pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(outputDir + '/js'));
});

gulp.task('watch', function(){
	gulp.watch(inputDir + '/**/*.sass', ['main-css']);
	gulp.watch(inputDir + '/**/*.js', ['main-js']);
});

gulp.task('default', ['main-css', 'main-js', 'watch']);

function handleError(err) {
	gulp.src(inputDir + '/main.sass').pipe(notify(err));
	this.emit('end');
}