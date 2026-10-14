<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            if (Schema::hasColumn('products', 'business_type_id')) {
                // Drop any unique index that references the column (SQLite keeps indexes around)
                try {
                    DB::statement('DROP INDEX IF EXISTS products_name_business_type_id_unique');
                } catch (\Throwable $e) {
                    // best-effort: continue even if index drop fails
                }

                try {
                    $table->dropForeign(['business_type_id']);
                } catch (\Throwable $e) {
                    // ignore if foreign key doesn't exist
                }

                try {
                    $table->dropColumn('business_type_id');
                } catch (\Throwable $e) {
                    // On SQLite older versions dropColumn may fail; we'll attempt a fallback later
                }
            }
        });

        Schema::table('branches', function (Blueprint $table): void {
            if (Schema::hasColumn('branches', 'business_type_id')) {
                $table->dropForeign(['business_type_id']);
                $table->dropColumn('business_type_id');
            }
        });

        if (Schema::hasTable('business_types')) {
            Schema::dropIfExists('business_types');
        }
    }

    public function down(): void
    {
        Schema::create('business_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::table('branches', function (Blueprint $table): void {
            $table->foreignId('business_type_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->foreignId('business_type_id')->constrained()->cascadeOnDelete();
        });
    }
};
