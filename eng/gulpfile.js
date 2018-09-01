'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cleanCSS = require('gulp-clean-css');

//SASS
gulp.task('sass', function() {
    return gulp.src('./sass/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 3 versions', 'iOS 7', 'android >= 4.2']
        }))
        .pipe(cleanCSS())
        .pipe(gulp.dest('./css'));
});
gulp.task('sass:watch', function() {
    gulp.watch('./sass/*.scss', ['sass']);
});
