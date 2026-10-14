<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_escalations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('note')->nullable();
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_escalations');
    }
};
