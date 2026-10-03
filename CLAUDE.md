# gaula-php

Gaula-siden (booking, admin, «Mitt fiske», elv og fangst) skrevet om fra
Python/Django (repoet `gaula-python`) til Laravel 13 + Filament 5, så den kan
kjøre på et vanlig webhotell (PHP 8.3+, MySQL/MariaDB, cron, cache og køer i
databasen). All tekst, kode og commit-meldinger er på norsk.

Planen og status står i [PLAN.md](PLAN.md). Django-siden i `gaula-python` er
fasit for logikken og går i prod til denne er ferdig og sammenlignet.

## Miljø

- Windows-maskinen har ikke PHP, Composer eller Docker. Alt kjøres i Docker
  på hjemmelaben (LXC `root@192.168.1.232`).
- Kjører på http://192.168.1.232:8081 (`/opt/gaula-php`, MariaDB i volumet
  `dbdata`). Lokal stack og VPS: se README (`docker-compose.local.yml`,
  `docker-compose.prod.yml`, `scripts/deploy-vps.sh`).

## Slik jobber vi

- `sh scripts/artisan.sh <kommando>` kjører artisan mot arbeidskopien på
  hjemmelaben og henter genererte filer tilbake (`make:*`, `test`).
  `COMPOSER=1 sh scripts/artisan.sh require …` for pakker.
- `sh scripts/deploy.sh` deployer det som er committet og bygger på nytt.
- Commit lokalt. Push bare når det er bedt om. Aldri passord eller nøkler i repoet.
- Logikken porteres 1:1 fra Django-koden, med de samme testene skrevet om.
