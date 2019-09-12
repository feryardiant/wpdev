#!/usr/bin/env bash
cd $HOME

if [ ! -z $WP_SITEURL ]; then
    vendor/bin/wp core install \
        --url="$WP_SITEURL" \
        --title="WordPress Site" \
        --admin_user="admin" \
        --admin_password="secret" \
        --admin_email="admin@$WP_SITEURL" \
        --skip-email
fi