<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache & clean
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::truncate();
        Role::truncate();

        // Permissions définies
        $permissions = [
            'pharmacie.view', 'pharmacie.create', 'pharmacie.update',
            'commande.view', 'commande.create', 'commande.update',
            'carte.view',
            'filtre.advanced',
            'notification.read',
            'rapport.generate',
            'log.view',
            'csv.import', 'csv.export',
            'zone.manage',
            'document.upload',
            'geocodage.auto',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Rôles et attributions
        $roles = [
            'admin' => $permissions,

            'commercial' => [
                'pharmacie.view', 'pharmacie.create', 'pharmacie.update',
                'commande.view', 'commande.create', 'commande.update',
            ],

            'logistique' => [
                'commande.view', 'commande.update',
            ],

            'administratif' => [
                'pharmacie.view',
                'filtre.advanced',
                'rapport.generate',
                'csv.import', 'csv.export',
                'document.upload',
            ],

            'standard' => [
                'pharmacie.view',
                'commande.view',
                'carte.view',
                'filtre.advanced',
            ],
        ];

        foreach ($roles as $name => $perms) {
            $role = Role::create(['name' => $name]);
            $role->syncPermissions($perms);
        }
    }
}

