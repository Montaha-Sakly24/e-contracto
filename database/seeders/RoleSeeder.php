<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Role::create(['name' => 'Superadmin']);
        $admin = Role::create(['name' => 'Admin']);
        $simpleuser = Role::create(['name' => 'Simple User']);

        $superadmin->givePermissionTo([
            'create-user',
            'show-liste-pser',
            'show-user-profil',
            'delete-user-profil',
            'create-contract-category',
            'show-liste-contract-category',
            'show-contract-category',
            'edit-contract-category',
            'delete-contract-category'
        ]);

        $admin->givePermissionTo([
            'show-liste-pser',
            'show-user-profil',
            'create-contract-category',
            'show-liste-contract-category',
            'show-contract-category',
            'edit-contract-category',
            'delete-contract-category'
        ]);

        $simpleuser->givePermissionTo([
            'show-liste-contract-category',
            'show-contract-category',
        ]);
    }
}
