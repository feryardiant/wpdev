const gulp = require('gulp')

const babel = require('gulp-babel')
const sass = require('gulp-sass')
const postcss = require('gulp-postcss')
const wpPot = require('gulp-wp-pot')
const rename = require('gulp-rename')

const del = require('del')
const path = require('path')
const autoprefixer = require('autoprefixer')
const cssnano = require('cssnano')
const reporter = require('postcss-reporter')
const stylelint = require('stylelint')

const { configure, watch } = require('./build/util')

const isProduction = process.env.NODE_ENV === 'production'

const tasks = configure('source', 'build', {
  pot (src, dest, opt) {
    const options = {
      domain: opt.name,
      package: `${opt.name} v${opt.version}`,
      relativeTo: path.dirname(path.resolve(src, '..')),
      lastTranslator: opt.author,
      team: opt.author
    }

    return gulp.src(src)
      .pipe(wpPot(options))
      .pipe(gulp.dest(dest))
  },
  css (src, dest) {
    sass.compiler = require('sass')

    const processors = [
      // stylelint,
      // reporter({ clearReportedMessages: true }),
      autoprefixer,
    ]

    const renameConfig = {}

    const sassOption = {
      includePaths: ['node_modules']
    }

    if (isProduction) {
      renameConfig.suffix = '.min'
      processors.push(cssnano)
    }

    return gulp.src(src, { since: gulp.lastRun(stylelint) })
      .pipe(sass(sassOption).on('error', sass.logError))
      .pipe(postcss(processors))
      .pipe(rename(renameConfig))
      .pipe(gulp.dest(dest))
  },
  js (src, dest, opt, done) {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    //   .pipe(gulp.dest(dest))
  },
  img (src, dest, opt, done) {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    //   .pipe(gulp.dest(dest))
  },
  zip (src, dest, opt, done) {
    console.log('compressing', src, `${dest}/${opt.name}.zip`)
    return done()
    // return gulp.src(src)
    //   .pipe(gulp.dest(dest))
  }
})

exports.default = () => {
  console.log('Watching source...')
  return watch(tasks)
}
