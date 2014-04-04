var gulp = require('gulp'),
	browserify = require('gulp-browserify'),
	browserifyHandlebars = require('browserify-handlebars'),
	concat = require('gulp-concat'),
	jshint = require('gulp-jshint'),
	plumber = require('gulp-plumber'),
	uglify = require('gulp-uglify');

gulp.task('admin-js', function(){
	return gulp.src(['src/admin-js/**/*.js', '!src/admin-js/vendors/**/*.js'])
		.pipe(plumber())
		.pipe(jshint('src/admin-js/.jshintrc'))
		.pipe(jshint.reporter('default'))
		.pipe(gulp.src(['src/admin-js/admin.js']))
		.pipe(browserify({
			transform: [browserifyHandlebars]
		}))
		.on('prebundle', function(bundle) {
			bundle.require(__dirname + '/src/admin-js/classes/core/Class.js', { expose: 'cakephp-admin-plugin/core/Class' });
			bundle.require(__dirname + '/src/admin-js/classes/utility/Inflector.js', { expose: 'cakephp-admin-plugin/utility/Inflector' });
			bundle.require(__dirname + '/src/admin-js/classes/components/SimpleFileUpload.js', { expose: 'cakephp-admin-plugin/components/SimpleFileUpload' });
			bundle.require(__dirname + '/src/admin-js/classes/components/ImageUpload.js', { expose: 'cakephp-admin-plugin/components/ImageUpload' });
		})
		.pipe(concat('admin.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('../webroot/admin-plugin/js/'));
});

gulp.task('admin-vendors-js', function(){
	return gulp.src([
			'src/admin-js/vendors/jquery.js',
			'src/admin-js/vendors/bootstrap.js',
			'src/admin-js/vendors/jquery-ui.js',
			'src/admin-js/vendors/load-image.js',
			'src/admin-js/vendors/load-image-meta.js',
			'src/admin-js/vendors/canvas-to-blob.js',
			'src/admin-js/vendors/jquery.iframe-transport.js',
			'src/admin-js/vendors/jquery.fileupload.js',
			'src/admin-js/vendors/jquery.fileupload-process.js',
			'src/admin-js/vendors/jquery.fileupload-image.js',
			'src/admin-js/vendors/jquery.fileupload-audio.js',
			'src/admin-js/vendors/jquery.fileupload-video.js',
			'src/admin-js/vendors/jquery.fileupload-validate.js'
        ])
		.pipe(plumber())
		.pipe(concat('vendors.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('../webroot/admin-plugin/js/'));

});

gulp.task('watch', function(){
	gulp.watch(['src/admin-js/**/*.js', '!src/admin-js/vendors/**/*.js'], ['admin-js']);
	gulp.watch('src/admin-js/vendors/**/*.js', ['admin-vendors-js']);
});