#!/bin/sh
# Ved oppstart: migreringene er kjørt (og cachen fersk i produksjon).
set -e
cd /app
php artisan migrate --force
php artisan storage:link 2>/dev/null || true
if [ "$APP_ENV" = "production" ]; then
  php artisan optimize
else
  php artisan optimize:clear
fi
exec frankenphp run --config /etc/frankenphp/Caddyfile
