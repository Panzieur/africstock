<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Client facultatif : vente comptoir possible
            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Vendeur ayant enregistré la vente
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('reference')->unique();

            $table->enum('status', [
                'completed',
                'cancelled',
            ])->default('completed');

            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('total', 12, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamp('sold_at')->nullable();

            $table->timestamps();

            $table->index('customer_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('sold_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
