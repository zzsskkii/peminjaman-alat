<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Loan;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = \App\Models\User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! $user->is_admin || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau password admin tidak valid.',
                ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function dashboard(Request $request): View
    {
        $activeLoans = Loan::query()
            ->with(['student', 'loanItems'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->where('approval_status', '!=', Loan::APPROVAL_REJECTED)
            ->latest('borrowed_at')
            ->limit(10)
            ->get();

        $items = Item::query()
            ->with('itemCategory')
            ->orderBy('name')
            ->get();
        $categories = ItemCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $editItemId = $request->integer('edit_item');
        $editItem = $editItemId > 0 ? $items->firstWhere('id', $editItemId) : null;

        return view('admin.dashboard', [
            'studentsCount' => Student::count(),
            'itemsCount' => Item::count(),
            'activeLoansCount' => Loan::whereIn('status', ['borrowed', 'overdue'])
                ->where('approval_status', '!=', Loan::APPROVAL_REJECTED)
                ->count(),
            'overdueLoansCount' => Loan::where('status', 'overdue')->count(),
            'activeLoans' => $activeLoans,
            'items' => $items,
            'categories' => $categories,
            'editItem' => $editItem,
        ]);
    }

    public function returnsDashboard(): View
    {
        $returnQueue = Loan::query()
            ->with(['student', 'loanItems'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->where('approval_status', Loan::APPROVAL_APPROVED)
            ->latest('borrowed_at')
            ->get();

        $returnedLoans = Loan::query()
            ->with('student')
            ->where('status', 'returned')
            ->latest('returned_at')
            ->limit(15)
            ->get();

        return view('admin.returns-dashboard', [
            'returnQueue' => $returnQueue,
            'returnedLoans' => $returnedLoans,
            'queueCount' => $returnQueue->count(),
            'returnedTodayCount' => Loan::query()
                ->where('status', 'returned')
                ->whereDate('returned_at', now()->toDateString())
                ->count(),
            'pendingApprovalCount' => Loan::query()
                ->whereIn('status', ['borrowed', 'overdue'])
                ->where('approval_status', Loan::APPROVAL_PENDING)
                ->count(),
        ]);
    }

    public function processReturn(Loan $loan): RedirectResponse
    {
        if ($loan->status === 'returned') {
            return redirect()
                ->route('admin.returns.dashboard')
                ->with('error', 'Pinjaman ini sudah berstatus dikembalikan.');
        }

        if ($loan->approval_status !== Loan::APPROVAL_APPROVED) {
            return redirect()
                ->route('admin.returns.dashboard')
                ->with('error', 'Hanya pinjaman berstatus disetujui yang bisa diproses pengembalian.');
        }

        DB::transaction(function () use ($loan): void {
            $loan->load('loanItems');

            foreach ($loan->loanItems as $loanItem) {
                $remainingQuantity = max($loanItem->quantity - $loanItem->returned_quantity, 0);

                if ($remainingQuantity > 0) {
                    Item::query()
                        ->whereKey($loanItem->item_id)
                        ->increment('available_stock', $remainingQuantity);

                    $loanItem->update([
                        'returned_quantity' => $loanItem->quantity,
                    ]);
                }
            }

            $loan->update([
                'status' => 'returned',
                'returned_at' => now(),
                'return_method' => 'manual',
            ]);
        });

        return redirect()
            ->route('admin.returns.dashboard')
            ->with('success', 'Pengembalian pinjaman berhasil diproses.');
    }

    public function updateLoan(Request $request, Loan $loan): RedirectResponse
    {
        $data = $request->validate([
            'approval_status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $newApprovalStatus = $data['approval_status'];

        if ($loan->approval_status === Loan::APPROVAL_REJECTED && $newApprovalStatus !== Loan::APPROVAL_REJECTED) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Pinjaman yang sudah ditolak tidak dapat diubah kembali.');
        }

        if ($newApprovalStatus === Loan::APPROVAL_REJECTED && $loan->approval_status !== Loan::APPROVAL_REJECTED) {
            $loan->load('loanItems');

            foreach ($loan->loanItems as $loanItem) {
                $remainingQuantity = max($loanItem->quantity - $loanItem->returned_quantity, 0);

                if ($remainingQuantity > 0) {
                    Item::query()
                        ->whereKey($loanItem->item_id)
                        ->increment('available_stock', $remainingQuantity);

                    $loanItem->update([
                        'returned_quantity' => $loanItem->quantity,
                    ]);
                }
            }

            $loan->update([
                'approval_status' => Loan::APPROVAL_REJECTED,
                'status' => 'returned',
                'returned_at' => now(),
                'return_method' => 'manual',
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Pinjaman ditolak dan stok barang telah dikembalikan.');
        }

        $loan->update([
            'approval_status' => $newApprovalStatus,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Status persetujuan pinjaman berhasil diperbarui.');
    }

    public function destroyLoan(Loan $loan): RedirectResponse
    {
        $loan->load('loanItems');

        foreach ($loan->loanItems as $loanItem) {
            $remainingQuantity = max($loanItem->quantity - $loanItem->returned_quantity, 0);

            if ($remainingQuantity > 0) {
                Item::query()
                    ->whereKey($loanItem->item_id)
                    ->increment('available_stock', $remainingQuantity);
            }
        }

        $loan->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Pinjaman berhasil dihapus.');
    }

    public function storeItem(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:item_categories,id'],
            'stock' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = ItemCategory::query()->findOrFail((int) $data['category_id']);
        $generatedCode = $this->generateItemCodeForCategory($category);

        Item::query()->create([
            'code' => $generatedCode,
            'name' => trim($data['name']),
            'category' => $category->name,
            'category_id' => $category->id,
            'stock' => (int) $data['stock'],
            'available_stock' => (int) $data['stock'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function updateItem(Request $request, Item $item): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:item_categories,id'],
            'stock' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = ItemCategory::query()->findOrFail((int) $data['category_id']);
        $shouldRegenerateCode = (int) $item->category_id !== (int) $category->id;
        $requestedStock = (int) $data['stock'];
        $borrowedQuantity = max($item->stock - $item->available_stock, 0);

        if ($requestedStock < $borrowedQuantity) {
            return back()
                ->withInput()
                ->withErrors([
                    'stock' => "Stok minimal {$borrowedQuantity}, karena {$borrowedQuantity} item sedang dipinjam.",
                ]);
        }

        $item->update([
            'code' => $shouldRegenerateCode
                ? $this->generateItemCodeForCategory($category, $item->id)
                : $item->code,
            'name' => trim($data['name']),
            'category' => $category->name,
            'category_id' => $category->id,
            'stock' => $requestedStock,
            'available_stock' => max($requestedStock - $borrowedQuantity, 0),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroyItem(Item $item): RedirectResponse
    {
        try {
            $item->delete();
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Barang tidak bisa dihapus karena sudah terkait data peminjaman.');
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login.form');
    }

    private function generateItemCodeForCategory(ItemCategory $category, ?int $ignoreItemId = null): string
    {
        $prefix = $this->buildCategoryPrefix($category->name);

        $maxSequence = Item::query()
            ->when($ignoreItemId, fn ($query) => $query->where('id', '!=', $ignoreItemId))
            ->where('code', 'like', $prefix.'-%')
            ->pluck('code')
            ->map(function (string $code): int {
                if (preg_match('/-(\d+)$/', $code, $matches) !== 1) {
                    return 0;
                }

                return (int) $matches[1];
            })
            ->max() ?? 0;

        $nextSequence = $maxSequence + 1;
        $candidate = sprintf('%s-%03d', $prefix, $nextSequence);

        while (
            Item::query()
                ->when($ignoreItemId, fn ($query) => $query->where('id', '!=', $ignoreItemId))
                ->where('code', $candidate)
                ->exists()
        ) {
            $nextSequence++;
            $candidate = sprintf('%s-%03d', $prefix, $nextSequence);
        }

        return $candidate;
    }

    private function buildCategoryPrefix(string $categoryName): string
    {
        $normalized = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $categoryName) ?? '');

        if ($normalized === '') {
            return 'ITM';
        }

        if (strlen($normalized) >= 3) {
            return substr($normalized, 0, 3);
        }

        return str_pad($normalized, 3, 'X');
    }
}
