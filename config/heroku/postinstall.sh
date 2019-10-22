#!/usr/bin/env bash

# usage color "31;0" "string"
# 0 default, 1 strong, 4 underlined, 5 blink
# fg: 31 red,  32 green, 33 yellow, 34 blue, 35 purple, 36 cyan, 37 white
# bg: 40 black, 41 red, 44 blue, 45 purple
c_err='41'
c_inf='33'
c_suc='32'
c_rst='37'

_inf() {
	echo -e "\e[34;1mInfo:\e[0m" "$@"
}

_suc() {
	echo -e "\e[32;1mSuccess:\e[0m" "$@"
}

_err() {
	echo -e "\e[31;1mError:\e[0m" "$@" | cat - 1>&2
}

if [ ! -z $HEROKU_APP_NAME ]; then
    cd $HOME
    WP_HOME="https://${HEROKU_APP_NAME}.herokuapp.com"
fi

if [ ! -f vendor/bin/wp ]; then
    _inf 'Downloading wp-cli...'
    curl -Lso vendor/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x vendor/bin/wp
else
    _inf 'Executable wp-cli already present in vendor/bin/wp'
fi

if [ -z $WP_HOME ]; then
    _err '`WP_HOME` is not defined'
    exit 1
else
    _inf '`WP_HOME` detected at '$WP_HOME
fi

if [ ! -f wp-cli.local.yml ]; then
    cp wp-cli.yml wp-cli.local.yml
    sed -i -E "s~;url =.*~url = ${WP_HOME}~" wp-cli.local.yml
    _inf 'Creating file: `wp-cli.local.yml` '
else
    _inf 'File `wp-cli.local.yml` already present'
fi

# vendor/bin/wp core install --skip-email --no-color --title="WordPress Site" \
#     --admin_user="admin" --admin_password="secret" --admin_email="admin@example.com"

# vendor/bin/wp option update permalink_structure '/%postname%/'
# # vendor/bin/wp option update link_manager_enabled '1'

vendor/bin/wp cache flush

if [ -f public/app/mu-plugins/redis-cache/includes/object-cache.php ]; then
    cp public/app/mu-plugins/redis-cache/includes/object-cache.php public/app/object-cache.php
    _inf 'Drop-in object-cache.php instaled'
    vendor/bin/wp redis enable
fi
