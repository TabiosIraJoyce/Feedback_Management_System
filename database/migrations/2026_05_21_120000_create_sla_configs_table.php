<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_configs', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->integer('hours')->default(24)->comment('SLA in hours');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_configs');
    }
};
