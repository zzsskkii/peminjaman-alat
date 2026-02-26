<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Carbon\CarbonImmutable;

class LoanFlowController extends Controller
{
    public function peminjamanAlat(): View
    {
        return view('loan-peminjaman-alat', [
            'items' => $this->fetchActiveItems(),
        ]);
    }

    public function activeItems(): JsonResponse
    {
        return response()->json([
            'items' => $this->fetchActiveItems(),
        ]);
    }

    public function tapCard(Request $request): JsonResponse
    {
        $data = $request->validate([
            'card_uid' => ['required', 'string'],
            'mode' => ['required', 'in:borrow,return'],
        ]);

        $student = Student::query()
            ->where('card_uid', $data['card_uid'])
            ->where('is_active', true)
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Kartu tidak terdaftar atau siswa tidak aktif.',
            ], 404);
        }

        $activeLoan = $this->findActiveLoanForStudent($student);

        if ($data['mode'] === 'borrow' && $activeLoan) {
            return response()->json([
                'message' => 'Siswa masih punya pinjaman aktif. Ganti ke mode Kembalikan.',
            ], 422);
        }

        if ($data['mode'] === 'borrow' && ! $activeLoan) {
            return response()->json([
                'next_action' => 'fill_borrow_form',
                'student' => $student,
            ]);
        }

        if ($data['mode'] === 'return' && ! $activeLoan) {
            return response()->json([
                'message' => 'Tidak ada pinjaman aktif untuk kartu ini.',
            ], 422);
        }

        return response()->json([
            'next_action' => 'select_return_items',
            'student' => $student,
            'loan' => $activeLoan,
            'loan_items' => $activeLoan->loanItems
                ->map(fn (LoanItem $loanItem) => [
                    'id' => $loanItem->id,
                    'item_id' => $loanItem->item_id,
                    'item_name' => $loanItem->item?->name,
                    'item_code' => $loanItem->item?->code,
                    'quantity' => $loanItem->quantity,
                    'returned_quantity' => $loanItem->returned_quantity,
                    'remaining_quantity' => max($loanItem->quantity - $loanItem->returned_quantity, 0),
                ])
                ->values(),
        ]);
    }

    public function storeBorrow(Request $request): JsonResponse
    {
        $data = $request->validate([
            'card_uid' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $student = Student::query()
            ->where('card_uid', $data['card_uid'])
            ->where('is_active', true)
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Kartu tidak terdaftar atau siswa tidak aktif.',
            ], 404);
        }

        $hasActiveLoan = Loan::query()
            ->whereBelongsTo($student)
            ->where('status', 'borrowed')
            ->where('approval_status', '!=', Loan::APPROVAL_REJECTED)
            ->exists();

        if ($hasActiveLoan) {
            throw ValidationException::withMessages([
                'card_uid' => 'Siswa masih memiliki pinjaman aktif. Silakan kembalikan dulu lewat tap kartu.',
            ]);
        }

        $loan = DB::transaction(function () use ($student, $data): Loan {
            $borrowedAt = now()->toImmutable();
            $dueAt = $this->resolveDueAt($borrowedAt);

            $loan = Loan::query()->create([
                'student_id' => $student->id,
                'borrowed_at' => $borrowedAt,
                'due_at' => $dueAt,
                'status' => 'borrowed',
                'approval_status' => Loan::APPROVAL_PENDING,
                'notes' => $data['notes'] ?? null,
            ]);

            $requestedItems = collect($data['items']);
            $itemsById = Item::query()
                ->whereIn('id', $requestedItems->pluck('item_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($requestedItems as $requestedItem) {
                /** @var Item|null $item */
                $item = $itemsById->get($requestedItem['item_id']);
                $quantity = (int) $requestedItem['quantity'];

                if (! $item || ! $item->is_active) {
                    throw ValidationException::withMessages([
                        'items' => "Alat ID {$requestedItem['item_id']} tidak tersedia atau tidak aktif.",
                    ]);
                }

                if ($item->available_stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$item->name} tidak cukup. Tersisa {$item->available_stock}.",
                    ]);
                }

                $item->decrement('available_stock', $quantity);

                LoanItem::query()->create([
                    'loan_id' => $loan->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                ]);
            }

            return $loan;
        });

        $loan->load('loanItems.item', 'student');

        return response()->json([
            'message' => 'Peminjaman berhasil disimpan.',
            'loan' => $loan,
        ], 201);
    }

    public function returnItems(Request $request): JsonResponse
    {
        $data = $request->validate([
            'card_uid' => ['required', 'string'],
            'loan_id' => ['required', 'integer', 'exists:loans,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'integer', 'distinct'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $student = Student::query()
            ->where('card_uid', $data['card_uid'])
            ->where('is_active', true)
            ->first();

        if (! $student) {
            return response()->json([
                'message' => 'Kartu tidak terdaftar atau siswa tidak aktif.',
            ], 404);
        }

        $response = DB::transaction(function () use ($data, $student): array {
            $loan = Loan::query()
                ->whereKey($data['loan_id'])
                ->whereBelongsTo($student)
                ->lockForUpdate()
                ->first();

            if (! $loan || ! in_array($loan->status, ['borrowed', 'overdue'], true)) {
                throw ValidationException::withMessages([
                    'loan_id' => 'Pinjaman aktif tidak ditemukan untuk siswa ini.',
                ]);
            }

            $loanItems = $loan->loanItems()
                ->with('item')
                ->lockForUpdate()
                ->get()
                ->keyBy('item_id');

            foreach ($data['items'] as $returnedItem) {
                /** @var LoanItem|null $loanItem */
                $loanItem = $loanItems->get($returnedItem['item_id']);

                if (! $loanItem) {
                    throw ValidationException::withMessages([
                        'items' => "Item ID {$returnedItem['item_id']} tidak ada di pinjaman ini.",
                    ]);
                }

                $remainingQuantity = max($loanItem->quantity - $loanItem->returned_quantity, 0);
                $quantity = (int) $returnedItem['quantity'];

                if ($quantity > $remainingQuantity) {
                    throw ValidationException::withMessages([
                        'items' => "Jumlah kembali untuk {$loanItem->item?->name} melebihi sisa pinjaman ({$remainingQuantity}).",
                    ]);
                }

                Item::query()
                    ->whereKey($loanItem->item_id)
                    ->increment('available_stock', $quantity);

                $loanItem->increment('returned_quantity', $quantity);
            }

            $loan->refresh()->load('loanItems.item');

            $isFullyReturned = $loan->loanItems->every(
                fn (LoanItem $loanItem) => $loanItem->returned_quantity >= $loanItem->quantity
            );

            if ($isFullyReturned) {
                $loan->update([
                    'status' => 'returned',
                    'returned_at' => now(),
                    'return_method' => 'tap_card',
                ]);
            } else {
                $loan->update([
                    'status' => now()->greaterThan($loan->due_at) ? 'overdue' : 'borrowed',
                ]);
            }

            $loan->refresh()->load('loanItems.item');

            return [
                'loan' => $loan,
                'is_fully_returned' => $loan->status === 'returned',
                'loan_items' => $loan->loanItems
                    ->map(fn (LoanItem $loanItem) => [
                        'item_id' => $loanItem->item_id,
                        'item_name' => $loanItem->item?->name,
                        'item_code' => $loanItem->item?->code,
                        'quantity' => $loanItem->quantity,
                        'returned_quantity' => $loanItem->returned_quantity,
                        'remaining_quantity' => max($loanItem->quantity - $loanItem->returned_quantity, 0),
                    ])
                    ->values(),
            ];
        });

        return response()->json([
            'message' => $response['is_fully_returned']
                ? 'Semua barang sudah dikembalikan.'
                : 'Pengembalian parsial tersimpan.',
            'loan' => $response['loan'],
            'is_fully_returned' => $response['is_fully_returned'],
            'loan_items' => $response['loan_items'],
        ]);
    }

    private function resolveDueAt(CarbonImmutable $borrowedAt): CarbonImmutable
    {
        $dueAt = $borrowedAt->setTime(16, 0, 0);

        if ($borrowedAt->greaterThan($dueAt)) {
            return $dueAt->addDay();
        }

        return $dueAt;
    }

    private function fetchActiveItems()
    {
        if (! Schema::hasTable('items')) {
            return collect();
        }

        return Item::query()
            ->select(['id', 'code', 'name', 'category', 'available_stock'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function findActiveLoanForStudent(Student $student): ?Loan
    {
        return Loan::query()
            ->with('loanItems.item')
            ->whereBelongsTo($student)
            ->whereIn('status', ['borrowed', 'overdue'])
            ->where('approval_status', '!=', Loan::APPROVAL_REJECTED)
            ->latest('borrowed_at')
            ->first();
    }
}
