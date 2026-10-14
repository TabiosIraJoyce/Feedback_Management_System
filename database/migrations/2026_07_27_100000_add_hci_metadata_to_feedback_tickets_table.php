<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback_tickets', function (Blueprint $table) {
            $table->json('hci_metadata')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('feedback_tickets', function (Blueprint $table) {
            $table->dropColumn('hci_metadata');
        });
    }
};
