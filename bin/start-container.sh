#!/usr/bin/env sh

set -eu

php artisan migrate --force --no-interaction
php artisan config:clear --no-interaction
php artisan view:clear --no-interaction
php artisan config:cache --no-interaction
php artisan view:cache --no-interaction

exec frankenphp run --config=/app/Caddyfile
