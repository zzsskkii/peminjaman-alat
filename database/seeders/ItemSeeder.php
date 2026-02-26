<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ItemCategory::query()
            ->pluck('id', 'name');

        Item::query()->upsert([
            [
                'code' => 'LAP-001',
                'name' => 'Laptop Asus 14"',
                'category' => 'Laptop',
                'category_id' => $categories['Laptop'] ?? null,
                'stock' => 6,
                'available_stock' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LAP-002',
                'name' => 'Laptop Lenovo 14"',
                'category' => 'Laptop',
                'category_id' => $categories['Laptop'] ?? null,
                'stock' => 4,
                'available_stock' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'LCD-001',
                'name' => 'Proyektor Epson XGA',
                'category' => 'LCD',
                'category_id' => $categories['LCD'] ?? null,
                'stock' => 3,
                'available_stock' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RMT-001',
                'name' => 'Remote Proyektor',
                'category' => 'Remote',
                'category_id' => $categories['Remote'] ?? null,
                'stock' => 5,
                'available_stock' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HDM-001',
                'name' => 'Kabel HDMI 10m',
                'category' => 'Kabel',
                'category_id' => $categories['Kabel'] ?? null,
                'stock' => 10,
                'available_stock' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SPK-001',
                'name' => 'Speaker Portable',
                'category' => 'Audio',
                'category_id' => $categories['Audio'] ?? null,
                'stock' => 2,
                'available_stock' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['code'], ['name', 'category', 'category_id', 'stock', 'available_stock', 'is_active', 'updated_at']);
    }
}
