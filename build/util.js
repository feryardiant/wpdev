const { spawn } = require('child_process')
const { readdirSync } = require('fs')
const path = require('path')

require('dotenv').config()

function serverLog (type) {
  return function (log) {
    console[type](log.toString().trim())
  }
}

exports.wpServer = () => {
  const server = spawn('wp', ['server'])

  server.stdout.on('data', serverLog('info'))
  server.stderr.on('data', serverLog('error'))

  server.on('close', (code) => {
    console.log('Server closed with status', code)
  })

  return server
}

const paths = {
  img: 'images/**',
  css: 'scss/*.scss',
  js: 'js/*.js',
}

const readdirOpt = { withFileTypes: true }
exports.scandir = (dir, dest) => readdirSync(dir, readdirOpt).reduce((build, sub) => {
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
