#!/usr/bin/env bash
cd $HOME

if [ ! -z $HEROKU_APP_NAME ]; then
    wp core multisite-install --skip-email --no-color
        --url="${HEROKU_APP_NAME}.herokuapp.com" --title="WordPress Site" \
        --admin_user="admin" --admin_password="secret" --admin_email="admin@example.com" \

    if [ `wp option update permalink_structure` != '/%postname%/' ]; then
        wp option update permalink_structure '/%postname%/'
    fi

    # wp option update link_manager_enabled '1'
fi
