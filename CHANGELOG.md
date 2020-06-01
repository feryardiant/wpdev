# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

### [0.2.6](https://github.com/feryardiant/wpdev/compare/v0.2.5...v0.2.6) (2020-06-01)


### Features

* **theme:** add ability to handle style attributes & add more kses ([ce234cf](https://github.com/feryardiant/wpdev/commit/ce234cfcfb2e78333805f44365a6fefd81e75ef8))
* **theme:** add new option for site layout ([a8685a9](https://github.com/feryardiant/wpdev/commit/a8685a9a90afcd31031311d834400bb1822d6737))
* **theme:** add site-wide wrapper and move `skip_link()` under body ([a79d423](https://github.com/feryardiant/wpdev/commit/a79d423638c842a781f885b651199bf64613432e))
* **theme:** cleanup menu output ([534fc20](https://github.com/feryardiant/wpdev/commit/534fc2017519f4bcb2d30850e641e2be5bd027e5))
* **theme:** clearfix each content segments ([84d29d7](https://github.com/feryardiant/wpdev/commit/84d29d766f33d2cc3c4ad5f588de4bbc3c9cad77))


### Bug Fixes

* **build:** things missing sometimes ([a204869](https://github.com/feryardiant/wpdev/commit/a204869d1a8556b52e4bc4aea1e928be5efb8783))
* **build:** use option instead of passing envvar ([90c42ee](https://github.com/feryardiant/wpdev/commit/90c42ee2e5e9b3eaef5364ba6d4821988b00f39e))
* **buildpack:** sed wp-cli.yml if possible ([38c1aa0](https://github.com/feryardiant/wpdev/commit/38c1aa056784d0d9841e7d96bd37e584ac4c88a7))
* **plugin:** fix undefined constant ([2caa814](https://github.com/feryardiant/wpdev/commit/2caa8148384627530db7100a62d2e123441dd451))
* **plugin:** fix undefined constant on testing ([87e13ff](https://github.com/feryardiant/wpdev/commit/87e13ff69f5a29577da9ca2620746bbf021a1423))
* **theme:** allow `name` attribute on input elements ([2254593](https://github.com/feryardiant/wpdev/commit/22545933e0a0c3f2fcf619c995fc360f101531c7))
* **theme:** fix form kses ([c743cf8](https://github.com/feryardiant/wpdev/commit/c743cf8fc2b2a92660ce91d4acc7e93f39fbe463))
* **theme:** fix menu, header and footer arrangement ([8b4f02c](https://github.com/feryardiant/wpdev/commit/8b4f02cf609f8f3eac3155c540c136762ccbe486))
* **theme:** fix missing hooks on 404 template ([b7ed9b1](https://github.com/feryardiant/wpdev/commit/b7ed9b16ee6392b354d538a9212309fb3f69988d))
* **theme:** fix undefined properties ([2cce02b](https://github.com/feryardiant/wpdev/commit/2cce02b8d49f2cd59a075886c133df2c9a54216c))
* **theme:** invoke `the_posts_navigation()` ([3d15818](https://github.com/feryardiant/wpdev/commit/3d1581828600d6cb1a7dfee32521bc9f3713ec6b))
* **theme:** patch 404 page ([1297cbd](https://github.com/feryardiant/wpdev/commit/1297cbd84c67c6fe7259fdc1e6110bdf0f685154))
* **theme:** patch clearfix ([9e0929e](https://github.com/feryardiant/wpdev/commit/9e0929eb9d293c4bb85d8a6a4118be8efb6c768e))
* **theme:** workaround with menu and some layouts ([d3dc77b](https://github.com/feryardiant/wpdev/commit/d3dc77bb3c43f7c120ec76df23912d7f9972ccc0))

### [0.2.5](https://github.com/feryardiant/wpdev/compare/v0.2.4...v0.2.5) (2019-10-31)


### Features

* **theme:** enhance flexibilities to customize theme layout ([1d280cc](https://github.com/feryardiant/wpdev/commit/1d280cc3ea5bd0f9f57feed0334173126115edbb))
* **theme:** implement WordPress built-in menu properties ([431fbce](https://github.com/feryardiant/wpdev/commit/431fbce21f76628b4916ea2399b096dc0810db64))


### Bug Fixes

* **theme:** wrong menu item arrangement ([0ecf936](https://github.com/feryardiant/wpdev/commit/0ecf936206c9fb7877aa936cd4b23a0941a64957))

### [0.2.4](https://github.com/feryardiant/wpdev/compare/v0.2.4-patch.1...v0.2.4) (2019-10-29)


### Features

* **buildpack:** if wp-cli.yml exists, rename to wp-cli.local.yml ([ae0f913](https://github.com/feryardiant/wpdev/commit/ae0f9138c771939a3e1e682d7c8cdde230cdeab1))
* **theme:** add ability to create nested html, ([6436b9f](https://github.com/feryardiant/wpdev/commit/6436b9f3a2be954c2e4931aecbb0c5cbca06a107))
* **theme:** add custom customizer control ([6ef3af7](https://github.com/feryardiant/wpdev/commit/6ef3af77c7463839e0e525160f60ffad3e2b59de))
* **theme:** add custom-logo hook & make use of make_html_tag helper ([ee2e41f](https://github.com/feryardiant/wpdev/commit/ee2e41ff79bcdce5723834c8d0db02a4e663f93a))
* **theme:** add default site logo filter ([86fd037](https://github.com/feryardiant/wpdev/commit/86fd037e9716f96369d7e6a4959a5aa66327c767))
* **theme:** add default theme option filter & change default logo size ([1e3fe30](https://github.com/feryardiant/wpdev/commit/1e3fe30aa773d50dc7bb1d7c0cd1456af9fada09))
* **theme:** init typography feature ([913af3d](https://github.com/feryardiant/wpdev/commit/913af3d9288dd86a4bd666eed1ef223cd8b0db04))
* **theme:** remove 'wp-block-styles' supports and use custom styling ([958d14b](https://github.com/feryardiant/wpdev/commit/958d14ba68a328b31fe6f927b169df9bb9e26e79))


### Bug Fixes

* **archive:** add commitAll flag ([d784a93](https://github.com/feryardiant/wpdev/commit/d784a935cc2136fbb23e758a9bffcf4d99d58bdd))
* **build:** remove skip bump flag on sub-package ([fe8f861](https://github.com/feryardiant/wpdev/commit/fe8f8617944177291a0e070fdf2ebc4e5b6eb3dc))
* **build:** try to use postbump hook ([49db7a9](https://github.com/feryardiant/wpdev/commit/49db7a900822835dad1f8ea3c8a10dd35ab61a15))
* **build:** use git add on precommit hook ([012bf5a](https://github.com/feryardiant/wpdev/commit/012bf5a827ee97a6121221a6a372577be736cf86))
* **buildpack:** bring back some ignored files :sweat_smile: ([f09d36a](https://github.com/feryardiant/wpdev/commit/f09d36a712f4557bdea6f2a885e32ddde23d4d33))
* **ci:** fix ci setup ([0be014e](https://github.com/feryardiant/wpdev/commit/0be014eb77819f8c5e08ca55bfa093d9eef53759))
* **ci:** fix ci setup (again and again) ([4ced1f1](https://github.com/feryardiant/wpdev/commit/4ced1f16c098866e8ded4e355042782a36edcc09))
* **ci:** fix ci setup (again) ([0d43a57](https://github.com/feryardiant/wpdev/commit/0d43a575ce97cff34ac3744bb5bb0cf0cf6b74a4))
* **ci:** fix ci setup (try again) :sweat_smile: ([ead42e0](https://github.com/feryardiant/wpdev/commit/ead42e04131159408f128be50ca6abc1d6346d2f))
* **ci:** make use of wp-cli.yml ([4383cb0](https://github.com/feryardiant/wpdev/commit/4383cb05bd0669bffbb80a0f5ca621694e763530))
* **deploy:** fix post-deploy script ([d7109d0](https://github.com/feryardiant/wpdev/commit/d7109d09a6cc4d281d0b361320d5d19cf6bc7ea1))
* **gulp:** fix missing php watcher ([14644d7](https://github.com/feryardiant/wpdev/commit/14644d79c06b38c15b6889b614c1bb6693d6eafb))
* **gulp:** fix reloading ([d307ce3](https://github.com/feryardiant/wpdev/commit/d307ce35c06dfda0dfadbe9945229d058f3490ec))
* **gulp:** only watch js and css tasks ([0d2969d](https://github.com/feryardiant/wpdev/commit/0d2969d02d46ffa567e387f7fe12cefae6b6a1b6))
* **workflow:** fix versioning ([ce8265d](https://github.com/feryardiant/wpdev/commit/ce8265de30f2bb777220c734708834a37062b6ce))

### [0.2.4-patch.1](https://github.com/feryardiant/wpdev/compare/v0.2.4-patch.0...v0.2.4-patch.1) (2019-10-29)

### [0.2.4-patch.0](https://github.com/feryardiant/wpdev/compare/v0.2.3...v0.2.4-patch.0) (2019-10-29)

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
