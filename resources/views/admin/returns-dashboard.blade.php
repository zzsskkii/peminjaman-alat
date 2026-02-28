<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pengembalian</title>
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

        .wrap {
            width: min(1500px, 100%);
            margin: 0 auto;
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

        .btn,
        button {
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 9px 14px;
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

        .metrics {
            margin-top: 14px;
            display: grid;
            grid-template-columns: repeat(3, minmax(180px, 1fr));
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

        .pill {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1px solid;
        }

        .pill.approved {
            background: #dcfce7;
            color: #166534;
            border-color: #86efac;
        }

        .pill.pending {
            background: #fef3c7;
            color: #92400e;
            border-color: #fcd34d;
        }

        .muted { color: var(--muted); }

        .section-note {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 1.12rem;
        }

        @media (max-width: 1200px) {
            .metrics {
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
        body.theme-dark {
            --bg-light: #0b1220;
            --white: #111827;
            --border: #334155;
            --text-dark: #e2e8f0;
            --muted: #94a3b8;
            --navy: #93c5fd;
            background: linear-gradient(180deg, #0b1220 0%, #020617 100%);
        }
        body.theme-dark .top,
        body.theme-dark .box,
        body.theme-dark .table-wrap,
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
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <div>
                <h1 style="margin: 0; color: var(--navy);">Dashboard Pengembalian</h1>
                <p class="section-note">Proses pengembalian dari admin.</p>
            </div>
            <div class="top-links">
                <button id="themeToggle" class="theme-switch" type="button" aria-label="Toggle theme" data-theme="light" aria-pressed="false">
                    <span class="theme-option" data-mode="light">ON</span>
                    <span class="theme-option" data-mode="dark">OFF</span>
                    <span class="theme-thumb" aria-hidden="true"></span>
                </button>
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
        <div class="table-wrap">
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
                                <form method="POST" action="{{ route('admin.returns.process', $loan) }}" class="js-confirm-form" data-confirm-message="Proses pengembalian untuk pinjaman ini?">
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
        </div>

        <h2 class="section-title">Riwayat Pengembalian Terbaru</h2>
        <div class="table-wrap">
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
    </div>

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
    </script>
</body>
</html>
