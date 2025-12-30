<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'entrada' ou 'saida'
            $table->date('due_date')->nullable();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->string('situation'); // 'pago' ou 'a_pagar'
            $table->date('payment_date')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};