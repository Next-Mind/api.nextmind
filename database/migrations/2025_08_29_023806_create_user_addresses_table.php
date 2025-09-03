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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('label');
            
            $table->string('postal_code');
            $table->string('street');
            $table->string('complement')
                ->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('number');
            $table->string('state',2);
            $table->string('country',20);
            $table->boolean('is_primary');
            $table->timestamps();
            
            $table->index(['user_id','is_primary'],'idx_addresses_user');
        });
    }
    
    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
