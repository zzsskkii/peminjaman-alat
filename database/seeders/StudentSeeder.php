<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::query()->upsert([
            [
                'student_number' => 'SISWA-001',
                'card_uid' => 'CARD-001',
                'name' => 'Alya Pramesti',
                'class_name' => 'X RPL 1',
                'whatsapp_number' => '628123450001',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_number' => 'SISWA-002',
                'card_uid' => 'CARD-002',
                'name' => 'Bagas Mahendra',
                'class_name' => 'X RPL 1',
                'whatsapp_number' => '628123450002',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_number' => 'SISWA-003',
                'card_uid' => 'CARD-003',
                'name' => 'Citra Amelia',
                'class_name' => 'X TKJ 2',
                'whatsapp_number' => '628123450003',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_number' => 'SISWA-004',
                'card_uid' => 'CARD-004',
                'name' => 'Dimas Wicaksono',
                'class_name' => 'XI RPL 1',
                'whatsapp_number' => '628123450004',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_number' => 'SISWA-005',
                'card_uid' => 'CARD-005',
                'name' => 'Eka Putri Lestari',
                'class_name' => 'XI TKJ 1',
                'whatsapp_number' => '628123450005',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_number' => 'SISWA-006',
                'card_uid' => 'CARD-006',
                'name' => 'Fajar Nugroho',
                'class_name' => 'XII RPL 2',
                'whatsapp_number' => '628123450006',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['student_number'], ['card_uid', 'name', 'class_name', 'whatsapp_number', 'is_active', 'updated_at']);
    }
}
