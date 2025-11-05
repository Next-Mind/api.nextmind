<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event', 100);
            $table->nullableUuidMorphs('auditable');
            $table->nullableUuidMorphs('user');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->json('extra')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index('event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
