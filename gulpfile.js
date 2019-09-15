const { readdirSync } = require('fs')
const { task, parallel, series, watch } = require('gulp');

const babel = require('gulp-babel');

const del = require('del');
const path = require('path');

require('dotenv').config();

const paths = {
  img: 'images/**',
  css: 'scss/*.scss',
  js: 'js/*.js',
}

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

const readdirOpt = { withFileTypes: true }
const scandir = (dir, dest) => readdirSync(dir, readdirOpt).reduce((build, sub) => {
  if (!sub.isDirectory()) return build

  return readdirSync(path.join(dir, sub.name), readdirOpt).reduce((build, source) => {
    if (!source.isDirectory()) return build

    let target = path.join(sub.name, source.name)
    build[source.name] = {
      pot: {
        src: path.join(dir, target, '**.php'),
        dest: path.join(dest, target, 'languages', `${source.name}.pot`)
      }
    }

    Object.keys(paths).forEach(asset => {
      target = path.join(target, 'assets')

      build[source.name][asset] = {
        src: path.join(dir, target, paths[asset]),
        dest: path.join(dest, target, asset)
      }
    })

    return build
  }, {})
}, {})

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
  console.log('done')
  return done()
}