@servers(['web' => ['deployer@84.38.181.107']])

@task('deploy', ['on' => ['web']])
    php8.4 -r 'exit(version_compare(PHP_VERSION, "8.4.27", ">=") ? 0 : 1);' || exit 1
    cd /home/deployer/laravel.su/current
    php8.4 artisan down --refresh=15
    git pull

    php8.4 artisan cache:clear
    php8.4 artisan config:clear

    php8.4 "$(command -v composer)" install --no-interaction --prefer-dist --optimize-autoloader --no-dev --classmap-authoritative

    php8.4 artisan view:clear
    php8.4 artisan sqlite:optimize
    php8.4 artisan migrate --force

    sudo -n service php8.4-fpm reload

    php8.4 artisan optimize
    php8.4 artisan up
    php8.4 artisan storage:link

    echo "🚀 Application deployed!"
@endtask
