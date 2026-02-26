<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        ItemCategory::query()->upsert([
            ['name' => 'Laptop', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LCD', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Remote', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kabel', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Audio', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ], ['name'], ['is_active', 'updated_at']);
    }
}
