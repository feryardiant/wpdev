#!/usr/bin/env bash
cd $HOME

curl -Lso vendor/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x vendor/bin/wp

if [ ! -z $HEROKU_APP_NAME ]; then
    vendor/bin/wp core install --skip-email --no-color \
        --url="https://${HEROKU_APP_NAME}.herokuapp.com" --title="WordPress Site" \
        --admin_user="admin" --admin_password="secret" --admin_email="admin@example.com"

    # wp option update permalink_structure '/%postname%/'
    # wp option update link_manager_enabled '1'
fi

APP_URL="https://${HEROKU_APP_NAME}.herokuapp.com"
