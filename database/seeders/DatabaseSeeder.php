<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'admin@peminjamanalat.local',
        ], [
            'name' => 'Admin Peminjaman',
            'password' => 'admin12345',
            'is_admin' => true,
        ]);

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->call([
            StudentSeeder::class,
            ItemCategorySeeder::class,
            ItemSeeder::class,
            LoanSeeder::class,
        ]);
    }
}
