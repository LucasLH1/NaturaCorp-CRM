<?php

namespace App\Services;

use App\Models\Pharmacie;
use Illuminate\Support\Facades\Http;

class GeocodageService
{
    public function geocoder(Pharmacie $pharmacie): bool
    {
        $adresse = "{$pharmacie->adresse}, {$pharmacie->code_postal} {$pharmacie->ville}, France";

        $response = Http::withHeaders([
            'User-Agent' => 'NaturaCorpCRM/1.0 (contact@natura.test)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $adresse,
            'format' => 'json',
            'limit' => 1,
        ]);

        if ($response->failed() || empty($response[0])) {
            return false;
        }

        $data = $response[0];

        $pharmacie->latitude = (float) $data['lat'];
        $pharmacie->longitude = (float) $data['lon'];

        return true;
    }
}
