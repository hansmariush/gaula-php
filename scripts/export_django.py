"""Eksporterer databasen fra gaula-python (Django) til NDJSON, én fil per tabell,
klar for `php artisan gaula:import`.

Kjøres i Django-containeren (leser bare, endrer ingenting):

    docker compose exec -T backend python manage.py shell < scripts/export_django.py
    docker compose cp backend:/tmp/gaula-export ./gaula-export

Tidspunkt skrives i Oslo-tid (Laravel lagrer i app-tidssonen), datoer som ISO.
Passord fra Django kan ikke brukes i Laravel — brukerne får nytt passord.
"""

import datetime as dt
import decimal
import json
import os
import shutil
import zoneinfo

from django.apps import apps
from django.db import connection

OSLO = zoneinfo.ZoneInfo("Europe/Oslo")
APPS = ["access", "catches", "crm", "guide", "lodging", "nina", "stations", "stats", "vald"]
OUT = "/tmp/gaula-export"


def conv(v):
    if isinstance(v, dt.datetime):
        if v.tzinfo:
            v = v.astimezone(OSLO).replace(tzinfo=None)
        return v.strftime("%Y-%m-%d %H:%M:%S")
    if isinstance(v, dt.date):
        return v.isoformat()
    if isinstance(v, decimal.Decimal):
        return str(v)
    if isinstance(v, (dict, list)):
        return json.dumps(v, ensure_ascii=False)
    return v


def dump(table, columns, cursor_sql, order):
    with connection.cursor() as c:
        c.execute(cursor_sql)
        n = 0
        with open(f"{OUT}/{table}.ndjson", "w", encoding="utf-8") as fh:
            for row in c:
                fh.write(json.dumps(dict(zip(columns, map(conv, row))), ensure_ascii=False) + "\n")
                n += 1
    order.append(table)
    print(f"{table}: {n}")


shutil.rmtree(OUT, ignore_errors=True)
os.makedirs(OUT)
order = []

q = connection.ops.quote_name
dump("auth_user", ["id", "username", "email", "first_name", "last_name", "is_superuser", "is_staff",
                   "is_active", "date_joined", "last_login"],
     "select id, username, email, first_name, last_name, is_superuser, is_staff, is_active, "
     "date_joined, last_login from auth_user order by id", order)
dump("auth_group", ["id", "name"], "select id, name from auth_group order by id", order)
dump("auth_user_groups", ["user_id", "group_id"], "select user_id, group_id from auth_user_groups", order)

for m in apps.get_models():
    if m._meta.app_label not in APPS:
        continue
    cols = [f.column for f in m._meta.local_fields]
    dump(m._meta.db_table, cols,
         f"select {', '.join(q(c) for c in cols)} from {q(m._meta.db_table)} order by 1", order)
    for f in m._meta.local_many_to_many:
        a, b = f.m2m_column_name(), f.m2m_reverse_name()
        dump(f.m2m_db_table(), ["id", a, b],
             f"select id, {q(a)}, {q(b)} from {q(f.m2m_db_table())} order by id", order)

json.dump(order, open(f"{OUT}/_order.json", "w"))
print("ferdig:", OUT)
