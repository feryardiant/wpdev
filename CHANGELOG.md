# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

### [0.2.1](https://github.com/feryardiant/wpdev/compare/v0.2.0...v0.2.1) (2019-10-17)


### ⚠ BREAKING CHANGES

* **test:** wdio.config.js file moved to tests directory, because why not? 😆

Signed-off-by: Fery Wardiyanto <ferywardiyanto@gmail.com>

### Bug Fixes

* **build:** fixes [#18](https://github.com/feryardiant/wpdev/issues/18) ([4209078](https://github.com/feryardiant/wpdev/commit/4209078))
* **theme:** fix admin_enqueue scripts ([de08be4](https://github.com/feryardiant/wpdev/commit/de08be4))
* **theme:** fix script_enqueue on customizer screen ([e198d89](https://github.com/feryardiant/wpdev/commit/e198d89))


### Features

* **test:** ability to start php server before e2e test ([e4e9d60](https://github.com/feryardiant/wpdev/commit/e4e9d60))
* **tests:** init unit testing ([ea25bda](https://github.com/feryardiant/wpdev/commit/ea25bda)), closes [#19](https://github.com/feryardiant/wpdev/issues/19)

## [0.2.0](https://github.com/feryardiant/wpdev/compare/v0.1.3...v0.2.0) (2019-10-04)


### Bug Fixes

* **workflow:** missing WP_HOME env on gulp build ([82153dd](https://github.com/feryardiant/wpdev/commit/82153dd))

### [0.1.3](https://github.com/feryardiant/wpdev/compare/v0.1.2...v0.1.3) (2019-10-02)


### Bug Fixes

* **workflow:** remove previous post-install script & update composer ([d406da8](https://github.com/feryardiant/wpdev/commit/d406da8))

### [0.1.2](https://github.com/feryardiant/wpdev/compare/v0.1.1...v0.1.2) (2019-10-02)


### Bug Fixes

* **workflow:** move salts generator to post-install script ([6580a41](https://github.com/feryardiant/wpdev/commit/6580a41))

### [0.1.1](https://github.com/feryardiant/wpdev/compare/v0.1.1-alpha.2...v0.1.1) (2019-10-02)


### Bug Fixes

* **deploy:** remove certain envvar on multisite setup ([d240917](https://github.com/feryardiant/wpdev/commit/d240917))
* **theme:** fixes [#14](https://github.com/feryardiant/wpdev/issues/14) ([ed7c446](https://github.com/feryardiant/wpdev/commit/ed7c446))
* **workflow:** infinite loop on gulp watch ([1535ff8](https://github.com/feryardiant/wpdev/commit/1535ff8)), closes [#12](https://github.com/feryardiant/wpdev/issues/12)


### Features

* **theme:** make sure all html output escaped ([330b6be](https://github.com/feryardiant/wpdev/commit/330b6be))
* **theme:** rename some template hooks according to ed7c446 ([574ba9b](https://github.com/feryardiant/wpdev/commit/574ba9b))

### [0.1.1-alpha.2](https://github.com/feryardiant/wpdev/compare/v0.1.1-alpha.1...v0.1.1-alpha.2) (2019-09-29)


### Bug Fixes

* **workflow:** execute git add after gulp build ([24c9666](https://github.com/feryardiant/wpdev/commit/24c9666))

### [0.1.1-alpha.1](https://github.com/feryardiant/wpdev/compare/v0.1.0...v0.1.1-alpha.1) (2019-09-29)


### Bug Fixes

* linting issues ([3712916](https://github.com/feryardiant/wpdev/commit/3712916))


### Features

* **workflow:** add lint-staged & commitlint ([6537a9a](https://github.com/feryardiant/wpdev/commit/6537a9a))
* **workflow:** generate zip file and ignore files on .distignore ([c3b9879](https://github.com/feryardiant/wpdev/commit/c3b9879))
* **workflow:** improve archive generator workflows ([6b5afca](https://github.com/feryardiant/wpdev/commit/6b5afca))
* **workflow:** make use of standard-version ([bc003a6](https://github.com/feryardiant/wpdev/commit/bc003a6))
