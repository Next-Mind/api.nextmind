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
        Schema::table('psychologist_profiles', function (Blueprint $table) {
            $table->unique('user_id');
            
            $table->enum('status', ['pending','approved','rejected','suspended'])
            ->default('pending')
            ->after('bio');
            
            $table->timestamp('submitted_at')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('submitted_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            
            $table->foreignUuid('approved_by')
            ->nullable()
            ->after('rejected_at')
            ->constrained('users')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            
            $table->text('rejection_reason')->nullable()->after('approved_by');
            $table->timestamp('verified_at')->nullable()->change();
            
            $table->index('status');
            $table->index('submitted_at');
        });
    }
    
      /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('psychologist_profiles', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['submitted_at']);
            
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['status','submitted_at','approved_at','rejected_at','rejection_reason']);
            
            $table->timestamp('verified_at')->nullable(false)->change();

            $table->dropForeign(['user_id']); 
            
            $table->dropUnique('psychologist_profiles_user_id_unique');
            
            $table->foreign('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });;
    }
};
