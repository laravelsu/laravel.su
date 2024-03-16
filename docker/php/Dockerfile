FROM php:8.3-fpm

WORKDIR "/home/laravel/laravel.su"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# ------------------------------------------------------------------------------
# Install Dependencies
# ------------------------------------------------------------------------------

RUN apt-get update \
    && pecl channel-update pecl.php.net \
    && apt-get -y --no-install-recommends install \
      libsqlite3-dev \
      libzip-dev \
      libicu-dev \
      libpq-dev \
      unzip \
      git \
      curl

# ------------------------------------------------------------------------------
# Install PHP
# ------------------------------------------------------------------------------

# Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir /home/laravel/.composer

# APCU
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# INTL
RUN docker-php-ext-install intl \
    && docker-php-ext-enable intl

# PDO PostgreSQL
RUN docker-php-ext-install pdo_sqlite \
    && docker-php-ext-enable pdo_sqlite

# Zip
RUN docker-php-ext-install zip \
    && docker-php-ext-enable zip

# Opcache
RUN docker-php-ext-install opcache

# Sockets
RUN docker-php-ext-install sockets

# ------------------------------------------------------------------------------
# Cleanup Dependencies
# ------------------------------------------------------------------------------

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && ln -sf /usr/share/zoneinfo/Etc/UTC /etc/localtime

# ------------------------------------------------------------------------------
#   Copy Runtime Files
# ------------------------------------------------------------------------------

COPY --chown=root:root boot.sh /etc/init.d/boot.sh
RUN chmod +x /etc/init.d/boot.sh

# ------------------------------------------------------------------------------
#   Execute Server
# ------------------------------------------------------------------------------

RUN useradd -u 1000 -m laravel
USER laravel

ENTRYPOINT ["/etc/init.d/boot.sh"]
