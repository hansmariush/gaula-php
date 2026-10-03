# Gaula: Laravel + Filament på FrankenPHP (PHP + Caddy i én prosess).
#   prod  (standard): komprimert og ferdig, brukes på hjemmelaben og VPS-en
#   dev:              med dev-pakker (Pest/Pint/PHPUnit), kildekoden monteres inn utenfra
FROM dunglas/frankenphp:1-php8.5 AS base

RUN install-php-extensions intl zip pdo_mysql pdo_sqlite gd opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
ENV SERVER_NAME=:80

# --- dev ---------------------------------------------------------------
FROM base AS dev
ENV APP_ENV=local APP_DEBUG=true
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist
COPY . .
RUN composer dump-autoload --no-interaction
COPY --chmod=755 docker/start.sh /usr/local/bin/start
CMD ["start"]

# --- prod --------------------------------------------------------------
FROM base AS prod
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist
COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && php artisan filament:assets \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache
COPY --chmod=755 docker/start.sh /usr/local/bin/start
CMD ["start"]
