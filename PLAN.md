# Plan: gaula-python → gaula-php

Mål: samme side på et vanlig webhotell (PHP 8.3+, MySQL/MariaDB, cron).
**Beslutning 18. november 2026:** Laravel er neste versjon. Django er utgått
og skal ikke brukes videre eller settes i produksjon igjen. Den gamle koden
kan brukes som historisk referanse for logikk og tester. Hver del porteres
sammen med testene sine.

Ved serverkontroll denne datoen kjørte den eksisterende VPS-en fortsatt
Django. Konfigurasjon, nøkler, kode og database er sikkerhetskopiert, og
databasedumpen er testgjenopprettet. VPS-en skal avvikles. Det er ikke behov
for produksjonsdrift mens løsningen ikke er i bruk; en ny server eller et
egnet webhotell kan tas i bruk for Laravel ved senere behov.

| # | Del | Innhold | Status |
| --- | --- | --- | --- |
| 1 | Grunnmur | Laravel + Filament i Docker (MariaDB), modeller og migreringer for alle appene, datamigrering fra Postgres | **ferdig** |
| 2 | Booking i admin | Opphold, rom, priser (3 nivåer), stenger og stangdøgn, Sesongen, Uka, trekning og rotasjon, økonomi, gjøremål, sjekklister, «Å gjøre nå», innstillinger, testdata | |
| 3 | Gjester | Gjesteregister, meldinger, e-postmaler, tilbakemeldinger, personvern, «Ikke booket ennå» | |
| 4 | Nettsiden og «Mitt fiske» | Forside, no/en/de, temaer, PIN og tilgangskoder, rotasjon, kart, offline | |
| 5 | Elv og fangst | NVE, Elveguiden, NINA, stasjoner, ukestall, sesongrapport, PDF og Excel | |
| 6 | Gjenoppstart ved behov | Fullføre og teste Laravel, kontrollere import fra backup og publisere på ny hosting. Django skal ikke settes i drift igjen. | |

## Valg

- Cache, sesjoner og køer i databasen (ingen Redis). Faste jobber via
  Laravels scheduler, med én cron-linje hvert minutt.
- Hosting: webhotell med SSH og Composer (f.eks. Domeneshop). Ingen
  prosesser som må gå hele tiden.
- PDF: dompdf (må fungere uten Chrome på serveren).
- Faste data (fiskerne, rommene) som migreringer, som i Django.

## Del 1: ferdig

- Docker-rigg: `docker-compose.yml` (app, scheduler, MariaDB) med overstyringer
  for hjemmelab, VPS (prod, https) og lokal stack (Mailpit). Se README.
- Alle 47 tabellene som migrering (samme navn som i Django) og en Eloquent-modell
  per tabell med relasjoner og casts. Logikken (queryset-metoder, regler)
  kommer med hver del.
- Datamigrering: `scripts/export_django.py` (kjøres i Django-containeren) og
  `php artisan gaula:import <mappe> --ja`. Testet mot demodataene: 6 818
  fangster, 147 opphold, 30 gjester med mer, alt på plass. Tidspunkt lagres i
  Oslo-tid. Brukerne får nytt passord (`php artisan gaula:passord <e-post>`).
- Mangler til del 2: roller og tilganger (Hjelper m.fl.), enum-ene for status,
  og all forretningslogikk.
