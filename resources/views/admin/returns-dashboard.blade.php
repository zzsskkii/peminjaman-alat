<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pengembalian</title>
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
        .top-links {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn, button {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }
        .btn {
            background: #1d3044;
            color: #edf4fb;
        }
        .btn.active {
            background: #ff8a3d;
            color: #1e1308;
        }
        button { background: #ff8a3d; color: #1e1308; }
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
        .section-title { margin: 24px 0 8px; }
        table {
            margin-top: 16px;
            width: 100%;
            border-collapse: collapse;
            background: #122232;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 10px;
            text-align: left;
            font-size: 0.95rem;
        }
        th { color: #93aac0; }
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
        .pill {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .pill.approved {
            background: #1b3b2a;
            color: #a7ebc3;
            border: 1px solid #2f7851;
        }
        .pill.pending {
            background: #46371f;
            color: #ffd18a;
            border: 1px solid #7f5b26;
        }
        .muted { color: #93aac0; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <div>
                <h1 style="margin: 0;">Dashboard Pengembalian</h1>
                <p style="margin: 4px 0 0; color: #93aac0;">Proses pengembalian dari admin.</p>
            </div>
            <div class="top-links">
                <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                <a class="btn active" href="{{ route('admin.returns.dashboard') }}">Pengembalian</a>
                <a class="btn" href="/">Back</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        <section class="metrics">
            <article class="box"><p>Menunggu Dikembalikan</p><h3>{{ $queueCount }}</h3></article>
            <article class="box"><p>Dikembalikan Hari Ini</p><h3>{{ $returnedTodayCount }}</h3></article>
            <article class="box"><p>Menunggu Persetujuan</p><h3>{{ $pendingApprovalCount }}</h3></article>
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

        <h2 class="section-title">Antrian Pengembalian (Disetujui)</h2>
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Card UID</th>
                    <th>Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Total Item</th>
                    <th>Persetujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returnQueue as $loan)
                    <tr>
                        <td>{{ $loan->student?->name ?? '-' }}</td>
                        <td>{{ $loan->student?->card_uid ?? '-' }}</td>
                        <td>{{ $loan->borrowed_at?->format('d-m-Y H:i') }}</td>
                        <td>{{ $loan->due_at?->format('d-m-Y H:i') }}</td>
                        <td>{{ $loan->loanItems->sum('quantity') }}</td>
                        <td><span class="pill approved">Disetujui</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.returns.process', $loan) }}" onsubmit="return confirm('Proses pengembalian untuk pinjaman ini?')">
                                @csrf
                                <button type="submit">Proses Kembali</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Tidak ada antrian pengembalian.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="section-title">Riwayat Pengembalian Terbaru</h2>
        <table>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Card UID</th>
                    <th>Pinjam</th>
                    <th>Dikembalikan</th>
                    <th>Metode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returnedLoans as $loan)
                    <tr>
                        <td>{{ $loan->student?->name ?? '-' }}</td>
                        <td>{{ $loan->student?->card_uid ?? '-' }}</td>
                        <td>{{ $loan->borrowed_at?->format('d-m-Y H:i') }}</td>
                        <td>{{ $loan->returned_at?->format('d-m-Y H:i') ?? '-' }}</td>
                        <td class="muted">{{ $loan->return_method ?? '-' }}</td>
                        <td class="muted">{{ $loan->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada riwayat pengembalian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
