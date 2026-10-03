<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SettPassord extends Command
{
    protected $signature = 'gaula:passord {epost}';

    protected $description = 'Setter nytt passord for en bruker (spør etter passordet)';

    public function handle(): int
    {
        $bruker = User::where('email', $this->argument('epost'))->first();
        if (! $bruker) {
            $this->error('Fant ingen bruker med den e-posten.');

            return self::FAILURE;
        }
        $passord = $this->secret('Nytt passord (minst 12 tegn)');
        if (strlen((string) $passord) < 12) {
            $this->error('For kort.');

            return self::FAILURE;
        }
        $bruker->update(['password' => Hash::make($passord)]);
        $this->info("Passordet til {$bruker->name} er oppdatert.");

        return self::SUCCESS;
    }
}
