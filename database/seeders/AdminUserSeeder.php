<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le rôle Administrateur s'il n'existe pas encore
        $adminRole = Role::firstOrCreate(['name' => 'Administrateur']);

        // Créer le compte admin s'il n'existe pas déjà
        $admin = User::firstOrCreate(
            ['email' => 'koukpessovenceslas@gmail.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('africstok2026@'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole($adminRole);
    }
}
