const path = require('path')

const bs = require('browser-sync').create()
const gulp = require('gulp')

require('dotenv').config()

const autoprefixer = require('gulp-autoprefixer')
const babel = require('gulp-babel')
const cleanCSS = require('gulp-clean-css')
const eslint = require('gulp-eslint')
const imagemin = require('gulp-imagemin')
const php = require('gulp-connect-php')
const phpcs = require('gulp-phpcs')
const rename = require('gulp-rename')
const sass = require('gulp-sass')
const stylelint = require('gulp-stylelint')
const uglify = require('gulp-uglify')
const wpPot = require('gulp-wp-pot')
const zip = require('gulp-zip')

const wpHome = new URL(process.env.WP_HOME)
const { configure, watch, isProduction } = require('./build/util')
const { argv } = require('yargs').options({
  open: {
    alias: 'o',
    describe: 'Open in browser',
    default: false,
    type: 'boolean'
  },
  notify: {
    alias: 'n',
    describe: 'Enable notification',
    default: false,
    type: 'boolean'
  },
  proxy: {
    alias: 'p',
    describe: 'Enable proxy',
    default: wpHome.hostname,
    type: 'string'
  }
})

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

    config.phpcs.standard = 'source/phpcs.xml'

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
      .pipe(eslint(config.eslint))
      // .pipe(eslint.format('pretty'))

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

exports.release = async () => {
  const version = require('standard-version')

  await version({
    sign: true,
    prerelease: 'minor'
  })
}

/**
 * Start php development server and watch files changes.
 */
exports.default = async () => {
  const bSync = (proxy) => new Promise((resolve, reject) => {
    bs.init({
      proxy: proxy,
      baseDir: './public',
      notify: argv.notify,
      open: argv.open,
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
    }, () => resolve())
  })

  const phpServer = () => new Promise((resolve, reject) => {
    process.on('exit', () => {
      php.closeServer()
    })

    php.server({
      ini: 'public/.user.ini',
      base: 'public',
      router: './server.php',
      configCallback (type, args) {
        if (type === php.OPTIONS_PHP_CLI_ARR) {
          return [
            '-e',
            '-d', 'cli_server.color=on'
          ].concat(args)
        }

        return args
      }
    }, () => {
      resolve()
    })
  })

  if (argv.proxy !== wpHome.hostname) {
    await phpServer()
  }

  await bSync(argv.proxy)

  watch(tasks, bs)

}
