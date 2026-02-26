<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Inventaris - SMK TI Airlangga</title>
    
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,700|ibm-plex-mono:600" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --navy: #002140; /* */
            --gold: #fcc419; /* */
            --bg-light: #f8fafc;
            --white: #ffffff;
            --border: #e2e8f0;
            --text-dark: #0f172a;
        }

        * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

        body {
            margin: 0; padding: 0;
            font-family: "Space Grotesk", sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* --- SIDEBAR KIRI --- */
        aside {
            width: 280px;
            background-color: var(--white);
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
            background: var(--white);
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
        }

        .nav-btn.active {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
            box-shadow: 0 4px 15px rgba(252, 196, 25, 0.3);
        }

        /* --- MAIN CONTENT --- */
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 20px;
        }

        /* INTERACTION ZONE (ATAS) */
        .interaction-zone {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .tap-card {
            background: var(--white);
            border: 3px solid var(--navy);
            border-radius: 25px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .tap-card h2 { font-size: 1.8rem; margin: 0; color: var(--navy); letter-spacing: 1px; }

        .uid-input {
            background: var(--bg-light);
            border: 2px solid var(--gold);
            border-radius: 15px;
            padding: 15px;
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            font-size: 2.5rem;
            color: var(--navy);
            text-align: center;
            font-family: 'IBM Plex Mono', monospace;
            outline: none;
        }

        /* ITEMS AREA (TENGAH) */
        .items-display {
            flex: 1;
            background: var(--white);
            border-radius: 25px;
            padding: 20px;
            border: 2px solid var(--border);
            overflow-y: auto;
        }
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 12px;
        }
        .item-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 12px;
            background: #fff;
            font-size: 1.2rem;
        }
        .item-qty {
            width: 100%;
            margin-top: 10px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 14px;
            min-height: 64px;
            font-size: 1.4rem;
            font-weight: 700;
        }
        .profile-bar {
            background: var(--gold);
            border-radius: 14px;
            padding: 14px 18px;
            font-weight: 700;
            font-size: 1.35rem;
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
            padding: 12px;
            resize: vertical;
            min-height: 64px;
            font-size: 1.15rem;
        }
        .action-btn {
            border: none;
            border-radius: 12px;
            padding: 16px 22px;
            font-weight: 700;
            font-size: 1.3rem;
            background: var(--gold);
            color: var(--navy);
            cursor: pointer;
            min-height: 64px;
            width: 100%;
        }

        /* --- STATS BAR (BAWAH) --- */
        .stats-bar {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1.5fr; /* Layout sketsa */
            gap: 12px;
            height: 120px;
        }

        .stat-item {
            background: var(--white);
            border: 2px solid var(--border);
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-item .label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .stat-item .value { font-size: 2rem; font-weight: 800; color: var(--navy); }

        .info-box {
            background: var(--navy);
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
    </style>
</head>
<body>
    @php
        $mode = request()->query('mode', 'borrow');
        $isReturnMode = $mode === 'return';
    @endphp

    <aside>
        <div class="brand-box">
            <h1>SMK TI AIRLANGGA</h1> </div>

        <a href="{{ url('/') }}?mode=borrow" class="nav-btn {{ $isReturnMode ? '' : 'active' }}">
            <i data-lucide="log-in" size="32"></i>
            <span>PINJAM ALAT</span>
        </a>

        <a href="{{ url('/') }}?mode=return" class="nav-btn {{ $isReturnMode ? 'active' : '' }}">
            <i data-lucide="log-out" size="32"></i>
            <span>PENGEMBALIAN</span>
        </a>

        <a href="{{ route('admin.login.form') }}" class="nav-btn">
            <i data-lucide="user-cog" size="32"></i>
            <span>ADMIN LOGIN</span>
        </a>

        <button type="button" class="nav-btn" onclick="location.reload()">
            <i data-lucide="refresh-cw" size="32"></i>
            <span>REFRESH</span>
        </button>
    </aside>

    <main>
        <div class="interaction-zone">
            <div style="display:flex; justify-content: space-between; align-items: center; padding: 0 10px;">
                <h2 style="margin:0; font-size: 1.2rem; color: #64748b;">{{ $isReturnMode ? 'Dashboard Pengembalian' : 'Dashboard Peminjaman' }}</h2>
                <div style="color: #10b981; font-weight: 700; font-size: 0.9rem;">● SYSTEM ONLINE</div>
            </div>

            <section class="tap-card">
                <h2>IDENTIFIKASI KARTU</h2>
                <p style="color: #64748b; margin-top: 5px;">(Silakan Tap Kartu Pelajar)</p>
                <input id="cardUidInput" class="uid-input" placeholder="Siswa UID..." autofocus>
                <div id="tapStatus" style="font-weight: 700; color: var(--navy);">Menunggu Scan...</div>
            </section>

            <div id="itemsDisplay" class="items-display">
                <div id="defaultInstruction" style="text-align: center; padding: 40px; color: #cbd5e1;">
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
                    <div style="color:#64748b; margin-top:4px;">Stok: ${item.available_stock}</div>
                    <input class="item-qty" type="number" min="0" max="${item.available_stock}" value="0" data-id="${item.id}">
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
                    <div style="color:#64748b; margin-top:4px;">Sisa: ${item.remaining_quantity}</div>
                    <input class="item-qty" type="number" min="0" max="${item.remaining_quantity}" value="0" data-id="${item.item_id}">
                </div>
            `).join('');
            document.getElementById('mainActionButton').textContent = 'PROSES PENGEMBALIAN';
            document.getElementById('notesInput').value = '';
            document.getElementById('notesInput').parentElement.classList.remove('hidden');
        }

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
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').textContent = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        }, 1000);
    </script>
</body>
</html>
