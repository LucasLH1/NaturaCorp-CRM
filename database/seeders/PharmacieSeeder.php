<?php

namespace Database\Seeders;

use App\Models\Pharmacie;
use App\Models\Produit;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class PharmacieSeeder extends Seeder
{
    public function run(): void
    {
        // Produits de base si aucun n'existe
        if (Produit::count() === 0) {
            $produits = [
                ['nom' => 'Oméga-3 Premium',        'tarif_unitaire' => 18.50, 'stock' => 200, 'is_actif' => true],
                ['nom' => 'Vitamine D3 + K2',        'tarif_unitaire' => 14.90, 'stock' => 300, 'is_actif' => true],
                ['nom' => 'Curcuma & Poivre Noir',   'tarif_unitaire' => 16.00, 'stock' => 150, 'is_actif' => true],
                ['nom' => 'Magnésium Marin',          'tarif_unitaire' => 12.50, 'stock' => 250, 'is_actif' => true],
                ['nom' => 'Mélatonine & Passiflore', 'tarif_unitaire' => 11.90, 'stock' => 180, 'is_actif' => true],
                ['nom' => 'Probiotiques Pro 50',      'tarif_unitaire' => 24.90, 'stock' => 120, 'is_actif' => true],
                ['nom' => 'Vitamine C Liposomale',   'tarif_unitaire' => 22.00, 'stock' => 160, 'is_actif' => true],
                ['nom' => 'Zinc & Sélénium',          'tarif_unitaire' => 9.90,  'stock' => 200, 'is_actif' => true],
                ['nom' => 'Complexe B Actif',         'tarif_unitaire' => 13.50, 'stock' => 140, 'is_actif' => true],
            ];
            foreach ($produits as $p) {
                Produit::create($p);
            }
        }

        $pharmacies = [
            // Paris (zone_id 76)
            ['nom' => 'Pharmacie du Panthéon',       'siret' => '12345678901234', 'email' => 'pantheon@pharma.fr',     'telephone' => '01 43 26 11 22', 'adresse' => '15 Rue Soufflot',           'code_postal' => '75005', 'ville' => 'Paris',         'statut' => 'client_actif',    'commercial_id' => 2],
            ['nom' => 'Pharmacie de la Bastille',    'siret' => '23456789012345', 'email' => 'bastille@pharma.fr',     'telephone' => '01 43 07 31 02', 'adresse' => '5 Place de la Bastille',    'code_postal' => '75011', 'ville' => 'Paris',         'statut' => 'client_actif',    'commercial_id' => 2],
            ['nom' => 'Pharmacie Opéra',             'siret' => '34567890123456', 'email' => 'opera@pharma.fr',        'telephone' => '01 47 42 49 40', 'adresse' => '6 Boulevard des Capucines', 'code_postal' => '75009', 'ville' => 'Paris',         'statut' => 'prospect',        'commercial_id' => 2],
            ['nom' => 'Grande Pharmacie de Paris',   'siret' => '45678901234567', 'email' => 'gpp@pharma.fr',          'telephone' => '01 48 05 63 19', 'adresse' => '22 Rue du Faubourg',        'code_postal' => '75010', 'ville' => 'Paris',         'statut' => 'client_inactif',  'commercial_id' => 2],

            // Lyon (zone_id 70)
            ['nom' => 'Pharmacie Bellecour',         'siret' => '56789012345678', 'email' => 'bellecour@pharma.fr',    'telephone' => '04 78 42 12 12', 'adresse' => '2 Place Bellecour',         'code_postal' => '69002', 'ville' => 'Lyon',          'statut' => 'client_actif',    'commercial_id' => 3],
            ['nom' => 'Pharmacie de la Croix-Rousse','siret' => '67890123456789', 'email' => 'croixrousse@pharma.fr', 'telephone' => '04 78 28 44 21', 'adresse' => '18 Boulevard de la CR',     'code_postal' => '69004', 'ville' => 'Lyon',          'statut' => 'client_actif',    'commercial_id' => 3],
            ['nom' => 'Pharmacie Part-Dieu',         'siret' => '78901234567890', 'email' => 'partdieu@pharma.fr',     'telephone' => '04 72 34 78 90', 'adresse' => '17 Rue du Docteur Bouchut', 'code_postal' => '69003', 'ville' => 'Lyon',          'statut' => 'prospect',        'commercial_id' => 3],

            // Marseille (zone_id 13)
            ['nom' => 'Pharmacie du Vieux-Port',     'siret' => '89012345678901', 'email' => 'vieuxport@pharma.fr',   'telephone' => '04 91 33 45 67', 'adresse' => '4 Quai du Port',            'code_postal' => '13002', 'ville' => 'Marseille',     'statut' => 'client_actif',    'commercial_id' => 4],
            ['nom' => 'Pharmacie Castellane',        'siret' => '90123456789012', 'email' => 'castellane@pharma.fr',  'telephone' => '04 91 53 21 43', 'adresse' => '12 Rue de Rome',            'code_postal' => '13006', 'ville' => 'Marseille',     'statut' => 'client_actif',    'commercial_id' => 4],

            // Toulouse (zone_id 32)
            ['nom' => 'Pharmacie du Capitole',       'siret' => '01234567890123', 'email' => 'capitole@pharma.fr',    'telephone' => '05 61 21 33 44', 'adresse' => '1 Place du Capitole',       'code_postal' => '31000', 'ville' => 'Toulouse',      'statut' => 'client_actif',    'commercial_id' => 5],
            ['nom' => 'Pharmacie Saint-Cyprien',     'siret' => '11223344556677', 'email' => 'stcyprien@pharma.fr',   'telephone' => '05 61 53 77 12', 'adresse' => '28 Allées Charles-de-Fitte', 'code_postal' => '31300', 'ville' => 'Toulouse',     'statut' => 'prospect',        'commercial_id' => 5],

            // Bordeaux (zone_id 34)
            ['nom' => 'Pharmacie des Quinconces',    'siret' => '22334455667788', 'email' => 'quinconces@pharma.fr',  'telephone' => '05 56 44 21 33', 'adresse' => '5 Place des Quinconces',    'code_postal' => '33000', 'ville' => 'Bordeaux',      'statut' => 'client_actif',    'commercial_id' => 5],

            // Nice (zone_id 6)
            ['nom' => 'Pharmacie de la Promenade',   'siret' => '33445566778899', 'email' => 'promenade@pharma.fr',   'telephone' => '04 93 87 22 11', 'adresse' => '10 Promenade des Anglais',  'code_postal' => '06000', 'ville' => 'Nice',          'statut' => 'client_actif',    'commercial_id' => 4],

            // Nantes (zone_id 45)
            ['nom' => 'Pharmacie du Commerce',       'siret' => '44556677889900', 'email' => 'commerce@pharma.fr',    'telephone' => '02 40 35 11 22', 'adresse' => '3 Place du Commerce',       'code_postal' => '44000', 'ville' => 'Nantes',        'statut' => 'client_actif',    'commercial_id' => 3],
            ['nom' => 'Pharmacie Bouffay',           'siret' => '55667788990011', 'email' => 'bouffay@pharma.fr',     'telephone' => '02 40 47 66 33', 'adresse' => '7 Rue de la Bâclerie',      'code_postal' => '44000', 'ville' => 'Nantes',        'statut' => 'prospect',        'commercial_id' => 3],

            // Strasbourg (zone_id 68)
            ['nom' => 'Pharmacie de la Cathédrale',  'siret' => '66778899001122', 'email' => 'cathedrale@pharma.fr',  'telephone' => '03 88 32 21 44', 'adresse' => '2 Place de la Cathédrale',  'code_postal' => '67000', 'ville' => 'Strasbourg',    'statut' => 'client_actif',    'commercial_id' => 2],

            // Lille (zone_id 60)
            ['nom' => 'Pharmacie du Grand Place',    'siret' => '77889900112233', 'email' => 'grandplace@pharma.fr',  'telephone' => '03 20 54 33 11', 'adresse' => '8 Grand Place',             'code_postal' => '59000', 'ville' => 'Lille',         'statut' => 'client_actif',    'commercial_id' => 2],
            ['nom' => 'Pharmacie Vieux-Lille',       'siret' => '88990011223344', 'email' => 'vieuxlille@pharma.fr',  'telephone' => '03 20 74 12 56', 'adresse' => '14 Rue de la Monnaie',      'code_postal' => '59800', 'ville' => 'Lille',         'statut' => 'prospect',        'commercial_id' => 2],
        ];

        foreach ($pharmacies as $data) {
            Pharmacie::create($data);
        }
    }
}
