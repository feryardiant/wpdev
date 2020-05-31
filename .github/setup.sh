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

_readable() {
    sed -e 's~<br \/>~\n~gi' -e 's~<\/p><p>~\n~gi' -e 's~<[^>]*>~~gim' -e 's~&#822[0|1];~"~gi'
}

wp() {
    vendor/bin/wp "$@"
}

if [ -z $WP_HOME ]; then
    _err '`WP_HOME` is not defined'
    exit 1
fi

if [[ ! -f wp-cli.local.yml ]]; then
    cp wp-cli.yml wp-cli.local.yml
fi

sed -i -E "s~url: .*~url: ${WP_HOME}~" wp-cli.local.yml
_suc 'File: `wp-cli.local.yml` created successfully'

if [ -f .env ]; then
    wp dotenv salts generate --color
    wp dotenv set WP_ENV $WP_ENV --color
    wp dotenv set WP_HOME $WP_HOME --color
    wp dotenv set DB_HOST $DB_HOST --color
fi

_inf 'Installling WordPress...'
wp core install --skip-email --title="WordPress Dev" \
    --admin_user="admin" --admin_password="secret" --admin_email="demo@example.com"

# Feel free to setup your own wp_options
wp option update permalink_structure '/%postname%/'
wp option update link_manager_enabled '1'

_inf 'Import dummy content'
wp import resources/dummy-content.xml --authors=create

