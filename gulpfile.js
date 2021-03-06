const path = require('path')

const bs = require('browser-sync').create()
const gulp = require('gulp')
const version = require('standard-version')

const autoprefixer = require('gulp-autoprefixer')
const babel = require('gulp-babel')
const cleanCSS = require('gulp-clean-css')
const eslint = require('gulp-eslint')
const imagemin = require('gulp-imagemin')
const php = require('gulp-connect-php')
const phpcs = require('gulp-phpcs')
const rename = require('gulp-rename')
const sass = require('gulp-sass')
const sourcemaps = require('gulp-sourcemaps')
const stylelint = require('gulp-stylelint')
const uglify = require('gulp-uglify')
const when = require('gulp-if')
const wpPot = require('gulp-wp-pot')
const zip = require('gulp-zip')

require('dotenv').config()

const { configure, args, watch, isProduction } = require('./config/build-util')

const tasks = configure('packages', 'resources/releases', {
  /**
   * Lint PHP fiels and generate translation file.
   *
   * @param {TaskConfig} param0
   * @return {ReadableStream}
   */
  php ({ src, dest, config }) {
    config.wpPot = {
      domain: config.name,
      package: `${config.name} v${config.version}`,
      relativeTo: config.path,
      lastTranslator: config.author,
      team: config.author
    }

    config.phpcs.standard = 'packages/phpcs.xml'

    return gulp.src(src)
      .pipe(phpcs(config.phpcs))
      .pipe(phpcs.reporter('log'))
      .pipe(wpPot(config.wpPot))
      .pipe(gulp.dest(dest))
  },

  /**
   * Compile SCSS files to CSS & minify.
   *
   * @param {TaskConfig} param0
   * @return {ReadableStream}
   */
  css ({ src, dest, config }) {
    return gulp.src(src, config.gulp)
      .pipe(stylelint(config.stylelint))
      .pipe(sass(config.sass).on('error', sass.logError))
      .pipe(autoprefixer())
      .pipe(gulp.dest(dest, config.gulp))
      .pipe(when(isProduction, cleanCSS()))
      .pipe(when(isProduction, rename(config.rename)))
      .pipe(gulp.dest(dest))
      .pipe(bs.stream())
  },

  /**
   * Minify javascripts.
   *
   * @param {TaskConfig} param0
   * @return {ReadableStream}
   */
  js ({ src, dest, config }) {
    return gulp.src(src, config.gulp)
      .pipe(eslint(config.eslint))
      .pipe(eslint.format())
      .pipe(eslint.failAfterError())
      .pipe(babel(config.babel))
      .pipe(when(isProduction, uglify(config.uglify)))
      .pipe(when(isProduction, rename(config.rename)))
      .pipe(when(isProduction, gulp.dest(dest, config.gulp)))
      .pipe(bs.stream())
  },

  /**
   * Optimize images.
   *
   * @param {TaskConfig} param0
   * @return {ReadableStream}
   */
  img ({ src, dest, config }) {
    return gulp.src(src)
      .pipe(imagemin(config.imagemin))
      .pipe(gulp.dest(dest))
  },

  /**
   * Generate CHANGELOG.md for each then Packaging them.
   *
   * @param {TaskConfig} param0
   * @return {ReadableStream}
   */
  zip: async ({ src, dest, config }) => {
    config.release.path = config.path
    config.release.infile = `${config.path}/CHANGELOG.md`
    config.release.releaseAs = config.version
    config.release.scripts = {
      postbump: `node config/build-util.js bump ${config.path}`
    }

    // Generate CHANGELOG.md file inside source directory
    await version(config.release)

    return gulp.src(src, config.gulp)
      .pipe(zip(`${config.name}-${config.version}.zip`, config.zip))
      .pipe(gulp.dest(dest))
  }
})

/**
 * Start php server.
 *
 * @async
 * @returns {String}
 */
const phpServer = () => new Promise((resolve, reject) => {
  const { argv } = args.options({
    proxy: {
      alias: 'p',
      describe: 'Enable proxy',
      default: process.env.WP_HOME,
      type: 'string'
    }
  })

  let url

  try {
    url = new URL(argv.proxy)
  } catch (err) {
    return reject(err)
  }

  // Don't start php dev server if the hostname isn't localhost
  // In case you've already set your envvar to something like:
  // WP_HOME = http://wpdev.local
  if (url.hostname !== 'localhost') {
    return resolve(url)
  } else {
    if (url.port === '') {
      url.port = 8080
    }
  }

  process.on('exit', () => {
    php.closeServer()
  })

  php.server({
    port: url.port,
    hostname: url.hostname,
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
    resolve(url)
  })
})

/**
 * Start php server.
 *
 * @async
 * @param {URL} url
 * @returns {String}
 */
const bSync = (url) => new Promise((resolve, reject) => {
  // if (process.env.NODE_ENV === 'testing') {
  //   return resolve(url)
  // }

  const { argv } = args.options({
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
  })

  const routes = [
    '/wp-admin/css',
    '/wp-admin/images',
    '/wp-admin/js',
    '/wp-includes/css',
    '/wp-includes/images',
    '/wp-includes/js'
  ]

  bs.init({
    proxy: url.toString(),
    baseDir: './public',
    notify: argv.notify,
    open: argv.open,
    https: url.protocol === 'https',
    serveStatic: routes.map(route => ({
      route,
      dir: `public/wp${route}`
    }))
  }, (err) => {
    if (err) return reject(err)

    url = new URL(url.toString())
    url.host = 'localhost:3000'

    return resolve(url)
  })
})

/**
 * Execute end-to-end test using webdriver.io and browser-stack.
 *
 * @async
 * @param {Function} done
 */
exports.e2e = (done) => {
  const { default: Launcher } = require('@wdio/cli')

  return phpServer().then(bSync).then(url => {
    const wdio = new Launcher('tests/wdio.config.js', {
      baseUrl: url.toString(),
      onComplete (code) {
        done()
        process.exit(code)
      }
    })

    return wdio.run()
  })
}

/**
 * Generate CHANGELOG.md file.
 */
exports.release = async () => {
  const { argv } = args.options({
    pre: {
      describe: 'Bump as prerelase version',
      type: 'string'
    },
    as: {
      describe: 'Bump as version',
      type: 'string'
    },
    sign: {
      describe: 'Sign the release tag',
      type: 'boolean',
      default: isProduction
    }
  })

  const releaseConfig = {
    commitAll: true,
    sign: argv.sign,
    skip: {},
    scripts: {
      postbump: `yarn archive --mode ${process.env.NODE_ENV}`,
    }
  }

  if (!isProduction) {
    releaseConfig.skip.tag = true
    releaseConfig.skip.commit = true
  } else {
    releaseConfig.scripts.precommit = 'git add -A'
  }

  if (typeof argv.pre === 'string') {
    releaseConfig.prerelease = argv.pre
  }

  if (typeof argv.as === 'string') {
    releaseConfig.releaseAs = argv.as
  }

  await version(releaseConfig)
}

/**
 * Start php development server and watch files changes through browserSync.
 */
exports.default = async () => {
  const url = await phpServer()

  await bSync(url)

  watch(tasks, bs)
}
