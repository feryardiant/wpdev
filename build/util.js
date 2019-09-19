const { readdirSync } = require('fs')
const { promisify } = require('util')
const execFile = promisify(require('child_process').execFile)
// const { spawn, execFile } = require('child_process')
const path = require('path')

const { task, parallel, series, watch } = require('gulp')

const pkgJson = require('./../package.json')

require('dotenv').config()

const isProduction = exports.isProduction = process.env.NODE_ENV === 'production'

if (process.env.WP_ENV && !process.env.NODE_ENV) {
  process.env.NODE_ENV = process.env.WP_ENV
}

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

const globalConfig = {
  version: pkgJson.version,
  author: pkgJson.author,

  paths: {
    img: 'img/**',
    css: 'scss/**/*.scss',
    js: 'js/**/*.js',
  },

  pot: {
    wpPot: {}
  },

  img: {
    imagemin: {}
  },

  css: {
    sass: {
      includePaths: ['node_modules']
    }
  },

  js: {
    uglify: {},
    babel: {
      presets: [
        [
          "@babel/preset-env",
          {
            modules: false
          }
        ]
      ]
    }
  }
}

const scandir = exports.scandir = (dir, dest) => {
  const readdirOpt = { withFileTypes: true }
  const tmpDir = 'public/app'
  const paths = globalConfig.paths

  const sourceDir = readdirSync(dir, readdirOpt).reduce((build, sub) => {
    if (!sub.isDirectory()) return build

    return readdirSync(path.join(dir, sub.name), readdirOpt).reduce((build, source) => {
      if (!source.isDirectory()) return build

      let target = path.join(sub.name, source.name)
      build[source.name] = {
        pot: {
          src: path.join(dir, target, '**', '*.php'),
          dest: path.join(tmpDir, target, 'languages', `${source.name}.pot`)
        }
      }

      Object.keys(paths).forEach(asset => {
        const assetPath = path.join(target, 'assets')
        const srcPath = [
          path.join(dir, assetPath, paths[asset])
        ]

        if (['js', 'css'].includes(asset)) {
          const excludes = path.join(dir, assetPath, paths[asset].replace(/\./, '.min.'))
          srcPath.push(`!${excludes}`)
        }

        build[source.name][asset] = {
          src: srcPath,
          dest: path.join(tmpDir, assetPath, asset)
        }
      })

      build[source.name].zip = {
        src: path.join(tmpDir, target, '**'),
        dest: path.join(dest)
      }

      return build
    }, {})
  }, {})

  return Object.entries(sourceDir)
}

const configure = exports.configure = (src, dest, tasks) => {
  const buildTasks = []
  const assetTasks = []
  const toWatch = {}

  for (const [name, asset] of scandir(src, dest)) {
    Object.keys(asset).forEach(key => {
      const assetTask = `${name}:${key}`

      const config = {
        name: name,
        type: '',
        version: globalConfig.version,
        author: globalConfig.author
      }

      if (['js', 'css'].includes(key)) {
        config.rename = {
          suffix: '.min'
        }
      }

      if (globalConfig.hasOwnProperty(key)) {
        Object.assign(config, globalConfig[key])
      }

      task(assetTask, (done) => {
        return tasks[key]({
          src: asset[key].src,
          dest: asset[key].dest,
          config: config
        }, done)
      })

      assetTasks.push(assetTask)
      toWatch[assetTask] = asset[key].src
    })

    task(`${name}:build`, series(...assetTasks))
    buildTasks.push(...assetTasks)
  }

  task('build', series(...buildTasks))

  return toWatch
}

exports.watch = (tasks) => {
  for (const [assetTask, src] of Object.entries(tasks)) {
    watch(src, series(assetTask))
  }
}
