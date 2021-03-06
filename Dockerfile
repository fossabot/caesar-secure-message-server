FROM php:7.4-cli-alpine as base

WORKDIR /var/www/html

RUN apk --update add \
    build-base \
    gpgme-dev \
    gpgme \
    autoconf \
    libzip-dev \
    zip

RUN pecl install gnupg redis \
    && docker-php-ext-enable gnupg redis \
    && docker-php-ext-install sockets zip

USER www-data
ENV APP_ENV=prod

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT -1

COPY composer.json .
COPY composer.lock .

# ---- Dependencies ----
FROM base AS dependencies
# install vendors
USER www-data
RUN APP_ENV=prod composer install --prefer-dist --no-plugins --no-scripts --no-dev --optimize-autoloader
RUN mkdir bin
RUN vendor/bin/rr get --location bin/

# ---- Release ----
FROM base AS release
USER www-data

COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=dependencies bin bin
COPY --chown=www-data:www-data --from=dependencies /var/www/html/vendor /var/www/html/vendor
ENTRYPOINT ["bin/rr serve", "--debug", "-vvv"]