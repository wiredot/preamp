'use strict';

var gulp = require('gulp'),
	sass = require('gulp-sass'),
	maps = require('gulp-sourcemaps'),
	concat = require('gulp-concat'),
	svgmin = require('gulp-svgmin'),
	svgstore = require('gulp-svgstore'),
	rsp = require('remove-svg-properties').stream,
	imagemin = require('gulp-tinypng'),
	livereload = require('gulp-livereload'),
	plumber = require('gulp-plumber'),
	notify = require('gulp-notify'),
	del = require('del'),
	path = require('path');

var options = {
	assets: 'assets/',
	src: 'src/'
}

gulp.task('js', function() {
	return gulp.src( options.src + 'js/*.js')
		.pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %>")}))
		.pipe(maps.init())
		// .pipe(concat('script.js'))
		.pipe(maps.write('./'))
		.pipe(gulp.dest( options.assets + 'js'))
		.pipe(notify({
			message: 'all done',
			title: 'JS'
		}))
		;
});

gulp.task('scss', function() {
	return gulp.src( options.src + 'scss/*.scss')
		.pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %>")}))
		.pipe(maps.init())
		.pipe(sass())
		.pipe(maps.write('./'))
		.pipe(gulp.dest( options.assets + 'css'))
		.pipe(livereload())
		.pipe(notify({
			message: 'all done',
			title: 'SCSS'
		}))
		;
});

gulp.task('svg', function() {
	return gulp.src( options.src + 'svg/*.svg')
		.pipe(svgmin(function (file) {
			var prefix = path.basename(file.relative, path.extname(file.relative));
			return {
				plugins: [{
					cleanupIDs: {
						prefix: prefix + '-',
						minify: true
					}
				}]
			}
		}))
		.pipe(svgstore(''))
		.pipe(gulp.dest( options.assets + 'svg'));
});

gulp.task('default', ['scss', 'js', 'svg']);

gulp.task('watch', function() {
	livereload.listen();
	gulp.watch( options.src + 'js/*.js', ['js']);
	gulp.watch( options.src + 'scss/**/*.scss', ['scss']);
	gulp.watch( options.src + 'svg/*.svg', ['svg']);
});