<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            background: #0b1622;
            color: #edf4fb;
            padding: 20px;
        }
        .wrap { max-width: 1000px; margin: 0 auto; }
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .metrics {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
        }
        .box {
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 14px;
            background: #122232;
        }
        .box p { margin: 0 0 6px; color: #93aac0; }
        .box h3 { margin: 0; font-size: 1.5rem; }
        table {
            margin-top: 16px;
            width: 100%;
            border-collapse: collapse;
            background: #122232;
            border-radius: 12px;
            overflow: hidden;
        }
        .section-title {
            margin: 24px 0 8px;
        }
        th, td {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 10px;
            text-align: left;
            font-size: 0.95rem;
        }
        th { color: #93aac0; }
        .form-grid {
            margin-top: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
            background: #122232;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 14px;
        }
        .form-grid label {
            display: block;
            font-size: 0.9rem;
            color: #93aac0;
            margin-bottom: 6px;
        }
        .form-grid input[type="text"],
        .form-grid input[type="number"],
        .form-grid select {
            width: 100%;
            border: 1px solid rgba(255,255,255,0.18);
            background: #0e1e2d;
            color: #edf4fb;
            border-radius: 8px;
            padding: 9px 10px;
            box-sizing: border-box;
        }
        .full { grid-column: 1 / -1; }
        .alert {
            margin-top: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.92rem;
        }
        .alert-ok {
            background: #183a2a;
            border: 1px solid #2d6f4a;
            color: #ace8c2;
        }
        .alert-err {
            background: #3e1f25;
            border: 1px solid #7c3745;
            color: #ffc6cf;
        }
        .link {
            color: #7ec3ff;
            text-decoration: none;
            font-weight: 600;
        }
        .muted { color: #93aac0; }
        .btn, button {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }
        .btn { background: #1d3044; color: #edf4fb; }
        button { background: #ff8a3d; color: #1e1308; }
        .status-pill {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .status-pill.pending {
            background: #46371f;
            color: #ffd18a;
            border: 1px solid #7f5b26;
        }
        .status-pill.approved {
            background: #1b3b2a;
            color: #a7ebc3;
            border: 1px solid #2f7851;
        }
        .status-pill.rejected {
            background: #48242c;
            color: #ffc9d1;
            border: 1px solid #8a3746;
        }
        .loan-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .loan-actions select {
            border: 1px solid rgba(255,255,255,0.18);
            background: #0e1e2d;
            color: #edf4fb;
            border-radius: 8px;
            padding: 6px 8px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <div>
                <h1 style="margin: 0;">Dashboard Admin</h1>
                <p style="margin: 4px 0 0; color: #93aac0;">Ringkasan data peminjaman alat.</p>
            </div>
            <div style="display:flex; gap:8px;">
                <a class="btn" href="/">Buka</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        <section class="metrics">
            <article class="box"><p>Total Siswa</p><h3>{{ $studentsCount }}</h3></article>
            <article class="box"><p>Total Alat</p><h3>{{ $itemsCount }}</h3></article>
            <article class="box"><p>Pinjaman Aktif</p><h3>{{ $activeLoansCount }}</h3></article>
            <article class="box"><p>Overdue</p><h3>{{ $overdueLoansCount }}</h3></article>
        </section>

        @if(session('success'))
            <div class="alert alert-ok">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-err">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-err">{{ $errors->first() }}</div>
        @endif

        @php
            $isEdit = isset($editItem) && $editItem;
            $formAction = $isEdit ? route('admin.items.update', $editItem) : route('admin.items.store');
            $nameValue = old('name', $isEdit ? $editItem->name : '');
            $categoryIdValue = (int) old('category_id', $isEdit ? $editItem->category_id : 0);
            $stockValue = old('stock', $isEdit ? $editItem->stock : 1);
            $activeValue = old('is_active', $isEdit ? (int) $editItem->is_active : 1);
        @endphp

        <h2 class="section-title">{{ $isEdit ? 'Edit Barang' : 'Tambah Barang' }}</h2>
        <form method="POST" action="{{ $formAction }}" class="form-grid">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div>
                <label for="name">Nama</label>
                <input id="name" name="name" type="text" value="{{ $nameValue }}" required>
            </div>
            <div>
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryIdValue === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="stock">Stok</label>
                <input id="stock" name="stock" type="number" min="1" value="{{ $stockValue }}" required>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <input id="is_active" name="is_active" type="checkbox" value="1" @checked((int) $activeValue === 1)>
                <label for="is_active" style="margin:0;">Aktif</label>
            </div>
            <div class="full" style="display:flex; gap:8px; align-items:center;">
                <button type="submit">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Barang' }}</button>
                @if($isEdit)
                    <a class="btn" href="{{ route('admin.dashboard') }}">Batal Edit</a>
                @endif
                <span class="muted">Kode barang dibuat otomatis berdasarkan kategori.</span>
            </div>
        </form>

        <h2 class="section-title">Data Barang</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Tersedia</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->itemCategory?->name ?? ($item->category ?: '-') }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>{{ $item->available_stock }}</td>
                        <td class="muted">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td>
                            <a class="link" href="{{ route('admin.dashboard', ['edit_item' => $item->id]) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.items.destroy', $item) }}" style="display:inline;" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:#5b2431; color:#ffd4dd; margin-left:8px;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Belum ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="section-title">Pinjaman Aktif Terbaru</h2>
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Card UID</th>
                    <th>Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Status Pinjaman</th>
                    <th>Persetujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeLoans as $loan)
                    @php
                        $approvalLabel = match($loan->approval_status) {
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => 'Menunggu',
                        };
                    @endphp
                    <tr>
                        <td>{{ $loan->student?->name ?? '-' }}</td>
                        <td>{{ $loan->student?->card_uid ?? '-' }}</td>
                        <td>{{ $loan->borrowed_at?->format('d-m-Y H:i') }}</td>
                        <td>{{ $loan->due_at?->format('d-m-Y H:i') }}</td>
                        <td>{{ $loan->status }}</td>
                        <td>
                            <span class="status-pill {{ $loan->approval_status }}">{{ $approvalLabel }}</span>
                        </td>
                        <td>
                            <div class="loan-actions">
                                <form method="POST" action="{{ route('admin.loans.update', $loan) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="approval_status" onchange="this.form.submit()">
                                        <option value="pending" @selected($loan->approval_status === 'pending')>Menunggu</option>
                                        <option value="approved" @selected($loan->approval_status === 'approved')>Disetujui</option>
                                        <option value="rejected" @selected($loan->approval_status === 'rejected')>Ditolak</option>
                                    </select>
                                </form>
                                <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}" onsubmit="return confirm('Hapus data pinjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:#5b2431; color:#ffd4dd;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Belum ada pinjaman aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
