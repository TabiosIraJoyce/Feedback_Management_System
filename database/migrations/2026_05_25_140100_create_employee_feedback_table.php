<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_no')->unique();
            $table->string('employee_name')->nullable();
            $table->string('category', 80);
            $table->string('subject');
            $table->text('description');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->string('attachment_mime_type')->nullable();
            $table->string('priority', 20)->default('medium');
            $table->string('status', 30)->default('pending')->index();
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedInteger('reopened_count')->default(0);
            $table->longText('internal_notes')->nullable();
            $table->timestamp('last_status_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_feedback');
    }
};
