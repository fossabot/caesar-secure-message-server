FROM php:7.4-fpm-alpine as base

WORKDIR /var/www/html

RUN apk --update add \
    build-base \
    gpgme-dev \
    gpgme \
    autoconf

RUN pecl install gnupg redis \
    && docker-php-ext-enable redis

USER www-data

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT -1

COPY composer.json .
RUN composer install --prefer-dist --no-plugins --no-scripts --no-dev --optimize-autoloader

COPY --chown=www-data:www-data . .

ENV APP_ENV=prod
ENTRYPOINT ["entrypoint.sh"]