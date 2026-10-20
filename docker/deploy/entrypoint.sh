#!/bin/sh
set -eu

: "${APP_KEY:?APP_KEY must be provided and kept between deployments}"
: "${DB_DATABASE:=/data/database.sqlite}"
export DB_DATABASE

mkdir -p "$(dirname "$DB_DATABASE")" \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [ ! -f "$DB_DATABASE" ]; then
    touch "$DB_DATABASE"
fi

php artisan migrate --force --no-interaction
php artisan storage:link --no-interaction

exec docker-php-entrypoint "$@"
