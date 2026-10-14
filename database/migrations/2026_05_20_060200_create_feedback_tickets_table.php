<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('reference_no')->unique();
            $table->string('satisfaction')->nullable();
            $table->string('category')->default('general');
            $table->text('comment');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->json('photos')->nullable();
            $table->enum('status', ['pending', 'under_review', 'assigned', 'in_progress', 'escalated', 'resolved', 'closed'])->default('pending');
            $table->boolean('is_anonymous')->default(true);
            $table->string('source')->default('qr');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_tickets');
    }
};
