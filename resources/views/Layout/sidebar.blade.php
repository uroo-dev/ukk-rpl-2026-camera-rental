<aside id="sidebar"
    class="no-print fixed inset-y-0 left-0 z-40 w-64 bg-white rounded-3xl p-5 flex flex-col justify-between shadow-card transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out my-2 md:my-0 ml-2 md:ml-0 shrink-0 border border-gray-100 overflow-y-auto">

    <!-- Top Brand Logo -->
    <div class="flex items-center justify-between pb-5 border-b border-gray-100 shrink-0">
        <a href="{{ Auth::user()->role === 'Admin' ? route('admin.dashboard.index') : route('petugas.dashboard.index') }}"
            class="flex items-center space-x-3 group">
            <div
                class="w-10 h-10 rounded-2xl bg-brand-800 text-white flex items-center justify-center font-bold text-xl shadow-md transition-transform group-hover:scale-105">
                <svg class="w-6 h-6 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
            <div>
                <span class="font-bold text-2xl tracking-tight text-gray-900 leading-none block">MyCam</span>
                <span class="text-[9px] font-bold uppercase tracking-wider text-brand-600 block mt-1">{{ Auth::user()->role }} Workspace</span>
            </div>
        </a>
        <button id="close-sidebar-btn"
            class="md:hidden p-1.5 rounded-xl text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation Scroll Area -->
    <div class="flex-1 overflow-y-auto py-5 space-y-5 pr-1">

        <!-- MAIN MENU -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Menu Utama</p>
            <nav class="space-y-1">
                @if (Auth::user()->role == 'Admin')
                    <a href="{{ route('admin.dashboard.index') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('admin.dashboard.index') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('admin.dashboard.index') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    <a href="{{ route('kategori.index') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('kategori.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="boxes" class="w-4 h-4 {{ request()->routeIs('kategori.*') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Kategori Alat</span>
                        </div>
                    </a>

                    <a href="{{ route('alat.index') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('alat.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="camera" class="w-4 h-4 {{ request()->routeIs('alat.*') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Kelola Alat</span>
                        </div>
                    </a>

                    <a href="{{ route('user.index') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('user.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('user.*') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Kelola User</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.keuangan.index') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('admin.keuangan.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="wallet" class="w-4 h-4 {{ request()->routeIs('admin.keuangan.*') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Laporan Keuangan</span>
                        </div>
                    </a>
                @endif

                @if (Auth::user()->role == 'Petugas')
                    <a href="{{ route('petugas.dashboard.index') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('petugas.dashboard.index') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('petugas.dashboard.index') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    <a href="{{ route('peminjaman') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('peminjaman') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="inbox" class="w-4 h-4 {{ request()->routeIs('peminjaman') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Peminjaman Aktif</span>
                        </div>
                    </a>
                @endif

                <a href="{{ route('peminjaman.all') }}"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('peminjaman.all') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="history" class="w-4 h-4 {{ request()->routeIs('peminjaman.all') ? 'text-white' : 'text-gray-500' }}"></i>
                        <span>Semua Riwayat</span>
                    </div>
                </a>
            </nav>
        </div>

        <!-- GENERAL & LOGS -->
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2.5 px-3">Sistem & Akun</p>
            <nav class="space-y-1">
                @if (Auth::user()->role == 'Admin')
                    <a href="{{ route('index.log') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('index.log') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="activity" class="w-4 h-4 {{ request()->routeIs('index.log') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Log Aktivitas</span>
                        </div>
                    </a>
                @endif

                @if (Auth::user()->role == 'Petugas')
                    <a href="{{ route('user.log') }}"
                        class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition-all relative {{ request()->routeIs('user.log') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="activity" class="w-4 h-4 {{ request()->routeIs('user.log') ? 'text-white' : 'text-gray-500' }}"></i>
                            <span>Log Aktivitas Saya</span>
                        </div>
                    </a>
                @endif

                <a href="{{ route('index') }}" target="_blank"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-semibold text-gray-600 hover:text-brand-800 hover:bg-brand-50 transition">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="external-link" class="w-4 h-4 text-gray-500"></i>
                        <span>Katalog Depan</span>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-rose-600 hover:bg-rose-50 text-sm font-semibold transition-all text-left">
                        <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </nav>
        </div>
    </div>
</aside>
