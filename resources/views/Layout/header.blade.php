<header
    class="no-print flex flex-col sm:flex-row sm:items-center justify-between py-3 mb-4 gap-4 sticky top-0 bg-[#f3f5f4]/95 backdrop-blur-md z-20 border-b border-gray-200/50">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn"
            class="md:hidden p-2 rounded-2xl bg-white text-gray-600 shadow-sm border border-gray-100 hover:bg-gray-50 transition"><i
                data-lucide="menu" class="w-5 h-5"></i></button>
        <div>
            <h1 class="font-bold text-xl sm:text-2xl text-gray-900 tracking-tight">Dashboard MyCam</h1>
            <p class="text-xs text-gray-500 mt-0.5">Sistem Manajemen & Peminjaman Alat Kamera.</p>
        </div>
    </div>

    <!-- Right Header Controls -->
    <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-white border border-gray-200/70 text-xs font-semibold text-gray-700 shadow-2xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            {{ Auth::user()->role }}: {{ Auth::user()->nama }}
        </span>

        <a href="{{ route('index') }}" target="_blank" class="donezo-btn donezo-btn-soft text-xs" title="Lihat Tampilan Depan">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span class="hidden sm:inline">Katalog</span>
        </a>
    </div>
</header>
