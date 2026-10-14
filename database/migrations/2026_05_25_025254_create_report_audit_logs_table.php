<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('scheduled_report_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('report_type');
            $table->string('format')->nullable();
            $table->string('status')->default('success');
            $table->json('filters')->nullable();
            $table->json('meta')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('generated_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_audit_logs');
    }
};
