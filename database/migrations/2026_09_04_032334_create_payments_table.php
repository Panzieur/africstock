<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('method', [
                'cash',
                'mobile_money',
                'bank_transfer',
                'card',
            ]);

            $table->decimal('amount', 12, 2);

            $table->string('reference')->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index('sale_id');
            $table->index('method');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
