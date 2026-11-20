<header class="no-print sticky top-0 z-40 bg-[#f3f5f4]/90 backdrop-blur-md border-b border-gray-200/60 shadow-xs">
    <div class="max-w-6xl mx-auto px-5 py-3.5 flex items-center justify-between gap-4">
        <!-- Logo Brand -->
        <a href="{{ route('index') }}" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 rounded-2xl bg-brand-800 text-white flex items-center justify-center shadow-md transition-transform group-hover:scale-105">
                <svg class="w-5 h-5 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
            <div>
                <p class="font-extrabold text-xl text-gray-900 tracking-tight leading-none">MyCam</p>
                <p class="text-[9px] font-bold text-brand-600 uppercase tracking-wider mt-0.5">Rental Kamera</p>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="hidden md:flex items-center gap-6 text-xs font-bold text-gray-600">
            <a href="{{ route('index') }}" class="hover:text-brand-800 transition {{ request()->routeIs('index') ? 'text-brand-800 font-extrabold' : '' }}">
                Katalog Kamera
            </a>

            @auth
                @if(Auth::user()->role === 'Pelanggan')
                    <a href="{{ route('riwayat.peminjaman') }}" class="hover:text-brand-800 transition {{ request()->routeIs('riwayat.peminjaman') ? 'text-brand-800 font-extrabold' : '' }}">
                        Riwayat Peminjaman
                    </a>
                    <a href="{{ route('pelanggan.log') }}" class="hover:text-brand-800 transition {{ request()->routeIs('pelanggan.log') ? 'text-brand-800 font-extrabold' : '' }}">
                        Log Aktivitas
                    </a>
                @else
                    <a href="{{ Auth::user()->role === 'Admin' ? route('admin.dashboard.index') : route('petugas.dashboard.index') }}"
                        class="hover:text-brand-800 transition text-brand-700 font-bold flex items-center gap-1">
                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                        <span>Panel {{ Auth::user()->role }}</span>
                    </a>
                @endif
            @endauth
        </nav>

        <!-- User Controls / Auth -->
        <div class="flex items-center gap-2.5">
            <!-- Keranjang Multi-Peminjaman Button -->
            <button type="button" onclick="openCartModal()"
                class="relative donezo-btn donezo-btn-soft text-xs px-3 py-2 flex items-center gap-1.5 hover:border-brand-400 transition"
                title="Keranjang Sewa Multi-Alat">
                <i data-lucide="shopping-cart" class="w-4 h-4 text-brand-700"></i>
                <span class="hidden sm:inline font-bold text-gray-800">Keranjang</span>
                <span id="header-cart-badge" class="hidden px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-brand-800 text-white leading-none">0</span>
            </button>

            @auth
                <div class="flex items-center gap-2.5">
                    <span class="text-xs font-bold text-gray-700 hidden sm:inline">
                        Halo, {{ Auth::user()->nama }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="donezo-btn donezo-btn-danger text-xs px-3 py-1.5" title="Keluar dari akun">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="donezo-btn donezo-btn-ghost text-xs px-3 py-1.5">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="donezo-btn donezo-btn-primary text-xs px-3 py-1.5">
                        Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
