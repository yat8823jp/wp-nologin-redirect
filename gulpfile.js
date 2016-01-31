/*
 * Global variables
 */
var gulp = require( 'gulp' ),
	scss = require( 'gulp-sass' ),
	plumber = require( 'gulp-plumber' ),//エラー通知
	notify = require( 'gulp-notify' ),//エラー通知
	pleeease = require( 'gulp-pleeease' ),//ベンダープレフィックス
	sourcemaps = require('gulp-sourcemaps'),
	watch = require('gulp-watch'),
	paths = {
		rootDir : './',
	}

/*
 * Sass
 */
gulp.task( 'scss', function() {
	gulp.src( paths.rootDir + 'scss/**/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( scss() )
		.pipe( pleeease() )
		.pipe( plumber({
			errorHandler: notify.onError( 'Error: <%= error.message %>' )
		}) )
		.pipe( sourcemaps.write( './' ) )
		.pipe( gulp.dest( paths.rootDir + 'css' ) );
});

/*
 * Pleeease
 */
gulp.task('pleeease', function() {
	return gulp.src( paths.rootDir + 'css/*.css' )
		.pipe( pleeease({
			// minifier: false, //圧縮の有無 true/false
			sass: true
		}) )
		.pipe( plumber ( {
			errorHandler: notify.onError( 'Error: <%= error.message %>' )
		} ) )
		.pipe( gulp.dest( paths.rootDir + 'css' ) );
});

/*
 * Default
 */
gulp.task( 'default', function() {
	watch(paths.rootDir + 'scss/**/*.scss', function(event){
		gulp.start( 'scss' );
	});
});

