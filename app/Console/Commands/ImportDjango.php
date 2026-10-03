<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Flytter dataene fra gaula-python (Django) hit. Eksporten lages med
 * scripts/export_django.py. Tabellene tømmes først, så kommandoen kan kjøres
 * på nytt (f.eks. rett før vi bytter over).
 *
 *   php artisan gaula:import /sti/til/gaula-export --ja
 */
class ImportDjango extends Command
{
    protected $signature = 'gaula:import {mappe : mappen med NDJSON-filene} {--ja : bekreft at tabellene tømmes}';

    protected $description = 'Importerer data eksportert fra gaula-python (Django)';

    public function handle(): int
    {
        $dir = rtrim($this->argument('mappe'), '/\\');
        if (! is_file("$dir/_order.json")) {
            $this->error("Fant ikke $dir/_order.json — har du kjørt scripts/export_django.py?");

            return self::FAILURE;
        }
        if (! $this->option('ja') && ! $this->confirm('Alle tabellene tømmes og fylles på nytt. Fortsette?')) {
            return self::FAILURE;
        }

        $order = json_decode(file_get_contents("$dir/_order.json"), true);
        Schema::disableForeignKeyConstraints();
        $brukere = [];

        foreach ($order as $table) {
            $path = "$dir/$table.ndjson";
            if ($table === 'auth_user') {
                $brukere = $this->lesBrukere($path);
                $this->settInnBrukere($brukere);

                continue;
            }
            if (str_starts_with($table, 'auth_')) {
                continue; // grupper og roller settes opp på nytt (se PLAN.md, del 2)
            }
            if (! Schema::hasTable($table)) {
                $this->warn("Hopper over $table (finnes ikke her)");

                continue;
            }
            DB::table($table)->truncate();
            $n = $this->settInn($table, $path);
            $this->line(sprintf('%-32s %6d', $table, $n));
        }

        Schema::enableForeignKeyConstraints();
        $this->info('Ferdig. Brukerne må sette nytt passord (php artisan gaula:passord <e-post>).');

        return self::SUCCESS;
    }

    /** @return iterable<array<string, mixed>> */
    private function rader(string $path): iterable
    {
        $fh = fopen($path, 'r');
        while (($linje = fgets($fh)) !== false) {
            if (trim($linje) !== '') {
                yield json_decode($linje, true);
            }
        }
        fclose($fh);
    }

    private function settInn(string $table, string $path): int
    {
        $n = 0;
        $bunt = [];
        foreach ($this->rader($path) as $rad) {
            $bunt[] = $rad;
            if (count($bunt) >= 500) {
                DB::table($table)->insert($bunt);
                $n += count($bunt);
                $bunt = [];
            }
        }
        if ($bunt) {
            DB::table($table)->insert($bunt);
            $n += count($bunt);
        }

        return $n;
    }

    /** @return list<array<string, mixed>> */
    private function lesBrukere(string $path): array
    {
        return iterator_to_array($this->rader($path), false);
    }

    private function settInnBrukere(array $brukere): void
    {
        DB::table('users')->truncate();
        $brukt = [];
        foreach ($brukere as $b) {
            $navn = trim(($b['first_name'] ?? '').' '.($b['last_name'] ?? '')) ?: $b['username'];
            $epost = strtolower(trim($b['email'] ?? ''));
            if ($epost === '' || isset($brukt[$epost])) {
                $epost = strtolower($b['username']).'@gaula.local';
            }
            $brukt[$epost] = true;
            DB::table('users')->insert([
                'id' => $b['id'],
                'name' => $navn,
                'email' => $epost,
                'password' => Hash::make(Str::random(40)), // Djangos hash kan ikke brukes her
                'created_at' => $b['date_joined'],
                'updated_at' => $b['date_joined'],
            ]);
        }
        $this->line(sprintf('%-32s %6d', 'users', count($brukere)));
    }
}
