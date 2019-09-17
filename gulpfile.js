const { task, parallel, series, watch } = require('gulp')

const babel = require('gulp-babel')

const del = require('del')
const path = require('path')

const { configure } = require('./build/util')

configure('source', 'public/app', {
  pot: (src, dest) => (done) => {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    // .pipe(dest)
  },
  img: (src, dest) => (done) => {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    // .pipe(dest)
  },
  css: (src, dest) => (done) => {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    // .pipe(dest)
  },
  js: (src, dest) => (done) => {
    console.log(src, dest)
    return done()
    // return gulp.src(src)
    //   .pipe(dest)
  },
})

exports.default = async (done) => {
  console.log('done')

  return done()
}
