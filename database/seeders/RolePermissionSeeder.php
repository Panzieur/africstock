<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Produits
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Catégories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Stock
            'stock.view',
            'stock.entry',
            'stock.exit',
            'stock.adjust',
            'stock.inventory',

            // Ventes
            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.delete',

            // Paiements
            'payments.create',

            // Remboursements
            'refunds.create',

            // Clients
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            // Utilisateurs
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Rapports généraux
            'reports.view',
            'reports.export',

            // Rapports spécifiques
            'reports.sales.view',
            'reports.stock.view',
            'reports.financial.view',

            // Journal des activités
            'activities.view',
            'activities.all.view',
            'activities.sales.view',
            'activities.stock.view',
            'activities.payments.view',

            // Paramètres
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Rôles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $stockManager = Role::firstOrCreate([
            'name' => 'gestionnaire-stock',
            'guard_name' => 'web',
        ]);

        $seller = Role::firstOrCreate([
            'name' => 'vendeur',
            'guard_name' => 'web',
        ]);

        $accountant = Role::firstOrCreate([
            'name' => 'comptable',
            'guard_name' => 'web',
        ]);

        $viewer = Role::firstOrCreate([
            'name' => 'lecteur',
            'guard_name' => 'web',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::all()
        );


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions([

            // Produits
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Catégories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Stock
            'stock.view',
            'stock.entry',
            'stock.exit',
            'stock.adjust',
            'stock.inventory',

            // Ventes
            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.delete',

            // Paiements
            'payments.create',

            // Remboursements
            'refunds.create',

            // Clients
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            // Utilisateurs
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Rapports
            'reports.view',
            'reports.export',
            'reports.sales.view',
            'reports.stock.view',
            'reports.financial.view',

            // Activités
            'activities.view',
            'activities.all.view',

            // Paramètres
            'settings.view',
            'settings.edit',
        ]);


        /*
        |--------------------------------------------------------------------------
        | GESTIONNAIRE DE STOCK
        |--------------------------------------------------------------------------
        */

        $stockManager->syncPermissions([

            // Produits
            'products.view',
            'products.create',
            'products.edit',

            // Catégories
            'categories.view',

            // Stock
            'stock.view',
            'stock.entry',
            'stock.exit',
            'stock.adjust',
            'stock.inventory',

            // Rapports
            'reports.view',
            'reports.export',
            'reports.stock.view',

            // Activités
            'activities.view',
            'activities.stock.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VENDEUR
        |--------------------------------------------------------------------------
        */

        $seller->syncPermissions([

            // Produits
            'products.view',

            // Stock
            'stock.view',

            // Ventes
            'sales.view',
            'sales.create',

            // Paiements
            'payments.create',

            // Clients
            'customers.view',
            'customers.create',
            'customers.edit',

            // Activités
            'activities.view',
            'activities.sales.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | COMPTABLE
        |--------------------------------------------------------------------------
        */

        $accountant->syncPermissions([

            // Produits
            'products.view',

            // Stock
            'stock.view',

            // Ventes
            'sales.view',

            // Clients
            'customers.view',

            // Remboursements
            'refunds.create',

            // Rapports
            'reports.view',
            'reports.export',
            'reports.sales.view',
            'reports.financial.view',

            // Activités
            'activities.view',
            'activities.sales.view',
            'activities.payments.view',
        ]);


        /*
        |--------------------------------------------------------------------------
        | LECTEUR
        |--------------------------------------------------------------------------
        */

        $viewer->syncPermissions([

            // Produits
            'products.view',

            // Catégories
            'categories.view',

            // Stock
            'stock.view',

            // Ventes
            'sales.view',

            // Clients
            'customers.view',

            // Rapports
            'reports.view',
            'reports.sales.view',
            'reports.stock.view',
            'reports.financial.view',
        ]);
    }
}