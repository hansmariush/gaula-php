#!/bin/sh
# Ved oppstart: migreringene er kjørt og cachen er fersk.
set -e
cd /app
php artisan migrate --force
php artisan storage:link 2>/dev/null || true
php artisan optimize
exec frankenphp run --config /etc/frankenphp/Caddyfile
