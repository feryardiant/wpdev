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
wpcli_url='https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar'
curl_retry --fail --silent --location -o vendor/bin/wp "$wpcli_url" || {
error <<-EOF
Failed to download a WP-CLI executable for bootstrapping!
EOF
}

chmod +x vendor/bin/wp

wp() {
    # Here we still using WP-CLI from composer because of some it's commands
    # are not working without installing the core first
    # See: https://github.com/feryardiant/wpdev/runs/727937952?check_suite_focus=true#step:7:11
    vendor/bin/wp "$@"
}

cp wp-cli.yml wp-cli.local.yml

cat >> .env <<-EOL
DB_NAME=wordpress
DB_USER=root
DB_PASSWORD=secret
DB_HOST=$DB_HOST

WP_ENV=$WP_ENV
WP_HOME=$WP_HOME
WP_DEFAULT_THEME=blank-child

AUTH_KEY='ffNM?:/+}9/}H*L/!#k*<sh{tzCz>?c*BZ;xx_/Qm/9GafYoOY:j{K/C{{Zf59o#'
SECURE_AUTH_KEY='TxZ4r1^NNeYsPX5?mcM.MB_:XH57zX^5<u)uX.Mi[iRN59qm-:.^d#6wINr[za[N'
LOGGED_IN_KEY='/i:|;37RVJ/ffeT6Q?Uc]!@WK%PcEvuN>wofK3N]k_T58/AOiUp4yU7-T5+<F/iY'
NONCE_KEY='AXBYCMr/UK^N*f)=4sn=aPu4pkvv,/nfb%,^b6-4Srw#:c^D)/JzI;A/XS,r.dUl'
AUTH_SALT='Dyh:{PXYIgnqZ/[C}vhuV14bDnZC8G_C?XEmg.Nm4*>*2!BcGT0:}rMbH1:md&V6'
SECURE_AUTH_SALT='p3eW{YPpM[9)0T&VjIntxTimSypI:jZ1pjZ-aciCSz0U3cN9EGbVk0Wp]JSv0Rb:'
LOGGED_IN_SALT=',n9-,v,B&siw22:v<T8mxj#%jPQr;S%M:F.#w{eDHCA[?@Qel<_vMF[:zzjr9W-:'
NONCE_SALT='W3HZnqL9Ar<,PZZ8JUrf2WkO*r5DW!A|X[6Dx:tj4w[C6*;oGk*.K3/I45Upe64!'
EOL

_inf 'Installling WordPress...'
wp core install --skip-email --url="${WP_HOME}" --title="WordPress Local" \
    --admin_user="admin" --admin_password="secret" --admin_email="demo@example.com"

# Feel free to setup your own wp_options
wp option update permalink_structure '/%postname%/'
wp option update link_manager_enabled '1'

# _inf 'Import dummy content'
wp import tests/dummy-content.xml --authors=tests/dummy-content.mapping.csv --skip=attachment

wp menu location assign primary primary
wp menu location assign footer footer
