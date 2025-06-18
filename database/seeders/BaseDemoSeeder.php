<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pharmacie;
use App\Models\Commande;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\StatutCommande;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BaseDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset base
        DB::table('commandes')->delete();
        DB::table('pharmacies')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('users')->delete();
        DB::table('roles')->delete();


        // 2. Créer rôles
        $roles = [
            'admin',
            'commercial',
            'communication',
            'logistique',
            'administratif',
            'standard',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

// Récupérer les rôles assignables
        $adminRole = Role::where('name', 'admin')->first();
        $commercialRole = Role::where('name', 'commercial')->first();


        // 3. Créer utilisateurs
        $admin = User::factory()->create([
            'name' => 'Admin Général',
            'email' => 'admin@natura.test',
            'is_active' => true,
        ]);
        $admin->assignRole($adminRole);

        $commerciaux = User::factory(5)->create([
            'is_active' => true,
        ]);

        $commerciaux->each(fn($user) => $user->assignRole($commercialRole));

        // 4. Créer pharmacies
        $villes = [
            ['Pharmacie République', '12 rue de la République', '75003', 'Paris'],
            ['Pharmacie Lafayette', '8 av. Jean Jaurès', '69007', 'Lyon'],
            ['Pharmacie Marché', '5 place du Marché', '33000', 'Bordeaux'],
            ['Pharmacie Gare', '1 rue de la Gare', '67000', 'Strasbourg'],
            ['Pharmacie Saint-Michel', '76 rue Saint-Michel', '35000', 'Rennes'],
            ['Pharmacie Centrale', '9 rue Nationale', '80000', 'Amiens'],
        ];

        $pharmacies = collect($villes)->map(function ($data) use ($commerciaux) {
            return Pharmacie::create([
                'nom' => $data[0],
                'adresse' => $data[1],
                'code_postal' => $data[2],
                'ville' => $data[3],
                'email' => fake()->unique()->safeEmail(),
                'telephone' => fake()->phoneNumber(),
                'statut' => collect(['client_actif', 'client_inactif', 'prospect'])->random(),
                'commercial_id' => $commerciaux->random()->id,
            ]);
        });

        // 5. Créer commandes
        foreach ($pharmacies as $pharmacie) {
            Commande::factory(rand(1, 3))->create([
                'pharmacie_id' => $pharmacie->id,
                'user_id' => $pharmacie->commercial_id,
                'date_commande' => Carbon::now()->subDays(rand(0, 60)),
                'statut' => collect(StatutCommande::cases())->random()->value,
            ]);
        }
    }
}
