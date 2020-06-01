# Thank to https://github.com/heroku/heroku-buildpack-php/blob/master/bin/util/common.sh

error() {
    # send all of our output to stderr
    exec 1>&2
    # if arguments are given, redirect them to stdin
    # this allows the funtion to be invoked with a string argument, or with stdin, e.g. via <<-EOF
    (( $# )) && exec <<< "$@"
    echo -e "\033[1;31m" # bold; red
    echo -n " !     ERROR: "
    # this will be fed from stdin
    indent no_first_line_indent " !     "
    if [[ -s "$_captured_warnings_file" ]]; then
        echo "" | indent "" " !     "
        echo -e "\033[1;33mREMINDER:\033[1;31m the following \033[1;33mwarnings\033[1;31m were emitted during the build;" | indent "" " !     "
        echo "check the details above, as they may be related to this error:" | indent "" " !     "
        cat "$_captured_warnings_file" | indent "" "$(echo -e " !     \033[1;33m-\033[1;31m ")"
    fi
    echo -e "\033[0m" # reset style
    exit 1
}

status() {
    # send all of our output to stderr
    exec 1>&2
    # if arguments are given, redirect them to stdin
    # this allows the funtion to be invoked with a string argument, or with stdin, e.g. via <<-EOF
    (( $# )) && exec <<< "$@"
    echo -n "-----> "
    # this will be fed from stdin
    cat
}

indent() {
    # if any value (e.g. a non-empty string, or true, or false) is given for the first argument, this will act as a flag indicating we shouldn't indent the first line; we use :+ to tell SED accordingly if that parameter is set, otherwise null string for no range selector prefix (it selects from line 2 onwards and then every 1st line, meaning all lines)
    # if the first argument is an empty string, it's the same as no argument (useful if a second argument is passed)
    # the second argument is the prefix to use for indenting; defaults to seven space characters, but can be set to e.g. " !     " to decorate each line of an error message
    local c="${1:+"2,999"} s/^/${2-"       "}/"
    case $(uname) in
        Darwin) sed -l "$c";; # mac/bsd sed: -l buffers on line boundaries
        *)      sed -u "$c";; # unix/gnu sed: -u unbuffered (arbitrary) chunks of data
    esac
}

curl_retry() {
    local ec=18;
    local attempts=0;
    while (( ec == 18 && attempts++ < 3 )); do
        curl "$@" # -C - would return code 33 if unsupported by server
        ec=$?
    done
    return $ec
}

export_env_dir() {
    env_dir=$1
    whitelist_regex=${2:-''}
    blacklist_regex=${3:-'^(PATH|GIT_DIR|CPATH|CPPATH|LD_PRELOAD|LIBRARY_PATH)$'}
    if [ -d "$env_dir" ]; then
        for e in $(ls $env_dir); do
        echo "$e" | grep -E "$whitelist_regex" | grep -qvE "$blacklist_regex" &&
        export "$e=$(cat $env_dir/$e)"
        :
        done
    fi
}

create_default_env() {
    local build_dir="$1"

    mkdir -p "$build_dir/.heroku/wp-cli/{bin,cache,packages}"

    # if the build dir is not "/app", we symlink in the .heroku/wp-cli subdir
    # (and only that, to avoid problems with other buildpacks) so that PHP correctly finds its INI files etc
    [[ $build_dir == '/app' ]] || ln -s $build_dir/.heroku/wp-cli /app/.heroku/wp-cli

    export WP_CLI_CACHE_DIR=${WP_CLI_CACHE_DIR:-$build_dir/.heroku/wp-cli/cache}
    export WP_CLI_PACKAGES_DIR=${WP_CLI_PACKAGES_DIR:-$build_dir/.heroku/wp-cli/packages}
    export WP_ENV=${WP_ENV:-production}
    export WP_HOME=${WP_HOME:-http://${HEROKU_APP_NAME}.herokuapp.com}
}

download_wpcli() {
    local output_dir="$1"
    local wpcli_url='https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar'

    curl_retry --fail --silent --location -o "$output_dir" "$wpcli_url" || {
error <<-EOF
    Failed to download a WP-CLI executable for bootstrapping!

    This is most likely a temporary internal error. If the problem
    persists, make sure that you are not running a custom or forked
    version of the Heroku Ruby buildpack which may need updating.
EOF
}
}
