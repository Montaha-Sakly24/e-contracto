<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Créer un super administrateur
        $superadmin = User::create([
            'name' => 'Montaha',
            'email' => 'saklymontaha@gmail.com',
            'password' => Hash::make('montaha123')
        ]);

        $superadmin->assignRole('Superadmin');

        //Créer un administrateur
        $admin = User::create([
            'name' => 'admin',
            'email' => 'saklymontaha5@gmail.com',
            'phone'=> '98254635',
            'address'=>'Monastir',
            'password' => Hash::make('montaha123')
        ]);

        $admin->assignRole('Admin');

        //Créer un simple utilisateur
        $simpleuser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'phone'=> '53647925',
            'address'=>'Monastir',
            'password' => Hash::make('montaha123')
        ]);

        $simpleuser->assignRole('Simple User');
    }
}
