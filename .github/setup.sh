#!/usr/bin/env bash

source bin/util.sh

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

if [ -z $WP_HOME ]; then
error <<-EOF
    Failed to configure WP-CLI!

    `WP_HOME` is not defined.
EOF
fi

# _inf 'Installing WP-CLI...'

# download_wpcli vendor/bin/wp

# chmod +x vendor/bin/wp

wp() {
    # Here we still using WP-CLI from composer because of some it's commands
    # are not working without installing the core first
    # See: https://github.com/feryardiant/wpdev/runs/727937952?check_suite_focus=true#step:7:11
    vendor/bin/wp "$@"
}

# _suc 'WP-CLI installed on vendor/bin/wp'

cp wp-cli.yml wp-cli.local.yml

sed -i -E "s~url: .*~url: ${WP_HOME}~" wp-cli.local.yml

_inf 'Setting up .env file...'
cp .env.example .env

wp dotenv salts generate --color
wp dotenv set WP_ENV $WP_ENV --color
wp dotenv set WP_HOME $WP_HOME --color
wp dotenv set DB_HOST $DB_HOST --color

_inf 'Installling WordPress...'
wp core install --skip-email --title="WordPress Dev" \
    --admin_user="admin" --admin_password="secret" --admin_email="demo@example.com"

# Feel free to setup your own wp_options
# wp option update permalink_structure '/%postname%/'
# wp option update link_manager_enabled '1'

_inf 'Import dummy content'
wp import resources/dummy-content.xml --authors=create

