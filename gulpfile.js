const gulp = require('gulp')

const babel = require('gulp-babel')
const imagemin = require('gulp-imagemin')
const postcss = require('gulp-postcss')
const rename = require('gulp-rename')
const sass = require('gulp-sass')
const uglify = require('gulp-uglify')
const wpPot = require('gulp-wp-pot')
const zip = require('gulp-zip')

const del = require('del')
const path = require('path')
const autoprefixer = require('autoprefixer')
const cssnano = require('cssnano')
const reporter = require('postcss-reporter')
const stylelint = require('stylelint')

const { configure, watch, isProduction } = require('./build/util')

const tasks = configure('source', 'build', {
  /**
   * Generate translation file.
   *
   * @param {Object}       param0
   * @param {Array|String} param0.src
   * @param {Array|String} param0.dest
   * @param {Object}       param0.
   * @return {stream}
   */
  pot ({ src, dest, config }) {
    config.wpPot = {
      domain: config.name,
      package: `${config.name} v${config.version}`,
      relativeTo: path.dirname(path.resolve(src, '..')),
      lastTranslator: config.author,
      team: config.author
    }

    return gulp.src(src)
      .pipe(wpPot(config.wpPot))
      .pipe(gulp.dest(dest))
  },

  /**
   * Compile SCSS files to CSS & minify.
   *
   * @param {Object}       param0
   * @param {Array|String} param0.src
   * @param {Array|String} param0.dest
   * @param {Object}       param0.config
   * @return {stream}
   */
  css ({ src, dest, config }) {
    config.postcss = [
      // stylelint,
      // reporter({ clearReportedMessages: true }),
      autoprefixer(),
    ]

    config.sass = {
      includePaths: ['node_modules']
    }

    if (isProduction) {
      config.postcss.push(cssnano())
    }

    return gulp.src(src, { since: gulp.lastRun(stylelint) })
      .pipe(sass(config.sass).on('error', sass.logError))
      .pipe(postcss(config.postcss))
      .pipe(rename(config.rename))
      .pipe(gulp.dest(dest))
  },

  /**
   * Minify javascripts.
   *
   * @param {Object}       param0
   * @param {Array|String} param0.src
   * @param {Array|String} param0.dest
   * @param {Object}       param0.config
   * @return {stream}
   */
  js ({ src, dest, config }) {
    return gulp.src(src)
      .pipe(babel())
      .pipe(uglify(config.uglify))
      .pipe(rename(config.rename))
      .pipe(gulp.dest(dest))
  },

  /**
   * Optimize images.
   *
   * @param {Object}       param0
   * @param {Array|String} param0.src
   * @param {Array|String} param0.dest
   * @param {Object}       param0.config
   * @return {stream}
   */
  img ({ src, dest, config }) {
    return gulp.src(src)
      .pipe(imagemin(config.imagemin))
      .pipe(gulp.dest(dest))
  },

  /**
   * Packaging.
   *
   * @param {Object}       param0
   * @param {Array|String} param0.src
   * @param {Array|String} param0.dest
   * @param {Object}       param0.config
   * @return {stream}
   */
  zip ({ src, dest, config }) {
    config.zip = {}

    return gulp.src(src)
      .pipe(zip(`${config.name}.zip`, config.zip))
      .pipe(gulp.dest(dest))
  }
})

/**
 * Watch the changes.
 *
 * @return {stream}
 */
exports.default = () => {
  console.log('Watching source...')
  return watch(tasks)
}
