<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table): void {
            // Workflow statuses have expanded over time; a string column remains compatible with
            // existing values while supporting the already-used reopened/escalated statuses.
            $table->string('status')->default('open')->change();
            $table->unsignedTinyInteger('escalation_level')->default(0)->after('status');
        });

        Schema::table('complaint_status_histories', function (Blueprint $table): void {
            $table->string('action')->nullable()->after('status');
            $table->string('previous_status')->nullable()->after('action');
            $table->string('new_status')->nullable()->after('previous_status');
            $table->foreignId('actor_user_id')->nullable()->after('complaint_id')->constrained('users')->nullOnDelete();
            $table->foreignId('from_assigned_user_id')->nullable()->after('actor_user_id')->constrained('users')->nullOnDelete();
            $table->foreignId('to_assigned_user_id')->nullable()->after('from_assigned_user_id')->constrained('users')->nullOnDelete();
            $table->text('escalation_reason')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('complaint_status_histories', function (Blueprint $table): void {
            $table->dropForeign(['actor_user_id']);
            $table->dropForeign(['from_assigned_user_id']);
            $table->dropForeign(['to_assigned_user_id']);
            $table->dropColumn(['action', 'previous_status', 'new_status', 'actor_user_id', 'from_assigned_user_id', 'to_assigned_user_id', 'escalation_reason']);
        });

        Schema::table('complaints', function (Blueprint $table): void {
            $table->dropColumn('escalation_level');
        });
    }
};
