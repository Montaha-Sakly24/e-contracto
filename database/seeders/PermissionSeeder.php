<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Réinitialiser les rôles et autorisations mis en cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        //Créer de permissions
        $permissions = [
            'create-user',
            'show-liste-pser',
            'show-user-profil',
            'delete-user-profil',
            'create-contract-category',
            'show-liste-contract-category',
            'show-contract-category',
            'edit-contract-category',
            'delete-contract-category'
         ];

          // Parcourir et insérer les permissions d'un tableau dans la table des permissions.
         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}
