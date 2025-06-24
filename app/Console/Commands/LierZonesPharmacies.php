<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pharmacie;
use App\Models\Zone;

class LierZonesPharmacies extends Command
{
    protected $signature = 'pharmacies:lier-zones';
    protected $description = 'Associe chaque pharmacie existante à une zone de prospection via le code postal';

    public function handle(): void
    {
        $pharmacies = Pharmacie::all();
        $liées = 0;
        $sans_zone = 0;

        foreach ($pharmacies as $pharmacie) {
            if (!$pharmacie->code_postal) {
                $sans_zone++;
                continue;
            }

            $dep = substr($pharmacie->code_postal, 0, 2);

            if (str_starts_with($pharmacie->code_postal, '20')) {
                $dep = (intval($pharmacie->code_postal) < 20200) ? '2A' : '2B';
            }

            $zone = Zone::where('code_departement', $dep)->first();

            if ($zone) {
                $pharmacie->zone_id = $zone->id;
                $pharmacie->save();
                $liées++;
            } else {
                $sans_zone++;
            }
        }

        $this->info("Pharmacies liées à une zone : {$liées}");
        $this->warn("Pharmacies sans zone trouvée : {$sans_zone}");
    }
}
