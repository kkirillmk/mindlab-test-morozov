FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    libzip \
    oniguruma

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
        opcache \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable redis \
    && apk del .build-deps

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

FROM base AS development

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

RUN echo "xdebug.mode=develop,debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

FROM base AS production

COPY --chown=www-data:www-data composer.json composer.lock /var/www/

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

COPY --chown=www-data:www-data . /var/www

RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
