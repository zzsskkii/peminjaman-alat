<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::query()->where('card_uid', 'CARD-001')->first();
        $laptop = Item::query()->where('code', 'LAP-001')->first();
        $remote = Item::query()->where('code', 'RMT-001')->first();

        if (! $student || ! $laptop || ! $remote) {
            return;
        }

        DB::transaction(function () use ($student, $laptop, $remote): void {
            $loan = Loan::query()->firstOrCreate(
                ['notes' => 'seed-demo-active-loan'],
                [
                    'student_id' => $student->id,
                    'borrowed_at' => now()->subHour(),
                    'due_at' => now()->setTime(16, 0),
                    'status' => 'borrowed',
                ]
            );

            $createdLaptopItem = LoanItem::query()->firstOrCreate(
                ['loan_id' => $loan->id, 'item_id' => $laptop->id],
                ['quantity' => 1, 'returned_quantity' => 0]
            );

            $createdRemoteItem = LoanItem::query()->firstOrCreate(
                ['loan_id' => $loan->id, 'item_id' => $remote->id],
                ['quantity' => 1, 'returned_quantity' => 0]
            );

            if ($createdLaptopItem->wasRecentlyCreated) {
                $laptop->decrement('available_stock');
            }

            if ($createdRemoteItem->wasRecentlyCreated) {
                $remote->decrement('available_stock');
            }
        });
    }
}
