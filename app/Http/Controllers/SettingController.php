<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Afficher les paramètres.
     */
    public function index()
    {
        $settings = Setting::first();

        // Créer la configuration par défaut si elle n'existe pas
        if (!$settings) {
            $settings = Setting::create([
                'company_name' => 'AfricStock',
                'currency' => 'FCFA',
                'timezone' => 'Africa/Ouagadougou',
                'date_format' => 'd/m/Y',
                'language' => 'fr',
                'allow_credit_sales' => true,
                'allow_negative_stock' => false,
                'allow_partial_payments' => true,
                'credit_due_days' => 30,
                'sale_prefix' => 'VTE',
                'allow_stock_adjustments' => true,
                'default_minimum_stock' => 5,
                'low_stock_notifications' => true,
                'credit_notifications' => true,
                'maintenance_mode' => false,
            ]);
        }

        return view('settings.index', compact('settings'));
    }

    /**
     * Enregistrer les paramètres.
     */
    public function update(Request $request)
    {
        $settings = Setting::first();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_email' => ['nullable', 'email', 'max:255'],

            'currency' => ['required', 'string', 'max:20'],
            'timezone' => ['required', 'string', 'max:100'],
            'date_format' => ['required', 'string', 'max:30'],
            'language' => ['required', 'string', 'max:10'],

            'allow_credit_sales' => ['nullable', 'boolean'],
            'allow_negative_stock' => ['nullable', 'boolean'],
            'allow_partial_payments' => ['nullable', 'boolean'],
            'credit_due_days' => ['required', 'integer', 'min:1', 'max:365'],
            'sale_prefix' => ['required', 'string', 'max:20'],

            'allow_stock_adjustments' => ['nullable', 'boolean'],
            'default_minimum_stock' => ['required', 'integer', 'min:0'],

            'low_stock_notifications' => ['nullable', 'boolean'],
            'credit_notifications' => ['nullable', 'boolean'],

            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        $booleanFields = [
            'allow_credit_sales',
            'allow_negative_stock',
            'allow_partial_payments',
            'allow_stock_adjustments',
            'low_stock_notifications',
            'credit_notifications',
            'maintenance_mode',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }

        $settings->update($validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Les paramètres ont été enregistrés avec succès.');
    }
}