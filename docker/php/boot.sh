#!/usr/bin/env bash
set -e

echo " > Prepare server APP_ENV=${APP_ENV}"

echo "$ copy .env.example .env"
cp -n /home/laravel/laravel.su/.env.example /home/laravel/laravel.su/.env

# NON Production Environment
if [[ ${APP_ENV} != *"production"* ]];
then
    # --------------------------------------------------------------------------
    #  Additional installation of DEV dependencies
    # --------------------------------------------------------------------------
    echo "$ touch database/database.sqlite"
    touch /home/laravel/laravel.su/database/database.sqlite

    echo "$ composer install -n -v"
    composer install -n -v

    echo "$ artisan key:generate"
    php /home/laravel/laravel.su/artisan key:generate

    echo "$ artisan migrate"
    php /home/laravel/laravel.su/artisan migrate
fi

# ----------------------------------------------------------------------------
#  Warmup
# ----------------------------------------------------------------------------

echo "$ artisan cache:clear"
php /home/laravel/laravel.su/artisan cache:clear

echo "$ artisan config:clear"
php /home/laravel/laravel.su/artisan config:clear

# ------------------------------------------------------------------------------
#  Execute Server
# ------------------------------------------------------------------------------

echo "Ready (APP_ENV=${APP_ENV}, APP_DEBUG=${APP_DEBUG})"

exec php-fpm
