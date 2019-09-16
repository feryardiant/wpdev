const { spawn } = require('child_process')
const { task, parallel, series, watch } = require('gulp')

const babel = require('gulp-babel')

const del = require('del')
const path = require('path')

const { wpServer, scandir } = require('./build/util')

let server

process.on('exit', () => {
  if (server) server.kill('SIGKILL')
})

const tasks = {
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
}

const build = scandir('source', 'public/app')
const assets = []

for (const [name, asset] of Object.entries(build)) {
  Object.keys(asset).reduce((arr, key) => {
    (typeof asset[key] !== 'string') && arr.push(key)
    return arr
  }, []).forEach(key => {
    task(`${name}:${key}`, tasks[key](asset[key].src, asset[key].dest))
    assets.push(`${name}:${key}`)
  })

  task(`${name}:build`, parallel(...assets))
}

exports.default = (done) => {
  server = wpServer()
  console.log('done')
  return done()
}
