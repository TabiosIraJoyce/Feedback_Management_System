<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('report_type');
            $table->string('format')->default('pdf');
            $table->string('frequency')->default('weekly');
            $table->json('filters')->nullable();
            $table->json('recipients')->nullable();
            $table->timestamp('next_run_at')->nullable()->index();
            $table->timestamp('last_run_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_reports');
    }
};
