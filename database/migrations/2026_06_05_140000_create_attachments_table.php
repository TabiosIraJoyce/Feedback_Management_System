<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable');
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('disk')->default('public');
            $table->string('directory')->nullable();
            $table->string('path');
            $table->string('stored_name');
            $table->string('original_name');
            $table->string('extension', 20);
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('size_bytes');
            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
