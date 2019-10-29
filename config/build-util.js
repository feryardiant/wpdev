const { existsSync, readdirSync, readFileSync } = require('fs')
const { promisify } = require('util')
const execFile = promisify(require('child_process').execFile)
const path = require('path')
const yargs = exports.args = require('yargs')

const { task, parallel, series, watch } = require('gulp')

const pkgJson = require('./../package.json')

const bumpFile = (filePath, cb) => {
  const fs = require('fs')

  return new Promise((resolve, reject) => {
    fs.readFile(filePath, 'utf8', (err, data) => {
      if (err) {
        if (err.code === 'ENOENT') return resolve()
        return reject(err)
      }

      fs.writeFile(filePath, cb(data), 'utf8', (err) => {
        if (err) return reject(err)
        return resolve()
      })
    })
  })
}

if (require.main === module) {
  yargs
    .usage('Usage $0 [command]')
    .version(pkgJson.version)
    .demandCommand(1)
    .command({
      command: 'bump <path>',
      desc: 'Bump version in readme.txt & style.css file',
      handler (argv) {
        const files = [
          'style.css',
          'readme.txt'
        ]

        return Promise.all(files.map(file => {
          return bumpFile(`${argv.path}/${file}`, (data) => {
            // Skip bump if contain '-'
            // eg: 0.0.0-pre, 0.0.0-alpha, etc
            if (pkgJson.version.includes('-')) return data
            return data.replace(/(:\s+)(\d.\d.\d)$/gm, `$1${pkgJson.version}`)
          })
        }))
      }
    }).argv
}

const { argv } = yargs.options({
  bump: {
    describe: 'Bump version before zipping',
    default: true,
    type: 'boolean'
  },
  mode: {
    describe: 'Override default `WP_ENV` in .env field',
    default: process.env.WP_ENV,
    type: 'string'
  }
})

process.env.NODE_ENV = argv.mode || 'production'
const isProduction = exports.isProduction = process.env.NODE_ENV === 'production'
const globalConfig = {
  version: pkgJson.version,
  author: pkgJson.author,
  watch: false,

  paths: {
    img: 'img/**',
    css: 'scss/**/*.scss',
    js: 'js/**/*.js',
  },

  gulp: {},

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
    zip: {},
    release: {
      sign: false,
      skip: {
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
  const ignoreFiles = readFileSync(path.join(dir, '.distignore'), 'utf-8').split(/\r?\n/)

  return readdirSync(dir, readdirOpt).reduce((types, type) => {
    if (!['plugins', 'themes'].includes(type.name)) return types

    const packages = readdirSync(path.join(dir, type.name), readdirOpt).reduce((pkgs, src) => {
      if (!src.isDirectory()) return pkgs

      let target = `${type.name}/${src.name}`
      const assetPath = `${target}/assets`
      const pkg = {
        type: type.name,
        path: `${dir}/${target}`,
        php: {
          src: [
            `${dir}/${target}/**/*.php`,
            `!${dir}/${target}/vendor`
          ],
          dest: `${tmpDir}/${target}/languages/${src.name}.pot`,
        }
      }

      if (existsSync(path.join(dir, assetPath))) {
        Object.keys(paths).forEach(asset => {
          if (!existsSync(path.join(dir, assetPath, paths[asset].split('/')[0]))) {
            return
          }

          const srcPath = [
            `${dir}/${assetPath}/${paths[asset]}`
          ]

          if (['js', 'css'].includes(asset)) {
            const excludes = path.join(dir, assetPath, paths[asset].replace(/\./, '.min.'))
            srcPath.push(`!${excludes}`)
          }

          pkg[asset] = {
            src: srcPath,
            dest: `${dir}/${assetPath}/${asset}`
          }
        })
      }

      const zipSrc = [
        `${dir}/${target}/**`
      ]

      ignoreFiles.forEach((line) => {
        if (line && /^#/.test(line) === false) {
          const ignore = path.join(dir, target, line)
          zipSrc.push(`!${ignore}`)
        }
      })

      pkg.zip = {
        src: zipSrc,
        dest: dest
      }

      pkgs[src.name] = pkg
      return pkgs
    }, {})

    for (const [name, pkg] of Object.entries(packages)) {
      types.push([name, pkg])
    }

    return types
  }, [])
}

const configure = exports.configure = (src, dest, tasks) => {
  const buildTasks = []
  const zipTasks = []
  const toWatch = {}
  const { argv } = yargs.option('skip', {
    describe: 'Skip certain task',
    choices: ['css', 'img', 'js', 'php']
  })

  for (const [name, asset] of scandir(src, dest)) {
    const assetTasks = []

    for (const key of Object.keys(asset)) {
      if (['type', 'path'].includes(key)) {
        continue
      }

      const config = {
        name: name,
        type: asset.type,
        path: asset.path,
        version: globalConfig.version,
        author: globalConfig.author
      }

      if (['js', 'css'].includes(key)) {
        config.rename = { suffix: '.min' }
        config.browserslist = pkgJson.browserslist
        config.sourcemaps = {}
        config.gulp = {
          sourcemaps: !isProduction
        }
      }

      const taskName = `${name}:${key}`

      if (globalConfig.hasOwnProperty(key)) {
        Object.assign(config, globalConfig[key])
      }

      if ('zip' !== key) {
        toWatch[taskName] = asset[key].src
        assetTasks.push(taskName)
      } else {
        config.gulp = {
          base: path.join(process.cwd(), config.path, '..')
        }

        config.release.releaseAs = config.version

        // Don't generate changelog if no version bump
        if (config.release.skip.bump) {
          config.release.skip.changelog = true
        }

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

    if (assetTasks.length > 0) {
      task(`${name}:build`, parallel(...assetTasks.sort()))
      buildTasks.push(...assetTasks)
    }
  }

  const skip = argv.skip && ['css', 'img', 'js', 'php'].includes(argv.skip) ? argv.skip : false
  const _tasks = !skip ? buildTasks : buildTasks.filter(t => !t.endsWith(skip))

  task('zip', series(...zipTasks))
  task('build', parallel(..._tasks.sort()))

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
