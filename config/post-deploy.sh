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

if [ ! -f vendor/bin/wp ]; then
    curl -Lso vendor/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x vendor/bin/wp
    _suc 'WP-CLI instaled successfully'
else
    _inf 'Executable wp-cli already present in vendor/bin/wp'
fi

if [ -z $WP_HOME ]; then
    _err '`WP_HOME` is not defined'
    exit 1
fi

if [ $WP_ENV != 'testing' ] && [ ! -f wp-cli.local.yml ]; then
    cp wp-cli.yml wp-cli.local.yml
    sed -i -E "s~url: .*~url: ${WP_HOME}~" wp-cli.local.yml
    _suc 'File: `wp-cli.local.yml` created successfully'
fi

if [ -f .env ]; then
    vendor/bin/wp dotenv salts generate --color
    vendor/bin/wp dotenv set WP_ENV $WP_ENV --color
    vendor/bin/wp dotenv set WP_HOME $WP_HOME --color
    vendor/bin/wp dotenv set DB_HOST $DB_HOST --color
fi

_inf 'Installling WordPress...'
vendor/bin/wp core install --color --url="$WP_HOME" --skip-email --title="WordPress Site" \
    --admin_user="admin" --admin_password="secret" --admin_email="demo@wp.feryardiant.id"

if [ ! -z $MULTISITE ] && [ $MULTISITE = 'true' ]; then
    vendor/bin/wp core multisite-convert --color --title="WordPress Network"
fi

if [ $WP_ENV != 'testing' ]; then
    vendor/bin/wp option update permalink_structure '/%postname%/' --color
    vendor/bin/wp option update link_manager_enabled '1' --color

    _inf 'Installing required plugins'
    vendor/bin/wp plugin install contact-form-7 jetpack --activate --color

    wp transient delete blank_theme_info --color
    vendor/bin/wp cache flush --color

    if [ ! -f public/app/object-cache.php ] && [ -f public/app/mu-plugins/redis-cache/includes/object-cache.php ]; then
        cp public/app/mu-plugins/redis-cache/includes/object-cache.php public/app/object-cache.php
        _suc 'Drop-in Object-Cache instaled successfully'
    fi
fi

if [ -z $HEROKU_APP_NAME ]; then
    _inf 'Import dummy content'
    vendor/bin/wp import source/assets/dummy-content.xml --authors=skip --skip=image-resize --quiet
fi

