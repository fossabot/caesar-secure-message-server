FROM php:7.4-fpm-alpine as base

USER www-data
#RUN mkdir -p /var/www/html /var/www/html/public/static /var/www/html/var/cache /var/www/html/var/logs /var/www/html/var/sessions

WORKDIR /var/www/html



# Run in production mode


COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT -1

COPY composer.json .
RUN composer install --prefer-dist --no-plugins --no-scripts --no-dev --optimize-autoloader

COPY --chown=www-data:www-data . .

ENV APP_ENV=prod
ENTRYPOINT ["entrypoint.sh"]