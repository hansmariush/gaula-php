# Plan: gaula-python → gaula-php

Mål: samme side på et vanlig webhotell (PHP 8.3+, MySQL/MariaDB, cron).
Django-siden er fasit og går i prod til alt er sammenlignet. Hver del
porteres sammen med testene sine.

| # | Del | Innhold | Status |
| --- | --- | --- | --- |
| 1 | Grunnmur | Laravel + Filament i Docker (MariaDB), modeller og migreringer for alle appene, datamigrering fra Postgres | pågår |
| 2 | Booking i admin | Opphold, rom, priser (3 nivåer), stenger og stangdøgn, Sesongen, Uka, trekning og rotasjon, økonomi, gjøremål, sjekklister, «Å gjøre nå», innstillinger, testdata | |
| 3 | Gjester | Gjesteregister, meldinger, e-postmaler, tilbakemeldinger, personvern, «Ikke booket ennå» | |
| 4 | Nettsiden og «Mitt fiske» | Forside, no/en/de, temaer, PIN og tilgangskoder, rotasjon, kart, offline | |
| 5 | Elv og fangst | NVE, Elveguiden, NINA, stasjoner, ukestall, sesongrapport, PDF og Excel | |
| 6 | Overgang | Testene, gammel og ny side om side, flytte domenet | |

## Valg

- Cache, sesjoner og køer i databasen (ingen Redis). Faste jobber via
  Laravels scheduler, med én cron-linje hvert minutt.
- Hosting: webhotell med SSH og Composer (f.eks. Domeneshop). Ingen
  prosesser som må gå hele tiden.
- PDF: dompdf (må fungere uten Chrome på serveren).
- Faste data (fiskerne, rommene) som migreringer, som i Django.
