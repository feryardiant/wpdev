const { readdirSync } = require('fs')
const { promisify } = require('util')
const execFile = promisify(require('child_process').execFile)
// const { spawn, execFile } = require('child_process')
const path = require('path')

const { task, parallel, series } = require('gulp')

require('dotenv').config()

function serverLog (type) {
  return function (log) {
    console[type].apply(null, [log.toString().trim()])
  }
}

const wpServer = exports.wpServer = async () => {
  const { stdout, stderr } = await execFile('wp', ['server'])

  console.info(stdout)
  console.error(stderr)
}

const scandir = exports.scandir = (dir, dest) => {
  const readdirOpt = { withFileTypes: true }
  const paths = {
    img: 'images/**',
    css: 'scss/*.scss',
    js: 'js/*.js',
  }

  const sourceDir = readdirSync(dir, readdirOpt).reduce((build, sub) => {
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

  return Object.entries(sourceDir)
}

const configure = exports.configure = (src, dest, tasks) => {
  const buildTasks = []
  const assetTasks = []

  for (const [name, asset] of scandir(src, dest)) {
    Object.keys(asset).forEach(key => {
      const assetTask = `${name}:${key}`
      task(assetTask, tasks[key](asset[key].src, asset[key].dest))
      assetTasks.push(assetTask)
    })

    task(`${name}:build`, series(...assetTasks))
    buildTasks.push(...assetTasks)
  }

  task('build', series(...buildTasks))
}
