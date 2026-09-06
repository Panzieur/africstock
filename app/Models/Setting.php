<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_logo',

        'currency',
        'timezone',
        'date_format',
        'language',

        'allow_credit_sales',
        'allow_negative_stock',
        'allow_partial_payments',
        'credit_due_days',
        'sale_prefix',

        'allow_stock_adjustments',
        'default_minimum_stock',

        'low_stock_notifications',
        'credit_notifications',

        'maintenance_mode',
    ];

    protected $casts = [
        'allow_credit_sales' => 'boolean',
        'allow_negative_stock' => 'boolean',
        'allow_partial_payments' => 'boolean',
        'allow_stock_adjustments' => 'boolean',
        'low_stock_notifications' => 'boolean',
        'credit_notifications' => 'boolean',
        'maintenance_mode' => 'boolean',
    ];
}