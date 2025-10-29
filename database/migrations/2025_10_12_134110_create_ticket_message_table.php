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
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('ticket_id')
                ->constrained('tickets')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignUuid('author_id')
                ->nullable()
                ->constrained('users');
            $table->unsignedBigInteger('seq');
            $table->longText('body');
            $table->boolean('is_internal')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index('ticket_id');
            $table->index(['ticket_id', 'seq']);
            $table->index(['ticket_id', 'created_at']);
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_message');
    }
};
