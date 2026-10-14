<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 50);
            $table->text('description')->nullable();
            $table->string('qr_token')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->string('logo')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
