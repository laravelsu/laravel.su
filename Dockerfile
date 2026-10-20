FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM dunglas/frankenphp:1-php8.4-bookworm AS runtime
RUN install-php-extensions pdo_sqlite sqlite3 mbstring dom curl zip gd intl pcntl bcmath exif opcache \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=http://148.253.210.79 \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/data/database.sqlite \
    CACHE_DRIVER=file \
    SESSION_DRIVER=file \
    SESSION_SECURE_COOKIE=false \
    QUEUE_CONNECTION=sync \
    LOG_CHANNEL=stderr \
    MAIL_MAILER=log \
    TELESCOPE_ENABLED=false \
    COMPOSER_ALLOW_SUPERUSER=1

COPY . .
RUN mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && composer install --prefer-dist --no-dev --no-interaction --optimize-autoloader \
    && rm -f public/hot
COPY --from=assets /app/public/build ./public/build
COPY docker/deploy/Caddyfile /etc/caddy/Caddyfile
COPY --chmod=755 docker/deploy/entrypoint.sh /usr/local/bin/app-entrypoint

EXPOSE 80
HEALTHCHECK --interval=10s --timeout=5s --start-period=60s --retries=5 \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1/up") === false ? 1 : 0);'
ENTRYPOINT ["app-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
