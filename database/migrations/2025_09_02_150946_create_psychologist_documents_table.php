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
        Schema::create('psychologist_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('psychologist_profile_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignUuid('user_file_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->enum('type',['crp_card','id_front','id_back','proof_of_address'])->index();
            $table->enum('status', ['pending','approved','rejected'])->default('pending')->index();
            $table->foreignUuid('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('reviewed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            
            $table->unique(['psychologist_profile_id','type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychologist_documents');
    }
};
