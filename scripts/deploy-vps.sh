#!/bin/sh
# Deployer det som er committet til VPS-en med docker-compose.prod.yml.
# Forutsetter .env på serveren (APP_KEY, APP_URL, APP_DOMAIN, DB_PASSWORD …).
#
#   VPS_HOST=bruker@vps sh scripts/deploy-vps.sh
set -e
: "${VPS_HOST:?sett VPS_HOST, f.eks. VPS_HOST=deploy@gaula.descartes.no}"
D=${VPS_DIR:-/opt/gaula-php}
C="docker compose -f docker-compose.yml -f docker-compose.prod.yml"
cd "$(dirname "$0")/.."

git diff --quiet HEAD || echo "NB: ucommittede endringer blir IKKE med." >&2

ssh "$VPS_HOST" "mkdir -p $D && find $D -mindepth 1 -maxdepth 1 ! -name .env -exec rm -rf {} +"
git archive --format=tar HEAD | ssh "$VPS_HOST" "tar -m --no-same-owner -x -C $D"
ssh "$VPS_HOST" "cd $D && $C up -d --build && $C ps"
