#!/usr/bin/env bash

_inf() {
	echo -e "\e[34;1mInfo:\e[0m" "$@"
}

_suc() {
	echo -e "\e[32;1mSuccess:\e[0m" "$@"
}

_err() {
	echo -e "\e[31;1mError:\e[0m" "$@" | cat - 1>&2
}

if [ -z WP_HOME ] && [ ! -z $HEROKU_APP_NAME ]; then
    cd $HOME
    WP_HOME="https://${HEROKU_APP_NAME}.herokuapp.com"
fi


if ! command -v wp >/dev/null 2>&1 || [ ! -f vendor/bin/wp ]; then
    curl -Lso vendor/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x vendor/bin/wp
    _suc 'WP-CLI instaled successfully'

    wp() {
        vendor/bin/wp "$@"
    }
else
    _inf 'Executable wp-cli already present in '$(which wp)
fi

if [ -z $WP_HOME ]; then
    _err '`WP_HOME` is not defined'
    exit 1
fi

if [[ $WP_ENV != 'testing' && ! -f wp-cli.local.yml ]]; then
    cp wp-cli.yml wp-cli.local.yml
    sed -i -E "s~url: .*~url: ${WP_HOME}~" wp-cli.local.yml
    _suc 'File: `wp-cli.local.yml` created successfully'
fi

if [ -f .env ]; then
    wp dotenv salts generate --color
    wp dotenv set WP_ENV $WP_ENV --color
    wp dotenv set WP_HOME $WP_HOME --color
    wp dotenv set DB_HOST $DB_HOST --color
fi

_inf 'Installling WordPress...'
wp core install --color --url="$WP_HOME" --skip-email --title="WordPress Site" \
    --admin_user="admin" --admin_password="secret" --admin_email="demo@wp.feryardiant.id"

if [[ $WP_ENV != 'testing' ]]; then
    wp option update permalink_structure '/%postname%/' --color
    wp option update link_manager_enabled '1' --color

    _inf 'Installing required plugins'
    wp plugin install contact-form-7 jetpack --activate --color

    wp transient delete blank_theme_info --color
    wp cache flush --color

    if [[ $WP_ENV = 'production' && ! -f public/app/object-cache.php && -f public/app/mu-plugins/redis-cache/includes/object-cache.php ]]; then
        cp public/app/mu-plugins/redis-cache/includes/object-cache.php public/app/object-cache.php
        _suc 'Drop-in Object-Cache instaled successfully'
    fi
fi

if [ -z $HEROKU_APP_NAME ]; then
    _inf 'Import dummy content'
    wp import source/assets/dummy-content.xml --authors=create --skip=image-resize --quiet | tail -n 1 | sed -e 's~<br \/>~\n~gi' -e 's~<\/p><p>~\n~gi' -e 's~<[^>]*>~~gim' -e 's~&#822[0|1];~"~gi'
fi

