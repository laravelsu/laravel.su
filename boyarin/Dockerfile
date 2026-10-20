FROM composer:2 AS composer
FROM php:8.5-cli-alpine

RUN apk add --no-cache oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS oniguruma-dev \
    && docker-php-ext-install mbstring \
    && apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN chown www-data:www-data /var/www/html
USER www-data
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader
COPY --chown=www-data:www-data . .
CMD ["composer", "start"]
