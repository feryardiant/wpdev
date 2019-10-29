# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

### [0.2.3](https://github.com/feryardiant/wpdev/compare/v0.2.2...v0.2.3) (2019-10-29)

### [0.2.2](https://github.com/feryardiant/wpdev/compare/v0.2.1...v0.2.2) (2019-10-29)


### Features

* **build:** ability to skip task during build ([8a3ef91](https://github.com/feryardiant/wpdev/commit/8a3ef91b985685194515eb68dece1f5e38f85e1f))
* **build:** add ability to bump readme.txt & style.css version ([ecb146f](https://github.com/feryardiant/wpdev/commit/ecb146f62d1b20726cf99ef8b127ee00a713fab5)), closes [#23](https://github.com/feryardiant/wpdev/issues/23)
* **build:** add postbump hook to bump style.css & readme.txt ([3b22bc2](https://github.com/feryardiant/wpdev/commit/3b22bc22634f721aeac7ad723abd751e7137dec3))
* **deploy:** update postinstall script, WIP ([bdb8acf](https://github.com/feryardiant/wpdev/commit/bdb8acf9c9d917592b8813bb2a11aaedab86f089))
* **gulp:** add option whether to tag the release ([3823e81](https://github.com/feryardiant/wpdev/commit/3823e81c032b611ac74a6424c55b33e45a14077a))
* **gulp:** do not generate changelog if no version bump ([95ce1b3](https://github.com/feryardiant/wpdev/commit/95ce1b3785b801f277576a5e25ba0074eb9ed1f6))
* **gulp:** skip browserSync on testing env ([dc78195](https://github.com/feryardiant/wpdev/commit/dc78195205fbacacbe271dc83a9803ba2c25f1f4))
* **theme:** add ability to load theme option from directory ([ecb5023](https://github.com/feryardiant/wpdev/commit/ecb5023b54f76c976c5ed43f24bc0634a810eeb2))
* **theme:** add dump() function only for dev ([185e82f](https://github.com/feryardiant/wpdev/commit/185e82fcc7e3407742b92baa2cba237f884c1b0c))
* **theme:** add html helper to generate & normalize elements ([dfaac97](https://github.com/feryardiant/wpdev/commit/dfaac97d4d061eabf4cff59c522c1f0430521891))
* **theme:** add Schema.org helper function ([342d1d8](https://github.com/feryardiant/wpdev/commit/342d1d81a0756d5ae8620c897b3070d0c0da0965))
* **theme:** make sure not throwing errors when object non-exists ([f9c02f0](https://github.com/feryardiant/wpdev/commit/f9c02f02f1d79aa85046ac8e41502b0f0148b70f))
* **theme:** make theme option accessible thru array ([3da344d](https://github.com/feryardiant/wpdev/commit/3da344d58d9109f63907576bd8ca0540b92a715f))
* **theme:** make use of Template class to render header ([83cb7f2](https://github.com/feryardiant/wpdev/commit/83cb7f274f12e9bbcfc9421bec5fe2ecdad48e82))
* **theme:** method Theme::get_option() will throw error if  undefined ([27f755a](https://github.com/feryardiant/wpdev/commit/27f755a1df53741db2f948f89f00d39774680aa0))
* **theme:** patch microdata ([ffbfd83](https://github.com/feryardiant/wpdev/commit/ffbfd837e194cd991dc94012e55f6140fe68405d))
* **workflow:** apply changes ed1ec77 & db15503 ([e9bba3f](https://github.com/feryardiant/wpdev/commit/e9bba3f06f8db5e3b1fd80e2a86fe5bcbe863fdf))
* **workflow:** bumb each plugins & theme version ([ed1ec77](https://github.com/feryardiant/wpdev/commit/ed1ec7768333f66cbb507c4aba5e0114281c17ef))


### Bug Fixes

* **build:** remove unintended task ([05172ee](https://github.com/feryardiant/wpdev/commit/05172eefb47b382a00da71cd48567d276c17a28d))
* **buildpack:** final patch for the buildpack ([d967daa](https://github.com/feryardiant/wpdev/commit/d967daa2d388a0eeae9dc4e1621be740de7ea2bd))
* **buildpack:** fix compile phase ([455dea3](https://github.com/feryardiant/wpdev/commit/455dea37eb07e5ac6dbff13aedcfba14aabbae22))
* **buildpack:** keep wp-cli.yml as is ([9aa216c](https://github.com/feryardiant/wpdev/commit/9aa216cfd60a2ac00441cdc2ee66f8d410078179))
* **buildpack:** update build_dir ([d3b7175](https://github.com/feryardiant/wpdev/commit/d3b71756e1bcd02f5efaf98da30e0a5fecda55a9))
* **deploy:** fix wp-cli installer ([9c5fa19](https://github.com/feryardiant/wpdev/commit/9c5fa19bcc48543f761724fec1e2bfe954a910e8))
* **deploy:** move redis-cache to plugins instead of mu-plugins ([4e87f66](https://github.com/feryardiant/wpdev/commit/4e87f66990bec1930c8933254f117114b87ec3ca))
* **gulp:** fix base package folder ([0e05191](https://github.com/feryardiant/wpdev/commit/0e051915ed248259139c384f1ebbde488f69a0c2)), closes [#24](https://github.com/feryardiant/wpdev/issues/24)
* **gulp:** fix incorrect task ([82e7b46](https://github.com/feryardiant/wpdev/commit/82e7b46083fd004df9617767fd6dc691c9c81b62))
* **heroku:** fix nginx config ([2612063](https://github.com/feryardiant/wpdev/commit/2612063287ae7da76aefbf48cbf715ad53deedc6))
* **heroku:** remove release command ([503369a](https://github.com/feryardiant/wpdev/commit/503369a841ba2958bf02429f7b04aee549444d26))
* **heroku:** require WP_DEFAULT_THEME & move to free dyno :sweat_smile: ([17b460c](https://github.com/feryardiant/wpdev/commit/17b460c858baaf699f9efcbbde21212e2f0872ef))
* **release:** fix [#32](https://github.com/feryardiant/wpdev/issues/32) ([7f0c61e](https://github.com/feryardiant/wpdev/commit/7f0c61ef6a299e9f0e0dbaaf07e05bfab20cf0b5))
* **theme:** do not remove empty attributes ([55889de](https://github.com/feryardiant/wpdev/commit/55889de51a40c7340c69bb85fa7bcd49c8f2ef00))
* **theme:** don't render sidebar if no widgets ([cc59299](https://github.com/feryardiant/wpdev/commit/cc59299f945c97d7376cd298065b051dbd9c1a37))
* **theme:** fix 404 page dispay ([4078ce8](https://github.com/feryardiant/wpdev/commit/4078ce83e69ff0c89567742c098259267a29abc0))

### [0.2.1](https://github.com/feryardiant/wpdev/compare/v0.2.0...v0.2.1) (2019-10-17)


### ⚠ BREAKING CHANGES

* **tests:** wdio.config.js file moved to tests directory, because why not? 😆

### Bug Fixes

* **build:** fixes [#18](https://github.com/feryardiant/wpdev/issues/18) ([4209078](https://github.com/feryardiant/wpdev/commit/4209078))
* **theme:** fix admin_enqueue scripts ([de08be4](https://github.com/feryardiant/wpdev/commit/de08be4))
* **theme:** fix script_enqueue on customizer screen ([e198d89](https://github.com/feryardiant/wpdev/commit/e198d89))


### Features

* **tests:** ability to start php server before e2e test ([e4e9d60](https://github.com/feryardiant/wpdev/commit/e4e9d60))
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
