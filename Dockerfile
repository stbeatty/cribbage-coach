FROM php:7.4-fpm-alpine

COPY --from=composer:2.6.3 /usr/bin/composer /usr/bin/composer

COPY composer.json .

RUN composer install --optimize-autoloader

EXPOSE 9999
