<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            // Produit concerné
            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            // Utilisateur ayant effectué l'opération
            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            // Type de mouvement
            $table->enum('type', [
                'entry',
                'exit',
                'adjustment',
            ]);

            // Quantité du mouvement
            $table->integer('quantity');

            // Stock avant et après l'opération
            $table->integer('stock_before');
            $table->integer('stock_after');

            // Motif / commentaire
            $table->text('reason')->nullable();

            $table->timestamps();

            // Index pour accélérer les recherches
            $table->index(['product_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
