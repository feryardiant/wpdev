const { readdirSync, readFileSync } = require('fs')
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
  },

  zip: {
    release: {
      sign: false,
      skip: {
        bump: true,
        commit: true,
        tag: true
      }
    }
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
        path: `${dir}/${target}`,
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

      const zipSrc = [
        `${dir}/${target}/**`
      ]

      readFileSync(path.join(dir, '.distignore'), 'utf-8').split(/\r?\n/).forEach((line) => {
        if (line && /^#/.test(line) === false) {
          const ignore = path.join(dir, target, line)
          zipSrc.push(`!${ignore}`)
        }
      })

      build[source.name].zip = {
        src: zipSrc,
        dest: dest
      }

      return build
    }, {})
  }, {})

  return Object.entries(sourceDir)
}

const configure = exports.configure = (src, dest, tasks) => {
  const buildTasks = []
  const zipTasks = []
  const toWatch = {}

  for (const [name, asset] of scandir(src, dest)) {
    const assetTasks = []
    const config = {
      name: name,
      type: asset.type,
      path: asset.path,
      version: globalConfig.version,
      author: globalConfig.author
    }

    for (const key of Object.keys(asset)) {
      if (['type', 'path'].includes(key)) {
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

      if ('zip' !== key) {
        assetTasks.push(taskName)
      } else {
        zipTasks.push(taskName)
      }

      task(taskName, (done) => {
        return tasks[key]({
          src: asset[key].src,
          dest: asset[key].dest,
          config: config
        }, done)
      })
    }

    task(`${name}:build`, parallel(...assetTasks))
    buildTasks.push(...assetTasks)
  }

  task('build', parallel(...buildTasks))
  task('zip', parallel(...zipTasks))
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
