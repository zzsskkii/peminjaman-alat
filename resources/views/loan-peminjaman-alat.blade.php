<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Inventaris - SMK TI Airlangga</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|ibm-plex-mono:400,500" rel="stylesheet" />
    
    <style>
        :root {
            --navy-dark: #001529; /* */
            --navy-sidebar: #002140; /* */
            --gold: #fcc419; /* */
            --white: #ffffff;
            --bg-main: #f8fafc;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; transition: all 0.25s ease-in-out; }

        body {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            background-color: var(--bg-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- SIDEBAR (Tetap Padat) --- */
        aside {
            width: 260px;
            background-color: var(--navy-sidebar);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 50;
        }

        .sidebar-brand {
            padding: 40px 20px;
            text-align: center;
            background: var(--navy-dark);
            border-bottom: 2px solid var(--gold);
        }

        .sidebar-brand h2 {
            margin: 0; color: var(--gold); font-size: 1.2rem; letter-spacing: 1px;
        }

        .nav-menu { padding: 20px 15px; flex-grow: 1; }

        .nav-item {
            display: flex; align-items: center; padding: 15px; margin-bottom: 10px;
            border-radius: 12px; cursor: pointer; color: rgba(255,255,255,0.6);
            font-weight: 600; text-decoration: none;
        }

        .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
        .nav-item.active { background: var(--gold); color: var(--navy-dark); }

        /* --- MAIN AREA --- */
        main { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; }

        header {
            background: var(--white); padding: 20px 40px;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid var(--border);
        }

        .status-indicator { display: flex; gap: 10px; align-items: center; }
        .dot { width: 10px; height: 10px; background: #22c55e; border-radius: 50%; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 25px;
            padding: 30px;
            height: calc(100vh - 80px);
        }

        /* --- ACTION SECTION (CENTER) --- */
        .content-main { display: flex; flex-direction: column; gap: 25px; }

        .tap-hero {
            background: var(--navy-dark);
            border-radius: 24px;
            padding: 50px;
            text-align: center;
            color: var(--white);
            position: relative;
            overflow: hidden;
            border: 2px solid var(--gold);
        }

        .tap-hero::before {
            content: ""; position: absolute; inset: 0;
            background-image: radial-gradient(circle at 2px 2px, rgba(252, 196, 25, 0.1) 1px, transparent 0);
            background-size: 20px 20px;
        }

        .tap-hero input {
            background: rgba(255,255,255,0.05); border: 2px solid var(--gold);
            border-radius: 15px; padding: 20px; color: var(--gold);
            font-size: 2rem; width: 100%; max-width: 400px; text-align: center;
            font-family: "IBM Plex Mono", monospace; outline: none; margin: 20px 0;
        }

        .tap-hero input::placeholder { color: rgba(252, 196, 25, 0.3); }

        /* --- STATS SECTION (RIGHT SIDE) --- */
        .content-side { display: flex; flex-direction: column; gap: 20px; }

        .stat-card {
            background: var(--white); border-radius: 20px; padding: 20px;
            border: 1px solid var(--border);
        }

        .stat-card h4 { margin: 0 0 10px 0; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; }
        .stat-card .val { font-size: 2rem; font-weight: 700; color: var(--navy-dark); }

        /* --- DYNAMIC ITEMS AREA --- */
        .items-area {
            background: var(--white); border-radius: 24px; padding: 25px;
            border: 1px solid var(--border); flex-grow: 1; overflow-y: auto;
        }

        .item-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;
        }

        .item-box {
            border: 1px solid var(--border); padding: 15px; border-radius: 15px;
            background: #fcfcfc; text-align: center;
        }

        .qty-badge {
            background: var(--navy-dark); color: var(--gold);
            padding: 4px 10px; border-radius: 8px; font-family: "IBM Plex Mono", monospace;
            font-size: 0.8rem; margin-top: 10px; display: inline-block;
        }

        .btn-action {
            background: var(--gold); color: var(--navy-dark); border: none;
            padding: 18px; border-radius: 15px; font-weight: 800;
            cursor: pointer; width: 100%; font-size: 1rem; margin-top: 20px;
        }

        .btn-action:hover { background: #e6b000; transform: scale(1.02); }

        .hidden { display: none; }
        .toast {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -56%) scale(0.96);
            width: min(520px, calc(100vw - 48px));
            min-height: 120px;
            padding: 20px 24px;
            border-radius: 16px;
            color: #ffffff;
            font-weight: 800;
            font-size: clamp(1.4rem, 3.8vw, 2.2rem);
            line-height: 1.2;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        .toast.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        .toast.success { background: #16a34a; }
        .toast.error {
            background: #dc2626;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.3), 0 0 0 4px rgba(220, 38, 38, 0.18);
        }
        .toast-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(1px);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        .toast-backdrop.show {
            opacity: 1;
        }

        /* --- PROFILE POPUP --- */
        .profile-bar {
            background: var(--gold); color: var(--navy-dark);
            padding: 15px 25px; border-radius: 15px; margin-bottom: 20px;
            display: flex; justify-content: space-between; align-items: center;
            font-weight: 700;
        }

        @media (max-width: 1100px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .content-side { display: none; }
        }
    </style>
</head>
<body>

    <aside>
        <div class="sidebar-brand">
            <h2>SMK TI AIRLANGGA</h2>
        </div>
        <nav class="nav-menu">
            <div id="navBorrow" class="nav-item active" onclick="setMode('borrow')">
                <span>PINJAM ALAT</span>
            </div>
            <div id="navReturn" class="nav-item" onclick="setMode('return')">
                <span>PENGEMBALIAN</span>
            </div>
            <a class="nav-item" href="{{ route('admin.login.form') }}">
                <span>LOGIN ADMIN</span>
            </a>
            <div class="nav-item" onclick="location.reload()">
                <span>REFRESH SISTEM</span>
            </div>
        </nav>
        <div style="padding: 20px; color: rgba(255,255,255,0.3); font-size: 0.75rem;">
            Inventory Management System v2.0
        </div>
    </aside>

    <main>
        <header>
            <h2 id="pageTitle" style="margin:0; color: var(--navy-dark);">Dashboard Peminjaman</h2>
            <div class="status-indicator">
                <span style="font-size: 0.9rem; font-weight: 600;">System Online</span>
                <div class="dot"></div>
            </div>
        </header>

        <div class="dashboard-grid">
            <div class="content-main">
                
                <section class="tap-hero">
                    <h3 style="margin:0; letter-spacing: 2px;">IDENTIFIKASI KARTU</h3>
                    <p style="color: rgba(255,255,255,0.6)">Silakan tap kartu pelajar pada reader untuk memulai</p>
                    <input id="cardUidInput" placeholder="Siswa UID..." autofocus autocomplete="off">
                    <div id="tapStatus" style="font-weight: 600; color: var(--gold)">Menunggu scan...</div>
                </section>

                <div id="dynamicContent" class="items-area">
                    <div id="defaultInstruction" style="text-align: center; padding: 40px;">
                        <img src="https://cdn-icons-png.flaticon.com/512/2910/2910795.png" width="80" style="opacity: 0.2; margin-bottom: 20px;">
                        <h3 style="color: var(--text-muted)">Belum Ada Kartu yang Terdeteksi</h3>
                        <p style="color: var(--text-muted)">Daftar alat akan muncul di sini secara otomatis setelah scan.</p>
                    </div>

                    <div id="studentProfile" class="profile-bar hidden"></div>

                    <div id="itemsContainer" class="item-grid hidden"></div>

                    <div id="actionFooter" class="hidden">
                        <textarea id="notesInput" style="width: 100%; margin-top:20px; padding:15px; border-radius:12px; border:1px solid var(--border)" rows="2" placeholder="Catatan tambahan..."></textarea>
                        <button id="mainActionButton" class="btn-action">KONFIRMASI SEKARANG</button>
                    </div>
                </div>
            </div>

            <div class="content-side">
                <div class="stat-card">
                    <h4>Total Alat Tersedia</h4>
                    <div class="val" id="totalItemsStat">342</div>
                </div>
                <div class="stat-card" style="border-left: 5px solid var(--gold);">
                    <h4>Peminjaman Aktif</h4>
                    <div class="val">18</div>
                </div>
                <div class="stat-card" style="background: var(--navy-dark); color: white;">
                    <h4 style="color: var(--gold)">Waktu Sistem</h4>
                    <div class="val" id="clock" style="color: white; font-family: 'IBM Plex Mono';">00:00</div>
                </div>
                <div class="stat-card">
                    <h4>Informasi</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                        Siswa wajib mengembalikan alat sebelum jam 16:00 WITA. Kerusakan alat menjadi tanggung jawab peminjam.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <div id="toastBackdrop" class="toast-backdrop" aria-hidden="true"></div>
    <div id="appToast" class="toast" aria-live="polite"></div>

    <script>
        // Data State
        const state = {
            mode: 'borrow',
            cardUid: '',
            student: null,
            items: @json($items),
            activeLoan: null
        };
        let toastTimer = null;

        function showToast(message, type = 'success') {
            const toast = document.getElementById('appToast');
            const backdrop = document.getElementById('toastBackdrop');
            toast.textContent = message;
            toast.className = `toast ${type}`;

            if (toastTimer) clearTimeout(toastTimer);

            requestAnimationFrame(() => {
                backdrop.classList.add('show');
                toast.classList.add('show');
            });

            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
                backdrop.classList.remove('show');
            }, 2200);
        }

        // UI Logic
        function setMode(mode) {
            state.mode = mode;
            document.getElementById('pageTitle').textContent = mode === 'borrow' ? 'Dashboard Peminjaman' : 'Dashboard Pengembalian';
            document.getElementById('navBorrow').classList.toggle('active', mode === 'borrow');
            document.getElementById('navReturn').classList.toggle('active', mode === 'return');
            resetUI();
        }

        function resetUI() {
            document.getElementById('defaultInstruction').classList.remove('hidden');
            document.getElementById('studentProfile').classList.add('hidden');
            document.getElementById('itemsContainer').classList.add('hidden');
            document.getElementById('actionFooter').classList.add('hidden');
            document.getElementById('cardUidInput').value = '';
            document.getElementById('cardUidInput').focus();
        }

        // Mock Jam
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').textContent = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
        }, 1000);

        // Core Interaction
        document.getElementById('cardUidInput').addEventListener('keypress', async (e) => {
            if (e.key === 'Enter') {
                const uid = e.target.value.trim();
                if(!uid) return;

                try {
                    // Tampilkan Loading
                    document.getElementById('tapStatus').textContent = "Memverifikasi data...";
                    
                    const response = await fetch('/tap-card', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ card_uid: uid, mode: state.mode })
                    });
                    const data = await response.json();
                    
                    if(!response.ok) throw new Error(data.message);

                    state.cardUid = uid;
                    state.student = data.student;

                    // Update UI Dinamis
                    document.getElementById('tapStatus').textContent = "Identitas Ditemukan!";
                    document.getElementById('tapStatus').style.color = 'var(--gold)';
                    document.getElementById('defaultInstruction').classList.add('hidden');
                    
                    // Show Profile
                    const profile = document.getElementById('studentProfile');
                    profile.innerHTML = `<span>SISWA: ${data.student.name}</span> <span>NIS: ${data.student.student_number}</span>`;
                    profile.classList.remove('hidden');

                    // Render Items
                    const container = document.getElementById('itemsContainer');
                    container.classList.remove('hidden');
                    document.getElementById('actionFooter').classList.remove('hidden');

                    if(state.mode === 'borrow') {
                        renderBorrow(data.items || state.items);
                    } else {
                        state.activeLoan = data.loan;
                        renderReturn(data.loan_items);
                    }

                } catch (err) {
                    document.getElementById('tapStatus').textContent = err.message;
                    document.getElementById('tapStatus').style.color = '#ef4444';
                }
            }
        });

        function renderBorrow(items) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = items.map(i => `
                <div class="item-box">
                    <strong>${i.name}</strong><br>
                    <span class="qty-badge">Stok: ${i.available_stock}</span>
                    <input type="number" class="qty-input" data-id="${i.id}" value="0" min="0" max="${i.available_stock}" style="width:100%; margin-top:10px; padding:5px; border-radius:5px;">
                </div>
            `).join('');
            document.getElementById('mainActionButton').textContent = "SIMPAN PEMINJAMAN";
        }

        function renderReturn(loanItems) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = loanItems.map(i => `
                <div class="item-box">
                    <strong>${i.item_name}</strong><br>
                    <span class="qty-badge">Sisa: ${i.remaining_quantity}</span>
                    <input type="number" class="qty-input" data-id="${i.item_id}" value="0" min="0" max="${i.remaining_quantity}" style="width:100%; margin-top:10px; padding:5px; border-radius:5px;">
                </div>
            `).join('');
            document.getElementById('mainActionButton').textContent = "PROSES PENGEMBALIAN";
        }

        // Action Save
        document.getElementById('mainActionButton').addEventListener('click', async () => {
            const inputs = Array.from(document.querySelectorAll('.qty-input'))
                .map(i => ({ item_id: Number(i.dataset.id), quantity: Number(i.value) }))
                .filter(i => i.quantity > 0);

            if (inputs.length === 0) {
                showToast('Pilih alat dulu!', 'error');
                return;
            }

            const url = state.mode === 'borrow' ? '/loans/borrow' : '/loans/return-items';
            const payload = state.mode === 'borrow' 
                ? { card_uid: state.cardUid, items: inputs, notes: document.getElementById('notesInput').value }
                : { card_uid: state.cardUid, loan_id: state.activeLoan.id, items: inputs };

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json().catch(() => ({}));
                if(!res.ok) throw new Error(data.message || 'Gagal menyimpan');
                showToast('Berhasil!', 'success');
                setTimeout(() => location.reload(), 1200);
            } catch (e) {
                showToast(e.message || 'Gagal menyimpan', 'error');
            }
        });
    </script>
</body>
</html>
