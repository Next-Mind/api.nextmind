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
        Schema::create('user_phones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('label',40);
            $table->string('country_code',4);
            $table->string('area_code',length: 3);
            $table->string('number',12);
            $table->boolean('is_whatsapp');
            $table->boolean('is_primary');
            $table->timestamps();

            //telefone único
            $table->unique(['country_code','area_code','number']);

            //index para performance
            $table->index(['user_id','is_primary'],'idx_phones_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_phones');
    }
};
