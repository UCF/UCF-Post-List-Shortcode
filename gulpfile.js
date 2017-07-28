var browserSync = require('browser-sync').create(),
    gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    cleanCSS = require('gulp-clean-css'),
    include = require('gulp-include'),
    eslint = require('gulp-eslint'),
    isFixed = require('gulp-eslint-if-fixed'),
    babel = require('gulp-babel'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    scsslint = require('gulp-scss-lint'),
    uglify = require('gulp-uglify'),
    merge = require('merge'),
    readme = require('gulp-readme-to-markdown');

var configLocal = require('./gulp-config.json'),
    configDefault = {
      src: {
        scssPath: './src/scss',
        jsPath: './src/js'
      },
      dist: {
        cssPath: './static/css',
        jsPath: './static/js'
      }
    },
    config = merge(configDefault, configLocal);


//
// CSS
//

// Lint all scss files
gulp.task('scss-lint', function() {
  return gulp.src(config.src.scssPath + '/*.scss')
    .pipe(scsslint());
});

// Compile + bless primary scss files
gulp.task('css-main', function() {
  return gulp.src(config.src.scssPath + '/ucf-post-list.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(rename('ucf-post-list.min.css'))
    .pipe(gulp.dest(config.dist.cssPath))
    .pipe(browserSync.stream());
});

// All css-related tasks
gulp.task('css', ['scss-lint', 'css-main']);

// Run eshint on js files in src.jsPath. Do not perform linting
// on vendor js files.
gulp.task('es-lint', function() {
  return gulp.src([config.src.jsPath + '/*.js'])
    .pipe(eslint({ fix: true }))
    .pipe(eslint.format())
    .pipe(isFixed(config.src.jsPath));
});

gulp.task('js-admin', function() {
  gulp.src(config.src.jsPath + '/ucf-post-list-admin.js')
    .pipe(include({
      includePaths: [config.packagesPath, config.src.jsPath]
    }))
      .on('error', console.log)
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename('ucf-post-list-admin.min.js'))
    .pipe(gulp.dest(config.dist.jsPath));
});

// Process main plugin js file
gulp.task('js-build', function() {
  return gulp.src(config.src.jsPath + '/ucf-post-list.js')
    .pipe(include({
      includePaths: [config.packagesPath, config.src.jsPath]
    }))
      .on('error', console.log)
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename('ucf-post-list.min.js'))
    .pipe(gulp.dest(config.dist.jsPath));
});

gulp.task('js', ['es-lint', 'js-build', 'js-admin']);


//
// Readme
//

// Create a Github-flavored markdown file from the plugin readme.txt
gulp.task('readme', function() {
  return gulp.src(['readme.txt'])
    .pipe(readme({
      details: false,
      screenshot_ext: [],
    }))
    .pipe(gulp.dest('.'));
});


// Rerun tasks when files change
gulp.task('watch', function() {
  if (config.sync) {
    browserSync.init({
        proxy: {
          target: config.target
        }
    });
  }

  gulp.watch(config.src.scssPath + '/**/*.scss', ['css']);
  gulp.watch(config.src.jsPath + '/**/*.js', ['js']).on('change', browserSync.reload);
  gulp.watch('./**/*.php').on('change', browserSync.reload);
  gulp.watch('readme.txt', ['readme']);
});

// Default task
gulp.task('default', ['css', 'js', 'readme']);
