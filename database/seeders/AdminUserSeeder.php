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
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@africstock.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('ChangeMoi123!'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole($superAdminRole);
    }
}
