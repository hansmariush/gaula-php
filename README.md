# gaula-php

Laksefiske i Gaula: booking og admin for oss som driver, og «Mitt fiske» for
gjestene. Laravel 13 + Filament 5 + HTMX. Omskriving av `gaula-python`.

Status og plan: [PLAN.md](PLAN.md). Arbeidsrutiner: [CLAUDE.md](CLAUDE.md).

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
