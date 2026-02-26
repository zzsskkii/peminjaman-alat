<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('category')
                ->constrained('item_categories')
                ->nullOnDelete();
        });

        $now = now();
        $categoryNames = DB::table('items')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->map(static fn ($name): string => trim((string) $name))
            ->filter(static fn (string $name): bool => $name !== '')
            ->values();

        foreach ($categoryNames as $categoryName) {
            DB::table('item_categories')->insertOrIgnore([
                'name' => $categoryName,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $categories = DB::table('item_categories')->pluck('id', 'name');

        foreach ($categories as $name => $id) {
            DB::table('items')
                ->where('category', $name)
                ->update(['category_id' => $id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
