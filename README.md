# My Personal WordPress project boilerplate

[Bedrock](https://roots.io/bedrock/) is a modern WordPress stack that helps you get started with the best development tools and project structure.

## Requirements

* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* WP-CLI - [Install](https://wp-cli.org/#installing)

For Database and HTTP Server required by WordPress please refer to [this documentation](https://wordpress.org/about/requirements).

## Installation

This project is available to install with the following command

```
composer create-project feryardiant/wordpress-boilerplate [project-folder]
```

Configure your `.env` file as described [here](https://roots.io/bedrock/docs/installing-bedrock).

Install WordPress core with the following command:

```
// https://developer.wordpress.org/cli/commands/core/install/
vendor/bin/wp core install
```

Configure your HTTP server' `document root` to `public` directory or you can simply run:

```
// https://developer.wordpress.org/cli/commands/server/
vendor/bin/wp server
```

to start `wp-cli` development server.

## Credits

* [WordPress](https://wordpress.org/) - [GPLv2 License](https://wordpress.org/about/license/)
* [Bedrock](https://roots.io/bedrock/) - [MIT License](https://github.com/roots/bedrock/blob/master/LICENSE.md)
* [Underscores](https://underscores.me) - [GPLv2 License](https://github.com/Automattic/_s/blob/master/LICENSE)
* [Bulma](https://bulma.io) - [MIT License](https://github.com/jgthms/bulma/blob/master/LICENSE)

