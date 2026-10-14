<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('code');
        });

        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('type', ['branch', 'department']);
            $table->string('code')->unique();
            $table->string('path')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('scan_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamp('last_downloaded_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('qr_code_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->string('path')->nullable();
            $table->timestamp('scanned_at');
            $table->timestamps();

            $table->index(['qr_code_id', 'scanned_at']);
        });

        Schema::table('feedback_tickets', function (Blueprint $table) {
            $table->foreignId('qr_code_id')->nullable()->after('department_id')->constrained('qr_codes')->nullOnDelete();
            $table->string('subject')->nullable()->after('category');
            $table->text('description')->nullable()->after('comment');
            $table->unsignedTinyInteger('rating')->nullable()->after('description');
        });

        DB::table('departments')->orderBy('id')->get()->each(function ($department): void {
            $base = Str::slug($department->code ?: $department->name);
            $base = $base !== '' ? $base : 'department';
            $slug = $base;
            $counter = 1;

            while (DB::table('departments')
                ->where('branch_id', $department->branch_id)
                ->where('slug', $slug)
                ->where('id', '!=', $department->id)
                ->exists()) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            DB::table('departments')->where('id', $department->id)->update(['slug' => $slug]);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->unique(['branch_id', 'slug']);
        });

        $timestamp = Carbon::now();

        DB::table('branches')->orderBy('id')->get()->each(function ($branch) use ($timestamp): void {
            DB::table('qr_codes')->updateOrInsert(
                ['type' => 'branch', 'branch_id' => $branch->id],
                [
                    'department_id' => null,
                    'code' => 'GLD-'.Str::upper(preg_replace('/[^A-Za-z0-9]+/', '-', $branch->code)),
                    'path' => 'feedback/'.$branch->slug,
                    'status' => $branch->status === 'active' ? 'active' : 'inactive',
                    'updated_at' => $timestamp,
                    'created_at' => $timestamp,
                ]
            );
        });

        DB::table('departments')
            ->join('branches', 'branches.id', '=', 'departments.branch_id')
            ->select('departments.*', 'branches.code as branch_code', 'branches.slug as branch_slug')
            ->orderBy('departments.id')
            ->get()
            ->each(function ($department) use ($timestamp): void {
                DB::table('qr_codes')->updateOrInsert(
                    ['type' => 'department', 'department_id' => $department->id],
                    [
                        'branch_id' => $department->branch_id,
                        'code' => 'GLD-'
                            .Str::upper(preg_replace('/[^A-Za-z0-9]+/', '-', $department->branch_code))
                            .'-'
                            .Str::upper(preg_replace('/[^A-Za-z0-9]+/', '-', $department->code)),
                        'path' => 'feedback/'.$department->branch_slug.'/'.$department->slug,
                        'status' => $department->status === 'active' ? 'active' : 'inactive',
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ]
                );
            });
    }

    public function down(): void
    {
        Schema::table('feedback_tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('qr_code_id');
            $table->dropColumn(['subject', 'description', 'rating']);
        });

        Schema::dropIfExists('qr_code_scans');
        Schema::dropIfExists('qr_codes');

        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique('departments_branch_id_slug_unique');
            $table->dropColumn('slug');
        });
    }
};
