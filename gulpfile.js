var gulp = require( 'gulp'),
    postcss = require('gulp-postcss'),
    watch = require('gulp-watch'),
    autoprefixer = require('autoprefixer'),
    sass = require('gulp-sass'),
    plumber = require('gulp-plumber'),
    cleanCSS = require('gulp-clean-css'),
    sourcemaps = require('gulp-sourcemaps');


var mainSass = 'scss/theme.scss';
var pathSass = 'scss/**/*.scss';
var pathCss = 'public/css/';

gulp.task('sass', function () {
    gulp.src(mainSass)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceMap: true,
            errLogToConsole: true
        }))
        .pipe(postcss([ autoprefixer({ browsers: ['last 2 versions'] }) ]))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(pathCss));
});

gulp.task('watch', function () {
    gulp.watch(pathSass, ['sass']);
});

gulp.task('default', [
    'sass'
]);
