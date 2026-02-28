<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --navy: #002140;
            --gold: #fcc419;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --border: #e2e8f0;
            --text-dark: #0f172a;
            --muted: #64748b;
            --danger: #b91c1c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            background: linear-gradient(180deg, var(--bg-light) 0%, #f1f5f9 100%);
            color: var(--text-dark);
            padding: 16px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        body.modal-open {
            overflow: hidden;
        }

        .wrap {
            width: min(1500px, 100%);
            margin: 0 auto;
            transition: filter 0.2s ease;
        }
        body.modal-open .wrap {
            filter: blur(6px);
            pointer-events: none;
            user-select: none;
        }

        .top {
            display: grid;
            grid-template-columns: minmax(280px, 1fr) auto;
            align-items: center;
            gap: 16px;
            background: var(--white);
            border: 2px solid var(--border);
            border-radius: 20px;
            padding: 18px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        .top-links {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .theme-switch {
            position: relative;
            width: 136px;
            height: 36px;
            border-radius: 999px;
            border: 1px solid #c8d4e3;
            background: linear-gradient(180deg, #f8fafc 0%, #e9eff7 100%);
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            padding: 0;
            cursor: pointer;
            overflow: hidden;
        }
        .theme-option {
            z-index: 2;
            font-weight: 700;
            font-size: 0.72rem;
            text-align: center;
            letter-spacing: 0.06em;
            color: #64748b;
            user-select: none;
        }
        .theme-thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: calc(50% - 6px);
            height: calc(100% - 6px);
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 6px 12px -10px rgba(15, 23, 42, 0.95), inset 0 0 0 1px rgba(15, 23, 42, 0.08);
            transition: transform 0.18s ease, background 0.18s ease;
            z-index: 1;
        }
        .theme-switch[data-theme="light"] .theme-option[data-mode="light"] { color: #84cc16; }
        .theme-switch[data-theme="dark"] .theme-option[data-mode="dark"] { color: #ef4444; }
        .theme-switch[data-theme="dark"] .theme-thumb {
            transform: translateX(100%);
            background: #151c28;
            box-shadow: 0 6px 12px -10px rgba(0, 0, 0, 0.95), inset 0 0 0 1px rgba(255, 255, 255, 0.08);
        }
        .theme-switch[data-theme="dark"] {
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            border-color: #2f3d52;
        }

        .metrics {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 12px;
        }

        .box {
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            background: var(--white);
            min-height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .box p {
            margin: 0 0 6px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .box h3 {
            margin: 0;
            color: var(--navy);
            font-size: 1.6rem;
        }

        .section-title {
            margin: 20px 0 8px;
            color: var(--navy);
            font-size: clamp(1.9rem, 2.3vw, 2.35rem);
            line-height: 1.08;
        }

        .table-wrap {
            overflow-x: auto;
            border: 2px solid var(--border);
            border-radius: 16px;
            background: var(--white);
            margin-top: 12px;
            box-shadow: 0 5px 16px rgba(15, 23, 42, 0.04);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        th,
        td {
            border-bottom: 1px solid var(--border);
            padding: 10px;
            text-align: left;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        th {
            background: #f1f5f9;
            color: var(--muted);
            font-weight: 700;
        }

        .form-grid {
            margin-top: 16px;
            display: grid;
            grid-template-columns: minmax(260px, 2fr) minmax(200px, 1.25fr) minmax(160px, 0.9fr) auto;
            gap: 12px;
            background: var(--white);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            align-items: end;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .form-grid label {
            display: block;
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .form-grid input[type="text"],
        .form-grid input[type="number"],
        .form-grid select {
            width: 100%;
            border: 1px solid var(--border);
            background: var(--bg-light);
            color: var(--text-dark);
            border-radius: 10px;
            padding: 11px 12px;
            font-family: inherit;
        }

        .full { grid-column: 1 / -1; }

        .alert {
            margin-top: 12px;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.92rem;
            border: 1px solid transparent;
        }

        .alert-ok {
            background: #dcfce7;
            border-color: #86efac;
            color: #166534;
        }

        .alert-err {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .link {
            color: var(--navy);
            text-decoration: underline;
            text-underline-offset: 2px;
            font-weight: 600;
        }

        .muted { color: var(--muted); }

        .btn,
        button {
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 10px 16px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }

        .btn {
            background: var(--white);
            color: var(--navy);
        }

        .btn.active {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
        }

        button {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
        }

        .btn-danger {
            background: #fee2e2;
            border-color: #fecaca;
            color: var(--danger);
        }

        .status-pill {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1px solid;
        }

        .status-pill.pending {
            background: #fef3c7;
            color: #92400e;
            border-color: #fcd34d;
        }

        .status-pill.approved {
            background: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .status-pill.rejected {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fca5a5;
        }

        .loan-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .loan-actions select {
            border: 1px solid var(--border);
            background: var(--bg-light);
            color: var(--text-dark);
            border-radius: 8px;
            padding: 6px 8px;
            font-family: inherit;
        }

        .section-note {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 1.12rem;
        }

        @media (max-width: 1200px) {
            .metrics {
                grid-template-columns: repeat(2, minmax(180px, 1fr));
            }
            .form-grid {
                grid-template-columns: repeat(2, minmax(180px, 1fr));
            }
            .top {
                grid-template-columns: 1fr;
            }
            .top-links {
                justify-content: flex-start;
            }
        }

        @media (max-width: 640px) {
            body { padding: 14px; }
            .top { padding: 14px; }
            .metrics {
                grid-template-columns: 1fr;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .confirm-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .confirm-backdrop.show {
            opacity: 1;
            pointer-events: auto;
        }

        .confirm-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -52%) scale(0.97);
            width: min(420px, calc(100vw - 28px));
            border-radius: 16px;
            border: 2px solid var(--border);
            background: var(--white);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.24);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            overflow: hidden;
        }

        .confirm-modal.show {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, -50%) scale(1);
        }

        .confirm-content {
            padding: 18px;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1.02rem;
            line-height: 1.4;
        }

        .confirm-actions {
            border-top: 1px solid var(--border);
            padding: 10px 14px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .confirm-btn {
            border: 0;
            border-radius: 10px;
            padding: 9px 14px;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .confirm-btn.cancel {
            background: #e2e8f0;
            color: #334155;
        }

        .confirm-btn.ok {
            background: var(--gold);
            color: var(--navy);
        }

        .edit-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 10020;
        }

        .edit-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: min(860px, calc(100vw - 24px));
            max-height: calc(100vh - 28px);
            overflow: auto;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 2px solid rgba(0, 33, 64, 0.9);
            border-radius: 22px;
            box-shadow: 0 16px 30px -22px rgba(15, 23, 42, 0.6);
            z-index: 10021;
            padding: 18px;
        }

        .edit-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .edit-modal-head h3 {
            margin: 0;
            color: var(--navy);
            font-size: 1.8rem;
            letter-spacing: 0.03em;
        }

        .edit-close {
            border: 2px solid #b6c5d8;
            border-radius: 10px;
            min-width: 44px;
            height: 44px;
            background: #f8fafc;
            color: var(--navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem;
        }
        .edit-close:hover {
            background: #eef2f7;
        }

        .edit-form-grid {
            margin-top: 8px;
            display: grid;
            grid-template-columns: minmax(260px, 2fr) minmax(220px, 1.2fr) minmax(170px, 0.8fr);
            gap: 12px;
            align-items: end;
        }

        .edit-form-grid label {
            display: block;
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .edit-form-grid input[type="text"],
        .edit-form-grid input[type="number"],
        .edit-form-grid select {
            width: 100%;
            border: 1px solid var(--border);
            background: var(--bg-light);
            color: var(--text-dark);
            border-radius: 10px;
            padding: 11px 12px;
            font-family: inherit;
        }
        .edit-form-grid input[type="text"]:focus,
        .edit-form-grid input[type="number"]:focus,
        .edit-form-grid select:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(252, 196, 25, 0.24);
        }

        .edit-form-grid .full {
            grid-column: 1 / -1;
        }

        .edit-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 4px;
        }
        .edit-actions button[type="submit"] {
            background: linear-gradient(90deg, #facc15 0%, #f59e0b 100%);
            border-color: #facc15;
            color: var(--navy);
        }

        .flash-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 10040;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .flash-backdrop.show {
            opacity: 1;
            pointer-events: auto;
        }

        .flash-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -54%) scale(0.96);
            width: min(460px, calc(100vw - 32px));
            min-height: 96px;
            padding: 16px 18px;
            border-radius: 16px;
            color: #fff;
            font-weight: 700;
            font-size: 1.08rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.28);
            z-index: 10041;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .flash-modal.show {
            opacity: 1;
            pointer-events: auto;
            transform: translate(-50%, -50%) scale(1);
        }

        .flash-modal.success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border: 1px solid rgba(255,255,255,0.24);
        }

        .flash-modal.error {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border: 1px solid rgba(255,255,255,0.24);
        }

        body.theme-dark {
            --bg-light: #0b1220;
            --white: #111827;
            --border: #334155;
            --text-dark: #e2e8f0;
            --muted: #94a3b8;
            --navy: #93c5fd;
            --danger: #f87171;
        }
        body.theme-dark {
            background: linear-gradient(180deg, #0b1220 0%, #020617 100%);
        }
        body.theme-dark .top,
        body.theme-dark .box,
        body.theme-dark .table-wrap,
        body.theme-dark .form-grid,
        body.theme-dark .confirm-modal {
            box-shadow: 0 10px 20px -18px rgba(0, 0, 0, 0.95);
        }
        body.theme-dark th {
            background: #0f172a;
            color: #94a3b8;
        }
        body.theme-dark .btn {
            background: #0f172a;
            color: #cbd5e1;
            border-color: #334155;
        }
        body.theme-dark .btn.active,
        body.theme-dark button {
            color: #0f172a;
        }
        body.theme-dark .loan-actions select,
        body.theme-dark .form-grid input[type="text"],
        body.theme-dark .form-grid input[type="number"],
        body.theme-dark .form-grid select,
        body.theme-dark .edit-form-grid input[type="text"],
        body.theme-dark .edit-form-grid input[type="number"],
        body.theme-dark .edit-form-grid select {
            background: #0b1220;
            color: #e2e8f0;
            border-color: #334155;
        }
        body.theme-dark .edit-modal {
            background: linear-gradient(180deg, #0f172a 0%, #0b1220 100%);
            border-color: #334155;
            box-shadow: 0 20px 36px -18px rgba(0, 0, 0, 0.95);
        }
        body.theme-dark .edit-close {
            background: #0f172a;
            border-color: #334155;
            color: #cbd5e1;
        }

        @media (max-width: 820px) {
            .edit-form-grid {
                grid-template-columns: 1fr;
            }
            .edit-modal-head h3 {
                font-size: 1.45rem;
            }
        }
    </style>
</head>
<body class="{{ isset($editItem) && $editItem ? 'modal-open' : '' }}">
    <div class="wrap">
        <div class="top">
            <div>
                <h1 style="margin: 0; color: var(--navy);">Dashboard Admin</h1>
                <p class="section-note">Ringkasan data peminjaman alat.</p>
            </div>
            <div class="top-links">
                <button id="themeToggle" class="theme-switch" type="button" aria-label="Toggle theme" data-theme="light" aria-pressed="false">
                    <span class="theme-option" data-mode="light">ON</span>
                    <span class="theme-option" data-mode="dark">OFF</span>
                    <span class="theme-thumb" aria-hidden="true"></span>
                </button>
                <a class="btn active" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                <a class="btn" href="{{ route('admin.returns.dashboard') }}">Pengembalian</a>
                <a class="btn" href="/">Back</a>
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

        @if($errors->any())
            <div class="alert alert-err">{{ $errors->first() }}</div>
        @endif

        @php
            $isEdit = isset($editItem) && $editItem;
            $createNameValue = $isEdit ? '' : old('name', '');
            $createCategoryIdValue = $isEdit ? 0 : (int) old('category_id', 0);
            $createStockValue = $isEdit ? 1 : old('stock', 1);
            $createActiveValue = $isEdit ? 1 : old('is_active', 1);
            $editNameValue = old('name', $isEdit ? $editItem->name : '');
            $editCategoryIdValue = (int) old('category_id', $isEdit ? $editItem->category_id : 0);
            $editStockValue = old('stock', $isEdit ? $editItem->stock : 1);
            $editActiveValue = old('is_active', $isEdit ? (int) $editItem->is_active : 1);
        @endphp

        <h2 class="section-title">Tambah Barang</h2>
        <form method="POST" action="{{ route('admin.items.store') }}" class="form-grid">
            @csrf

            <div>
                <label for="name">Nama</label>
                <input id="name" name="name" type="text" value="{{ $createNameValue }}" required>
            </div>
            <div>
                <label for="category_id">Kategori</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected($createCategoryIdValue === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="stock">Stok</label>
                <input id="stock" name="stock" type="number" min="1" value="{{ $createStockValue }}" required>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <input id="is_active" name="is_active" type="checkbox" value="1" @checked((int) $createActiveValue === 1)>
                <label for="is_active" style="margin:0;">Aktif</label>
            </div>
            <div class="full" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                <button type="submit">Tambah Barang</button>
                <span class="muted">Kode barang dibuat otomatis berdasarkan kategori.</span>
            </div>
        </form>

        <h2 class="section-title">Data Barang</h2>
        <div class="table-wrap">
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
                                <form method="POST" action="{{ route('admin.items.destroy', $item) }}" style="display:inline;" class="js-confirm-form" data-confirm-message="Hapus barang ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="margin-left:8px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">Belum ada data barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h2 class="section-title">Pinjaman Aktif Terbaru</h2>
        <div class="table-wrap">
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
                                    <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}" class="js-confirm-form" data-confirm-message="Hapus data pinjaman ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Hapus</button>
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
    </div>

    @if($isEdit)
        <div id="editBackdrop" class="edit-backdrop" aria-hidden="true"></div>
        <div id="editModal" class="edit-modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
            <div class="edit-modal-head">
                <h3 id="editModalTitle">Edit Barang</h3>
                <a href="{{ route('admin.dashboard') }}" class="edit-close" aria-label="Tutup modal">×</a>
            </div>
            <form method="POST" action="{{ route('admin.items.update', $editItem) }}" class="edit-form-grid">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_name">Nama</label>
                    <input id="edit_name" name="name" type="text" value="{{ $editNameValue }}" required>
                </div>
                <div>
                    <label for="edit_category_id">Kategori</label>
                    <select id="edit_category_id" name="category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($editCategoryIdValue === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_stock">Stok</label>
                    <input id="edit_stock" name="stock" type="number" min="1" value="{{ $editStockValue }}" required>
                </div>
                <div class="full" style="display:flex; align-items:center; gap:8px;">
                    <input id="edit_is_active" name="is_active" type="checkbox" value="1" @checked((int) $editActiveValue === 1)>
                    <label for="edit_is_active" style="margin:0;">Aktif</label>
                </div>
                <div class="full edit-actions">
                    <button type="submit">Simpan Perubahan</button>
                    <a class="btn" href="{{ route('admin.dashboard') }}">Batal Edit</a>
                    <span class="muted">Kode barang dibuat otomatis berdasarkan kategori.</span>
                </div>
            </form>
        </div>
    @endif

    @if(session('success') || session('error'))
        <div id="flashBackdrop" class="flash-backdrop show" aria-hidden="true"></div>
        <div id="flashModal" class="flash-modal {{ session('error') ? 'error' : 'success' }} show" role="dialog" aria-modal="true">
            {{ session('error') ?: session('success') }}
        </div>
    @endif

    <div id="confirmBackdrop" class="confirm-backdrop" aria-hidden="true"></div>
    <div id="confirmModal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirmMessage">
        <div id="confirmMessage" class="confirm-content">Yakin lanjutkan aksi ini?</div>
        <div class="confirm-actions">
            <button id="confirmCancel" type="button" class="confirm-btn cancel">Batalkan</button>
            <button id="confirmOk" type="button" class="confirm-btn ok">OKE</button>
        </div>
    </div>

    <script>
        (() => {
            const THEME_KEY = 'peminjaman-theme';
            const toggle = document.getElementById('themeToggle');
            if (!toggle) return;

            const applyTheme = (theme) => {
                const isDark = theme === 'dark';
                document.body.classList.toggle('theme-dark', isDark);
                toggle.dataset.theme = isDark ? 'dark' : 'light';
                toggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            };

            const saved = localStorage.getItem(THEME_KEY);
            applyTheme(saved === 'dark' ? 'dark' : 'light');

            toggle.addEventListener('click', () => {
                const nextTheme = document.body.classList.contains('theme-dark') ? 'light' : 'dark';
                localStorage.setItem(THEME_KEY, nextTheme);
                applyTheme(nextTheme);
            });
        })();

        (() => {
            const forms = document.querySelectorAll('.js-confirm-form');
            const backdrop = document.getElementById('confirmBackdrop');
            const modal = document.getElementById('confirmModal');
            const messageEl = document.getElementById('confirmMessage');
            const cancelBtn = document.getElementById('confirmCancel');
            const okBtn = document.getElementById('confirmOk');
            let targetForm = null;

            function closeModal() {
                backdrop.classList.remove('show');
                modal.classList.remove('show');
                targetForm = null;
            }

            forms.forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    targetForm = form;
                    messageEl.textContent = form.dataset.confirmMessage || 'Yakin lanjutkan aksi ini?';
                    backdrop.classList.add('show');
                    modal.classList.add('show');
                });
            });

            cancelBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);
            okBtn.addEventListener('click', () => {
                if (targetForm) {
                    targetForm.submit();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        })();

        (() => {
            const editBackdrop = document.getElementById('editBackdrop');
            if (!editBackdrop) return;

            const closeUrl = @json(route('admin.dashboard'));
            const closeEditModal = () => {
                window.location.href = closeUrl;
            };

            editBackdrop.addEventListener('click', closeEditModal);
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeEditModal();
                }
            });
        })();

        (() => {
            const flashBackdrop = document.getElementById('flashBackdrop');
            const flashModal = document.getElementById('flashModal');
            if (!flashBackdrop || !flashModal) return;

            const closeFlash = () => {
                flashBackdrop.classList.remove('show');
                flashModal.classList.remove('show');
            };

            const autoCloseTimer = setTimeout(closeFlash, 2200);
            flashBackdrop.addEventListener('click', () => {
                clearTimeout(autoCloseTimer);
                closeFlash();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    clearTimeout(autoCloseTimer);
                    closeFlash();
                }
            });
        })();
    </script>
</body>
</html>
