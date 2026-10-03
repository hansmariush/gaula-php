#!/bin/sh
# Deployer det som er committet til hjemmelaben (LXC-en «gaula») og bygger
# containeren på nytt. Databasen og opplastede bilder ligger i Docker-volumer
# og overlever. .env på serveren tas vare på.
#
#   sh scripts/deploy.sh
set -e
H=${HOMELAB_HOST:-root@192.168.1.232}
D=/opt/gaula-php
cd "$(dirname "$0")/.."

if ! git diff --quiet HEAD; then
  echo "NB: ucommittede endringer blir IKKE med." >&2
fi

ssh "$H" "mkdir -p $D && find $D -mindepth 1 -maxdepth 1 ! -name .env -exec rm -rf {} +"
git archive --format=tar HEAD | ssh "$H" "tar -m --no-same-owner -x -C $D"
ssh "$H" "cd $D && docker compose -f docker-compose.yml -f docker-compose.homelab.yml up -d --build && docker compose -f docker-compose.yml -f docker-compose.homelab.yml ps"
