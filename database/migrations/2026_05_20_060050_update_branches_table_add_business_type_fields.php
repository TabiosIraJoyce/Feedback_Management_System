<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->foreignId('business_type_id')->nullable()->after('description')->constrained()->nullOnDelete();
            $table->string('qr_token', 64)->nullable()->unique()->after('business_type_id');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('qr_token');
            $table->string('logo')->nullable()->after('status');
            $table->text('address')->nullable()->after('slug');
            $table->string('contact_number', 50)->nullable()->after('address');
            $table->string('email')->nullable()->after('contact_number');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['business_type_id']);
            $table->dropColumn(['business_type_id', 'qr_token', 'status', 'logo', 'address', 'contact_number', 'email']);
        });
    }
};
