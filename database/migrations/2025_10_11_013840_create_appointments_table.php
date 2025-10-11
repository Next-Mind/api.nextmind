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
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('availability_id')
                ->constrained('availabilities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignUuid('psychologist_id')
                ->constrained('users') 
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status', ['pending', 'scheduled', 'completed', 'canceled', 'no_show'])
                  ->default('pending');
            $table->text('description')->nullable();

            $table->timestamps();

            // Índices úteis
            $table->index(['psychologist_id', 'status']);
            $table->index(['user_id', 'status']);

            // Evita duplo agendamento na mesma disponibilidade
            $table->unique('availability_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
