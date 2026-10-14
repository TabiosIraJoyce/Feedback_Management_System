<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('branch_id')->constrained()->nullOnDelete();
            $table->text('resolution_summary')->nullable()->after('message');
            $table->timestamp('resolved_at')->nullable()->after('resolution_summary');
            $table->boolean('allow_public_reopen')->default(true)->after('resolved_at');
            $table->unsignedTinyInteger('satisfaction_rating')->nullable()->after('allow_public_reopen');
            $table->text('satisfaction_comment')->nullable()->after('satisfaction_rating');
            $table->timestamp('satisfaction_submitted_at')->nullable()->after('satisfaction_comment');
            $table->timestamp('public_reopened_at')->nullable()->after('satisfaction_submitted_at');
        });

        Schema::table('complaint_status_histories', function (Blueprint $table) {
            $table->string('title')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('complaint_status_histories', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn([
                'resolution_summary',
                'resolved_at',
                'allow_public_reopen',
                'satisfaction_rating',
                'satisfaction_comment',
                'satisfaction_submitted_at',
                'public_reopened_at',
            ]);
        });
    }
};
