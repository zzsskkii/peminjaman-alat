<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
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
            --danger: #991b1b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Space Grotesk", sans-serif;
            background: linear-gradient(180deg, var(--bg-light) 0%, #f1f5f9 100%);
            color: var(--text-dark);
            padding: 20px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .theme-wrap {
            width: 100%;
            max-width: 420px;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
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
        .card {
            width: 100%;
            max-width: 420px;
            padding: 22px;
            border-radius: 20px;
            border: 2px solid var(--border);
            background: var(--white);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }
        h1 {
            margin: 0 0 8px;
            color: var(--navy);
        }
        p {
            margin: 0 0 16px;
            color: var(--muted);
        }
        label {
            display: block;
            margin: 10px 0 6px;
            font-weight: 600;
            color: var(--navy);
        }
        input {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-light);
            color: var(--text-dark);
            font-family: inherit;
        }
        button {
            margin-top: 14px;
            width: 100%;
            padding: 11px 12px;
            border: 2px solid var(--gold);
            border-radius: 10px;
            background: var(--gold);
            color: var(--navy);
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
        }
        .back-link {
            display: inline-block;
            margin-top: 14px;
            color: var(--navy);
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            color: #0d3b66;
            text-decoration: underline;
        }
        .error {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: var(--danger);
        }
        body.theme-dark {
            --bg-light: #0b1220;
            --white: #111827;
            --border: #334155;
            --text-dark: #e2e8f0;
            --muted: #94a3b8;
            --navy: #93c5fd;
            --danger: #f87171;
            background: linear-gradient(180deg, #0b1220 0%, #020617 100%);
        }
        body.theme-dark .card {
            box-shadow: 0 12px 24px -20px rgba(0, 0, 0, 0.95);
        }
        body.theme-dark input {
            background: #0b1220;
            border-color: #334155;
            color: #e2e8f0;
        }
        body.theme-dark .back-link {
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="theme-wrap">
        <button id="themeToggle" class="theme-switch" type="button" aria-label="Toggle theme" data-theme="light" aria-pressed="false">
            <span class="theme-option" data-mode="light">ON</span>
            <span class="theme-option" data-mode="dark">OFF</span>
            <span class="theme-thumb" aria-hidden="true"></span>
        </button>
    </div>
    <form class="card" method="POST" action="{{ route('admin.login') }}">
        @csrf
        <h1>Login Admin</h1>
        <p>Masuk untuk mengelola data peminjaman alat.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Masuk</button>
        <a class="back-link" href="{{ url('/') }}">← Kembali ke Landing Page</a>
    </form>
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
    </script>
</body>
</html>
