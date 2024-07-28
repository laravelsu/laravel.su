@servers(['web' => ['deployer@84.38.181.107']])

@task('deploy', ['on' => ['web']])
    cd /home/deployer/laravel.su/current
    php artisan down --refresh=15
    git pull

    php artisan cache:clear
    php artisan config:clear

    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --classmap-authoritative

    php artisan view:clear
    php artisan sqlite:optimize
    php artisan migrate --force

    echo "" | sudo -S service php8.3-fpm reload

    php artisan optimize
    php artisan up
    php artisan storage:link

    echo "🚀 Application deployed!"
@endtask
