# gaula-php

Laksefiske i Gaula: booking og admin for oss som driver, og «Mitt fiske» for
gjestene. Laravel 13 + Filament 5 + HTMX. Omskriving av `gaula-python`.

Status og plan: [PLAN.md](PLAN.md). Arbeidsrutiner: [CLAUDE.md](CLAUDE.md).

## Status og videre retning – 18. november 2026

- **Laravel i dette repoet er neste versjon av Gaula.** Omskrivingen er under
  arbeid; statusen i planen skal ikke tolkes som en ferdig produksjonsløsning.
- **Django-versjonen i `gaula-python` er utgått og skal ikke brukes videre,**
  verken til nyutvikling eller som fremtidig produksjonsløsning. Koden og
  backupen beholdes bare som historisk referanse og grunnlag for datamigrering.
- Ved kontroll 18. november 2026 kjørte `gaula.descartes.no` fortsatt Django,
  ikke Laravel. Konfigurasjon, nøkler, kode og database er sikkerhetskopiert;
  databasedumpen er testgjenopprettet. Den eksisterende VPS-en skal avvikles.
- Det er ikke behov for produksjonsdrift i ventetiden. Når løsningen trengs
  igjen, fullføres og testes Laravel-versjonen før eventuell ny publisering.

## Kjøre lokalt (Docker Desktop)

```sh
docker compose -f docker-compose.yml -f docker-compose.local.yml up --build
```

- Siden: http://localhost:8000 (admin på `/admin`)
- E-post fanges av Mailpit: http://localhost:8025
- MariaDB: `localhost:3307` (bruker/passord/database: `gaula`)
- Kildekoden er montert inn, så endringer synes med én gang.

Nyttige kommandoer (`dc` = kommandoen over uten `up --build`):

```sh
dc exec app php artisan make:filament-user      # lag innlogging
dc exec app php artisan test                    # testene
dc exec app vendor/bin/pint                     # formatering
dc run --rm app composer require pakke/navn     # ny pakke
```

## Kjøre på hjemmelaben

`sh scripts/deploy.sh` (committet kode, http://192.168.1.232:8081).

## Kjøre på VPS-en

Samme containere med eget domene og automatisk https:

1. På serveren: `/opt/gaula-php/.env` med `APP_KEY`, `APP_ENV=production`,
   `APP_URL`, `APP_DOMAIN`, `DB_PASSWORD`.
2. `VPS_HOST=bruker@vps sh scripts/deploy-vps.sh`

`scheduler`-containeren tar de faste jobbene (det cron gjør på et webhotell).

## Kjøre på webhotell uten Docker

PHP 8.3+, MySQL/MariaDB, SSH og Composer: `composer install --no-dev`,
`php artisan migrate --force`, og denne cron-linjen:

```
* * * * * cd /sti/til/gaula-php && php artisan schedule:run >> /dev/null 2>&1
```
