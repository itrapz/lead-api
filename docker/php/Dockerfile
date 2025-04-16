FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip libicu-dev libonig-dev libzip-dev libpq-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
