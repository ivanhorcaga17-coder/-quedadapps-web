#!/usr/bin/env sh

set -u

run_optional() {
    echo ">> $1"

    if sh -c "$1"; then
        echo ">> ok"
        return 0
    fi

    echo ">> warning: command failed, continuing startup"
    return 0
}

run_optional "php artisan migrate --force --no-interaction"
run_optional "php artisan config:clear --no-interaction"
run_optional "php artisan view:clear --no-interaction"
run_optional "php artisan config:cache --no-interaction"
run_optional "php artisan view:cache --no-interaction"

exec frankenphp run --config=/app/Caddyfile
