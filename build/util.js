const { readdirSync } = require('fs')
const { promisify } = require('util')
const execFile = promisify(require('child_process').execFile)
const path = require('path')

const { task, parallel, series, watch } = require('gulp')

const pkgJson = require('./../package.json')

const isProduction = exports.isProduction = process.env.NODE_ENV === 'production'

if (process.env.WP_ENV && !process.env.NODE_ENV) {
  process.env.NODE_ENV = process.env.WP_ENV
}

const globalConfig = {
  version: pkgJson.version,
  author: pkgJson.author,
  watch: false,

  paths: {
    img: 'img/**',
    css: 'scss/**/*.scss',
    js: 'js/**/*.js',
  },

  php: {
    phpcs: {
      bin: 'vendor/bin/phpcs'
    },
    wpPot: {}
  },

  img: {
    imagemin: {}
  },

  css: {
    stylelint: {
      failAfterError: isProduction,
      reporters: [
        {
          formatter: 'string',
          console: true
        }
      ],
      debug: !isProduction
    },
    sass: {
      includePaths: ['node_modules']
    }
  },

  js: {
    uglify: {},
    eslint: {},
    babel: pkgJson.babel
  }
}

const scandir = exports.scandir = (dir, dest) => {
  const readdirOpt = { withFileTypes: true }
  const tmpDir = 'public/app'
  const paths = globalConfig.paths

  const sourceDir = readdirSync(dir, readdirOpt).reduce((build, type) => {
    if (!['plugins', 'themes'].includes(type) && !type.isDirectory()) return build

    return readdirSync(path.join(dir, type.name), readdirOpt).reduce((build, source) => {
      if (!source.isDirectory()) return build

      let target = `${type.name}/${source.name}`
      build[source.name] = {
        type: type.name,
        php: {
          src: `${dir}/${target}/**/*.php`,
          dest: `${tmpDir}/${target}/languages/${source.name}.pot`,
        }
      }

      Object.keys(paths).forEach(asset => {
        const assetPath = `${target}/assets`
        const srcPath = [
          `${dir}/${assetPath}/${paths[asset]}`
        ]

        if (['js', 'css'].includes(asset)) {
          const excludes = path.join(dir, assetPath, paths[asset].replace(/\./, '.min.'))
          srcPath.push(`!${excludes}`)
        }

        build[source.name][asset] = {
          src: srcPath,
          dest: `${dir}/${assetPath}/${asset}`
        }
      })

      build[source.name].zip = {
        src: `${tmpDir}/${target}/**`,
        dest: dest
      }

      return build
    }, {})
  }, {})

  return Object.entries(sourceDir)
}

const configure = exports.configure = (src, dest, tasks) => {
  const buildTasks = []
  const toWatch = {}

  for (const [name, asset] of scandir(src, dest)) {
    const assetTasks = []
    const config = {
      name: name,
      type: asset.type,
      version: globalConfig.version,
      author: globalConfig.author
    }

    for (const key of Object.keys(asset)) {
      if (key === 'type') {
        continue
      }

      if (['js', 'css'].includes(key)) {
        config.rename = {
          suffix: '.min'
        }

        config.browserslist = pkgJson.browserslist
      }

      const taskName = `${name}:${key}`

      if (globalConfig.hasOwnProperty(key)) {
        Object.assign(config, globalConfig[key])
        toWatch[taskName] = asset[key].src
      }

      task(taskName, (done) => {
        return tasks[key]({
          src: asset[key].src,
          dest: asset[key].dest,
          config: config
        }, done)
      })

      assetTasks.push(taskName)
    }

    task(`${name}:build`, series(...assetTasks))
    buildTasks.push(...assetTasks)
  }

  task('build', series(...buildTasks))
  return toWatch
}

exports.watch = (tasks, browserSync) => {
  const reload = (done) => {
    browserSync.reload()
    done()
  }

  for (const [taskName, src] of Object.entries(tasks)) {
    watch(src, series(taskName, reload))
  }
}
