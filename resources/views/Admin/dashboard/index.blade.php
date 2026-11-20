@extends('Layout.app')
@section('title', 'Admin Dashboard - Manajemen Aset & Sistem')
@section('content')
<main class="space-y-6 pb-12">

    <!-- KPI Master Data Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('alat.index') }}" class="donezo-card p-5 space-y-3 hover:border-brand-500 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="camera" class="w-5 h-5 text-brand-800"></i>
                </div>
                <span class="text-[10px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-200">
                    {{ $totalStokFisik }} Unit Fisik
                </span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalAlat }}</h3>
                <p class="text-[11px] text-gray-500">Total Katalog Alat</p>
            </div>
        </a>

        <a href="{{ route('kategori.index') }}" class="donezo-card p-5 space-y-3 hover:border-blue-500 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="boxes" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Klasifikasi</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalKategori }}</h3>
                <p class="text-[11px] text-gray-500">Kategori Alat</p>
            </div>
        </a>

        <a href="{{ route('user.index') }}" class="donezo-card p-5 space-y-3 hover:border-amber-500 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="users" class="w-5 h-5 text-amber-600"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                    {{ $totalPetugas }} Petugas
                </span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalPelanggan }}</h3>
                <p class="text-[11px] text-gray-500">Pelanggan Terdaftar</p>
            </div>
        </a>

        <a href="{{ route('peminjaman.all') }}" class="donezo-card p-5 space-y-3 hover:border-emerald-500 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Semua Waktu</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalPeminjaman }}</h3>
                <p class="text-[11px] text-gray-500">Total Transaksi</p>
            </div>
        </a>
    </div>

    <!-- Ringkasan Keuangan Kas Toko -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="donezo-card p-4 flex items-center justify-between bg-gradient-to-r from-white to-brand-50/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-800 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Omzet Pendapatan Sewa</p>
                    <h4 class="text-xl font-extrabold text-brand-800 font-mono">Rp {{ number_format($totalPendapatanSewa, 0, ',', '.') }}</h4>
                </div>
            </div>
            <span class="text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200 px-2.5 py-1 rounded-full">Sewa Kas</span>
        </div>

        <div class="donezo-card p-4 flex items-center justify-between bg-gradient-to-r from-white to-rose-50/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                    <i data-lucide="alert-octagon" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Kas Denda Keterlambatan</p>
                    <h4 class="text-xl font-extrabold text-rose-600 font-mono">Rp {{ number_format($totalPendapatanDenda, 0, ',', '.') }}</h4>
                </div>
            </div>
            <span class="text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-1 rounded-full">Denda</span>
        </div>
    </div>

    <!-- Baris 1: Admin Fokus - Memantau Barang yang Paling Sering Dipinjam & Distribusi Kategori -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart: Obyek Barang yang Sering Dipinjam -->
        <div class="lg:col-span-2 donezo-card p-6 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-brand-700"></i>
                        <span>Barang yang Paling Sering Dipinjam</span>
                    </h3>
                    <p class="text-[11px] text-gray-400">Analisis kebutuhan alat kamera favorit untuk perencanaan pengadaan aset baru</p>
                </div>
                <a href="{{ route('alat.index') }}" class="text-xs font-bold text-brand-700 hover:underline">
                    Kelola Semua Alat &rarr;
                </a>
            </div>
            <div class="relative h-64">
                <canvas id="adminTopAlatChart"></canvas>
            </div>
        </div>

        <!-- Chart: Distribusi Kategori Alat -->
        <div class="donezo-card p-6 space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-blue-600"></i>
                    <span>Distribusi Kategori Alat</span>
                </h3>
                <p class="text-[11px] text-gray-400">Komposisi jumlah unit berdasarkan kategori</p>
            </div>
            <div class="relative max-w-[200px] mx-auto my-auto py-2">
                <canvas id="adminKategoriChart" height="150"></canvas>
            </div>
            <div class="space-y-1.5 text-[11px] pt-3 border-t border-gray-100 max-h-32 overflow-y-auto">
                @foreach($kategoriLabels as $index => $katName)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 truncate max-w-[150px]">{{ $katName }}:</span>
                        <span class="font-bold text-gray-900">{{ $kategoriCounts[$index] ?? 0 }} unit</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Baris 2: Kontrol Stok Kritis & Tren Pendapatan Keuangan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tabel Kontrol Inventaris Stok Kritis -->
        <div class="lg:col-span-1 donezo-card overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500"></i>
                            <span>Peringatan Stok Menipis</span>
                        </h3>
                        <p class="text-[11px] text-gray-400">Alat dengan sisa stok &le; 2 unit</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $alatStokKritis->count() > 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $alatStokKritis->count() }} Perhatian
                    </span>
                </div>
                <div class="p-4 space-y-2.5">
                    @forelse($alatStokKritis as $ak)
                        <div class="flex items-center justify-between p-2.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs">
                            <div class="min-w-0 pr-2">
                                <p class="font-bold text-gray-900 truncate">{{ $ak->nama_alat }}</p>
                                <p class="text-[10px] text-gray-400">{{ $ak->kategori?->nama_kategori }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold shrink-0 {{ $ak->stok == 0 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800' }}">
                                {{ $ak->stok == 0 ? 'Habis' : 'Sisa ' . $ak->stok }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400">
                            <i data-lucide="check-circle" class="w-8 h-8 text-emerald-400 mx-auto mb-2"></i>
                            <p class="text-xs font-semibold text-gray-700">Semua stok alat aman!</p>
                            <p class="text-[11px] text-gray-400">Tidak ada unit dengan stok kritis.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <a href="{{ route('alat.index') }}" class="donezo-btn donezo-btn-soft w-full justify-center text-xs">
                    Kelola Stok & Inventaris &rarr;
                </a>
            </div>
        </div>

        <!-- Chart Keuangan Sewa & Denda -->
        <div class="lg:col-span-2 donezo-card p-6 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-emerald-600"></i>
                        <span>Tren Arus Keuangan Rental</span>
                    </h3>
                    <p class="text-[11px] text-gray-400">Laporan pemasukan biaya sewa vs denda kas (6 Bulan Terakhir)</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5 text-brand-700 font-bold">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span> Pendapatan Sewa
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-600 font-bold">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Kas Denda
                    </span>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="adminFinanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Baris 3: Pelanggan Paling Aktif & Log Aktivitas Sistem Keseluruhan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Pelanggan Teraktif -->
        <div class="donezo-card p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                        <i data-lucide="award" class="w-4 h-4 text-amber-500"></i>
                        <span>Pelanggan Paling Aktif</span>
                    </h3>
                    <p class="text-[11px] text-gray-400">User dengan total riwayat peminjaman terbanyak</p>
                </div>
                <a href="{{ route('user.index') }}" class="text-xs font-bold text-brand-700 hover:underline">Kelola User</a>
            </div>
            <div class="relative h-60">
                <canvas id="adminTopUserChart"></canvas>
            </div>
        </div>

        <!-- Log Aktivitas Sistem Menyeluruh (CRUD & Transaksi) -->
        <div class="donezo-card p-5 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4 text-brand-700"></i>
                        <span>Log Aktivitas Sistem Keseluruhan</span>
                    </h3>
                    <p class="text-[11px] text-gray-400">Audit jejak aktivitas master data & peminjaman</p>
                </div>
                <a href="{{ route('index.log') }}" class="text-xs font-bold text-brand-700 hover:underline">Semua Log &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($aktivitasTerbaru as $log)
                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-bold text-gray-800 truncate">{{ $log->aksi }}</p>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-[11px] text-gray-500 line-clamp-1 leading-relaxed mt-0.5">{{ $log->keterangan }}</p>
                            <span class="text-[10px] text-brand-700 font-semibold">Oleh: {{ $log->user?->nama ?? 'Sistem' }} ({{ $log->user?->role ?? 'User' }})</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat aktivitas sistem.</p>
                @endforelse
            </div>
        </div>
    </div>

</main>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Chart Top Alat yang Sering Dipinjam
        const ctxTopAlat = document.getElementById('adminTopAlatChart');
        if (ctxTopAlat) {
            new Chart(ctxTopAlat, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topAlatLabels) !!},
                    datasets: [{
                        label: 'Total Peminjaman',
                        data: {!! json_encode($topAlatData) !!},
                        backgroundColor: '#1b4d3e',
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }

        // 2. Chart Distribusi Kategori
        const ctxKategori = document.getElementById('adminKategoriChart');
        if (ctxKategori) {
            new Chart(ctxKategori, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($kategoriLabels) !!},
                    datasets: [{
                        data: {!! json_encode($kategoriCounts) !!},
                        backgroundColor: ['#2e7d32', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        // 3. Chart Keuangan
        const ctxFinance = document.getElementById('adminFinanceChart');
        if (ctxFinance) {
            new Chart(ctxFinance, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($keuanganLabels) !!},
                    datasets: [
                        {
                            label: 'Pendapatan Sewa (Rp)',
                            data: {!! json_encode($keuanganSewa) !!},
                            backgroundColor: '#2e7d32',
                            borderRadius: 6,
                        },
                        {
                            label: 'Kas Denda (Rp)',
                            data: {!! json_encode($keuanganDenda) !!},
                            backgroundColor: '#e11d48',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value >= 1000 ? (value / 1000) + 'k' : value);
                                }
                            }
                        }
                    }
                }
            });
        }

        // 4. Chart Pelanggan Teraktif
        const ctxTopUser = document.getElementById('adminTopUserChart');
        if (ctxTopUser) {
            new Chart(ctxTopUser, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topUserLabels) !!},
                    datasets: [{
                        label: 'Total Transaksi',
                        data: {!! json_encode($topUserData) !!},
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
