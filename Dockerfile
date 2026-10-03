# Gaula: Laravel + Filament på FrankenPHP (PHP + Caddy i én prosess).
FROM dunglas/frankenphp:1-php8.5

RUN install-php-extensions intl zip pdo_mysql pdo_sqlite gd opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && php artisan filament:assets \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

# FrankenPHP serverer /app/public. Ingen TLS her — bare http på hjemmelaben.
ENV SERVER_NAME=:80

COPY --chmod=755 docker/start.sh /usr/local/bin/start
CMD ["start"]
