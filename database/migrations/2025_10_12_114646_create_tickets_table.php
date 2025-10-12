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
        
        
        Schema::create('tickets', function (Blueprint $table) {
            
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('ticket_number')->unique();
            $table->string('subject');
            
            $table->foreignUuid('opened_by_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('requester_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->foreignUuid('ticket_category_id')->constrained('ticket_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUuid('ticket_subcategory_id')->nullable()->constrained('ticket_subcategories')->nullOnDelete();
            $table->foreignUuid('ticket_status_id')->constrained('ticket_statuses')->cascadeOnUpdate()->restrictOnDelete();
            
            $table->timestamp('first_response_due_at')->nullable();
            $table->timestamp('resolution_due_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('attachments_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['ticket_status_id', 'assigned_to_id'], 'idx_tickets_status_assignee');
        });
        
        Schema::create('ticket_counters', function (Blueprint $table) {
            $table->id(); // 1 linha só
            $table->unsignedBigInteger('last_number')->default(0);
            $table->timestamps();
        });
    }
    
    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
