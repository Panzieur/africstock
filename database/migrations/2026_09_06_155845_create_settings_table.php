<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Identification de l'entreprise
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_logo')->nullable();

            // Configuration générale
            $table->string('currency')->default('FCFA');
            $table->string('timezone')->default('Africa/Ouagadougou');
            $table->string('date_format')->default('d/m/Y');
            $table->string('language')->default('fr');

            // Ventes
            $table->boolean('allow_credit_sales')->default(true);
            $table->boolean('allow_negative_stock')->default(false);
            $table->boolean('allow_partial_payments')->default(true);
            $table->unsignedInteger('credit_due_days')->default(30);
            $table->string('sale_prefix')->default('VTE');

            // Stock
            $table->boolean('allow_stock_adjustments')->default(true);
            $table->unsignedInteger('default_minimum_stock')->default(5);

            // Notifications
            $table->boolean('low_stock_notifications')->default(true);
            $table->boolean('credit_notifications')->default(true);

            // Système
            $table->boolean('maintenance_mode')->default(false);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
