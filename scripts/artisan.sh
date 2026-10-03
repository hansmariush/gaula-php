#!/bin/sh
# Kjører artisan (eller composer) mot arbeidskopien — også det som ikke er
# committet — i en container på hjemmelaben, og henter filene som ble laget
# eller endret tilbake hit. Nyttig for make:*-kommandoer og tester.
#
#   sh scripts/artisan.sh make:model Fangst -m
#   sh scripts/artisan.sh test
#   COMPOSER=1 sh scripts/artisan.sh require pakke/navn
set -e
H=${HOMELAB_HOST:-root@192.168.1.232}
D=/opt/gaula-php-dev
cd "$(dirname "$0")/.."

# Arbeidskopien over (vendor ligger igjen der borte mellom kjøringene).
ssh "$H" "mkdir -p $D && find $D -mindepth 1 -maxdepth 1 ! -name vendor ! -name .env -exec rm -rf {} +"
tar --exclude=./vendor --exclude=./node_modules --exclude=./.git --exclude=./.env -cf - . \
  | ssh "$H" "tar -m --no-same-owner -x -C $D"

if [ -n "$COMPOSER" ]; then
  CMD="composer $*"
else
  CMD="php artisan $*"
fi
ssh "$H" "cd $D && [ -f .env ] || (cp .env.example .env && sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env)
  docker run --rm -v $D:/app -w /app gaula-php:latest sh -c '[ -d vendor ] || composer install -q --no-interaction; [ -n \"\$(grep ^APP_KEY=base64 .env)\" ] || php artisan key:generate -q; $CMD'"

# Det som ble laget eller endret, tilbake hit.
ssh "$H" "cd $D && tar --exclude=./vendor --exclude=./node_modules --exclude=./.env \
  --exclude=./storage --exclude=./bootstrap/cache --exclude=./database/database.sqlite -cf - ." | tar -xf -
