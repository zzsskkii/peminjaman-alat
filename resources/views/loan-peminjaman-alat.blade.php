<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Inventaris - SMK TI Airlangga</title>
    
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,700|ibm-plex-mono:600|share-tech-mono:400" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --navy: #002140; /* */
            --gold: #fcc419;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --border: #e2e8f0;
            --text-dark: #0f172a;
            --muted: #64748b;
            --soft-shadow: 0 16px 30px -22px rgba(15, 23, 42, 0.6);
            --body-grad-a: rgba(252, 196, 25, 0.12);
            --body-grad-b: rgba(0, 33, 64, 0.12);
            --surface: rgba(255, 255, 255, 0.92);
            --surface-strong: #ffffff;
            --surface-soft: #f8fafc;
            --border-strong: rgba(0, 33, 64, 0.9);
            --text-primary: #0f172a;
            --text-secondary: #64748b;
        }

        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

        body {
            margin: 0; padding: 0;
            font-family: "Space Grotesk", sans-serif;
            background:
                radial-gradient(circle at 90% 10%, var(--body-grad-a) 0%, rgba(252, 196, 25, 0) 35%),
                radial-gradient(circle at 0% 100%, var(--body-grad-b) 0%, rgba(0, 33, 64, 0) 40%),
                var(--bg-light);
            color: var(--text-primary);
            height: 100vh;
            display: flex;
            overflow: hidden;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* --- SIDEBAR KIRI --- */
        aside {
            width: 280px;
            background-color: var(--surface);
            backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 12px;
            border-right: 2px solid var(--border);
        }

        .brand-box {
            padding: 20px 10px;
            text-align: center;
            border-bottom: 2px solid var(--gold);
            margin-bottom: 10px;
        }

        .brand-box h1 { font-size: 1.1rem; color: var(--navy); margin: 0; font-weight: 800; }

        .nav-btn {
            flex: 1; /* Tombol tinggi memenuhi sidebar */
            border: 2px solid var(--border);
            border-radius: 15px;
            background: var(--surface-strong);
            color: var(--navy);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 10px 24px -24px rgba(15, 23, 42, 0.8);
        }

        .nav-btn.active {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
            box-shadow: 0 4px 15px rgba(252, 196, 25, 0.3);
        }
        .nav-btn:hover { transform: translateY(-2px); }
        .nav-btn.nav-admin {
            flex: 0 0 auto;
            min-height: 64px;
            font-size: 0.95rem;
            padding: 12px 14px;
            border-radius: 12px;
            flex-direction: row;
            gap: 8px;
            margin-top: auto;
            align-self: stretch;
            width: 100%;
            justify-content: center;
            background: linear-gradient(90deg, #0b3a67 0%, #0f4e86 100%);
            border-color: #0b3a67;
            color: #ffffff;
            box-shadow: 0 10px 22px -18px rgba(11, 58, 103, 0.9);
        }
        .nav-btn.nav-admin:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }
        /* --- MAIN CONTENT --- */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 14px;
            min-width: 0;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 14px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--surface);
            backdrop-filter: blur(6px);
        }
        .top-title {
            margin: 0;
            font-size: 1.05rem;
            color: var(--muted);
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .status-pill {
            color: #059669;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 999px;
            padding: 7px 12px;
            font-weight: 700;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        .top-actions {
            display: flex;
            align-items: center;
            gap: 8px;
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

        /* INTERACTION ZONE (ATAS) */
        .interaction-zone {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 14px;
            min-height: 0;
        }

        .tap-card {
            background: linear-gradient(180deg, var(--surface-strong) 0%, var(--surface-soft) 100%);
            border: 2px solid var(--border-strong);
            border-radius: 22px;
            padding: 24px 30px 20px;
            text-align: center;
            box-shadow: var(--soft-shadow);
        }

        .tap-card h2 { font-size: 1.8rem; margin: 0; color: var(--navy); letter-spacing: 0.05em; }
        .tap-card p {
            color: var(--muted);
            margin-top: 6px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .uid-input {
            background: var(--surface-soft);
            border: 2px solid var(--gold);
            border-radius: 15px;
            padding: 15px;
            width: 100%;
            max-width: 500px;
            margin: 16px auto 10px;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            color: var(--navy);
            text-align: center;
            font-family: 'IBM Plex Mono', monospace;
            outline: none;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .uid-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(252, 196, 25, 0.26);
        }
        #tapStatus {
            font-weight: 700;
            color: var(--navy);
            min-height: 1.4em;
        }

        /* ITEMS AREA (TENGAH) */
        .items-display {
            flex: 0 1 auto;
            background: var(--surface);
            border-radius: 22px;
            padding: 16px;
            border: 2px solid var(--border);
            overflow-y: auto;
            max-height: calc(100vh - 340px);
            box-shadow: 0 10px 30px -28px rgba(15, 23, 42, 0.9);
        }
        .default-instruction {
            text-align: center;
            padding: 48px 20px;
            color: #94a3b8;
            border: 2px dashed #dbe4f1;
            border-radius: 16px;
            background: var(--surface-soft);
            min-height: 210px;
            display: grid;
            place-content: center;
        }
        .default-instruction h3 {
            margin: 10px 0 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #94a3b8;
        }
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }
        .item-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            background: var(--surface-strong);
            font-size: 1.4rem;
            box-shadow: 0 12px 24px -26px rgba(15, 23, 42, 0.9);
        }
        .item-photo {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            border: 1px solid #d1d9e6;
            border-radius: 12px;
            background: #e2e8f0;
            margin: 10px 0 10px;
            display: block;
        }
        .item-meta {
            color: #64748b;
            margin-top: 4px;
            font-size: 0.95em;
            font-weight: 600;
        }
        .item-qty {
            -moz-appearance: textfield;
            appearance: textfield;
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 0;
            min-height: 72px;
            font-size: 1.9rem;
            font-weight: 700;
            font-family: 'IBM Plex Mono', monospace;
            text-align: center;
            color: var(--navy);
            background: #f8fafc;
        }
        .item-qty::-webkit-outer-spin-button,
        .item-qty::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .qty-control {
            margin-top: 12px;
            border: 1px solid #b7c6da;
            border-radius: 14px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 74px 1fr 74px;
            min-height: 72px;
            background: #ffffff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75), 0 10px 16px -16px rgba(15, 23, 42, 0.75);
        }
        .qty-btn {
            border: none;
            background: linear-gradient(180deg, #f1f5fb 0%, #dfe8f5 100%);
            color: #1f2f47;
            font-size: 1.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: filter 0.18s ease, transform 0.12s ease, box-shadow 0.12s ease;
            min-width: 44px;
            min-height: 44px;
        }
        .qty-btn:hover {
            filter: brightness(0.99);
            box-shadow: inset 0 0 0 999px rgba(255, 255, 255, 0.12);
        }
        .qty-btn:active {
            transform: scale(0.96);
            filter: brightness(0.93);
        }
        .qty-btn.minus {
            border-right: 1px solid #9fb1c8;
            color: #334155;
        }
        .qty-btn.plus {
            border-left: 1px solid #9fb1c8;
            color: #0b3a67;
            background: linear-gradient(180deg, #eaf3ff 0%, #d6e7fb 100%);
        }
        .qty-value {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #ffffff 0%, #f7fafd 100%);
            border: none;
            min-width: 0;
            font-size: 1.9rem;
            font-weight: 800;
            color: #d97706;
            letter-spacing: 0.01em;
        }
        .qty-value:focus {
            outline: none;
        }
        .profile-bar {
            background: linear-gradient(95deg, #ffe082 0%, #fcc419 85%);
            border-radius: 14px;
            padding: 16px 20px;
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .action-footer {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            flex-direction: column;
            align-items: stretch;
        }
        .action-footer textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px;
            resize: vertical;
            min-height: 78px;
            font-size: 1.35rem;
        }
        .action-btn {
            border: none;
            border-radius: 12px;
            padding: 18px 22px;
            font-weight: 700;
            font-size: 2rem;
            background: linear-gradient(90deg, #facc15 0%, #f59e0b 100%);
            color: var(--navy);
            cursor: pointer;
            min-height: 84px;
            width: 100%;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 18px -18px rgba(15, 23, 42, 0.9);
        }

        /* --- STATS BAR (BAWAH) --- */
        .stats-bar {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1.5fr; /* Layout sketsa */
            gap: 12px;
            min-height: 92px;
        }

        .stat-item {
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 6px 10px;
        }

        .stat-item .label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .stat-item .value { font-size: 2rem; font-weight: 800; color: var(--navy); }
        #clock {
            font-family: "Share Tech Mono", "IBM Plex Mono", monospace;
            letter-spacing: 0.05em;
            font-size: 2.25rem;
            text-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
        }

        .info-box {
            background: linear-gradient(120deg, #03284d 0%, #0b3a67 100%);
            color: var(--white);
            border-radius: 18px;
            padding: 15px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            line-height: 1.4;
        }

        .hidden { display: none; }
        .toast {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -54%) scale(0.96);
            width: min(440px, calc(100vw - 40px));
            min-height: 96px;
            padding: 16px 18px;
            border-radius: 16px;
            color: #fff;
            font-weight: 700;
            font-size: clamp(1rem, 2.6vw, 1.25rem);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.28);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            overflow: hidden;
        }
        .toast::before {
            content: "";
            position: absolute;
            inset: -30% -10%;
            background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, rgba(255,255,255,0) 60%);
            pointer-events: none;
        }
        .toast.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        .toast.success {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border: 1px solid rgba(255,255,255,0.24);
        }
        .toast.error {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border: 1px solid rgba(255,255,255,0.24);
        }
        .toast-backdrop {
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
        .toast-backdrop.show {
            opacity: 1;
        }

        body.theme-dark {
            --bg-light: #0b1220;
            --body-grad-a: rgba(59, 130, 246, 0.12);
            --body-grad-b: rgba(15, 23, 42, 0.75);
            --surface: rgba(15, 23, 42, 0.8);
            --surface-strong: #111827;
            --surface-soft: #0f172a;
            --border: #334155;
            --border-strong: #93c5fd;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --navy: #93c5fd;
            --text-dark: #e2e8f0;
        }
        body.theme-dark .brand-box h1,
        body.theme-dark .top-title,
        body.theme-dark .tap-card h2,
        body.theme-dark #tapStatus,
        body.theme-dark .stat-item .value,
        body.theme-dark .nav-btn,
        body.theme-dark .item-title,
        body.theme-dark .info-box,
        body.theme-dark .action-btn {
            color: #e2e8f0;
        }
        body.theme-dark .nav-btn {
            background: #111827;
            border-color: #334155;
            color: #e2e8f0;
        }
        body.theme-dark .nav-btn.active {
            background: var(--gold);
            border-color: var(--gold);
            color: #0f172a;
            box-shadow: 0 8px 20px -14px rgba(252, 196, 25, 0.7);
        }
        body.theme-dark .nav-btn.active i,
        body.theme-dark .nav-btn.active span {
            color: #0f172a;
        }
        body.theme-dark .brand-box h1 {
            color: #f8fafc;
        }
        body.theme-dark .top-bar,
        body.theme-dark aside {
            background: rgba(15, 23, 42, 0.76);
        }
        body.theme-dark .tap-card {
            box-shadow: 0 16px 30px -22px rgba(0, 0, 0, 0.75);
        }
        body.theme-dark .uid-input,
        body.theme-dark .item-qty,
        body.theme-dark .qty-value,
        body.theme-dark .action-footer textarea {
            background: #0b1220;
            color: #e2e8f0;
            border-color: #334155;
        }
        body.theme-dark .item-meta,
        body.theme-dark .tap-card p,
        body.theme-dark .stat-item .label {
            color: #94a3b8;
        }
        body.theme-dark .default-instruction {
            color: #94a3b8;
            border-color: #334155;
        }
        body.theme-dark .item-card,
        body.theme-dark .stat-item {
            border-color: #334155;
        }
        body.theme-dark .qty-control {
            border-color: #475569;
            background: #0f172a;
        }
        body.theme-dark .qty-btn {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #cbd5e1;
        }
        body.theme-dark .qty-btn.plus {
            background: linear-gradient(180deg, #0b3a67 0%, #1d4f8f 100%);
            color: #dbeafe;
        }
        body.theme-dark .status-pill {
            background: #052e2b;
            color: #6ee7b7;
            border-color: #065f46;
        }
        @media (max-width: 1180px) {
            aside { width: 230px; }
            .nav-btn { font-size: 0.92rem; }
            .stats-bar { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 900px) {
            body {
                flex-direction: column;
                overflow-y: auto;
                height: auto;
                min-height: 100vh;
            }
            aside {
                width: 100%;
                border-right: 1px solid var(--border);
                border-bottom: 1px solid var(--border);
                flex-direction: row;
                overflow-x: auto;
                padding: 12px;
                gap: 10px;
            }
            .brand-box {
                display: none;
            }
            .nav-btn {
                min-width: 170px;
                min-height: 86px;
                flex: 0 0 auto;
            }
            .nav-btn.nav-admin {
                min-width: 100%;
                min-height: 52px;
                font-size: 0.78rem;
                margin-top: 0;
                align-self: auto;
            }
            main {
                padding: 12px;
                gap: 12px;
            }
            .tap-card {
                padding: 18px 16px;
            }
            .tap-card h2 { font-size: 1.35rem; }
            .uid-input {
                font-size: 1.55rem;
                padding: 12px;
            }
            .items-display {
                max-height: none;
            }
            .item-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .item-card {
                font-size: 1.15rem;
                padding: 12px;
            }
            .item-photo {
                margin: 8px 0;
                border-radius: 10px;
            }
            .item-qty {
                min-height: 56px;
                font-size: 1.3rem;
            }
            .qty-control {
                min-height: 60px;
                grid-template-columns: 60px 1fr 60px;
            }
            .qty-btn {
                font-size: 1.6rem;
            }
            .qty-value {
                font-size: 1.45rem;
            }
            .action-footer textarea {
                min-height: 66px;
                font-size: 1.05rem;
            }
            .action-btn {
                min-height: 66px;
                font-size: 1.35rem;
            }
            .profile-bar {
                font-size: 1.05rem;
                padding: 12px;
            }
            .stats-bar {
                grid-template-columns: 1fr;
            }
            .stat-item .value { font-size: 1.55rem; }
            .info-box { min-height: 68px; }
        }
    </style>
</head>
<body>
    @php
        $mode = request()->query('mode', 'borrow');
        $isReturnMode = $mode === 'return';
    @endphp

    <aside>
        <div class="brand-box">
            <h1>SMK TI AIRLANGGA</h1>
        </div>

        <a href="{{ url('/') }}?mode=borrow" class="nav-btn {{ $isReturnMode ? '' : 'active' }}">
            <i data-lucide="log-in" size="32"></i>
            <span>PINJAM ALAT</span>
        </a>

        <a href="{{ url('/') }}?mode=return" class="nav-btn {{ $isReturnMode ? 'active' : '' }}">
            <i data-lucide="log-out" size="32"></i>
            <span>PENGEMBALIAN</span>
        </a>

        <button type="button" class="nav-btn" onclick="location.reload()">
            <i data-lucide="refresh-cw" size="32"></i>
            <span>REFRESH</span>
        </button>

        <a href="{{ route('admin.login.form') }}" class="nav-btn nav-admin">
            <i data-lucide="user-cog" size="18"></i>
            <span>ADMIN LOGIN</span>
        </a>
    </aside>

    <main>
        <div class="interaction-zone">
            <div class="top-bar">
                <h2 class="top-title">{{ $isReturnMode ? 'Dashboard Pengembalian' : 'Dashboard Peminjaman' }}</h2>
                <div class="top-actions">
                    <button id="themeToggle" class="theme-switch" type="button" aria-label="Toggle theme" data-theme="light" aria-pressed="false">
                        <span class="theme-option" data-mode="light">ON</span>
                        <span class="theme-option" data-mode="dark">OFF</span>
                        <span class="theme-thumb" aria-hidden="true"></span>
                    </button>
                    <div class="status-pill">SYSTEM ONLINE</div>
                </div>
            </div>

            <section class="tap-card">
                <h2>IDENTIFIKASI KARTU</h2>
                <p>(Silakan Tap Kartu Pelajar)</p>
                <input id="cardUidInput" class="uid-input" placeholder="Siswa UID..." autofocus>
                <div id="tapStatus">Menunggu Scan...</div>
            </section>

            <div id="itemsDisplay" class="items-display">
                <div id="defaultInstruction" class="default-instruction">
                    <i data-lucide="layers" size="64"></i>
                    <h3>Daftar alat akan muncul di sini.</h3>
                </div>
                <div id="studentProfile" class="profile-bar hidden"></div>
                <div id="itemsContainer" class="item-grid hidden"></div>
                <div id="actionFooter" class="action-footer hidden">
                    <textarea id="notesInput" placeholder="Catatan tambahan..."></textarea>
                    <button id="mainActionButton" type="button" class="action-btn">SIMPAN</button>
                </div>
            </div>
        </div>

        <footer class="stats-bar">
            <div class="stat-item">
                <div class="label">Total Alat</div>
                <div class="value">342</div>
            </div>
            <div class="stat-item">
                <div class="label">Dipinjam</div>
                <div class="value">18</div>
            </div>
            <div class="stat-item">
                <div class="label">Jam</div>
                <div id="clock" class="value">21:11</div>
            </div>
            <div class="info-box">
                <p><strong>INFO:</strong> Siswa wajib mengembalikan alat tepat waktu. Segala kerusakan menjadi tanggung jawab peminjam.</p>
            </div>
        </footer>
    </main>
    <div id="toastBackdrop" class="toast-backdrop" aria-hidden="true"></div>
    <div id="appToast" class="toast" aria-live="polite"></div>

    <script>
        const state = {
            mode: @json($isReturnMode ? 'return' : 'borrow'),
            cardUid: '',
            student: null,
            items: @json($items),
            activeLoan: null,
        };
        let toastTimer = null;
        let scanTimer = null;
        let isScanProcessing = false;
        const THEME_KEY = 'peminjaman-theme';

        function applyTheme(theme) {
            const body = document.body;
            const toggle = document.getElementById('themeToggle');
            const isDark = theme === 'dark';
            body.classList.toggle('theme-dark', isDark);
            if (toggle) {
                toggle.dataset.theme = isDark ? 'dark' : 'light';
                toggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            }
        }

        function initThemeToggle() {
            const toggle = document.getElementById('themeToggle');
            if (!toggle) return;

            const saved = localStorage.getItem(THEME_KEY);
            const initialTheme = saved === 'dark' ? 'dark' : 'light';
            applyTheme(initialTheme);

            toggle.addEventListener('click', () => {
                const nextTheme = document.body.classList.contains('theme-dark') ? 'light' : 'dark';
                localStorage.setItem(THEME_KEY, nextTheme);
                applyTheme(nextTheme);
            });
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('appToast');
            const backdrop = document.getElementById('toastBackdrop');
            toast.textContent = message;
            toast.className = `toast ${type}`;

            if (toastTimer) {
                clearTimeout(toastTimer);
            }

            requestAnimationFrame(() => {
                backdrop.classList.add('show');
                toast.classList.add('show');
            });

            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
                backdrop.classList.remove('show');
            }, 2200);
        }

        function renderBorrow(items) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = items.map((item) => `
                <div class="item-card">
                    <strong>${item.name}</strong>
                    <div class="item-meta">Stok: ${item.available_stock}</div>
                    <img class="item-photo" src="${getItemImageUrl(item.name)}" alt="${item.name}" loading="lazy" onerror="this.onerror=null;this.src='${getFallbackImageData()}';">
                    <div class="qty-control">
                        <button type="button" class="qty-btn minus" data-action="decrease" aria-label="Kurangi jumlah">-</button>
                        <input class="item-qty qty-value" type="number" min="0" max="${item.available_stock}" value="0" data-id="${item.id}" readonly>
                        <button type="button" class="qty-btn plus" data-action="increase" aria-label="Tambah jumlah">+</button>
                    </div>
                </div>
            `).join('');
            document.getElementById('mainActionButton').textContent = 'SIMPAN PEMINJAMAN';
            document.getElementById('notesInput').parentElement.classList.remove('hidden');
        }

        function renderReturn(loanItems) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = loanItems.map((item) => `
                <div class="item-card">
                    <strong>${item.item_name}</strong>
                    <div class="item-meta">Sisa: ${item.remaining_quantity}</div>
                    <img class="item-photo" src="${getItemImageUrl(item.item_name)}" alt="${item.item_name}" loading="lazy" onerror="this.onerror=null;this.src='${getFallbackImageData()}';">
                    <div class="qty-control">
                        <button type="button" class="qty-btn minus" data-action="decrease" aria-label="Kurangi jumlah">-</button>
                        <input class="item-qty qty-value" type="number" min="0" max="${item.remaining_quantity}" value="0" data-id="${item.item_id}" readonly>
                        <button type="button" class="qty-btn plus" data-action="increase" aria-label="Tambah jumlah">+</button>
                    </div>
                </div>
            `).join('');
            document.getElementById('mainActionButton').textContent = 'PROSES PENGEMBALIAN';
            document.getElementById('notesInput').value = '';
            document.getElementById('notesInput').parentElement.classList.remove('hidden');
        }

        function getFallbackImageData() {
            const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='640' height='360' viewBox='0 0 640 360'><defs><linearGradient id='g' x1='0' x2='1' y1='0' y2='1'><stop stop-color='#e2e8f0' offset='0'/><stop stop-color='#cbd5e1' offset='1'/></linearGradient></defs><rect fill='url(#g)' width='640' height='360'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#334155' font-size='34' font-family='Arial, sans-serif'>Foto barang</text></svg>`;
            return `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`;
        }

        function getItemImageUrl(itemName) {
            const name = String(itemName || '').toLowerCase();
            if (name.includes('hdmi') || name.includes('kabel')) return '/images/items/hdmi-cable.svg';
            if (name.includes('laptop') || name.includes('notebook')) return '/images/items/laptop.svg';
            if (name.includes('proyektor') || name.includes('projector')) return '/images/items/projector.svg';
            if (name.includes('remote')) return '/images/items/remote.svg';
            if (name.includes('speaker') || name.includes('audio')) return '/images/items/speaker.svg';
            return '/images/items/generic-equipment.svg';
        }

        document.addEventListener('click', (event) => {
            const button = event.target.closest('.qty-btn');
            if (!button) return;

            const control = button.closest('.qty-control');
            const input = control ? control.querySelector('.item-qty') : null;
            if (!input) return;

            const max = Number(input.max || 0);
            const min = Number(input.min || 0);
            const current = Number(input.value || 0);
            const step = button.dataset.action === 'increase' ? 1 : -1;
            const next = Math.min(max, Math.max(min, current + step));
            input.value = next;
        });

        async function onTapCard(uid) {
            const tapStatus = document.getElementById('tapStatus');
            tapStatus.textContent = 'Memverifikasi data...';
            tapStatus.style.color = 'var(--navy)';

            const response = await fetch('/tap-card', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ card_uid: uid, mode: state.mode }),
            });
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Gagal membaca kartu.');
            }

            state.cardUid = uid;
            state.student = data.student;
            if (state.mode === 'return') {
                state.activeLoan = data.loan;
            }

            document.getElementById('defaultInstruction').classList.add('hidden');
            document.getElementById('itemsContainer').classList.remove('hidden');
            document.getElementById('actionFooter').classList.remove('hidden');

            const profile = document.getElementById('studentProfile');
            profile.classList.remove('hidden');
            profile.innerHTML = `<span>SISWA: ${data.student.name}</span><span>NIS: ${data.student.student_number}</span>`;

            tapStatus.textContent = 'Kartu valid, lanjutkan proses.';
            tapStatus.style.color = '#16a34a';

            if (state.mode === 'borrow') {
                renderBorrow(data.items || state.items);
            } else {
                renderReturn(data.loan_items || []);
            }
        }

        async function processScannedUid(force = false) {
            if (isScanProcessing) return;

            const input = document.getElementById('cardUidInput');
            const uid = input.value.trim();

            // Scanner biasanya kirim karakter cepat; debounce ini membuat proses otomatis tanpa Enter.
            if (!force && uid.length < 6) return;

            try {
                isScanProcessing = true;
                await onTapCard(uid);
            } catch (error) {
                const tapStatus = document.getElementById('tapStatus');
                tapStatus.textContent = error.message;
                tapStatus.style.color = '#dc2626';
            } finally {
                isScanProcessing = false;
            }
        }

        const cardUidInput = document.getElementById('cardUidInput');
        cardUidInput.addEventListener('input', () => {
            if (scanTimer) clearTimeout(scanTimer);
            scanTimer = setTimeout(() => {
                processScannedUid(false);
            }, 220);
        });

        cardUidInput.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') return;
            event.preventDefault();
            if (scanTimer) clearTimeout(scanTimer);
            processScannedUid(true);
        });

        document.getElementById('mainActionButton').addEventListener('click', async () => {
            const selectedItems = Array.from(document.querySelectorAll('.item-qty'))
                .map((input) => ({ item_id: Number(input.dataset.id), quantity: Number(input.value) }))
                .filter((item) => item.quantity > 0);

            if (selectedItems.length === 0) {
                showToast('Pilih item dulu.', 'error');
                return;
            }

            const url = state.mode === 'borrow' ? '/loans/borrow' : '/loans/return-items';
            const payload = state.mode === 'borrow'
                ? { card_uid: state.cardUid, items: selectedItems, notes: document.getElementById('notesInput').value }
                : { card_uid: state.cardUid, loan_id: state.activeLoan.id, items: selectedItems };

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menyimpan.');
                }
                showToast(state.mode === 'borrow' ? 'Peminjaman berhasil disimpan.' : 'Pengembalian berhasil diproses.', 'success');
                setTimeout(() => location.reload(), 900);
            } catch (error) {
                showToast(error.message || 'Gagal menyimpan data.', 'error');
            }
        });

        lucide.createIcons();
        initThemeToggle();
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').textContent = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        }, 1000);
    </script>
</body>
</html>
