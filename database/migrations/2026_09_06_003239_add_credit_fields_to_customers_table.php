<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('credit_allowed')
                ->default(false)
                ->after('is_active');

            $table->decimal('credit_limit', 15, 2)
                ->default(0)
                ->after('credit_allowed');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'credit_allowed',
                'credit_limit',
            ]);
        });
    }
};
