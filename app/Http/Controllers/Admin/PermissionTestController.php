<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermissionTestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permissions = [
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            'stock.view',
            'stock.entry',
            'stock.exit',
            'stock.adjust',
            'stock.inventory',

            'sales.view',
            'sales.create',
            'sales.edit',
            'sales.delete',

            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            'reports.view',
            'reports.export',

            'settings.view',
            'settings.edit',
        ];

        return view('admin.permissions-test', compact(
            'user',
            'permissions'
        ));
    }
}
