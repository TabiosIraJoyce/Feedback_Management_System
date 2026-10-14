<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->string('sentiment', 20)->nullable()->after('message');
        });

        Schema::table('complaints', function (Blueprint $table): void {
            $table->string('sentiment', 20)->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table): void {
            $table->dropColumn('sentiment');
        });

        Schema::table('complaints', function (Blueprint $table): void {
            $table->dropColumn('sentiment');
        });
    }
};
