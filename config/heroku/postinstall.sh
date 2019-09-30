#!/usr/bin/env bash
cd $HOME

if [ ! -z $HEROKU_APP_NAME ]; then
    wp core multisite-install --skip-email --no-color \
        --url="https://${HEROKU_APP_NAME}.herokuapp.com" --title="WordPress Site" \
        --admin_user="admin" --admin_password="secret" --admin_email="admin@example.com"

    # wp option update permalink_structure '/%postname%/'
    # wp option update link_manager_enabled '1'
fi
