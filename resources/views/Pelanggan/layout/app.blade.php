<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — MyCam Rental</title>
    <link rel="stylesheet" href="{{ asset('template/assets/css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/donezo.css') }}">
    <script src="{{ asset('template/assets/vendor/lucide.min.js') }}"></script>
    <style>
        .mycam-tooltip-container {
            position: relative;
            display: inline-flex;
        }
        .mycam-tooltip-box {
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background-color: #111827;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.15s ease-in-out;
            z-index: 50;
            pointer-events: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .mycam-tooltip-box::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 4px;
            border-style: solid;
            border-color: #111827 transparent transparent transparent;
        }
        .mycam-tooltip-container:hover .mycam-tooltip-box {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>

<body class="bg-[#f3f5f4] text-gray-900 font-sans antialiased h-full flex flex-col selection:bg-brand-400 selection:text-white">

    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm pointer-events-none"></div>

    <!-- Top Navigation Header -->
    @include('Pelanggan.layout.header')

    <!-- Main Content -->
    <div class="flex-1">
        @yield('content')
    </div>

    <!-- Global Modal Keranjang Sewa Multi-Peminjaman (UKK Multi Peminjaman & Pembayaran) -->
    <div id="modal-keranjang-sewa"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto"
        onclick="if(event.target===this)closeModal('modal-keranjang-sewa')">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-7 transform transition-all scale-95 my-8 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-2xl bg-brand-50 text-brand-800 flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900 tracking-tight">Keranjang Sewa Multi-Alat</h3>
                        <p class="text-[11px] text-gray-400">Pinjam beberapa alat kamera dalam satu transaksi</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-keranjang-sewa')" class="p-1.5 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Cart Body Container -->
            <div id="cart-content-wrapper" class="pt-4 space-y-4">
                <!-- If Cart is Empty -->
                <div id="cart-empty-state" class="py-12 text-center text-gray-400 space-y-3 hidden">
                    <div class="w-16 h-16 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto text-gray-300">
                        <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-gray-700">Keranjang Masih Kosong</p>
                        <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto">Pilih kamera atau lensa dari katalog dan klik tombol <strong>+ Sewa</strong>.</p>
                    </div>
                    <button type="button" onclick="closeModal('modal-keranjang-sewa')" class="donezo-btn donezo-btn-primary text-xs mx-auto">
                        Lihat Katalog Kamera
                    </button>
                </div>

                <!-- Active Loan Alert (UKK Tantangan 1) -->
                @php
                    $userHasActiveLoan = Auth::check() && Auth::user()->role === 'Pelanggan'
                        ? \App\Models\Peminjaman::where('user_id', Auth::id())->whereIn('status', ['Menunggu', 'Diaprove', 'Dipinjam'])->exists()
                        : false;
                @endphp

                @if($userHasActiveLoan)
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs space-y-1">
                        <p class="font-bold flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i>
                            Peminjaman Aktif Masih Berjalan
                        </p>
                        <p class="text-[11px] leading-relaxed text-amber-700">
                            Sesuai aturan UKK, Anda tidak dapat mengajukan peminjaman baru sebelum peminjaman aktif sebelumnya selesai atau dikembalikan.
                        </p>
                    </div>
                @endif

                <!-- Cart Form -->
                <form id="cart-checkout-form" action="{{ route('ajukan.peminjaman.multi') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Items List Container -->
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-700">
                            <span>Daftar Alat Dipilih (<span id="cart-items-count">0</span>):</span>
                            <button type="button" onclick="clearCart()" class="text-rose-600 hover:underline text-[11px] font-semibold">
                                Kosongkan
                            </button>
                        </div>
                        <div id="cart-items-list" class="space-y-2 max-h-48 overflow-y-auto pr-1"></div>
                    </div>

                    <!-- Date Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                            <input type="date" id="cart-tgl-pinjam" name="tgl_pinjam" required
                                min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                onchange="recalculateCart()" class="donezo-input text-xs w-full">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Kembali <span class="text-rose-500">*</span></label>
                            <input type="date" id="cart-tgl-kembali" name="tgl_kembali" required
                                min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                onchange="recalculateCart()" class="donezo-input text-xs w-full">
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl bg-brand-50/70 border border-brand-200/60 text-xs">
                        <span class="text-gray-600 font-medium">Durasi Sewa:</span>
                        <span id="cart-durasi-label" class="font-extrabold text-brand-800">1 Hari</span>
                    </div>

                    <!-- Payment Details (UKK Tantangan 2 & Nilai Tambah) -->
                    <div class="space-y-3 pt-2 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                            <i data-lucide="credit-card" class="w-4 h-4 text-brand-700"></i>
                            <span>Ketentuan Pembayaran & Jaminan</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="cart-metode-bayar" class="donezo-input text-xs w-full">
                                    <option value="Transfer Bank BCA">Transfer Bank BCA</option>
                                    <option value="Transfer Bank Mandiri">Transfer Bank Mandiri</option>
                                    <option value="Transfer Bank BRI">Transfer Bank BRI</option>
                                    <option value="QRIS / E-Wallet">QRIS / E-Wallet</option>
                                    <option value="Tunai saat Pengambilan">Tunai / Cash saat Ambil</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Jaminan Identitas <span class="text-rose-500">*</span></label>
                                <input type="text" name="jaminan" required value="KTP Asli Penyewa"
                                    placeholder="Contoh: KTP No. 3201..." class="donezo-input text-xs w-full">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-bold text-gray-700">Nominal Dibayarkan (Rp)</label>
                                <div class="flex items-center gap-1 text-[10px]">
                                    <button type="button" onclick="setPaymentAmount('full')" class="text-brand-800 font-bold hover:underline">Lunas</button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="setPaymentAmount('dp')" class="text-brand-800 font-bold hover:underline">DP 50%</button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="setPaymentAmount('zero')" class="text-brand-800 font-bold hover:underline">Di Toko</button>
                                </div>
                            </div>
                            <input type="number" id="cart-jumlah-bayar" name="jumlah_bayar" min="0" value="0"
                                class="donezo-input text-xs w-full font-mono">
                            <p class="text-[10px] text-gray-400 mt-1">Anda dapat membayar lunas, DP terlebih dahulu, atau bayar tunai di toko saat serah terima.</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="catatan" placeholder="Catatan khusus untuk petugas rental..." class="donezo-input text-xs w-full">
                        </div>
                    </div>

                    <!-- Grand Total Summary -->
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Estimasi Sewa</p>
                            <p id="cart-grand-total" class="text-xl font-extrabold text-brand-800 font-mono">Rp 0</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="closeModal('modal-keranjang-sewa')" class="donezo-btn donezo-btn-ghost text-xs">
                                Batal
                            </button>

                            @guest
                                <a href="{{ route('login') }}" class="donezo-btn donezo-btn-primary text-xs">
                                    Login untuk Sewa
                                </a>
                            @else
                                @if(Auth::user()->role !== 'Pelanggan')
                                    <button type="button" disabled class="donezo-btn donezo-btn-ghost opacity-60 text-xs cursor-not-allowed">
                                        Akun Staf
                                    </button>
                                @elseif($userHasActiveLoan)
                                    <button type="button" disabled class="donezo-btn donezo-btn-ghost opacity-60 text-xs cursor-not-allowed" title="Ada tanggungan peminjaman aktif">
                                        Tanggungan Aktif
                                    </button>
                                @else
                                    <button type="submit" id="cart-submit-btn" class="donezo-btn donezo-btn-primary text-xs">
                                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                                        <span>Ajukan Peminjaman</span>
                                    </button>
                                @endif
                            @endguest
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="no-print bg-white border-t border-gray-100 mt-12">
        <div class="max-w-6xl mx-auto px-5 py-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-xl bg-brand-800 text-white flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <span class="font-bold text-gray-900">MyCam Rental Kamera</span>
                <span>&copy; {{ date('Y') }} All rights reserved.</span>
            </div>
            <p class="text-[11px] text-gray-400">Platform Peminjaman Alat Kamera Profesional & Terpercaya.</p>
        </div>
    </footer>

    <script src="{{ asset('template/assets/js/donezo.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            updateCartUI();
        });

        // Keranjang Sewa Multi-Peminjaman Store (localStorage)
        const CART_KEY = 'mycam_rental_cart';

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(CART_KEY)) || [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            updateCartUI();
        }

        function addToCart(item) {
            let cart = getCart();
            const exists = cart.find(i => i.id === item.id);
            if (exists) {
                triggerToast('info', 'Sudah Ada di Keranjang', item.nama_alat + ' sudah ada di keranjang sewa Anda.', 3000);
                return;
            }
            cart.push(item);
            saveCart(cart);
            triggerToast('success', 'Ditambahkan ke Keranjang', item.nama_alat + ' berhasil dimasukkan ke keranjang sewa.', 3500);
        }

        function addToCartAndOpen(item) {
            addToCart(item);
            openCartModal();
        }

        function removeFromCart(id) {
            let cart = getCart();
            cart = cart.filter(i => i.id !== id);
            saveCart(cart);
            recalculateCart();
            triggerToast('info', 'Dihapus', 'Alat berhasil dikeluarkan dari keranjang.', 2500);
        }

        function clearCart() {
            localStorage.removeItem(CART_KEY);
            updateCartUI();
            recalculateCart();
            triggerToast('info', 'Keranjang Kosong', 'Semua alat telah dikeluarkan dari keranjang.', 2500);
        }

        function updateCartUI() {
            const cart = getCart();
            const badge = document.getElementById('header-cart-badge');
            if (badge) {
                if (cart.length > 0) {
                    badge.textContent = cart.length;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
            const countLabel = document.getElementById('cart-items-count');
            if (countLabel) countLabel.textContent = cart.length;
        }

        function openCartModal() {
            renderCartItems();
            recalculateCart();
            openModal('modal-keranjang-sewa');
        }

        function renderCartItems() {
            const cart = getCart();
            const emptyState = document.getElementById('cart-empty-state');
            const form = document.getElementById('cart-checkout-form');
            const list = document.getElementById('cart-items-list');

            if (!list || !emptyState || !form) return;

            if (cart.length === 0) {
                emptyState.classList.remove('hidden');
                form.classList.add('hidden');
                list.innerHTML = '';
                return;
            }

            emptyState.classList.add('hidden');
            form.classList.remove('hidden');

            list.innerHTML = cart.map((item, idx) => `
                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 gap-3">
                    <input type="hidden" name="alat_ids[]" value="${item.id}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
                            ${item.gambar ? `<img src="${item.gambar}" class="w-full h-full object-cover">` : `<i data-lucide="camera" class="w-5 h-5 text-gray-300"></i>`}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-xs text-gray-900 truncate">${item.nama_alat}</p>
                            <p class="text-[10px] text-gray-400 font-mono">Rp ${Number(item.harga_sewa).toLocaleString('id-ID')} / hari</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFromCart(${item.id})" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Hapus">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            `).join('');

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function recalculateCart() {
            const cart = getCart();
            const tglPinjam = document.getElementById('cart-tgl-pinjam')?.value;
            const tglKembali = document.getElementById('cart-tgl-kembali')?.value;
            const durasiLabel = document.getElementById('cart-durasi-label');
            const totalLabel = document.getElementById('cart-grand-total');

            if (!tglPinjam || !tglKembali) return;

            const start = new Date(tglPinjam);
            const end = new Date(tglKembali);
            const diffTime = end - start;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays <= 0) diffDays = 1;

            if (durasiLabel) durasiLabel.textContent = diffDays + ' Hari';

            let grandTotal = 0;
            cart.forEach(item => {
                grandTotal += (Number(item.harga_sewa) * diffDays);
            });

            if (totalLabel) totalLabel.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

            // Set default nominal bayar if current is full
            const inputBayar = document.getElementById('cart-jumlah-bayar');
            if (inputBayar && inputBayar.dataset.mode === 'full') {
                inputBayar.value = grandTotal;
            }
        }

        function setPaymentAmount(mode) {
            const cart = getCart();
            const tglPinjam = document.getElementById('cart-tgl-pinjam')?.value;
            const tglKembali = document.getElementById('cart-tgl-kembali')?.value;
            let diffDays = 1;
            if (tglPinjam && tglKembali) {
                const start = new Date(tglPinjam);
                const end = new Date(tglKembali);
                diffDays = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)));
            }

            let total = 0;
            cart.forEach(i => total += (Number(i.harga_sewa) * diffDays));

            const inputBayar = document.getElementById('cart-jumlah-bayar');
            if (!inputBayar) return;

            if (mode === 'full') {
                inputBayar.value = total;
                inputBayar.dataset.mode = 'full';
            } else if (mode === 'dp') {
                inputBayar.value = Math.round(total * 0.5);
                inputBayar.dataset.mode = 'dp';
            } else {
                inputBayar.value = 0;
                inputBayar.dataset.mode = 'zero';
            }
        }
    </script>

    <!-- Trigger Toast Notifikasi dengan Timeline & Auto Close -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                triggerToast('success', 'Berhasil', {!! json_encode(session('success')) !!}, 4000);
                // Jika peminjaman berhasil dibuat, kosongkan keranjang
                if ({!! json_encode(session('success')) !!}.includes('Pengajuan peminjaman')) {
                    localStorage.removeItem('mycam_rental_cart');
                    updateCartUI();
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                triggerToast('error', 'Terjadi Kesalahan', {!! json_encode(session('error')) !!}, 4500);
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $err)
                    triggerToast('error', 'Validasi Gagal', {!! json_encode($err) !!}, 5000);
                @endforeach
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
