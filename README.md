# My Personal WordPress project boilerplate

[Bedrock](https://roots.io/bedrock/) is a modern WordPress stack that helps you get started with the best development tools and project structure.

## TODO

* [x] Configure demo on Heroku
* [x] Configure Development server
* [ ] Configure Testing
* [ ] Create WP-CLI command to extend its [`scaffolder`](https://developer.wordpress.org/cli/commands/scaffold/) to suite the current directory structure
* [ ] Generate zip files that ready to be published on WordPress plugins or theme directory

## Requirements

* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* Gulp - [Install](https://gulpjs.com/docs/en/getting-started/quick-start#install-the-gulp-command-line-utility)

Its always good to have WP-CLI [installed globally](https://wp-cli.org/#installing) on your system. For Database and HTTP Server requirements please refer to [this documentation](https://wordpress.org/about/requirements).

## Installation

This project is available to install with the following command

```bash
$ composer create-project feryardiant/wpdev [project-folder]
```

Create new database through PHPMyAdmin or from CLI

```bash
$ mysql -u[username] -p -e 'create database <database-name>'
```

Configure your `.env` file as you will, described [here](https://roots.io/bedrock/docs/installing-bedrock), then install WordPress core with the following command:

```bash
# https://developer.wordpress.org/cli/commands/core/install/
$ vendor/bin/wp core install
```

Don't forget to configure your HTTP server' `document root` to `public` directory.

### Development

I utilize `gulp` for all development workflow in this project, not only for compiling `scss` files, minify images and compressing `js`, but also to run development server. Once you're done with installation process above, please install the development dependencies through `npm`

```bash
$ npm -g install glup-cli # in case you didn't have gulp instaled globally on your system
$ npm instal
```

To start the development server you can simply run command below:

```bash
$ gulp
```

The gulp default task will fire up [PHP Developent Server](https://www.php.net/manual/en/features.commandline.webserver.php), start BrowserSync on port `3000` and watch the changes you made. That way, all you need to do is open your web browser and type [`http://localhost:3000`](http://locahost:3000) on the address bar, open the project on your favorite code editor or IDE and start working.

All the changes you've made will be automatically linted and compiled, once it done BrowserSync will reload the browser for you.

## Workflow

Since this project is based on [Bedrock](https://roots.io/bedrock/docs/folder-structure/), the project directory structure is pretty much the same, but with some tweak.

### Directory Structure and Gulp tasks

* **config** directory : Consists of all build utilities, configuration files, including all the scripts you'd be using on all environment,
* **public** directory : The server document root directory,
* **source** directory : Them main source directory of your project, it consists of two primary sub-directory, which is `plugins` and `themes`,
* **tests** directory : Testing directory, obviously.

```
├── config
│   ├── environments
│   └── heroku
├── public
│   └── app
├── source
│   ├── assets
│   ├── plugins
│   └── themes
└── tests
    ├── plugins
    └── themes
```

The `gulp` scripts will autoatically scan any sub-directories under `plugins` and `themes` and generate all the required `gulp` tasks if the sub-directory meets the following structures:

```
<dirname>
├── assets
│   ├── img
│   ├── js
│   └── scss
└── languages
```

Once you've create `<dirname>` above inside `plugins` or `themes` directory, you'll see the following gulp tasks:

```
$ gulp -T
├── <dirname>:php
├── <dirname>:img
├── <dirname>:css
├── <dirname>:js
├── <dirname>:zip
├─┬ <dirname>:build
│ └─┬ <parallel>
│   ├── <dirname>:php
│   ├── <dirname>:img
│   ├── <dirname>:css
│   └── <dirname>:js
├─┬ build
│ └─┬ <parallel>
│   ├── <dirname>:php
│   ├── <dirname>:img
│   ├── <dirname>:css
│   └── <dirname>:js
├─┬ zip
│ └─┬ <parallel>
│   └── wpbp:zip
├── release
└── default
```

### Notes

* `<dirname>:build` task will execute all task under `<dirname>` namespace, except for `<dirname>:zip` task.
* `build` task will combine all `*:build` tasks on each namespace.
* `zip` task will combine all `*:zip` tasks on each namespace.
* `release` task will run `build` task, generate `CHANGELOG.md`, run `zip` task and bump version number of this project.

## Sponsors

* [BrowserStack](https://browserstack.com)

## Credits

* [WordPress](https://wordpress.org/) - [GPLv2 License](https://wordpress.org/about/license/)
* [Bedrock](https://roots.io/bedrock/) - [MIT License](https://github.com/roots/bedrock/blob/master/LICENSE.md)
* [Underscores](https://underscores.me) - [GPLv2 License](https://github.com/Automattic/_s/blob/master/LICENSE)
* [Bulma](https://bulma.io) - [MIT License](https://github.com/jgthms/bulma/blob/master/LICENSE)
* [Gulp](https://gulpjs.com) and its plugins
* [BrowserSync](https://browsersync.io)

## License

(c) 2019 Fery Wardiyanto - [MIT license](LICENSE).
