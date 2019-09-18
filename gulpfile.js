const gulp = require('gulp')

const babel = require('gulp-babel')
const wpPot = require('gulp-wp-pot')

const del = require('del')
const path = require('path')

const { configure, watch } = require('./build/util')

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
  img (src, dest, opt, done) {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    // .pipe(dest)
  },
  css (src, dest, opt, done) {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    // .pipe(dest)
  },
  js (src, dest, opt, done) {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    //   .pipe(dest)
  },
  zip (src, dest, opt, done) {
    console.log('compressing', src, `${dest}/${opt.name}.zip`)
    return done()
    // return gulp.src(src)
    //   .pipe(dest)
  }
})

exports.default = () => {
  console.log('Watching source...')
  return watch(tasks)
}
