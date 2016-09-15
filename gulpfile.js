var gulp      = require('gulp');
var concat    = require('gulp-concat');
var rename    = require('gulp-rename');
var uglify    = require('gulp-uglify');
var minify    = require('gulp-minify');
var minifyCSS = require('gulp-minify-css');

var alljsFiles  = ['js/index.js', 'js/jquery.nanoscroller.min.js', 'js/jquery-3.0.0.min.js'];
var allcssFiles = 'css/*.css';
var jsDest      = 'dist/js';
var cssDest     = 'dist/css';

gulp.task('scripts', function() {
	return gulp.src(alljsFiles)
		.pipe(uglify())
		.pipe(concat('index.js'))
		.pipe(gulp.dest(jsDest));
});

gulp.task('styles', function() {
	return gulp.src(allcssFiles)
		.pipe(minifyCSS())
		.pipe(gulp.dest(cssDest));
});
