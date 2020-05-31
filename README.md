# My Personal WordPress Project Boilerplate [![Actions Status](https://github.com/feryardiant/wpdev/workflows/Tests%20and%20Build/badge.svg)](https://github.com/feryardiant/wpdev/actions)

This project aims to provide complete solution for WordPress development and deployment to heroku, even though it isn't completed just yet. 😬

Inspired by [wordpress-heroku](https://github.com/PhilippHeuer/wordpress-heroku) by Phillip Heuer and based on the exact same boilerplate of [Bedrock](https://roots.io/bedrock/), this project will give you better experiences while developing your very own WordPress themes and plugins that easily deployed to heroku.

## Features

* [x] Integrated Unit and end-to-end testing
* [x] Custom [heroku buildpack](https://devcenter.heroku.com/articles/buildpack-api)
* [x] Automated changelog generator
* [x] Better assets compilation
* [x] Easily create archive that ready to distribute to WordPress themes or plugins directory
* [ ] Thoughts? [let me know](https://github.com/feryardiant/wpdev/issues/new) 😁

## Requirements

* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* Gulp - [Install](https://gulpjs.com/docs/en/getting-started/quick-start#install-the-gulp-command-line-utility)

Its always good to have WP-CLI [installed globally](https://wp-cli.org/#installing) on your system. For Database and HTTP Server requirements please refer to [this documentation](https://wordpress.org/about/requirements).

## Installation

### 1. One-Click-Deployment

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/feryardiant/wpdev/tree/master)

Using this button you can deploy a new instance of WordPress. All required extensions will be deployed automatically. This also works if you fork your own project to work on your site.

### 2. Using `composer create-project` command

```bash
$ composer create-project feryardiant/wpdev [project-folder]
```

Create new database through PHPMyAdmin or from CLI

```bash
$ mysql -u[username] -p -e 'create database <database-name>'
```

Configure your `.env` file to suit your local setup, described [here](https://roots.io/bedrock/docs/installing-bedrock), also don't forget to set the `url` key on `wp-cli.local.yml` file as well. Once you're done, let's install WordPress core with the following command:

```bash
# Please consult to the official documentation for additional option you might needed for the setup
# See: https://developer.wordpress.org/cli/commands/core/install/
$ vendor/bin/wp core install

# Or you can simply run the following sortcut
$ composer wp:install
```

Please make sure to configure your HTTP server' `document root` to `public` directory.

### Development

I using `gulp` for almost all development workflow in this project, not only for compiling `scss` files, minify images and compressing `js`, but also to run development server. Once you're done with installation process above, please install the development dependencies through `npm`

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

Since this project is based on [Bedrock](https://roots.io/bedrock/docs/folder-structure/), the project directory structure is pretty much similar, but with some tweaks.

### Directory Structure and Gulp tasks

* **bin** directory : Consists of all [buildpack executables](https://devcenter.heroku.com/articles/buildpack-api#buildpack-api) only, feel free to delete this directory once you've cloned this project or install it on your local machine,
* **config** directory : Consists of all build utilities and configuration files, including all the scripts you'd be using on all environment,
* **public** directory : The server document root directory,
* **packages** directory : The Themes and Plugins development directory of your project,
* **tests** directory : Testing directory, obviously.

```
├── bin
├── config
│   ├── environments
│   └── heroku
├── public
│   ├── app
│   └── wp
├── packages
├── resources
└── tests
    ├── e2e
    ├── stubs
    └── unit
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

Once you've create `<dirname>` above inside `plugins` or `themes` directory, you'll see similar gulp tasks as following:

```
$ gulp -T
├── theme-plugin:php
├── theme-plugin:zip
├─┬ theme-plugin:build
│ └─┬ <parallel>
│   └── theme-plugin:php
├── theme:php
├── theme:img
├── theme:css
├── theme:js
├── theme:zip
├─┬ theme:build
│ └─┬ <parallel>
│   ├── theme:css
│   ├── theme:img
│   ├── theme:js
│   └── theme:php
├── theme-child:php
├── theme-child:zip
├─┬ theme-child:build
│ └─┬ <parallel>
│   └── theme-child:php
├─┬ zip
│ └─┬ <series>
│   ├── theme-plugin:zip
│   ├── theme:zip
│   └── theme-child:zip
├─┬ build
│ └─┬ <parallel>
│   ├── theme-child:php
│   ├── theme-plugin:php
│   ├── theme:css
│   ├── theme:img
│   ├── theme:js
│   └── theme:php
├── e2e
├── release
└── default
```

### Notes

* `<dirname>:build` task will execute all task under `<dirname>` namespace, except for `<dirname>:zip` task.
* Each `*:css`, `*:img` and `*:js` will only available if you have `img`, `js` and `scss` directories under `assets` directory.
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
