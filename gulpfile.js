const path = require('path')

const gulp = require('gulp')
const autoprefixer = require('gulp-autoprefixer')
const babel = require('gulp-babel')
const connect = require('gulp-connect-php')
const cleanCSS = require('gulp-clean-css')
const eslint = require('gulp-eslint')
const imagemin = require('gulp-imagemin')
const phpcs = require('gulp-phpcs')
const rename = require('gulp-rename')
const sass = require('gulp-sass')
const stylelint = require('gulp-stylelint')
const uglify = require('gulp-uglify')
const wpPot = require('gulp-wp-pot')
const zip = require('gulp-zip')

const { configure, watch, isProduction } = require('./build/util')

const tasks = configure('source', 'build', {
  /**
   * Lint PHP fiels and generate translation file.
   *
   * @param {Object}       param0
   * @param {Array|String} param0.src
   * @param {Array|String} param0.dest
   * @param {Object}       param0.
   * @return {stream}
   */
  php ({ src, dest, config }) {
    config.wpPot = {
      domain: config.name,
      package: `${config.name} v${config.version}`,
      relativeTo: path.dirname(path.resolve(src, '..')),
      lastTranslator: config.author,
      team: config.author
    }

    config.phpcs.standard = 'phpcs.xml'

    return gulp.src(src)
      .pipe(phpcs(config.phpcs))
      .pipe(phpcs.reporter('log'))
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
    config.stylelint.reporters.push({
      formatter: require('stylelint-formatter-pretty'),
      console: true
    })

    return gulp.src(src, { sourcemaps: true })
      .pipe(stylelint(config.stylelint))
      .pipe(sass(config.sass).on('error', sass.logError))
      .pipe(autoprefixer())
      .pipe(gulp.dest(dest))
      .pipe(cleanCSS())
      .pipe(rename(config.rename))
      .pipe(gulp.dest(dest, { sourcemaps: true }))
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
    const stream = gulp.src(src, { sourcemaps: true })
      .pipe(eslint())
      .pipe(eslint.format('pretty'))

    if (isProduction) {
      stream.pipe(eslint.failAfterError())
    }

    return stream
      .pipe(babel(config.babel))
      .pipe(uglify(config.uglify))
      .pipe(rename(config.rename))
      .pipe(gulp.dest(dest, { sourcemaps: true }))
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
 * Start php development server and watch files changes.
 */
exports.default = () => {
  const server = require('browser-sync').create()

  const config = {
    ini: 'public/.user.ini',
    base: 'public',
    router: './server.php',
    configCallback (type, args) {
      if (type === connect.OPTIONS_PHP_CLI_ARR) {
        return [
          '-e',
          '-d', 'cli_server.color=on'
        ].concat(args)
      }

      return args
    }
  }

  connect.server(config, () => {
    server.init({
      proxy: '127.0.0.1:8000',
      baseDir: './public',
      notify: false,
      open: false,
      serveStatic: [
        {
          route: '/wp-admin/css',
          dir: 'public/wp/wp-admin/css'
        },
        {
          route: '/wp-admin/images',
          dir: 'public/wp/wp-admin/images',
        },
        {
          route: '/wp-admin/js',
          dir: 'public/wp/wp-admin/js',
        },
        {
          route: '/wp-includes/css',
          dir: 'public/wp/wp-includes/css',
        },
        {
          route: '/wp-includes/images',
          dir: 'public/wp/wp-includes/images',
        },
        {
          route: '/wp-includes/js',
          dir: 'public/wp/wp-includes/js',
        }
      ]
    })

    watch(tasks, server)
  })
}
