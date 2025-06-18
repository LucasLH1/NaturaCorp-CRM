<?php

namespace App\Console\Commands;

use App\Models\Pharmacie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GeocoderPharmacies extends Command
{
    protected $signature = 'pharmacies:geocode';
    protected $description = 'Géocoder les pharmacies avec adresse et sans coordonnées GPS';

    public function handle(): void
    {
        $pharmacies = Pharmacie::whereNull('latitude')
            ->orWhereNull('longitude')
            ->get();

        if ($pharmacies->isEmpty()) {
            $this->info('Toutes les pharmacies ont déjà des coordonnées.');
            return;
        }

        foreach ($pharmacies as $pharmacie) {
            $adresse = "{$pharmacie->adresse}, {$pharmacie->code_postal} {$pharmacie->ville}, France";

            $this->info("Géocodage : {$pharmacie->nom} -> $adresse");

            $response = Http::withHeaders([
                'User-Agent' => 'NaturaCorpCRM/1.0 (contact@natura.test)',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $adresse,
                'format' => 'json',
                'limit' => 1,
            ]);


            if ($response->failed() || empty($response[0])) {
                $this->warn("Aucune coordonnée trouvée.");
                continue;
            }

            $data = $response[0];

            $pharmacie->latitude = (float) $data['lat'];
            $pharmacie->longitude = (float) $data['lon'];
            $pharmacie->save();


            $this->info("OK -> lat: {$data['lat']} / lon: {$data['lon']}");
            sleep(1); // Respect Nominatim rate limit
        }

        $this->info('Géocodage terminé.');
    }
}
